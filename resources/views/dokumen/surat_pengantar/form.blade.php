@extends('layouts.app')

@section('title', $document ? 'Edit Surat Pengantar' : 'Buat Surat Pengantar')

@section('content')
    <div class="w-full space-y-6">
        <div>
            <a href="{{ route('dokumen.index') }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-ink-500 transition hover:text-green-700">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
                Kembali ke Daftar Dokumen
            </a>
            <h2 class="mt-2 font-display text-[28px] font-semibold leading-tight text-ink-900">
                {{ $document ? 'Edit surat pengantar' : 'Buat surat pengantar baru' }}
            </h2>
        </div>

        @if ($errors->any())
            <div class="rounded-md border border-error-bg bg-error-bg px-4 py-3 text-sm text-error-text">
                <p class="font-semibold">Mohon lengkapi isian form berikut:</p>
                <ul class="mt-1 list-inside list-disc space-y-0.5 text-xs">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ $document ? route('dokumen.surat-pengantar.update', $document) : route('dokumen.surat-pengantar.store') }}" class="space-y-6">
            @csrf
            @if ($document)
                @method('PUT')
            @endif

            <div class="rounded-lg border border-frame border-t-[3px] border-t-ink-700 bg-surface p-5 sm:p-6 space-y-4">
                <h3 class="font-display text-lg font-semibold text-ink-900">Informasi surat pengantar</h3>
                <div>
                    <label class="block text-xs font-medium text-ink-700">Perihal <span class="text-error-text">*</span></label>
                    <input type="text" name="perihal" value="{{ old('perihal', $content['perihal'] ?? '') }}" required class="mt-1.5 w-full rounded-md border border-frame bg-surface px-3 py-2.5 text-sm text-ink-900 outline-none transition focus:border-gold-600 focus:ring-2 focus:ring-gold-100">
                </div>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-medium text-ink-700">Tanggal surat</label>
                        <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat', $content['tanggal_surat'] ?? date('Y-m-d')) }}" class="tnum mt-1.5 w-full rounded-md border border-frame bg-surface px-3 py-2 text-sm text-ink-900 outline-none transition focus:border-gold-600 focus:ring-2 focus:ring-gold-100">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-ink-700">Tujuan / alamat <span class="text-error-text">*</span></label>
                        <input type="text" name="tujuan" value="{{ old('tujuan', $content['tujuan'] ?? '') }}" class="mt-1.5 w-full rounded-md border border-frame bg-surface px-3 py-2 text-sm text-ink-900 outline-none transition focus:border-gold-600 focus:ring-2 focus:ring-gold-100">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-ink-700">Isi ringkas</label>
                    <textarea name="isi_ringkas" rows="4" class="mt-1.5 w-full rounded-md border border-frame bg-surface px-3 py-2 text-sm text-ink-900 outline-none transition focus:border-gold-600 focus:ring-2 focus:ring-gold-100">{{ old('isi_ringkas', $content['isi_ringkas'] ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-medium text-ink-700">Penandatangan</label>
                    <input type="text" name="penandatangan" value="{{ old('penandatangan', $content['penandatangan'] ?? '') }}" class="mt-1.5 w-full rounded-md border border-frame bg-surface px-3 py-2 text-sm text-ink-900 outline-none transition focus:border-gold-600 focus:ring-2 focus:ring-gold-100">
                </div>
            </div>

            <div class="flex flex-col-reverse items-center justify-end gap-3 pt-2 sm:flex-row">
                <button type="submit" name="action" value="draft" class="w-full rounded-md border border-frame-strong bg-surface px-5 py-2.5 text-sm font-medium text-ink-900 transition hover:bg-surface-muted sm:w-auto">Simpan Draf</button>
                <button type="submit" name="action" value="final" onclick="return confirm('Finalisasi dokumen? Setelah final tidak dapat diubah/dihapus.')" class="w-full rounded-md bg-gold-600 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-gold-500 sm:w-auto">Finalisasi Dokumen</button>
            </div>
        </form>
    </div>
@endsection
