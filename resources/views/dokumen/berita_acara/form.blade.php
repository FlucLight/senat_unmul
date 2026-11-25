@extends('layouts.app')

@section('title', $document ? 'Edit Berita Acara' : 'Buat Berita Acara')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">
        <div>
            <a href="{{ route('dokumen.index') }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-ink-500 transition hover:text-green-700">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
                Kembali ke Daftar Dokumen
            </a>
            <h2 class="mt-2 font-display text-[28px] font-semibold leading-tight text-ink-900">
                {{ $document ? 'Edit berita acara' : 'Buat berita acara baru' }}
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

        <form method="POST" action="{{ $document ? route('dokumen.berita-acara.update', $document) : route('dokumen.berita-acara.store') }}" class="space-y-6">
            @csrf
            @if ($document)
                @method('PUT')
            @endif

            <div class="rounded-lg border border-frame border-t-[3px] border-t-gold-600 bg-surface p-5 sm:p-6 space-y-4">
                <h3 class="font-display text-lg font-semibold text-ink-900">Informasi berita acara</h3>
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
                        <label class="block text-xs font-medium text-ink-700">Tanggal rapat</label>
                        <input type="date" name="tanggal_rapat" value="{{ old('tanggal_rapat', $content['tanggal_rapat'] ?? '') }}" class="tnum mt-1.5 w-full rounded-md border border-frame bg-surface px-3 py-2 text-sm text-ink-900 outline-none transition focus:border-gold-600 focus:ring-2 focus:ring-gold-100">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-ink-700">Tempat</label>
                        <input type="text" name="tempat" value="{{ old('tempat', $content['tempat'] ?? '') }}" class="mt-1.5 w-full rounded-md border border-frame bg-surface px-3 py-2 text-sm text-ink-900 outline-none transition focus:border-gold-600 focus:ring-2 focus:ring-gold-100">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-ink-700">Pemimpin rapat</label>
                    <input type="text" name="pemimpin_rapat" value="{{ old('pemimpin_rapat', $content['pemimpin_rapat'] ?? '') }}" class="mt-1.5 w-full rounded-md border border-frame bg-surface px-3 py-2 text-sm text-ink-900 outline-none transition focus:border-gold-600 focus:ring-2 focus:ring-gold-100">
                </div>
                <div>
                    <label class="block text-xs font-medium text-ink-700">Pembahasan</label>
                    <textarea name="pembahasan" rows="4" class="mt-1.5 w-full rounded-md border border-frame bg-surface px-3 py-2 text-sm text-ink-900 outline-none transition focus:border-gold-600 focus:ring-2 focus:ring-gold-100">{{ old('pembahasan', $content['pembahasan'] ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-medium text-ink-700">Keputusan</label>
                    <textarea name="keputusan" rows="3" class="mt-1.5 w-full rounded-md border border-frame bg-surface px-3 py-2 text-sm text-ink-900 outline-none transition focus:border-gold-600 focus:ring-2 focus:ring-gold-100">{{ old('keputusan', $content['keputusan'] ?? '') }}</textarea>
                </div>
            </div>

            <div class="rounded-lg border border-frame border-t-[3px] border-t-gold-600 bg-surface p-5 sm:p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-display text-lg font-semibold text-ink-900">Peserta rapat</h3>
                        <p class="mt-0.5 text-xs text-ink-400">Catat peserta yang hadir beserta jabatan/instansinya.</p>
                    </div>
                    <button type="button" id="btn-add-peserta" class="inline-flex items-center gap-1.5 rounded-md border border-frame-strong bg-surface px-3 py-1.5 text-xs font-medium text-ink-900 transition hover:bg-surface-muted">
                        <svg class="h-3.5 w-3.5 text-gold-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        Tambah
                    </button>
                </div>
                <div id="peserta-rows" class="mt-4 space-y-2">
                    @php
                        $rows = old('peserta', $content['peserta'] ?? []);
                        if (empty($rows)) { $rows = [['nama' => '', 'jabatan_instansi' => '']]; }
                    @endphp
                    @foreach ($rows as $i => $row)
                        <div class="peserta-row grid grid-cols-1 items-center gap-2 sm:grid-cols-[1fr_1fr_auto]">
                            <input type="text" name="peserta[{{ $i }}][nama]" value="{{ $row['nama'] ?? '' }}" placeholder="Nama" class="rounded-md border border-frame bg-surface px-3 py-2 text-sm text-ink-900 outline-none transition focus:border-gold-600 focus:ring-2 focus:ring-gold-100">
                            <input type="text" name="peserta[{{ $i }}][jabatan_instansi]" value="{{ $row['jabatan_instansi'] ?? '' }}" placeholder="Jabatan / instansi" class="rounded-md border border-frame bg-surface px-3 py-2 text-sm text-ink-900 outline-none transition focus:border-gold-600 focus:ring-2 focus:ring-gold-100">
                            <button type="button" class="btn-remove-peserta inline-flex h-9 w-9 items-center justify-center rounded text-ink-400 transition hover:bg-error-bg hover:text-error-text" title="Hapus baris">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex flex-col-reverse items-center justify-end gap-3 pt-2 sm:flex-row">
                <button type="submit" name="action" value="draft" class="w-full rounded-md border border-frame-strong bg-surface px-5 py-2.5 text-sm font-medium text-ink-900 transition hover:bg-surface-muted sm:w-auto">Simpan Draf</button>
                <button type="submit" name="action" value="final" onclick="return confirm('Finalisasi dokumen? Setelah final tidak dapat diubah/dihapus.')" class="w-full rounded-md bg-gold-600 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-gold-500 sm:w-auto">Finalisasi Dokumen</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('peserta-rows');
            function reindex() {
                container.querySelectorAll('.peserta-row').forEach((row, i) => {
                    const inputs = row.querySelectorAll('input');
                    if (inputs[0]) inputs[0].name = `peserta[${i}][nama]`;
                    if (inputs[1]) inputs[1].name = `peserta[${i}][jabatan_instansi]`;
                });
            }
            function addRow(nama = '', jabatan = '') {
                const i = container.querySelectorAll('.peserta-row').length;
                const div = document.createElement('div');
                div.className = 'peserta-row grid grid-cols-1 items-center gap-2 sm:grid-cols-[1fr_1fr_auto]';
                div.innerHTML = `
                    <input type="text" name="peserta[${i}][nama]" value="${nama}" placeholder="Nama" class="rounded-md border border-frame bg-surface px-3 py-2 text-sm text-ink-900 outline-none transition focus:border-gold-600 focus:ring-2 focus:ring-gold-100">
                    <input type="text" name="peserta[${i}][jabatan_instansi]" value="${jabatan}" placeholder="Jabatan / instansi" class="rounded-md border border-frame bg-surface px-3 py-2 text-sm text-ink-900 outline-none transition focus:border-gold-600 focus:ring-2 focus:ring-gold-100">
                    <button type="button" class="btn-remove-peserta inline-flex h-9 w-9 items-center justify-center rounded text-ink-400 transition hover:bg-error-bg hover:text-error-text" title="Hapus baris">✕</button>
                `;
                container.appendChild(div);
            }
            document.getElementById('btn-add-peserta')?.addEventListener('click', () => { addRow(); reindex(); });
            container.addEventListener('click', (e) => {
                if (e.target.closest('.btn-remove-peserta')) {
                    e.target.closest('.peserta-row').remove();
                    if (container.querySelectorAll('.peserta-row').length === 0) addRow();
                    reindex();
                }
            });
        });
    </script>
@endsection