<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesHtmlDocument;
use App\Models\Document;
use App\Services\Document\DocumentRenderer;
use App\Services\NomorSuratService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UndanganController extends Controller
{
    use HandlesHtmlDocument;

    public function create()
    {
        return $this->renderHtmlCreate('undangan', 'Undangan', 'dokumen.undangan.form');
    }

    public function store(Request $request)
    {
        if ($request->input('form_mode') === 'html') {
            return $this->saveHtmlDocument($request, 'undangan', null);
        }

        return $this->save($request, null);
    }

    public function edit(Document $document)
    {
        abort_unless($document->jenis === 'undangan', 404);
        $this->authorize('update', $document);
        abort_if($document->isFinal(), 403, 'Dokumen final tidak dapat diedit.');

        return $this->renderHtmlEdit($document, 'undangan', 'dokumen.undangan.form', 'dokumen.undangan.form_legacy');
    }

    public function update(Request $request, Document $document)
    {
        abort_unless($document->jenis === 'undangan', 404);
        $this->authorize('update', $document);
        abort_if($document->isFinal(), 403, 'Dokumen final tidak dapat diubah.');

        if ($request->input('form_mode') === 'html' || (($document->content['format'] ?? null) === 'html')) {
            return $this->saveHtmlDocument($request, 'undangan', $document);
        }

        return $this->save($request, $document);
    }

    public function printView(Document $document, DocumentRenderer $renderer)
    {
        abort_unless($document->jenis === 'undangan', 404);
        $document->load('creator');

        return $renderer->print($document);
    }

    protected function defaultContent(): array
    {
        return [
            'perihal' => '',
            'tanggal_surat' => date('Y-m-d'),
            'tanggal_acara' => '',
            'waktu_mulai' => '',
            'waktu_selesai' => '',
            'tempat' => '',
            'pemimpin_rapat' => '',
            'agenda' => '',
            'undangan' => [],
        ];
    }

    protected function save(Request $request, ?Document $document)
    {
        $isFinal = $request->input('action') === 'final';

        $rules = [
            'perihal' => ['required', 'string', 'max:255'],
            'tanggal_surat' => [$isFinal ? 'required' : 'nullable', 'date'],
            'tanggal_acara' => [$isFinal ? 'required' : 'nullable', 'date'],
            'waktu_mulai' => [$isFinal ? 'required' : 'nullable', 'string', 'max:20'],
            'waktu_selesai' => [$isFinal ? 'required' : 'nullable', 'string', 'max:20'],
            'tempat' => [$isFinal ? 'required' : 'nullable', 'string', 'max:255'],
            'pemimpin_rapat' => [$isFinal ? 'required' : 'nullable', 'string', 'max:255'],
            'agenda' => [$isFinal ? 'required' : 'nullable', 'string'],
            'undangan' => ['nullable', 'array'],
            'undangan.*.nama' => ['nullable', 'string', 'max:255'],
            'undangan.*.jabatan_instansi' => ['nullable', 'string', 'max:255'],
        ];

        $validated = $request->validate($rules);

        $undangan = collect($request->input('undangan', []))
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
            'tanggal_acara' => $validated['tanggal_acara'] ?? null,
            'waktu_mulai' => $validated['waktu_mulai'] ?? null,
            'waktu_selesai' => $validated['waktu_selesai'] ?? null,
            'tempat' => $validated['tempat'] ?? null,
            'pemimpin_rapat' => $validated['pemimpin_rapat'] ?? null,
            'agenda' => $validated['agenda'] ?? null,
            'undangan' => $undangan,
        ];

        return DB::transaction(function () use ($document, $validated, $content, $isFinal) {
            if (! $document) {
                $document = Document::create([
                    'jenis' => 'undangan',
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
                $nomorSurat = app(NomorSuratService::class)->generate('undangan');
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
                    ->with('status', 'Undangan berhasil difinalisasi dan nomor surat resmi telah diterbitkan.');
            }

            return redirect()->route('dokumen.undangan.edit', $document)
                ->with('status', $document->wasRecentlyCreated ? 'Draf undangan berhasil disimpan.' : 'Perubahan draf undangan berhasil disimpan.');
        });
    }
}
