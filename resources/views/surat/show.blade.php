@extends('layouts.app')

@section('title', 'Pratinjau Surat')

@section('content')
    <div class="space-y-6">
        <x-breadcrumb :items="[
            ['label' => 'Beranda', 'url' => route('beranda')],
            ['label' => 'Daftar Surat', 'url' => route('surat.index')],
            ['label' => 'Pratinjau Surat'],
        ]" />

        <x-page-header title="Pratinjau Surat" description="Gunakan Unduh PDF untuk menyimpan file ke perangkat Anda.">
            <x-slot:actions>
                <x-button href="{{ route('surat.edit', $surat) }}" variant="secondary">Edit</x-button>
                <x-button href="{{ route('surat.pdf', $surat) }}" variant="secondary" target="_blank" rel="noopener">
                    <x-icon name="printer" class="size-4" /> Lihat PDF
                </x-button>
                <x-button href="{{ route('surat.pdf.download', $surat) }}">
                    <x-icon name="download" class="size-4" /> Unduh PDF
                </x-button>
            </x-slot:actions>
        </x-page-header>

        <div class="overflow-x-auto rounded-md border border-line bg-paper-200 p-4 sm:p-8">
            <div class="pratinjau-surat">{!! $konten !!}</div>
        </div>
    </div>
@endsection
