<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use App\Models\Surat;
use App\Services\SuratRenderer;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SuratController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $surat = Surat::with('jenisSurat')->latest()->get();

        return view('surat.index', compact('surat'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $jenisSuratList = JenisSurat::orderBy('nama_surat')->get();

        $jenisSuratId = old('jenis_surat_id') ?? $request->query('jenis_surat_id');

        if (! $jenisSuratId) {
            return view('surat.create', ['jenisSuratList' => $jenisSuratList, 'jenisSurat' => null]);
        }

        $jenisSurat = JenisSurat::findOrFail($jenisSuratId);

        return view('surat.create', compact('jenisSuratList', 'jenisSurat'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'jenis_surat_id' => ['required', 'exists:jenis_surat,id'],
            'nomor_surat' => ['required', 'string', 'max:255'],
            'tanggal_surat' => ['required', 'date'],
            'konten_surat' => ['required', 'string'],
        ]);

        $surat = Surat::create($validated);

        return redirect()->route('surat.show', $surat);
    }

    /**
     * Display the specified resource.
     */
    public function show(Surat $surat, SuratRenderer $renderer): View
    {
        $konten = $this->renderKonten($surat, $renderer);

        return view('surat.show', compact('surat', 'konten'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Surat $surat): View
    {
        return view('surat.edit', [
            'jenisSurat' => $surat->jenisSurat,
            'surat' => $surat,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Surat $surat): RedirectResponse
    {
        $validated = $request->validate([
            'nomor_surat' => ['required', 'string', 'max:255'],
            'tanggal_surat' => ['required', 'date'],
            'konten_surat' => ['required', 'string'],
        ]);

        $surat->update($validated);

        return redirect()->route('surat.show', $surat);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Surat $surat): RedirectResponse
    {
        $surat->delete();

        return redirect()->route('surat.index');
    }

    /**
     * Export the specified resource as a PDF file (preview in browser).
     */
    public function exportPdf(Surat $surat, SuratRenderer $renderer): Response
    {
        return $this->makePdf($surat, $renderer)->stream($this->pdfFilename($surat));
    }

    /**
     * Download the specified resource as a PDF file to device.
     */
    public function downloadPdf(Surat $surat, SuratRenderer $renderer): Response
    {
        return $this->makePdf($surat, $renderer)->download($this->pdfFilename($surat));
    }

    protected function makePdf(Surat $surat, SuratRenderer $renderer): \Barryvdh\DomPDF\PDF
    {
        $surat->load('jenisSurat');
        $jenisSurat = $surat->jenisSurat;

        $konten = $this->renderKonten($surat, $renderer);
        $headerHtml = null;
        $footerHtml = null;

        // Otomatis: ulangi kop di setiap halaman jika terdeteksi.
        $parts = $renderer->splitKop($konten);
        if ($parts['header'] !== null) {
            $headerHtml = $parts['header'];
            $konten = $parts['body'];
        }

        // Footer: gunakan footer manual jika diisi, jika tidak ambil otomatis
        // dari tabel paling bawah (blok tanda tangan: Samarinda, tanggal s.d. NIP).
        if ($jenisSurat->ulang_footer && filled($jenisSurat->footer_surat)) {
            $footerHtml = $renderer->render($jenisSurat->footer_surat, [
                'nama_surat' => $jenisSurat->nama_surat,
                'nomor_surat' => $surat->nomor_surat,
                'tanggal_surat' => $renderer->tanggalIndonesia($surat->tanggal_surat),
            ], $jenisSurat->logo);
        } else {
            $footerParts = $renderer->splitFooter($konten);
            if ($footerParts['footer'] !== null) {
                $footerHtml = $footerParts['footer'];
                $konten = $footerParts['body'];
            }
        }

        return Pdf::loadView('surat.pdf', [
            'konten' => $konten,
            'headerHtml' => $headerHtml,
            'footerHtml' => $footerHtml,
            'marginAtas' => $jenisSurat->margin_atas,
            'marginBawah' => $jenisSurat->margin_bawah,
            'marginKiri' => $jenisSurat->margin_kiri,
            'marginKanan' => $jenisSurat->margin_kanan,
        ])->setPaper($jenisSurat->ukuran_kertas ?: 'a4');
    }

    protected function pdfFilename(Surat $surat): string
    {
        return str_replace(['/', '\\'], '-', $surat->nomor_surat).'.pdf';
    }

    /**
     * Render konten surat dengan mengganti kode isian.
     */
    protected function renderKonten(Surat $surat, SuratRenderer $renderer): string
    {
        $jenisSurat = $surat->jenisSurat;

        return $renderer->render($surat->konten_surat, [
            'nama_surat' => $jenisSurat->nama_surat,
            'nomor_surat' => $surat->nomor_surat,
            'tanggal_surat' => $renderer->tanggalIndonesia($surat->tanggal_surat),
        ], $jenisSurat->logo);
    }
}
