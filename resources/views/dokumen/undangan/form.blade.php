@extends('layouts.app')

@section('title', $document ? 'Edit Undangan' : 'Buat Undangan')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">
        <div>
            <a href="{{ route('dokumen.index') }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-ink-500 transition hover:text-green-700">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
                Kembali ke Daftar Dokumen
            </a>
            <h2 class="mt-2 font-display text-[28px] font-semibold leading-tight text-ink-900">
                {{ $document ? 'Edit undangan' : 'Buat undangan baru' }}
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

        <form method="POST" action="{{ $document ? route('dokumen.undangan.update', $document) : route('dokumen.undangan.store') }}" class="space-y-6">
            @csrf
            @if ($document)
                @method('PUT')
            @endif

            <div class="rounded-lg border border-frame bg-surface p-5 sm:p-6 space-y-4">
                <h3 class="font-display text-lg font-semibold text-ink-900">Informasi undangan</h3>
                <div>
                    <label class="block text-xs font-medium text-ink-700">Perihal <span class="text-error-text">*</span></label>
                    <input type="text" name="perihal" value="{{ old('perihal', $content['perihal'] ?? '') }}" required class="mt-1.5 w-full rounded-md border border-frame bg-surface px-3 py-2.5 text-sm text-ink-900 outline-none transition focus:border-gold-600 focus:ring-2 focus:ring-gold-100">
                </div>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div>
                        <label class="block text-xs font-medium text-ink-700">Tanggal surat</label>
                        <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat', $content['tanggal_surat'] ?? date('Y-m-d')) }}" class="tnum mt-1.5 w-full rounded-md border border-frame bg-surface px-3 py-2 text-sm text-ink-900 outline-none transition focus:border-gold-600 focus:ring-2 focus:ring-gold-100">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-ink-700">Tanggal acara</label>
                        <input type="date" name="tanggal_acara" value="{{ old('tanggal_acara', $content['tanggal_acara'] ?? '') }}" class="tnum mt-1.5 w-full rounded-md border border-frame bg-surface px-3 py-2 text-sm text-ink-900 outline-none transition focus:border-gold-600 focus:ring-2 focus:ring-gold-100">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-ink-700">Tempat</label>
                        <input type="text" name="tempat" value="{{ old('tempat', $content['tempat'] ?? '') }}" class="mt-1.5 w-full rounded-md border border-frame bg-surface px-3 py-2 text-sm text-ink-900 outline-none transition focus:border-gold-600 focus:ring-2 focus:ring-gold-100">
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-medium text-ink-700">Waktu mulai</label>
                        <input type="time" name="waktu_mulai" value="{{ old('waktu_mulai', $content['waktu_mulai'] ?? '') }}" class="tnum mt-1.5 w-full rounded-md border border-frame bg-surface px-3 py-2 text-sm text-ink-900 outline-none transition focus:border-gold-600 focus:ring-2 focus:ring-gold-100">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-ink-700">Waktu selesai</label>
                        <input type="time" name="waktu_selesai" value="{{ old('waktu_selesai', $content['waktu_selesai'] ?? '') }}" class="tnum mt-1.5 w-full rounded-md border border-frame bg-surface px-3 py-2 text-sm text-ink-900 outline-none transition focus:border-gold-600 focus:ring-2 focus:ring-gold-100">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-ink-700">Pemimpin rapat</label>
                    <input type="text" name="pemimpin_rapat" value="{{ old('pemimpin_rapat', $content['pemimpin_rapat'] ?? '') }}" class="mt-1.5 w-full rounded-md border border-frame bg-surface px-3 py-2 text-sm text-ink-900 outline-none transition focus:border-gold-600 focus:ring-2 focus:ring-gold-100">
                </div>
                <div>
                    <label class="block text-xs font-medium text-ink-700">Agenda</label>
                    <textarea name="agenda" rows="3" class="mt-1.5 w-full rounded-md border border-frame bg-surface px-3 py-2 text-sm text-ink-900 outline-none transition focus:border-gold-600 focus:ring-2 focus:ring-gold-100">{{ old('agenda', $content['agenda'] ?? '') }}</textarea>
                </div>
            </div>

            <div class="rounded-lg border border-frame bg-surface p-5 sm:p-6">
                <h3 class="font-display text-lg font-semibold text-ink-900">Daftar undangan</h3>
                <p class="mt-0.5 text-xs text-ink-400">Isi nama dan jabatan (5 baris tersedia).</p>
                <div class="mt-4 space-y-2">
                    @for ($i = 0; $i < 5; $i++)
                        @php $row = old("undangan.$i", $content['undangan'][$i] ?? ['nama' => '', 'jabatan_instansi' => '']); @endphp
                        <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                            <input type="text" name="undangan[{{ $i }}][nama]" value="{{ $row['nama'] ?? '' }}" placeholder="Nama" class="rounded-md border border-frame bg-surface px-3 py-2 text-sm text-ink-900 outline-none transition focus:border-gold-600 focus:ring-2 focus:ring-gold-100">
                            <input type="text" name="undangan[{{ $i }}][jabatan_instansi]" value="{{ $row['jabatan_instansi'] ?? '' }}" placeholder="Jabatan / instansi" class="rounded-md border border-frame bg-surface px-3 py-2 text-sm text-ink-900 outline-none transition focus:border-gold-600 focus:ring-2 focus:ring-gold-100">
                        </div>
                    @endfor
                </div>
            </div>

            <div class="flex flex-col-reverse items-center justify-end gap-3 pt-2 sm:flex-row">
                <button type="submit" name="action" value="draft" class="w-full rounded-md border border-frame-strong bg-surface px-5 py-2.5 text-sm font-medium text-ink-900 transition hover:bg-surface-muted sm:w-auto">Simpan Draf</button>
                <button type="submit" name="action" value="final" onclick="return confirm('Finalisasi dokumen? Setelah final tidak dapat diubah/dihapus.')" class="w-full rounded-md bg-gold-600 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-gold-500 sm:w-auto">Finalisasi Dokumen</button>
            </div>
        </form>
    </div>
@endsection