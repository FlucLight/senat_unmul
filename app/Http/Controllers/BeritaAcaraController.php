<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesHtmlDocument;
use App\Models\Document;
use App\Services\Document\DocumentRenderer;
use App\Services\NomorSuratService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BeritaAcaraController extends Controller
{
    use HandlesHtmlDocument;

    public function create()
    {
        return $this->renderHtmlCreate('berita_acara', 'Berita Acara', 'dokumen.berita_acara.form');
    }

    public function store(Request $request)
    {
        if ($request->input('form_mode') === 'html') {
            return $this->saveHtmlDocument($request, 'berita_acara', null);
        }

        return $this->save($request, null);
    }

    public function edit(Document $document)
    {
        abort_unless($document->jenis === 'berita_acara', 404);
        $this->authorize('update', $document);
        abort_if($document->isFinal(), 403, 'Dokumen final tidak dapat diedit.');

        return $this->renderHtmlEdit($document, 'berita_acara', 'dokumen.berita_acara.form', 'dokumen.berita_acara.form_legacy');
    }

    public function update(Request $request, Document $document)
    {
        abort_unless($document->jenis === 'berita_acara', 404);
        $this->authorize('update', $document);
        abort_if($document->isFinal(), 403, 'Dokumen final tidak dapat diubah.');

        if ($request->input('form_mode') === 'html' || (($document->content['format'] ?? null) === 'html')) {
            return $this->saveHtmlDocument($request, 'berita_acara', $document);
        }

        return $this->save($request, $document);
    }

    public function printView(Document $document, DocumentRenderer $renderer)
    {
        abort_unless($document->jenis === 'berita_acara', 404);
        $document->load('creator');

        return $renderer->print($document);
    }

    protected function defaultContent(): array
    {
        return [
            'perihal' => '',
            'tanggal_surat' => date('Y-m-d'),
            'tanggal_rapat' => '',
            'tempat' => '',
            'pemimpin_rapat' => '',
            'pembahasan' => '',
            'keputusan' => '',
            'peserta' => [],
        ];
    }

    protected function save(Request $request, ?Document $document)
    {
        $isFinal = $request->input('action') === 'final';

        $rules = [
            'perihal' => ['required', 'string', 'max:255'],
            'tanggal_surat' => [$isFinal ? 'required' : 'nullable', 'date'],
            'tanggal_rapat' => [$isFinal ? 'required' : 'nullable', 'date'],
            'tempat' => [$isFinal ? 'required' : 'nullable', 'string', 'max:255'],
            'pemimpin_rapat' => [$isFinal ? 'required' : 'nullable', 'string', 'max:255'],
            'pembahasan' => [$isFinal ? 'required' : 'nullable', 'string'],
            'keputusan' => [$isFinal ? 'required' : 'nullable', 'string'],
            'peserta' => ['nullable', 'array'],
            'peserta.*.nama' => ['nullable', 'string', 'max:255'],
            'peserta.*.jabatan_instansi' => ['nullable', 'string', 'max:255'],
        ];

        $validated = $request->validate($rules);

        $peserta = collect($request->input('peserta', []))
            ->map(fn ($p) => [
                'nama' => trim($p['nama'] ?? ''),
                'jabatan_instansi' => trim($p['jabatan_instansi'] ?? ''),
            ])
            ->filter(fn ($p) => $p['nama'] !== '')
            ->values()
            ->all();

        $content = [
            'perihal' => $validated['perihal'],
            'tanggal_surat' => $validated['tanggal_surat'] ?? null,
            'tanggal_rapat' => $validated['tanggal_rapat'] ?? null,
            'tempat' => $validated['tempat'] ?? null,
            'pemimpin_rapat' => $validated['pemimpin_rapat'] ?? null,
            'pembahasan' => $validated['pembahasan'] ?? null,
            'keputusan' => $validated['keputusan'] ?? null,
            'peserta' => $peserta,
        ];

        return DB::transaction(function () use ($document, $validated, $content, $isFinal) {
            if (! $document) {
                $document = Document::create([
                    'jenis' => 'berita_acara',
                    'judul' => $validated['perihal'],
                    'status' => Document::STATUS_DRAFT,
                    'created_by' => auth()->id(),
                    'content' => $content,
                ]);
            } else {
                $document->update([
                    'judul' => $validated['perihal'],
                    'content' => $content,
                ]);
            }

            if ($isFinal) {
                $nomorSurat = app(NomorSuratService::class)->generate('berita_acara');
                $document->update([
                    'status' => Document::STATUS_FINAL,
                    'nomor_surat' => $nomorSurat,
                    'finalized_at' => now(),
                    'qr_verification_url' => route('dokumen.verifikasi', $document->id),
                ]);

                $fresh = $document->fresh()->load('creator');
                $payload = app(\App\Services\Document\DocumentRenderer::class)->pdfPayload($fresh);
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($payload['view'], $payload['data'])->setPaper('a4');
                $path = 'dokumen/' . $document->id . '.pdf';
                \Illuminate\Support\Facades\Storage::disk('public')->put($path, $pdf->output());
                $document->update(['pdf_path' => $path]);

                return redirect()->route('dokumen.show', $document)
                    ->with('status', 'Berita acara berhasil difinalisasi dan nomor surat resmi telah diterbitkan.');
            }

            return redirect()->route('dokumen.berita-acara.edit', $document)
                ->with('status', $document->wasRecentlyCreated ? 'Draf berita acara berhasil disimpan.' : 'Perubahan draf berita acara berhasil disimpan.');
        });
    }
}
