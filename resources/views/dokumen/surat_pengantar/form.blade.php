@extends('layouts.app')

@section('title', isset($document) && $document ? 'Edit Surat Pengantar' : 'Buat Surat Pengantar')

@section('content')
    <div class="space-y-6">
        <x-breadcrumb :items="[
            ['label' => 'Beranda', 'url' => route('beranda')],
            ['label' => 'Daftar Dokumen', 'url' => route('dokumen.index')],
            ['label' => isset($document) && $document ? 'Edit Surat Pengantar' : 'Buat Surat Pengantar'],
        ]" />

        <x-page-header :title="isset($document) && $document ? 'Edit Surat Pengantar' : 'Buat Surat Pengantar'"
            description="Pilih template, sunting isi, lalu simpan sebagai draf atau finalisasi." />

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

        @include('dokumen._html_form', [
            'jenis' => 'surat_pengantar',
            'jenisLabel' => 'Surat Pengantar',
            'document' => $document ?? null,
            'templates' => $templates ?? [],
            'templateBodies' => $templateBodies ?? [],
            'selectedTemplate' => $selectedTemplate ?? 'default',
            'meta' => $meta ?? [],
            'bodyHtml' => $bodyHtml ?? '',
        ])
    </div>
@endsection
