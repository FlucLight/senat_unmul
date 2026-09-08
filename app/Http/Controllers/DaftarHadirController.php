<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DaftarHadirController extends Controller
{
    public function create()
    {
        return view('dokumen.daftar_hadir.form', [
            'document' => null,
            'content' => [
                'nama_acara' => '',
                'tanggal' => date('Y-m-d'),
                'waktu_mulai' => '',
                'waktu_selesai' => '',
                'tempat' => '',
                'penyelenggara' => '',
                'peserta' => [],
            ],
        ]);
    }

    public function store(Request $request)
    {
        $isFinal = $request->input('action') === 'final';

        $rules = [
            'nama_acara' => ['required', 'string', 'max:255'],
            'tanggal' => [$isFinal ? 'required' : 'nullable', 'date'],
            'waktu_mulai' => [$isFinal ? 'required' : 'nullable', 'string', 'max:20'],
            'waktu_selesai' => [$isFinal ? 'required' : 'nullable', 'string', 'max:20'],
            'tempat' => [$isFinal ? 'required' : 'nullable', 'string', 'max:255'],
            'penyelenggara' => ['nullable', 'string', 'max:255'],
            'peserta' => ['nullable', 'array'],
            'peserta.*.nama' => ['nullable', 'string', 'max:255'],
            'peserta.*.jabatan_instansi' => ['nullable', 'string', 'max:255'],
        ];

        $validated = $request->validate($rules);

        // Filter peserta: hanya ambil yang memiliki nama terisi
        $peserta = collect($request->input('peserta', []))
            ->map(fn ($p) => [
                'nama' => trim($p['nama'] ?? ''),
                'jabatan_instansi' => trim($p['jabatan_instansi'] ?? ''),
            ])
            ->filter(fn ($p) => $p['nama'] !== '')
            ->values()
            ->all();

        if ($isFinal && count($peserta) < 1) {
            return back()->withInput()->withErrors([
                'peserta' => 'Minimal 1 nama peserta wajib diisi untuk finalisasi dokumen.',
            ]);
        }

        $content = [
            'nama_acara' => $validated['nama_acara'],
            'tanggal' => $validated['tanggal'] ?? null,
            'waktu_mulai' => $validated['waktu_mulai'] ?? null,
            'waktu_selesai' => $validated['waktu_selesai'] ?? null,
            'tempat' => $validated['tempat'] ?? null,
            'penyelenggara' => $validated['penyelenggara'] ?? null,
            'peserta' => $peserta,
        ];

        return DB::transaction(function () use ($validated, $content, $isFinal) {
            $document = Document::create([
                'jenis' => 'daftar_hadir',
                'judul' => $validated['nama_acara'],
                'status' => Document::STATUS_DRAFT,
                'created_by' => auth()->id(),
                'content' => $content,
            ]);

            if ($isFinal) {
                $nomorSurat = $this->generateNomorSurat();
                $document->update([
                    'status' => Document::STATUS_FINAL,
                    'nomor_surat' => $nomorSurat,
                    'finalized_at' => now(),
                    'qr_verification_url' => route('dokumen.verifikasi', $document->id),
                ]);

                return redirect()->route('dokumen.show', $document)
                    ->with('status', 'Daftar hadir berhasil difinalisasi dan nomor surat resmi telah diterbitkan.');
            }

            return redirect()->route('dokumen.daftar-hadir.edit', $document)
                ->with('status', 'Draf daftar hadir berhasil disimpan.');
        });
    }

    public function edit(Document $document)
    {
        abort_unless($document->jenis === 'daftar_hadir', 404);
        abort_if($document->isFinal(), 403, 'Dokumen final tidak dapat diedit.');

        $content = array_merge([
            'nama_acara' => $document->judul,
            'tanggal' => '',
            'waktu_mulai' => '',
            'waktu_selesai' => '',
            'tempat' => '',
            'penyelenggara' => '',
            'peserta' => [],
        ], (array) $document->content);

        return view('dokumen.daftar_hadir.form', [
            'document' => $document,
            'content' => $content,
        ]);
    }

    public function update(Request $request, Document $document)
    {
        abort_unless($document->jenis === 'daftar_hadir', 404);
        abort_if($document->isFinal(), 403, 'Dokumen final tidak dapat diubah.');

        $isFinal = $request->input('action') === 'final';

        $rules = [
            'nama_acara' => ['required', 'string', 'max:255'],
            'tanggal' => [$isFinal ? 'required' : 'nullable', 'date'],
            'waktu_mulai' => [$isFinal ? 'required' : 'nullable', 'string', 'max:20'],
            'waktu_selesai' => [$isFinal ? 'required' : 'nullable', 'string', 'max:20'],
            'tempat' => [$isFinal ? 'required' : 'nullable', 'string', 'max:255'],
            'penyelenggara' => ['nullable', 'string', 'max:255'],
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

        if ($isFinal && count($peserta) < 1) {
            return back()->withInput()->withErrors([
                'peserta' => 'Minimal 1 nama peserta wajib diisi untuk finalisasi dokumen.',
            ]);
        }

        $content = [
            'nama_acara' => $validated['nama_acara'],
            'tanggal' => $validated['tanggal'] ?? null,
            'waktu_mulai' => $validated['waktu_mulai'] ?? null,
            'waktu_selesai' => $validated['waktu_selesai'] ?? null,
            'tempat' => $validated['tempat'] ?? null,
            'penyelenggara' => $validated['penyelenggara'] ?? null,
            'peserta' => $peserta,
        ];

        return DB::transaction(function () use ($document, $validated, $content, $isFinal) {
            $document->update([
                'judul' => $validated['nama_acara'],
                'content' => $content,
            ]);

            if ($isFinal) {
                $nomorSurat = $this->generateNomorSurat();
                $document->update([
                    'status' => Document::STATUS_FINAL,
                    'nomor_surat' => $nomorSurat,
                    'finalized_at' => now(),
                    'qr_verification_url' => route('dokumen.verifikasi', $document->id),
                ]);

                return redirect()->route('dokumen.show', $document)
                    ->with('status', 'Daftar hadir berhasil difinalisasi dan nomor surat resmi telah diterbitkan.');
            }

            return redirect()->route('dokumen.daftar-hadir.edit', $document)
                ->with('status', 'Perubahan draf daftar hadir berhasil disimpan.');
        });
    }

    public function printView(Document $document)
    {
        abort_unless($document->jenis === 'daftar_hadir', 404);
        $document->load('creator');

        return view('dokumen.daftar_hadir.print', [
            'document' => $document,
            'content' => (array) $document->content,
        ]);
    }

    public function verifikasi(Document $document)
    {
        abort_unless($document->isFinal(), 404);
        $document->load('creator');

        return view('dokumen.verifikasi', [
            'document' => $document,
            'content' => (array) $document->content,
        ]);
    }

    protected function generateNomorSurat(): string
    {
        $count = Document::where('status', Document::STATUS_FINAL)->count() + 1;
        $sequence = str_pad($count, 3, '0', STR_PAD_LEFT);
        $romans = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII',
        ];
        $month = $romans[now()->month] ?? 'IX';
        $year = now()->year;

        return "{$sequence}/DH/Senat-FT/{$month}/{$year}";
    }
}
