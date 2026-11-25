<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesHtmlDocument;
use App\Models\Document;
use App\Services\DaftarHadirGenerator;
use App\Services\Document\DocumentRenderer;
use App\Services\HtmlSanitizer;
use App\Services\NomorSuratService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DaftarHadirController extends Controller
{
    use HandlesHtmlDocument;

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
                'variant' => 'zigzag',
                'blank_rows' => 20,
            ],
        ]);
    }

    public function store(Request $request)
    {
        if ($request->input('form_mode') === 'generator') {
            return $this->storeFromGenerator($request);
        }
        if ($request->input('form_mode') === 'html') {
            return $this->saveHtmlDaftarHadir($request, null);
        }

        return $this->saveLegacy($request, null);
    }

    public function edit(Document $document)
    {
        abort_unless($document->jenis === 'daftar_hadir', 404);
        $this->authorize('update', $document);
        abort_if($document->isFinal(), 403, 'Dokumen final tidak dapat diedit.');

        $content = (array) $document->content;
        if (($content['format'] ?? null) === 'html') {
            [$templates, $bodies] = $this->htmlTemplates('daftar_hadir');
            $meta = (array) ($content['meta'] ?? []);
            $generator = (array) ($content['generator'] ?? []);

            return view('dokumen.daftar_hadir.form_html', [
                'document' => $document,
                'templates' => $templates,
                'templateBodies' => $bodies,
                'selectedTemplate' => $content['template_key'] ?? 'zigzag',
                'meta' => [
                    'perihal' => $meta['perihal'] ?? $document->judul,
                    'tanggal_surat' => $meta['tanggal_surat'] ?? now()->toDateString(),
                ],
                'bodyHtml' => (string) ($content['body_html'] ?? ''),
                'generator' => $generator,
            ]);
        }

        $content = array_merge([
            'nama_acara' => $document->judul,
            'tanggal' => '',
            'waktu_mulai' => '',
            'waktu_selesai' => '',
            'tempat' => '',
            'penyelenggara' => '',
            'peserta' => [],
        ], $content);

        return view('dokumen.daftar_hadir.form_legacy', [
            'document' => $document,
            'content' => $content,
        ]);
    }

    public function update(Request $request, Document $document)
    {
        abort_unless($document->jenis === 'daftar_hadir', 404);
        $this->authorize('update', $document);
        abort_if($document->isFinal(), 403, 'Dokumen final tidak dapat diubah.');

        if ($request->input('form_mode') === 'html' || (($document->content['format'] ?? null) === 'html')) {
            if ($request->input('action') === 'regenerate') {
                return $this->regenerateFromData($request, $document);
            }

            return $this->saveHtmlDaftarHadir($request, $document);
        }

        return $this->saveLegacy($request, $document);
    }

    public function printView(Document $document, DocumentRenderer $renderer)
    {
        abort_unless($document->jenis === 'daftar_hadir', 404);
        $document->load('creator');

        return $renderer->print($document);
    }

    public function verifikasi(Document $document)
    {
        $document->load('creator');

        $state = match (true) {
            $document->isFinal() => 'sah',
            $document->status === Document::STATUS_DIBATALKAN => 'dibatalkan',
            default => 'tidak_ditemukan',
        };

        return view('dokumen.verifikasi', [
            'document' => $document,
            'state' => $state,
        ]);
    }

    private function storeFromGenerator(Request $request)
    {
        $validated = $request->validate([
            'nama_acara' => ['required', 'string', 'max:255'],
            'tanggal' => ['nullable', 'date'],
            'waktu_mulai' => ['nullable', 'string', 'max:20'],
            'waktu_selesai' => ['nullable', 'string', 'max:20'],
            'tempat' => ['nullable', 'string', 'max:255'],
            'penyelenggara' => ['nullable', 'string', 'max:255'],
            'variant' => ['required', 'in:zigzag,satu_kolom,blank'],
            'blank_rows' => ['nullable', 'integer', 'min:1', 'max:100'],
            'peserta' => ['nullable', 'array'],
            'peserta.*.nama' => ['nullable', 'string', 'max:255'],
            'peserta.*.jabatan_instansi' => ['nullable', 'string', 'max:255'],
        ]);

        $peserta = $this->normalizePeserta($request->input('peserta', []));
        $generator = [
            'nama_acara' => $validated['nama_acara'],
            'tanggal' => $validated['tanggal'] ?? null,
            'waktu_mulai' => $validated['waktu_mulai'] ?? null,
            'waktu_selesai' => $validated['waktu_selesai'] ?? null,
            'tempat' => $validated['tempat'] ?? null,
            'penyelenggara' => $validated['penyelenggara'] ?? null,
            'variant' => $validated['variant'],
            'blank_rows' => (int) ($validated['blank_rows'] ?? 20),
            'peserta' => $peserta,
        ];

        $bodyHtml = app(DaftarHadirGenerator::class)->generate($generator);
        $bodyHtml = app(HtmlSanitizer::class)->sanitize($bodyHtml);

        $document = Document::create([
            'jenis' => 'daftar_hadir',
            'judul' => $validated['nama_acara'],
            'status' => Document::STATUS_DRAFT,
            'created_by' => auth()->id(),
            'content' => [
                'format' => 'html',
                'schema_version' => 2,
                'template_key' => $validated['variant'],
                'body_html' => $bodyHtml,
                'meta' => [
                    'perihal' => $validated['nama_acara'],
                    'tanggal_surat' => $validated['tanggal'] ?? now()->toDateString(),
                ],
                'generator' => $generator,
            ],
        ]);

        return redirect()->route('dokumen.daftar-hadir.edit', $document)
            ->with('status', 'Dokumen daftar hadir berhasil dibuat. Silakan sunting isi dokumen.');
    }

    private function saveHtmlDaftarHadir(Request $request, ?Document $document)
    {
        $isFinal = $request->input('action') === 'final';
        $isPreview = $request->input('action') === 'preview';

        $validated = $request->validate([
            'perihal' => ['required', 'string', 'max:255'],
            'tanggal_surat' => ['required', 'date'],
            'template_key' => ['nullable', 'string', 'max:100'],
            'body_html' => [$isFinal ? 'required' : 'nullable', 'string'],
        ]);

        $sanitizer = app(HtmlSanitizer::class);
        $bodyHtml = $sanitizer->sanitize($validated['body_html'] ?? '');

        if ($isFinal && trim(strip_tags($bodyHtml)) === '') {
            return back()->withInput()->withErrors(['body_html' => 'Isi dokumen wajib diisi sebelum finalisasi.']);
        }
        if ($isFinal && ! str_contains($bodyHtml, 'data-table="peserta"')) {
            return back()->withInput()->withErrors(['body_html' => 'Tabel peserta tidak ditemukan. Dokumen daftar hadir harus memiliki tabel peserta.']);
        }

        $content = (array) ($document?->content ?? []);
        $content = array_merge($content, [
            'format' => 'html',
            'schema_version' => 2,
            'template_key' => $validated['template_key'] ?: ($content['template_key'] ?? 'zigzag'),
            'body_html' => $bodyHtml,
            'meta' => [
                'perihal' => $validated['perihal'],
                'tanggal_surat' => $validated['tanggal_surat'],
            ],
        ]);

        return DB::transaction(function () use ($document, $validated, $content, $isFinal, $isPreview) {
            if (! $document) {
                $document = Document::create([
                    'jenis' => 'daftar_hadir',
                    'judul' => $validated['perihal'],
                    'status' => Document::STATUS_DRAFT,
                    'created_by' => auth()->id(),
                    'content' => $content,
                ]);
            } else {
                $document->update(['judul' => $validated['perihal'], 'content' => $content]);
            }

            if ($isFinal) {
                $nomorSurat = app(NomorSuratService::class)->generate('daftar_hadir');
                $document->update([
                    'status' => Document::STATUS_FINAL,
                    'nomor_surat' => $nomorSurat,
                    'finalized_at' => now(),
                    'qr_verification_url' => route('dokumen.verifikasi', $document->id),
                ]);
                $fresh = $document->fresh()->load('creator');
                $payload = app(DocumentRenderer::class)->pdfPayload($fresh);
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($payload['view'], $payload['data'])->setPaper('a4');
                $path = 'dokumen/' . $document->id . '.pdf';
                Storage::disk('public')->put($path, $pdf->output());
                $document->update(['pdf_path' => $path]);

                return redirect()->route('dokumen.show', $document)
                    ->with('status', 'Daftar hadir berhasil difinalisasi dan nomor surat resmi telah diterbitkan.');
            }

            if ($isPreview) {
                return redirect()->route('dokumen.show', $document)->with('status', 'Draf berhasil disimpan. Ini pratinjau dokumen Anda.');
            }

            return redirect()->route('dokumen.daftar-hadir.edit', $document)->with('status', 'Perubahan draf daftar hadir berhasil disimpan.');
        });
    }

    private function regenerateFromData(Request $request, Document $document)
    {
        $content = (array) $document->content;
        $generator = (array) ($content['generator'] ?? []);
        if (empty($generator)) {
            return back()->with('error', 'Data generator tidak tersedia untuk dokumen ini.');
        }

        $bodyHtml = app(DaftarHadirGenerator::class)->generate($generator);
        $bodyHtml = app(HtmlSanitizer::class)->sanitize($bodyHtml);
        $content['body_html'] = $bodyHtml;
        $content['template_key'] = $generator['variant'] ?? ($content['template_key'] ?? 'zigzag');
        $document->update(['content' => $content]);

        return redirect()->route('dokumen.daftar-hadir.edit', $document)
            ->with('status', 'Dokumen berhasil dibuat ulang dari data generator. Suntingan manual sebelumnya diganti.');
    }

    private function normalizePeserta(array $peserta): array
    {
        return collect($peserta)
            ->map(fn ($p) => [
                'nama' => trim($p['nama'] ?? ''),
                'jabatan_instansi' => trim($p['jabatan_instansi'] ?? ''),
            ])
            ->filter(fn ($p) => $p['nama'] !== '' || $p['jabatan_instansi'] !== '')
            ->values()
            ->all();
    }

    protected function saveLegacy(Request $request, ?Document $document)
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
            if (! $document) {
                $document = Document::create([
                    'jenis' => 'daftar_hadir',
                    'judul' => $validated['nama_acara'],
                    'status' => Document::STATUS_DRAFT,
                    'created_by' => auth()->id(),
                    'content' => $content,
                ]);
            } else {
                $document->update(['judul' => $validated['nama_acara'], 'content' => $content]);
            }

            if ($isFinal) {
                $nomorSurat = app(NomorSuratService::class)->generate('daftar_hadir');
                $document->update([
                    'status' => Document::STATUS_FINAL,
                    'nomor_surat' => $nomorSurat,
                    'finalized_at' => now(),
                    'qr_verification_url' => route('dokumen.verifikasi', $document->id),
                ]);

                $fresh = $document->fresh()->load('creator');
                $payload = app(DocumentRenderer::class)->pdfPayload($fresh);
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($payload['view'], $payload['data'])->setPaper('a4');
                $path = 'dokumen/' . $document->id . '.pdf';
                Storage::disk('public')->put($path, $pdf->output());
                $document->update(['pdf_path' => $path]);

                return redirect()->route('dokumen.show', $document)
                    ->with('status', 'Daftar hadir berhasil difinalisasi dan nomor surat resmi telah diterbitkan.');
            }

            return redirect()->route('dokumen.daftar-hadir.edit', $document)
                ->with('status', $document->wasRecentlyCreated ? 'Draf daftar hadir berhasil disimpan.' : 'Perubahan draf daftar hadir berhasil disimpan.');
        });
    }

    protected function generateNomorSurat(): string
    {
        $count = Document::where('status', Document::STATUS_FINAL)->count() + 1;
        $sequence = str_pad($count, 3, '0', STR_PAD_LEFT);
        $romans = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'];
        $month = $romans[now()->month] ?? 'IX';
        $year = now()->year;

        return "{$sequence}/DH/Senat-FT/{$month}/{$year}";
    }
}
