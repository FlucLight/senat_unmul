<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Services\NomorSuratService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuratPengantarController extends Controller
{
    public function create()
    {
        return view('dokumen.surat_pengantar.form', [
            'document' => null,
            'content' => $this->defaultContent(),
        ]);
    }

    public function store(Request $request)
    {
        return $this->save($request, null);
    }

    public function edit(Document $document)
    {
        abort_unless($document->jenis === 'surat_pengantar', 404);
        abort_if($document->isFinal(), 403, 'Dokumen final tidak dapat diedit.');

        $content = array_merge($this->defaultContent(), (array) $document->content);

        return view('dokumen.surat_pengantar.form', [
            'document' => $document,
            'content' => $content,
        ]);
    }

    public function update(Request $request, Document $document)
    {
        abort_unless($document->jenis === 'surat_pengantar', 404);
        abort_if($document->isFinal(), 403, 'Dokumen final tidak dapat diubah.');

        return $this->save($request, $document);
    }

    public function printView(Document $document)
    {
        abort_unless($document->jenis === 'surat_pengantar', 404);
        $document->load('creator');

        return view('dokumen.surat_pengantar.print', [
            'document' => $document,
            'content' => (array) $document->content,
            'qrDataUri' => app(\App\Services\QrCodeService::class)->dataUri($document->qr_verification_url ?? route('dokumen.verifikasi', $document->id)),
        ]);
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
            'isi_ringkas' => ['nullable', 'string'],
            'penandatangan' => ['nullable', 'string', 'max:255'],
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
            if ($document === null) {
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

                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('dokumen.surat_pengantar.print', [
                    'document' => $document->fresh(),
                    'content' => (array) $document->fresh()->content,
                    'qrDataUri' => app(\App\Services\QrCodeService::class)->dataUri(route('dokumen.verifikasi', $document->id)),
                ]);
                $path = 'dokumen/' . $document->id . '.pdf';
                \Illuminate\Support\Facades\Storage::disk('public')->put($path, $pdf->output());
                $document->update(['pdf_path' => $path]);

                return redirect()->route('dokumen.show', $document)
                    ->with('status', 'Surat pengantar berhasil difinalisasi dan nomor surat resmi telah diterbitkan.');
            }

            return redirect()->route('dokumen.surat-pengantar.edit', $document)
                ->with('status', 'Draf surat pengantar berhasil disimpan.');
        });
    }
}
