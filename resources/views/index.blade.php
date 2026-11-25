@extends('layouts.app')

@section('title', 'Pembuat Surat')

@section('content')
    <div class="space-y-6">
        <x-breadcrumb :items="[['label' => 'Beranda', 'url' => route('beranda')], ['label' => 'Pembuat Surat']]" />

        <x-page-header title="Pembuat Surat" description="Buat dan kelola surat resmi Senat FT dari template." />

        <div class="grid gap-4 sm:grid-cols-2">
            <section class="rounded-md border border-line bg-surface p-6">
                <x-icon name="mail" class="size-6 text-brand" />
                <h2 class="mt-3 font-serif text-xl font-semibold text-ink">Surat</h2>
                <p class="mt-1 text-sm text-ink-muted">Buat surat baru dari template, kelola, lalu pratinjau atau unduh sebagai PDF.</p>
                <x-button href="{{ route('surat.index') }}" class="mt-4">Kelola dan Buat Surat</x-button>
            </section>
            <section class="rounded-md border border-line bg-surface p-6">
                <x-icon name="clipboard-list" class="size-6 text-brand" />
                <h2 class="mt-3 font-serif text-xl font-semibold text-ink">Template Surat</h2>
                <p class="mt-1 text-sm text-ink-muted">Atur template, kop, logo, ukuran kertas, dan margin surat.</p>
                <x-button href="{{ route('jenis_surat.index') }}" variant="secondary" class="mt-4">Kelola Template Surat</x-button>
            </section>
        </div>
    </div>
@endsection
