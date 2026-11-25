@extends('layouts.app')

@section('title', 'Pratinjau Template')

@section('content')
    <div class="space-y-6">
        <x-breadcrumb :items="[
            ['label' => 'Beranda', 'url' => route('beranda')],
            ['label' => 'Template Surat', 'url' => route('jenis_surat.index')],
            ['label' => $jenis_surat->nama_surat],
        ]" />

        <x-page-header :title="'Pratinjau: ' . $jenis_surat->nama_surat" description="Tampilan template surat sebagaimana tercetak pada PDF.">
            <x-slot:actions>
                <x-button href="{{ route('jenis_surat.edit', $jenis_surat) }}" variant="secondary">Edit Template</x-button>
            </x-slot:actions>
        </x-page-header>

        <div class="overflow-x-auto rounded-md border border-line bg-paper-200 p-4 sm:p-8">
            <div class="pratinjau-surat">{!! $konten !!}</div>
        </div>
    </div>
@endsection
