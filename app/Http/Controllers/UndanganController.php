<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Services\NomorSuratService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UndanganController extends Controller
{
    public function create()
    {
        return view('dokumen.undangan.form', [
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
        abort_unless($document->jenis === 'undangan', 404);
        abort_if($document->isFinal(), 403, 'Dokumen final tidak dapat diedit.');

        $content = array_merge($this->defaultContent(), (array) $document->content);

        return view('dokumen.undangan.form', [
            'document' => $document,
            'content' => $content,
        ]);
    }

    public function update(Request $request, Document $document)
    {
        abort_unless($document->jenis === 'undangan', 404);
        abort_if($document->isFinal(), 403, 'Dokumen final tidak dapat diubah.');

        return $this->save($request, $document);
    }

    public function printView(Document $document)
    {
        abort_unless($document->jenis === 'undangan', 404);
        $document->load('creator');

        return view('dokumen.undangan.print', [
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
            'waktu_mulai' => ['nullable', 'string', 'max:20'],
            'waktu_selesai' => ['nullable', 'string', 'max:20'],
            'tempat' => [$isFinal ? 'required' : 'nullable', 'string', 'max:255'],
            'pemimpin_rapat' => ['nullable', 'string', 'max:255'],
            'agenda' => ['nullable', 'string'],
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

        if ($isFinal && count($undangan) < 1) {
            return back()->withInput()->withErrors([
                'undangan' => 'Minimal 1 nama undangan wajib diisi untuk finalisasi dokumen.',
            ]);
        }

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
            if ($document === null) {
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

                return redirect()->route('dokumen.show', $document)
                    ->with('status', 'Undangan berhasil difinalisasi dan nomor surat resmi telah diterbitkan.');
            }

            $route = $document->wasRecentlyCreated ? 'dokumen.undangan.edit' : 'dokumen.undangan.edit';
            return redirect()->route($route, $document)
                ->with('status', 'Draf undangan berhasil disimpan.');
        });
    }
}
