@extends('layouts.app')

@section('title', $document ? 'Edit Daftar Hadir' : 'Buat Daftar Hadir')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">
        {{-- Header & Breadcrumb --}}
        <div>
            <a href="{{ route('dokumen.index') }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-ink-500 transition hover:text-green-700">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar Isi
            </a>
            <h2 class="mt-2 font-display text-[28px] font-semibold leading-tight text-ink-900">
                {{ $document ? 'Edit daftar hadir' : 'Buat daftar hadir baru' }}
            </h2>
            <p class="mt-1 text-sm text-ink-400">
                Formulir terstruktur untuk presensi rapat/acara resmi Senat FT.
            </p>
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

        <form id="daftar-hadir-form" method="POST" action="{{ $document ? route('dokumen.daftar-hadir.update', $document) : route('dokumen.daftar-hadir.store') }}" class="space-y-6">
            @csrf
            @if ($document)
                @method('PUT')
            @endif

            {{-- 1. Bagian Info Acara --}}
            <div class="rounded-lg border border-frame bg-surface p-5 sm:p-6">
                <h3 class="font-display text-lg font-semibold text-ink-900">Informasi acara</h3>
                <p class="mt-0.5 text-xs text-ink-400">Rincian kegiatan yang akan diselenggarakan.</p>

                <div class="mt-5 space-y-4">
                    <div>
                        <label for="nama_acara" class="block text-xs font-medium text-ink-700">
                            Nama acara / rapat <span class="text-error-text">*</span>
                        </label>
                        <input
                            type="text"
                            name="nama_acara"
                            id="nama_acara"
                            value="{{ old('nama_acara', $content['nama_acara'] ?? '') }}"
                            required
                            placeholder="Contoh: Rapat Pleno Penetapan Senat FT"
                            class="mt-1.5 w-full rounded-md border border-frame bg-surface px-3 py-2.5 text-sm text-ink-900 placeholder-ink-400 outline-none transition focus:border-gold-600 focus:ring-2 focus:ring-gold-100"
                        >
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <div>
                            <label for="tanggal" class="block text-xs font-medium text-ink-700">
                                Tanggal acara <span class="text-error-text">*</span>
                            </label>
                            <input
                                type="date"
                                name="tanggal"
                                id="tanggal"
                                value="{{ old('tanggal', $content['tanggal'] ?? date('Y-m-d')) }}"
                                class="tnum mt-1.5 w-full rounded-md border border-frame bg-surface px-3 py-2 text-sm text-ink-900 outline-none transition focus:border-gold-600 focus:ring-2 focus:ring-gold-100"
                            >
                        </div>

                        <div>
                            <label for="waktu_mulai" class="block text-xs font-medium text-ink-700">
                                Waktu mulai <span class="text-error-text">*</span>
                            </label>
                            <input
                                type="time"
                                name="waktu_mulai"
                                id="waktu_mulai"
                                value="{{ old('waktu_mulai', $content['waktu_mulai'] ?? '09:00') }}"
                                class="tnum mt-1.5 w-full rounded-md border border-frame bg-surface px-3 py-2 text-sm text-ink-900 outline-none transition focus:border-gold-600 focus:ring-2 focus:ring-gold-100"
                            >
                        </div>

                        <div>
                            <label for="waktu_selesai" class="block text-xs font-medium text-ink-700">
                                Waktu selesai <span class="text-error-text">*</span>
                            </label>
                            <input
                                type="time"
                                name="waktu_selesai"
                                id="waktu_selesai"
                                value="{{ old('waktu_selesai', $content['waktu_selesai'] ?? '11:00') }}"
                                class="tnum mt-1.5 w-full rounded-md border border-frame bg-surface px-3 py-2 text-sm text-ink-900 outline-none transition focus:border-gold-600 focus:ring-2 focus:ring-gold-100"
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="tempat" class="block text-xs font-medium text-ink-700">
                                Tempat / lokasi <span class="text-error-text">*</span>
                            </label>
                            <input
                                type="text"
                                name="tempat"
                                id="tempat"
                                value="{{ old('tempat', $content['tempat'] ?? '') }}"
                                placeholder="Contoh: Ruang Sidang Senat Lantai 3 Dekanat"
                                class="mt-1.5 w-full rounded-md border border-frame bg-surface px-3 py-2 text-sm text-ink-900 placeholder-ink-400 outline-none transition focus:border-gold-600 focus:ring-2 focus:ring-gold-100"
                            >
                        </div>

                        <div>
                            <label for="penyelenggara" class="block text-xs font-medium text-ink-700">
                                Penyelenggara / pimpinan rapat <span class="text-ink-400 font-normal">(opsional)</span>
                            </label>
                            <input
                                type="text"
                                name="penyelenggara"
                                id="penyelenggara"
                                value="{{ old('penyelenggara', $content['penyelenggara'] ?? '') }}"
                                placeholder="Contoh: Ketua Senat Fakultas Teknik"
                                class="mt-1.5 w-full rounded-md border border-frame bg-surface px-3 py-2 text-sm text-ink-900 placeholder-ink-400 outline-none transition focus:border-gold-600 focus:ring-2 focus:ring-gold-100"
                            >
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Bagian Daftar Peserta --}}
            <div class="rounded-lg border border-frame bg-surface p-5 sm:p-6">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="font-display text-lg font-semibold text-ink-900">Daftar peserta</h3>
                        <p class="mt-0.5 text-xs text-ink-400">
                            Nama dan jabatan peserta rapat. Kolom tanda tangan basah akan digenerate di PDF cetak.
                        </p>
                    </div>

                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            id="btn-open-paste-modal"
                            class="inline-flex items-center gap-1.5 rounded-md border border-frame-strong bg-surface px-3 py-1.5 text-xs font-medium text-ink-700 transition hover:bg-surface-muted"
                        >
                            <svg class="h-3.5 w-3.5 text-ink-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" />
                            </svg>
                            Tempel dari teks
                        </button>

                        <button
                            type="button"
                            id="btn-add-row"
                            class="inline-flex items-center gap-1.5 rounded-md border border-frame-strong bg-surface px-3 py-1.5 text-xs font-medium text-ink-900 transition hover:bg-surface-muted"
                        >
                            <svg class="h-3.5 w-3.5 text-green-700" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Tambah baris
                        </button>
                    </div>
                </div>

                {{-- Tabel Peserta Responsive (Tabel di Desktop, Card List di Mobile) --}}
                <div class="mt-4 overflow-hidden rounded-md border border-frame">
                    <table class="w-full text-left text-sm">
                        <thead class="hidden bg-surface-muted text-xs font-medium text-ink-700 md:table-header-group">
                            <tr>
                                <th class="w-12 px-3 py-2.5 text-center">No.</th>
                                <th class="px-3 py-2.5">Nama peserta <span class="text-error-text">*</span></th>
                                <th class="px-3 py-2.5">Jabatan / instansi</th>
                                <th class="w-12 px-3 py-2.5 text-center">Hapus</th>
                            </tr>
                        </thead>
                        <tbody id="peserta-tbody" class="divide-y divide-frame p-2 md:p-0">
                            @php
                                $rows = old('peserta', $content['peserta'] ?? []);
                                if (empty($rows)) {
                                    $rows = [
                                        ['nama' => '', 'jabatan_instansi' => ''],
                                        ['nama' => '', 'jabatan_instansi' => ''],
                                    ];
                                }
                            @endphp

                            @foreach ($rows as $index => $p)
                                <tr class="peserta-row relative mb-3 flex flex-col gap-2 rounded-md border border-frame bg-surface p-3 transition md:mb-0 md:table-row md:border-0 md:border-b md:bg-transparent md:p-0 hover:bg-gold-50/50">
                                    <td class="tnum text-xs font-semibold text-ink-400 md:table-cell md:w-12 md:px-3 md:py-2.5 md:text-center">
                                        <span class="md:hidden">Peserta #</span><span class="row-number">{{ $index + 1 }}</span>
                                    </td>
                                    <td class="md:table-cell md:px-3 md:py-2">
                                        <label class="mb-1 block text-[11px] font-medium text-ink-500 md:hidden">Nama peserta</label>
                                        <input
                                            type="text"
                                            name="peserta[{{ $index }}][nama]"
                                            value="{{ $p['nama'] ?? '' }}"
                                            placeholder="Nama lengkap & gelar"
                                            class="peserta-input-nama w-full rounded border border-frame bg-surface px-2.5 py-1.5 text-xs text-ink-900 placeholder-ink-400 outline-none transition focus:border-gold-600 focus:ring-1 focus:ring-gold-100"
                                        >
                                    </td>
                                    <td class="md:table-cell md:px-3 md:py-2">
                                        <label class="mb-1 block text-[11px] font-medium text-ink-500 md:hidden">Jabatan / Instansi</label>
                                        <input
                                            type="text"
                                            name="peserta[{{ $index }}][jabatan_instansi]"
                                            value="{{ $p['jabatan_instansi'] ?? '' }}"
                                            placeholder="Contoh: Dosen Teknik Sipil"
                                            class="w-full rounded border border-frame bg-surface px-2.5 py-1.5 text-xs text-ink-900 placeholder-ink-400 outline-none transition focus:border-gold-600 focus:ring-1 focus:ring-gold-100"
                                        >
                                    </td>
                                    <td class="absolute top-2 right-2 md:static md:table-cell md:w-12 md:px-3 md:py-2 md:text-center">
                                        <button
                                            type="button"
                                            class="btn-remove-row inline-flex h-7 w-7 items-center justify-center rounded text-ink-400 transition hover:bg-error-bg hover:text-error-text"
                                            title="Hapus baris peserta"
                                        >
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 md:hidden">
                    <button
                        type="button"
                        id="btn-add-row-mobile"
                        class="w-full rounded-md border border-frame-strong bg-surface py-2 text-center text-xs font-semibold text-ink-900 transition hover:bg-surface-muted"
                    >
                        + Tambah Peserta
                    </button>
                </div>
            </div>

            {{-- 3. Tombol Aksi Bawah --}}
            <div class="flex flex-col-reverse items-center justify-end gap-3 pt-2 sm:flex-row">
                <button
                    type="submit"
                    name="action"
                    value="draft"
                    class="w-full rounded-md border border-frame-strong bg-surface px-5 py-2.5 text-sm font-medium text-ink-900 transition hover:bg-surface-muted sm:w-auto"
                >
                    Simpan Draf
                </button>

                <button
                    type="button"
                    id="btn-open-finalize"
                    class="w-full rounded-md bg-gold-600 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-gold-500 sm:w-auto"
                >
                    Finalisasi Dokumen
                </button>
            </div>
        </form>
    </div>

    {{-- Modal Konfirmasi Finalisasi --}}
    <div id="finalize-modal-backdrop" class="fixed inset-0 z-50 hidden bg-ink-900/60 backdrop-blur-sm"></div>
    <div id="finalize-modal" class="fixed inset-x-4 top-1/2 z-50 mx-auto hidden max-w-md -translate-y-1/2 rounded-lg border border-frame bg-surface p-6 shadow-[0_2px_8px_rgba(34,34,29,0.08)]">
        <h3 class="font-display text-lg font-semibold text-ink-900">Konfirmasi finalisasi</h3>
        <p class="mt-2 text-xs leading-relaxed text-ink-500">
            Dokumen yang difinalisasi akan otomatis diterbitkan nomor surat resmi dan kode QR verifikasi. <strong>Dokumen yang sudah final tidak dapat diubah atau dihapus kembali.</strong>
        </p>

        <div class="my-4 rounded-md border border-frame bg-surface-muted p-3.5 text-xs text-ink-700 space-y-1.5">
            <p><span class="text-ink-400">Acara:</span> <strong id="modal-summary-acara" class="text-ink-900">—</strong></p>
            <p><span class="text-ink-400">Tanggal:</span> <span id="modal-summary-tanggal" class="tnum">—</span></p>
            <p><span class="text-ink-400">Tempat:</span> <span id="modal-summary-tempat">—</span></p>
            <p><span class="text-ink-400">Jumlah Peserta Terisi:</span> <strong id="modal-summary-peserta" class="tnum text-green-700">0</strong> orang</p>
        </div>

        <div class="flex items-center justify-end gap-2 pt-2">
            <button
                type="button"
                id="btn-cancel-finalize"
                class="rounded-md border border-frame bg-surface px-4 py-2 text-xs font-medium text-ink-700 transition hover:bg-surface-muted"
            >
                Batal
            </button>
            <button
                type="button"
                id="btn-confirm-finalize"
                class="rounded-md bg-gold-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-gold-500"
            >
                Ya, Finalisasi
            </button>
        </div>
    </div>

    {{-- Modal Tempel Cepat Nama Peserta --}}
    <div id="paste-modal-backdrop" class="fixed inset-0 z-50 hidden bg-ink-900/60 backdrop-blur-sm"></div>
    <div id="paste-modal" class="fixed inset-x-4 top-1/2 z-50 mx-auto hidden max-w-lg -translate-y-1/2 rounded-lg border border-frame bg-surface p-6 shadow-[0_2px_8px_rgba(34,34,29,0.08)]">
        <h3 class="font-display text-lg font-semibold text-ink-900">Tempel daftar nama peserta</h3>
        <p class="mt-1 text-xs text-ink-400">
            Salin dan tempel daftar nama dari pesan WhatsApp atau Excel (satu nama per baris).
        </p>

        <textarea
            id="paste-textarea"
            rows="7"
            placeholder="Dr. Eng. Ir. Fulan, M.T.&#10;Prof. Dr. Ir. Budi, M.Eng.&#10;Siti Rahma, S.T., M.T."
            class="mt-3 w-full rounded-md border border-frame bg-surface p-3 text-xs text-ink-900 placeholder-ink-400 outline-none transition focus:border-gold-600 focus:ring-2 focus:ring-gold-100 font-mono"
        ></textarea>

        <div class="mt-4 flex items-center justify-end gap-2">
            <button
                type="button"
                id="btn-cancel-paste"
                class="rounded-md border border-frame bg-surface px-4 py-2 text-xs font-medium text-ink-700 hover:bg-surface-muted"
            >
                Batal
            </button>
            <button
                type="button"
                id="btn-apply-paste"
                class="rounded-md bg-gold-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-gold-500"
            >
                Tambahkan ke tabel
            </button>
        </div>
    </div>

    {{-- Script interaktif untuk baris dinamis & modal --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tbody = document.getElementById('peserta-tbody');
            const form = document.getElementById('daftar-hadir-form');

            function updateRowNumbers() {
                const rows = tbody.querySelectorAll('.peserta-row');
                rows.forEach((row, index) => {
                    const numberSpan = row.querySelector('.row-number');
                    if (numberSpan) numberSpan.textContent = index + 1;

                    const nameInput = row.querySelector('input[name*="[nama]"]');
                    const jabInput = row.querySelector('input[name*="[jabatan_instansi]"]');

                    if (nameInput) nameInput.name = `peserta[${index}][nama]`;
                    if (jabInput) jabInput.name = `peserta[${index}][jabatan_instansi]`;
                });
            }

            function createRow(nama = '', jabatan = '') {
                const index = tbody.querySelectorAll('.peserta-row').length;
                const tr = document.createElement('tr');
                tr.className = 'peserta-row relative mb-3 flex flex-col gap-2 rounded-md border border-frame bg-surface p-3 transition md:mb-0 md:table-row md:border-0 md:border-b md:bg-transparent md:p-0 hover:bg-gold-50/50';

                tr.innerHTML = `
                    <td class="tnum text-xs font-semibold text-ink-400 md:table-cell md:w-12 md:px-3 md:py-2.5 md:text-center">
                        <span class="md:hidden">Peserta #</span><span class="row-number">${index + 1}</span>
                    </td>
                    <td class="md:table-cell md:px-3 md:py-2">
                        <label class="mb-1 block text-[11px] font-medium text-ink-500 md:hidden">Nama peserta</label>
                        <input
                            type="text"
                            name="peserta[${index}][nama]"
                            value="${escapeHtml(nama)}"
                            placeholder="Nama lengkap & gelar"
                            class="peserta-input-nama w-full rounded border border-frame bg-surface px-2.5 py-1.5 text-xs text-ink-900 placeholder-ink-400 outline-none transition focus:border-gold-600 focus:ring-1 focus:ring-gold-100"
                        >
                    </td>
                    <td class="md:table-cell md:px-3 md:py-2">
                        <label class="mb-1 block text-[11px] font-medium text-ink-500 md:hidden">Jabatan / Instansi</label>
                        <input
                            type="text"
                            name="peserta[${index}][jabatan_instansi]"
                            value="${escapeHtml(jabatan)}"
                            placeholder="Contoh: Dosen Teknik Sipil"
                            class="w-full rounded border border-frame bg-surface px-2.5 py-1.5 text-xs text-ink-900 placeholder-ink-400 outline-none transition focus:border-gold-600 focus:ring-1 focus:ring-gold-100"
                        >
                    </td>
                    <td class="absolute top-2 right-2 md:static md:table-cell md:w-12 md:px-3 md:py-2 md:text-center">
                        <button
                            type="button"
                            class="btn-remove-row inline-flex h-7 w-7 items-center justify-center rounded text-ink-400 transition hover:bg-error-bg hover:text-error-text"
                            title="Hapus baris peserta"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                        </button>
                    </td>
                `;

                tbody.appendChild(tr);
            }

            function escapeHtml(str) {
                return String(str)
                    .replace(/&/g, '&amp;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;');
            }

            document.getElementById('btn-add-row')?.addEventListener('click', () => {
                createRow();
                updateRowNumbers();
            });

            document.getElementById('btn-add-row-mobile')?.addEventListener('click', () => {
                createRow();
                updateRowNumbers();
            });

            tbody.addEventListener('click', (e) => {
                const btn = e.target.closest('.btn-remove-row');
                if (!btn) return;

                const row = btn.closest('.peserta-row');
                if (row) {
                    row.remove();
                    if (tbody.querySelectorAll('.peserta-row').length === 0) {
                        createRow();
                    }
                    updateRowNumbers();
                }
            });

            // Modal Finalisasi
            const finalizeModal = document.getElementById('finalize-modal');
            const finalizeBackdrop = document.getElementById('finalize-modal-backdrop');
            const btnOpenFinalize = document.getElementById('btn-open-final-modal') || document.getElementById('btn-open-finalize');
            const btnCancelFinalize = document.getElementById('btn-cancel-finalize');
            const btnConfirmFinalize = document.getElementById('btn-confirm-finalize');

            function openFinalizeModal() {
                const namaAcara = document.getElementById('nama_acara')?.value.trim();
                const tanggal = document.getElementById('tanggal')?.value;
                const tempat = document.getElementById('tempat')?.value.trim();

                const filledPeserta = Array.from(tbody.querySelectorAll('.peserta-input-nama'))
                    .filter(input => input.value.trim() !== '').length;

                document.getElementById('modal-summary-acara').textContent = namaAcara || '(Belum diisi)';
                document.getElementById('modal-summary-tanggal').textContent = tanggal || '(Belum diisi)';
                document.getElementById('modal-summary-tempat').textContent = tempat || '(Belum diisi)';
                document.getElementById('modal-summary-peserta').textContent = filledPeserta;

                finalizeModal.classList.remove('hidden');
                finalizeBackdrop.classList.remove('hidden');
            }

            function closeFinalizeModal() {
                finalizeModal.classList.add('hidden');
                finalizeBackdrop.classList.add('hidden');
            }

            if (btnOpenFinalize) btnOpenFinalize.addEventListener('click', openFinalizeModal);
            if (btnCancelFinalize) btnCancelFinalize.addEventListener('click', closeFinalizeModal);
            if (finalizeBackdrop) finalizeBackdrop.addEventListener('click', closeFinalizeModal);

            if (btnConfirmFinalize) {
                btnConfirmFinalize.addEventListener('click', () => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'action';
                    input.value = 'final';
                    form.appendChild(input);
                    form.submit();
                });
            }

            // Modal Tempel Cepat
            const pasteModal = document.getElementById('paste-modal');
            const pasteBackdrop = document.getElementById('paste-modal-backdrop');
            const btnOpenPaste = document.getElementById('btn-open-paste-modal');
            const btnCancelPaste = document.getElementById('btn-cancel-paste');
            const btnApplyPaste = document.getElementById('btn-apply-paste');
            const pasteTextarea = document.getElementById('paste-textarea');

            function openPasteModal() {
                pasteTextarea.value = '';
                pasteModal.classList.remove('hidden');
                pasteBackdrop.classList.remove('hidden');
                pasteTextarea.focus();
            }

            function closePasteModal() {
                pasteModal.classList.add('hidden');
                pasteBackdrop.classList.add('hidden');
            }

            if (btnOpenPaste) btnOpenPaste.addEventListener('click', openPasteModal);
            if (btnCancelPaste) btnCancelPaste.addEventListener('click', closePasteModal);
            if (pasteBackdrop) pasteBackdrop.addEventListener('click', closePasteModal);

            if (btnApplyPaste) {
                btnApplyPaste.addEventListener('click', () => {
                    const text = pasteTextarea.value;
                    const lines = text.split('\n')
                        .map(l => l.trim())
                        .filter(l => l.length > 0);

                    if (lines.length > 0) {
                        // Clear empty first rows if any
                        const existingInputs = tbody.querySelectorAll('.peserta-input-nama');
                        if (existingInputs.length <= 2) {
                            const allEmpty = Array.from(existingInputs).every(i => i.value.trim() === '');
                            if (allEmpty) {
                                tbody.innerHTML = '';
                            }
                        }

                        lines.forEach(line => {
                            // Support TSV/tab separation if copied from Excel (Nama \t Jabatan)
                            const parts = line.split('\t');
                            const nama = parts[0] || '';
                            const jab = parts[1] || '';
                            createRow(nama, jab);
                        });

                        updateRowNumbers();
                    }

                    closePasteModal();
                });
            }
        });
    </script>
@endsection
