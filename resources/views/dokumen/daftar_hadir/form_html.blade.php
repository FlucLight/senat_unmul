@extends('layouts.app')

@section('title', 'Edit Daftar Hadir')

@section('content')
@php
    $actionUrl = route('dokumen.daftar-hadir.update', $document);
    $perihal = old('perihal', $meta['perihal'] ?? $document->judul);
    $tanggalSurat = old('tanggal_surat', $meta['tanggal_surat'] ?? now()->toDateString());
    $templateKey = old('template_key', $selectedTemplate ?? 'zigzag');
    $body = old('body_html', $bodyHtml ?? '');
@endphp
<div class="space-y-6">
    <x-breadcrumb :items="[
        ['label' => 'Beranda', 'url' => route('beranda')],
        ['label' => 'Daftar Dokumen', 'url' => route('dokumen.index')],
        ['label' => 'Edit Daftar Hadir'],
    ]" />

    <x-page-header title="Edit Daftar Hadir" description="Sunting isi dokumen daftar hadir. Gunakan helper untuk mengelola baris peserta." />

    @if ($errors->any())
        <x-alert variant="error">
            <p class="font-semibold">Terdapat {{ $errors->count() }} isian yang perlu diperbaiki.</p>
            <ul class="mt-1 list-inside list-disc space-y-1 text-xs">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </x-alert>
    @endif
    @if (session('error'))
        <x-alert variant="error">{{ session('error') }}</x-alert>
    @endif

    <form method="POST" action="{{ $actionUrl }}" class="space-y-6">
        @csrf
        @method('PUT')
        <input type="hidden" name="form_mode" value="html">

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
            <div class="space-y-6 lg:col-span-8">
                <section class="rounded-md border border-line bg-surface p-6">
                    <h2 class="font-serif text-xl font-semibold text-ink">Data Surat</h2>
                    <div class="mt-1 border-b border-line pb-3"></div>
                    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label for="perihal" class="block text-sm font-medium text-ink">Perihal / Judul <span class="font-normal text-ink-muted">(wajib)</span></label>
                            <input id="perihal" name="perihal" type="text" value="{{ $perihal }}" required class="mt-1 h-10 w-full rounded-md border border-control bg-surface px-3 text-base text-ink">
                        </div>
                        <div>
                            <label for="tanggal_surat" class="block text-sm font-medium text-ink">Tanggal Surat <span class="font-normal text-ink-muted">(wajib)</span></label>
                            <input id="tanggal_surat" name="tanggal_surat" type="date" value="{{ $tanggalSurat }}" required class="tnum mt-1 h-10 w-full rounded-md border border-control bg-surface px-3 text-base text-ink">
                        </div>
                        <div>
                            <label for="template_key" class="block text-sm font-medium text-ink">Varian Template</label>
                            <select id="template_key" name="template_key" class="mt-1 h-10 w-full rounded-md border border-control bg-surface px-3 text-base text-ink">
                                @foreach($templates as $key => $tpl)
                                    <option value="{{ $key }}" @selected($templateKey === $key)>{{ $tpl['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </section>

                <section class="rounded-md border border-line bg-surface p-6">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <h2 class="font-serif text-xl font-semibold text-ink">Isi Dokumen</h2>
                            <p class="mt-1 text-sm text-ink-muted">Kop surat otomatis. Token abu-abu tidak dapat diedit langsung.</p>
                        </div>
                        @if(!empty($generator))
                        <button type="submit" name="action" value="regenerate" class="rounded-md border border-frame-strong bg-surface px-3 py-1.5 text-xs font-medium text-ink hover:bg-surface-muted"
                            data-confirm="Buat ulang dari data akan menimpa suntingan manual di editor. Lanjutkan?"
                            data-confirm-title="Buat Ulang dari Data"
                            data-confirm-ok="Ya, Buat Ulang">Buat Ulang dari Data</button>
                        @endif
                    </div>

                    <div class="mt-4 rounded-md border border-line bg-paper-200 p-3">
                        <p class="text-xs font-medium text-ink">Helper Tabel Peserta</p>
                        <p class="mt-0.5 text-xs text-ink-muted">Helper hanya bekerja pada tabel peserta di dalam editor.</p>
                        <div class="mt-2 flex flex-wrap gap-2">
                            <button type="button" id="dh-add-row" class="rounded border border-frame-strong bg-surface px-2.5 py-1.5 text-xs hover:bg-surface-muted">+ Tambah Baris</button>
                            <button type="button" id="dh-add-10" class="rounded border border-frame-strong bg-surface px-2.5 py-1.5 text-xs hover:bg-surface-muted">+ 10 Baris</button>
                            <button type="button" id="dh-paste" class="rounded border border-frame-strong bg-surface px-2.5 py-1.5 text-xs hover:bg-surface-muted">Tempel Daftar Nama</button>
                            <button type="button" id="dh-remove-empty" class="rounded border border-frame-strong bg-surface px-2.5 py-1.5 text-xs hover:bg-surface-muted">Hapus Baris Kosong di Akhir</button>
                        </div>
                        <p id="dh-helper-msg" class="mt-2 text-xs text-ink-muted"></p>
                    </div>

                    <div class="mt-4 rounded-md border border-line bg-paper-200 p-4">
                        <div class="flex items-center gap-4 border-b border-line pb-3">
                            <img src="{{ asset('image.png') }}" alt="" class="h-12 w-auto object-contain">
                            <div class="min-w-0 flex-1 text-center">
                                <p class="text-[11px] font-bold uppercase text-ink">Kementerian Pendidikan Tinggi, Sains, dan Teknologi</p>
                                <p class="text-[13px] font-bold uppercase text-ink">Universitas Mulawarman</p>
                                <p class="text-[14px] font-bold uppercase text-ink">Fakultas Teknik — Senat Fakultas</p>
                                <p class="text-[9px] text-ink-muted">Jalan Sambaliung No. 9, Kampus Gunung Kelua, Samarinda 75119</p>
                            </div>
                            <div class="w-12"></div>
                        </div>
                        <div class="h-0.5 bg-gold-600"></div>
                        <p class="mt-2 text-center text-[11px] text-ink-muted">Kop surat otomatis (tidak dapat diedit)</p>
                    </div>

                    <div class="mt-4">
                        <x-rich-editor name="body_html" id="body_html" :label="null" :value="$body" :required="true" />
                    </div>
                </section>
            </div>

            <aside class="lg:col-span-4">
                <div class="space-y-4 lg:sticky lg:top-24">
                    <section class="rounded-md border border-line bg-surface p-6">
                        <h2 class="font-serif text-lg font-semibold text-ink">Status Dokumen</h2>
                        <div class="mt-3 flex items-center gap-2"><x-status-badge :status="$document->status" /><span class="text-sm text-ink-muted">Dokumen tersimpan</span></div>
                        <p class="mt-3 text-sm text-ink-muted">Nomor surat dan QR diberikan otomatis saat finalisasi.</p>
                    </section>
                    <section class="rounded-md border border-line bg-surface p-6">
                        <div class="space-y-2">
                            <x-button type="submit" name="action" value="draft" variant="secondary" class="w-full">Simpan Draf</x-button>
                            <x-button type="submit" name="action" value="preview" variant="secondary" class="w-full">Pratinjau</x-button>
                            <x-button type="submit" name="action" value="final" class="w-full"
                                data-confirm="Setelah difinalisasi, dokumen akan mendapatkan nomor resmi dan tidak dapat diubah atau dihapus lagi."
                                data-confirm-title="Finalisasi Dokumen?"
                                data-confirm-ok="Finalisasi">Finalisasi</x-button>
                        </div>
                    </section>
                </div>
            </aside>
        </div>
    </form>
</div>

<div id="dh-paste-modal" class="fixed inset-0 z-50 hidden bg-ink/50">
    <div class="mx-auto mt-24 max-w-lg rounded-lg border border-line bg-surface p-6">
        <h3 class="font-serif text-xl font-semibold text-ink">Tempel Daftar Nama</h3>
        <p class="mt-1 text-sm text-ink-muted">Satu nama per baris. Opsional: "Nama | Jabatan" atau tab.</p>
        <textarea id="dh-paste-text" rows="8" class="mt-3 w-full rounded-md border border-frame bg-surface p-3 text-xs"></textarea>
        <div class="mt-4 flex justify-end gap-2">
            <x-button type="button" id="dh-paste-cancel" variant="secondary" size="sm">Batal</x-button>
            <x-button type="button" id="dh-paste-apply" size="sm">Tambahkan</x-button>
        </div>
    </div>
</div>

@vite('resources/js/daftar-hadir-table.js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    function getEditor() { return window.tinymce?.get('body_html'); }
    function withTable(fn) {
        const ed = getEditor();
        const msg = document.getElementById('dh-helper-msg');
        const doc = ed ? ed.getDoc() : document;
        const table = (window.DaftarHadirTable?.getPesertaTable || (()=>null))(doc);
        if (!table) { if (msg) msg.textContent = 'Tabel peserta tidak ditemukan. Helper dinonaktifkan untuk dokumen ini.'; return; }
        if (msg) msg.textContent = '';
        fn(table, ed);
        if (ed) { ed.undoManager.transact(function(){ renumberInEditor(ed); }); ed.setDirty(true); }
    }
    function renumberInEditor(ed) {
        const doc = ed ? ed.getDoc() : document;
        const table = window.DaftarHadirTable?.getPesertaTable(doc);
        if (table) window.DaftarHadirTable.renumber(table);
    }

    document.getElementById('dh-add-row')?.addEventListener('click', () => withTable((table) => window.DaftarHadirTable.addRows(table, 1)));
    document.getElementById('dh-add-10')?.addEventListener('click', () => withTable((table) => window.DaftarHadirTable.addRows(table, 10)));
    document.getElementById('dh-remove-empty')?.addEventListener('click', () => withTable((table) => { const n = window.DaftarHadirTable.removeEmptyTrailing(table); const msg = document.getElementById('dh-helper-msg'); if (msg) msg.textContent = n ? `${n} baris kosong dihapus.` : 'Tidak ada baris kosong di akhir.'; }));

    const modal = document.getElementById('dh-paste-modal');
    document.getElementById('dh-paste')?.addEventListener('click', () => { modal.classList.remove('hidden'); document.getElementById('dh-paste-text').value=''; document.getElementById('dh-paste-text').focus(); });
    document.getElementById('dh-paste-cancel')?.addEventListener('click', () => modal.classList.add('hidden'));
    modal?.addEventListener('click', (e) => { if (e.target === modal) modal.classList.add('hidden'); });
    document.getElementById('dh-paste-apply')?.addEventListener('click', () => {
        const text = document.getElementById('dh-paste-text').value || '';
        withTable((table) => { const res = window.DaftarHadirTable.pasteNames(table, text); const msg = document.getElementById('dh-helper-msg'); if (msg) msg.textContent = `${res.filled} nama diproses, ${res.added} baris baru ditambah.`; });
        modal.classList.add('hidden');
    });
});
</script>
@endsection
