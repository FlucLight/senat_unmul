@extends('layouts.app')

@section('title', $document ? 'Edit Surat Pengantar' : 'Buat Surat Pengantar')

@section('content')
    @php
        $input = 'mt-1 h-10 w-full rounded-md border border-control bg-surface px-3 text-base text-ink placeholder:text-ink-muted focus-visible:outline-2 focus-visible:outline-offset-0 focus-visible:outline-focus';
        $label = 'block text-sm font-medium text-ink';
    @endphp

    <div class="space-y-6">
        <x-breadcrumb :items="[
            ['label' => 'Beranda', 'url' => route('beranda')],
            ['label' => 'Daftar Dokumen', 'url' => route('dokumen.index')],
            ['label' => $document ? 'Edit Surat Pengantar' : 'Buat Surat Pengantar'],
        ]" />

        <x-page-header :title="$document ? 'Edit Surat Pengantar' : 'Buat Surat Pengantar'"
            description="Pengiriman berkas pertimbangan atau rekomendasi senat kepada Dekan maupun Rektorat." />

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

        <form method="POST" action="{{ $document ? route('dokumen.surat-pengantar.update', $document) : route('dokumen.surat-pengantar.store') }}">
            @csrf
            @if ($document)
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
                <div class="space-y-6 lg:col-span-8">
                    <section class="rounded-md border border-line bg-surface p-6">
                        <h2 class="font-serif text-xl font-semibold text-ink">Informasi Surat Pengantar</h2>
                        <div class="mt-1 border-b border-line pb-3"></div>

                        <div class="mt-4 space-y-4">
                            <div>
                                <label for="perihal" class="{{ $label }}">Perihal <span class="font-normal text-ink-muted">(wajib)</span></label>
                                <input id="perihal" type="text" name="perihal" value="{{ old('perihal', $content['perihal'] ?? '') }}" required class="{{ $input }}">
                            </div>

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="tanggal_surat" class="{{ $label }}">Tanggal surat</label>
                                    <input id="tanggal_surat" type="date" name="tanggal_surat" value="{{ old('tanggal_surat', $content['tanggal_surat'] ?? date('Y-m-d')) }}" class="tnum {{ $input }}">
                                </div>
                                <div>
                                    <label for="tujuan" class="{{ $label }}">Tujuan / alamat <span class="font-normal text-ink-muted">(wajib)</span></label>
                                    <input id="tujuan" type="text" name="tujuan" value="{{ old('tujuan', $content['tujuan'] ?? '') }}" class="{{ $input }}">
                                </div>
                            </div>

                            <div>
                                <label for="isi_ringkas" class="{{ $label }}">Isi ringkas</label>
                                <textarea id="isi_ringkas" name="isi_ringkas" rows="5" class="{{ $input }} h-auto py-2">{{ old('isi_ringkas', $content['isi_ringkas'] ?? '') }}</textarea>
                            </div>

                            <div>
                                <label for="penandatangan" class="{{ $label }}">Penandatangan</label>
                                <input id="penandatangan" type="text" name="penandatangan" value="{{ old('penandatangan', $content['penandatangan'] ?? '') }}" class="{{ $input }}">
                            </div>
                        </div>
                    </section>
                </div>

                <aside class="lg:col-span-4">
                    <div class="space-y-4 lg:sticky lg:top-24">
                        <section class="rounded-md border border-line bg-surface p-6">
                            <h2 class="font-serif text-lg font-semibold text-ink">Status Dokumen</h2>
                            <div class="mt-3 flex items-center gap-2">
                                <x-status-badge :status="$document?->status ?? 'draft'" />
                                <span class="text-sm text-ink-muted">{{ $document ? 'Dokumen tersimpan' : 'Dokumen baru' }}</span>
                            </div>
                            <p class="mt-3 text-sm leading-relaxed text-ink-muted">Nomor surat dan QR diberikan otomatis saat finalisasi.</p>
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
                            <p class="mt-3 text-xs leading-relaxed text-ink-muted">Finalisasi menerbitkan nomor resmi. Dokumen final tidak dapat diubah atau dihapus.</p>
                        </section>
                    </div>
                </aside>
            </div>
        </form>
    </div>
@endsection
