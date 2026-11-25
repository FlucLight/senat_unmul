@extends('layouts.app')

@section('title', 'Buat Surat')

@section('content')
    @php
        $input = 'mt-1 h-10 w-full rounded-md border border-control bg-surface px-3 text-base text-ink placeholder:text-ink-muted focus-visible:outline-2 focus-visible:outline-offset-0 focus-visible:outline-focus';
        $label = 'block text-sm font-medium text-ink';
    @endphp

    <div class="space-y-6">
        <x-breadcrumb :items="[
            ['label' => 'Beranda', 'url' => route('beranda')],
            ['label' => 'Daftar Surat', 'url' => route('surat.index')],
            ['label' => 'Buat Surat'],
        ]" />

        <x-page-header title="Buat Surat Baru" description="Isi detail surat berdasarkan template yang tersedia." />

        <section class="rounded-md border border-line bg-surface p-6">
            @if ($jenisSurat)
                <p class="mb-6 text-sm text-ink-muted">Isi detail surat berdasarkan template <span class="font-medium text-ink">{{ $jenisSurat->nama_surat }}</span>.</p>
                @include('surat._form', ['surat' => $surat ?? null])
            @else
                <p class="mb-6 text-sm text-ink-muted">Pilih template surat yang akan digunakan. Kelola template di <a href="{{ route('jenis_surat.index') }}" class="font-medium text-brand underline-offset-2 hover:underline">Template Surat</a>.</p>

                @if ($jenisSuratList->isEmpty())
                    <x-alert variant="warning">
                        Belum ada template surat. <a href="{{ route('jenis_surat.create') }}" class="font-semibold underline underline-offset-2">Tambah template surat terlebih dahulu</a>.
                    </x-alert>
                @else
                    <form method="GET" action="{{ route('surat.create') }}" class="flex flex-col gap-3 sm:flex-row sm:items-end">
                        <div class="flex-1">
                            <label for="jenis_surat_id" class="{{ $label }}">Template Surat</label>
                            <select id="jenis_surat_id" name="jenis_surat_id" required class="{{ $input }}">
                                <option value="">Pilih template surat</option>
                                @foreach ($jenisSuratList as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_surat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <x-button type="submit" class="shrink-0">Lanjut Buat Surat</x-button>
                    </form>
                @endif
            @endif
        </section>
    </div>
@endsection
