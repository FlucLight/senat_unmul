<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesHtmlDocument;
use App\Models\Document;
use App\Services\Document\DocumentRenderer;
use App\Services\NomorSuratService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuratPengantarController extends Controller
{
    use HandlesHtmlDocument;

    public function create()
    {
        return $this->renderHtmlCreate('surat_pengantar', 'Surat Pengantar', 'dokumen.surat_pengantar.form');
    }

    public function store(Request $request)
    {
        if ($request->input('form_mode') === 'html') {
            return $this->saveHtmlDocument($request, 'surat_pengantar', null);
        }

        return $this->save($request, null);
    }

    public function edit(Document $document)
    {
        abort_unless($document->jenis === 'surat_pengantar', 404);
        abort_if($document->isFinal(), 403, 'Dokumen final tidak dapat diedit.');

        return $this->renderHtmlEdit($document, 'surat_pengantar', 'dokumen.surat_pengantar.form', 'dokumen.surat_pengantar.form_legacy');
    }

    public function update(Request $request, Document $document)
    {
        abort_unless($document->jenis === 'surat_pengantar', 404);
        abort_if($document->isFinal(), 403, 'Dokumen final tidak dapat diubah.');

        if ($request->input('form_mode') === 'html' || (($document->content['format'] ?? null) === 'html')) {
            return $this->saveHtmlDocument($request, 'surat_pengantar', $document);
        }

        return $this->save($request, $document);
    }

    public function printView(Document $document, DocumentRenderer $renderer)
    {
        abort_unless($document->jenis === 'surat_pengantar', 404);
        $document->load('creator');

        return $renderer->print($document);
    }

    protected function defaultContent(): array
    {
        return [
            'perihal' => '',
            'tanggal_surat' => date('Y-m-d'),
            'tujuan' => '',
            'isi_ringkas' => '',
            'penandatangan' => '',
        ];
    }

    protected function save(Request $request, ?Document $document)
    {
        $isFinal = $request->input('action') === 'final';

        $rules = [
            'perihal' => ['required', 'string', 'max:255'],
            'tanggal_surat' => [$isFinal ? 'required' : 'nullable', 'date'],
            'tujuan' => [$isFinal ? 'required' : 'nullable', 'string', 'max:255'],
            'isi_ringkas' => [$isFinal ? 'required' : 'nullable', 'string'],
            'penandatangan' => [$isFinal ? 'required' : 'nullable', 'string', 'max:255'],
        ];

        $validated = $request->validate($rules);

        $content = [
            'perihal' => $validated['perihal'],
            'tanggal_surat' => $validated['tanggal_surat'] ?? null,
            'tujuan' => $validated['tujuan'] ?? null,
            'isi_ringkas' => $validated['isi_ringkas'] ?? null,
            'penandatangan' => $validated['penandatangan'] ?? null,
        ];

        return DB::transaction(function () use ($document, $validated, $content, $isFinal) {
            if (! $document) {
                $document = Document::create([
                    'jenis' => 'surat_pengantar',
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
                $nomorSurat = app(NomorSuratService::class)->generate('surat_pengantar');
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
                    ->with('status', 'Surat pengantar berhasil difinalisasi dan nomor surat resmi telah diterbitkan.');
            }

            return redirect()->route('dokumen.surat-pengantar.edit', $document)
                ->with('status', $document->wasRecentlyCreated ? 'Draf surat pengantar berhasil disimpan.' : 'Perubahan draf surat pengantar berhasil disimpan.');
        });
    }
}
