<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DokumenController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'jenis' => ['nullable', 'in:undangan,berita_acara,surat_pengantar,daftar_hadir'],
            'status' => ['nullable', 'in:draft,final'],
            'tanggal_awal' => ['nullable', 'date'],
            'tanggal_akhir' => ['nullable', 'date', 'after_or_equal:tanggal_awal'],
            'pembuat' => ['nullable', 'exists:users,id'],
        ]);

        $documents = Document::query()
            ->with('creator')
            ->latest()
            ->when($request->filled('q'), fn ($q) => $q->where(fn ($sub) => $sub
                ->where('judul', 'like', '%'.$request->string('q').'%')
                ->orWhere('nomor_surat', 'like', '%'.$request->string('q').'%')))
            ->when($request->filled('jenis'), fn ($q) => $q->where('jenis', $request->jenis))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('pembuat'), fn ($q) => $q->where('created_by', $request->pembuat))
            ->when($request->filled('tanggal_awal'), fn ($q) => $q->whereDate('created_at', '>=', $request->tanggal_awal))
            ->when($request->filled('tanggal_akhir'), fn ($q) => $q->whereDate('created_at', '<=', $request->tanggal_akhir))
            ->paginate(15)
            ->withQueryString();

        return view('dokumen.index', [
            'documents' => $documents,
            'pembuatList' => \App\Models\User::query()->orderBy('name')->get(['id', 'name']),
            'filters' => $request->only(['q', 'jenis', 'status', 'pembuat', 'tanggal_awal', 'tanggal_akhir']),
            'filtered' => $request->hasAny(['q', 'jenis', 'status', 'pembuat', 'tanggal_awal', 'tanggal_akhir']),
        ]);
    }

    public function show(Document $document)
    {
        $document->load('creator');

        return view('dokumen.show', compact('document'));
    }

    public function destroy(Document $document)
    {
        abort_if($document->isFinal(), 403, 'Dokumen final tidak dapat dihapus.');

        $document->delete();

        return redirect()->route('dokumen.index')
            ->with('status', 'Dokumen draft berhasil dihapus.');
    }

    public function downloadPdf(Document $document)
    {
        if ($document->isDraft()) {
            return redirect()->route('dokumen.show', $document)
                ->with('error', 'File PDF belum tersedia untuk dokumen draf.');
        }

        if ($document->pdf_path && Storage::disk('public')->exists($document->pdf_path)) {
            return Storage::disk('public')->download($document->pdf_path);
        }

        if ($document->jenis === 'daftar_hadir') {
            return redirect()->route('dokumen.cetak', $document);
        }

        return redirect()->route('dokumen.show', $document)
            ->with('error', 'File PDF belum tersedia untuk dokumen ini.');
    }

    public function createPlaceholder(Request $request, string $jenis)
    {
        if ($jenis === 'daftar_hadir') {
            return redirect()->route('dokumen.daftar-hadir.create');
        }

        abort_unless(array_key_exists($jenis, Document::JENIS), 404);

        return view('dokumen.placeholder', [
            'jenis' => $jenis,
            'jenisLabel' => Document::JENIS[$jenis],
            'document' => null,
        ]);
    }

    public function editPlaceholder(Request $request, string $jenis, Document $document)
    {
        if ($jenis === 'daftar_hadir') {
            return redirect()->route('dokumen.daftar-hadir.edit', $document);
        }

        abort_unless(array_key_exists($jenis, Document::JENIS), 404);
        abort_unless($document->isDraft(), 403, 'Hanya dokumen draft yang dapat diedit.');
        abort_unless($document->jenis === $jenis, 404);

        return view('dokumen.placeholder', [
            'jenis' => $jenis,
            'jenisLabel' => Document::JENIS[$jenis],
            'document' => $document,
        ]);
    }
}