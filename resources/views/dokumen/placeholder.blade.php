@extends('layouts.app')

@section('title', 'Buat '.$jenisLabel)

@section('content')
    <div class="space-y-6">
        <x-breadcrumb :items="[
            ['label' => 'Beranda', 'url' => route('beranda')],
            ['label' => 'Daftar Dokumen', 'url' => route('dokumen.index')],
            ['label' => $document ? 'Edit '.$jenisLabel : 'Buat '.$jenisLabel],
        ]" />

        <x-page-header :title="$document ? 'Edit '.$jenisLabel : 'Buat '.$jenisLabel"
            description="Halaman editor untuk jenis dokumen ini sedang dalam pengembangan." />

        <section class="rounded-md border border-line bg-surface p-8">
            <x-icon name="clock" class="size-6 text-ink-muted" />
            <h2 class="mt-3 font-serif text-xl font-semibold text-ink">Modul belum tersedia</h2>
            <p class="mt-1 max-w-prose text-sm text-ink-muted">
                Editor untuk jenis dokumen <strong class="font-medium text-ink">{{ $jenisLabel }}</strong> akan tersedia pada modul berikutnya.
            </p>
            @if ($document)
                <p class="mt-1 text-sm text-ink-muted">Dokumen terkait: {{ $document->judul }}</p>
            @endif
            <x-button href="{{ route('dokumen.index') }}" variant="secondary" class="mt-6">Kembali ke Daftar Dokumen</x-button>
        </section>
    </div>
@endsection
