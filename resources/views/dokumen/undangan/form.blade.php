@extends('layouts.app')

@section('title', $document ? 'Edit Undangan' : 'Buat Undangan')

@section('content')
    @php
        $input = 'mt-1 h-10 w-full rounded-md border border-control bg-surface px-3 text-base text-ink placeholder:text-ink-muted focus-visible:outline-2 focus-visible:outline-offset-0 focus-visible:outline-focus';
        $label = 'block text-sm font-medium text-ink';
    @endphp

    <div class="space-y-6">
        <x-breadcrumb :items="[
            ['label' => 'Beranda', 'url' => route('beranda')],
            ['label' => 'Daftar Dokumen', 'url' => route('dokumen.index')],
            ['label' => $document ? 'Edit Undangan' : 'Buat Undangan'],
        ]" />

        <x-page-header :title="$document ? 'Edit Undangan' : 'Buat Undangan'"
            description="Pemanggilan resmi anggota senat untuk rapat rutin, sidang pleno, atau rapat koordinasi." />

        @if ($errors->any())
            <x-alert variant="error">
                <p class="font-semibold">Terdapat {{ $errors->count() }} isian yang perlu diperbaiki.</p>
                <ul class="mt-1 list-inside list-disc space-y-1 text-xs">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-alert>
        @endif

        <form method="POST" action="{{ $document ? route('dokumen.undangan.update', $document) : route('dokumen.undangan.store') }}">
            @csrf
            @if ($document)
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
                <div class="space-y-6 lg:col-span-8">
                    {{-- Informasi surat --}}
                    <section class="rounded-md border border-line bg-surface p-6">
                        <h2 class="font-serif text-xl font-semibold text-ink">Informasi Surat</h2>
                        <div class="mt-1 border-b border-line pb-3"></div>

                        <div class="mt-4 space-y-4">
                            <div>
                                <label for="perihal" class="{{ $label }}">Perihal <span class="font-normal text-ink-muted">(wajib)</span></label>
                                <input id="perihal" type="text" name="perihal" value="{{ old('perihal', $content['perihal'] ?? '') }}" required class="{{ $input }}">
                            </div>

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                                <div>
                                    <label for="tanggal_surat" class="{{ $label }}">Tanggal surat</label>
                                    <input id="tanggal_surat" type="date" name="tanggal_surat" value="{{ old('tanggal_surat', $content['tanggal_surat'] ?? date('Y-m-d')) }}" class="tnum {{ $input }}">
                                </div>
                                <div>
                                    <label for="tanggal_acara" class="{{ $label }}">Tanggal acara</label>
                                    <input id="tanggal_acara" type="date" name="tanggal_acara" value="{{ old('tanggal_acara', $content['tanggal_acara'] ?? '') }}" class="tnum {{ $input }}">
                                </div>
                                <div>
                                    <label for="tempat" class="{{ $label }}">Tempat</label>
                                    <input id="tempat" type="text" name="tempat" value="{{ old('tempat', $content['tempat'] ?? '') }}" class="{{ $input }}">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="waktu_mulai" class="{{ $label }}">Waktu mulai</label>
                                    <input id="waktu_mulai" type="time" name="waktu_mulai" value="{{ old('waktu_mulai', $content['waktu_mulai'] ?? '') }}" class="tnum {{ $input }}">
                                </div>
                                <div>
                                    <label for="waktu_selesai" class="{{ $label }}">Waktu selesai</label>
                                    <input id="waktu_selesai" type="time" name="waktu_selesai" value="{{ old('waktu_selesai', $content['waktu_selesai'] ?? '') }}" class="tnum {{ $input }}">
                                </div>
                            </div>

                            <div>
                                <label for="pemimpin_rapat" class="{{ $label }}">Pemimpin rapat</label>
                                <input id="pemimpin_rapat" type="text" name="pemimpin_rapat" value="{{ old('pemimpin_rapat', $content['pemimpin_rapat'] ?? '') }}" class="{{ $input }}">
                            </div>
                        </div>
                    </section>

                    {{-- Agenda --}}
                    <section class="rounded-md border border-line bg-surface p-6">
                        <h2 class="font-serif text-xl font-semibold text-ink">Agenda</h2>
                        <div class="mt-1 border-b border-line pb-3"></div>
                        <div class="mt-4">
                            <label for="agenda" class="{{ $label }}">Poin agenda rapat</label>
                            <textarea id="agenda" name="agenda" rows="4" class="{{ $input }} h-auto py-2">{{ old('agenda', $content['agenda'] ?? '') }}</textarea>
                            <p class="mt-1 text-sm text-ink-muted">Tuliskan satu poin per baris.</p>
                        </div>
                    </section>

                    {{-- Daftar undangan --}}
                    <section class="rounded-md border border-line bg-surface p-6">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <div>
                                <h2 class="font-serif text-xl font-semibold text-ink">Daftar Undangan</h2>
                                <p class="mt-1 text-sm text-ink-muted">Tambahkan nama dan jabatan penerima undangan.</p>
                            </div>
                            <x-button type="button" id="btn-add-undangan" variant="secondary" size="sm">
                                <x-icon name="plus" class="size-4" /> Tambah baris
                            </x-button>
                        </div>

                        <div id="undangan-rows" class="mt-4 space-y-2">
                            @php
                                $rows = old('undangan', $content['undangan'] ?? []);
                                if (empty($rows)) { $rows = [['nama' => '', 'jabatan_instansi' => '']]; }
                            @endphp
                            @foreach ($rows as $i => $row)
                                <div class="undangan-row grid grid-cols-1 items-center gap-2 sm:grid-cols-[1fr_1fr_auto]">
                                    <input type="text" name="undangan[{{ $i }}][nama]" value="{{ $row['nama'] ?? '' }}" placeholder="Nama" aria-label="Nama penerima undangan" class="{{ $input }} mt-0">
                                    <input type="text" name="undangan[{{ $i }}][jabatan_instansi]" value="{{ $row['jabatan_instansi'] ?? '' }}" placeholder="Jabatan / instansi" aria-label="Jabatan atau instansi" class="{{ $input }} mt-0">
                                    <button type="button" class="btn-remove-undangan inline-flex h-10 w-10 items-center justify-center rounded-md text-ink-muted transition-colors hover:bg-danger-50 hover:text-danger-700" aria-label="Hapus baris">
                                        <x-icon name="trash-2" class="size-4" />
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </section>
                </div>

                {{-- Panel aksi --}}
                <aside class="lg:col-span-4">
                    <div class="space-y-4 lg:sticky lg:top-24">
                        <section class="rounded-md border border-line bg-surface p-6">
                            <h2 class="font-serif text-lg font-semibold text-ink">Status Dokumen</h2>
                            <div class="mt-3 flex items-center gap-2">
                                <x-status-badge :status="$document?->status ?? 'draft'" />
                                <span class="text-sm text-ink-muted">{{ $document ? 'Dokumen tersimpan' : 'Dokumen baru' }}</span>
                            </div>
                            <p class="mt-3 text-sm leading-relaxed text-ink-muted">
                                Nomor surat dan QR diberikan otomatis saat finalisasi.
                            </p>
                            @if ($document?->updated_at)
                                <p class="tnum mt-2 text-xs text-ink-muted">Terakhir disimpan {{ $document->updated_at->translatedFormat('d M Y, H.i') }} WITA</p>
                            @endif
                        </section>

                        <section class="rounded-md border border-line bg-surface p-6">
                            <div class="space-y-2">
                                <x-button type="submit" name="action" value="draft" variant="secondary" class="w-full">Simpan Draf</x-button>
                                <x-button type="submit" name="action" value="final" class="w-full"
                                    onclick="return confirm('Finalisasi dokumen? Setelah final, dokumen mendapat nomor resmi dan tidak dapat diubah atau dihapus.')">
                                    Finalisasi
                                </x-button>
                            </div>
                            <p class="mt-3 text-xs leading-relaxed text-ink-muted">
                                Finalisasi menerbitkan nomor resmi. Dokumen final tidak dapat diubah atau dihapus.
                            </p>
                        </section>
                    </div>
                </aside>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('undangan-rows');
            if (!container) return;

            function reindex() {
                container.querySelectorAll('.undangan-row').forEach((row, i) => {
                    const inputs = row.querySelectorAll('input');
                    if (inputs[0]) inputs[0].name = `undangan[${i}][nama]`;
                    if (inputs[1]) inputs[1].name = `undangan[${i}][jabatan_instansi]`;
                });
            }

            function addRow() {
                const i = container.querySelectorAll('.undangan-row').length;
                const div = document.createElement('div');
                div.className = 'undangan-row grid grid-cols-1 items-center gap-2 sm:grid-cols-[1fr_1fr_auto]';
                const inputClass = 'mt-0 h-10 w-full rounded-md border border-control bg-surface px-3 text-base text-ink placeholder:text-ink-muted focus-visible:outline-2 focus-visible:outline-offset-0 focus-visible:outline-focus';
                div.innerHTML = `
                    <input type="text" name="undangan[${i}][nama]" placeholder="Nama" aria-label="Nama penerima undangan" class="${inputClass}">
                    <input type="text" name="undangan[${i}][jabatan_instansi]" placeholder="Jabatan / instansi" aria-label="Jabatan atau instansi" class="${inputClass}">
                    <button type="button" class="btn-remove-undangan inline-flex h-10 w-10 items-center justify-center rounded-md text-ink-muted transition-colors hover:bg-danger-50 hover:text-danger-700" aria-label="Hapus baris">Hapus</button>
                `;
                container.appendChild(div);
            }

            document.getElementById('btn-add-undangan')?.addEventListener('click', () => { addRow(); reindex(); });

            container.addEventListener('click', (e) => {
                if (e.target.closest('.btn-remove-undangan')) {
                    e.target.closest('.undangan-row').remove();
                    if (container.querySelectorAll('.undangan-row').length === 0) addRow();
                    reindex();
                }
            });
        });
    </script>
@endsection
