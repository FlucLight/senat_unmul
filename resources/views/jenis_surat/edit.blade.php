@extends('layouts.app')

@section('title', 'Edit Template Surat')

@section('content')
    @php
        $input = 'mt-1 h-10 w-full rounded-md border border-control bg-surface px-3 text-base text-ink placeholder:text-ink-muted focus-visible:outline-2 focus-visible:outline-offset-0 focus-visible:outline-focus';
        $label = 'block text-sm font-medium text-ink';
    @endphp

    <div class="space-y-6">
        <x-breadcrumb :items="[
            ['label' => 'Beranda', 'url' => route('beranda')],
            ['label' => 'Template Surat', 'url' => route('jenis_surat.index')],
            ['label' => 'Edit Template'],
        ]" />

        <x-page-header title="Edit Template Surat" description="{{ $jenis_surat->nama_surat }}" />

        <section class="rounded-md border border-line bg-surface p-6">
            <form action="{{ route('jenis_surat.update', $jenis_surat) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                <div>
                    <label for="nama_surat" class="{{ $label }}">Nama Surat <span class="font-normal text-ink-muted">(wajib)</span></label>
                    <input type="text" id="nama_surat" name="nama_surat" value="{{ old('nama_surat', $jenis_surat->nama_surat) }}" required class="{{ $input }}">
                    @error('nama_surat')
                        <p class="mt-1 text-xs text-danger-700">{{ $message }}</p>
                    @enderror
                </div>

                @include('jenis_surat._form_fields')

                <div>
                    <label class="{{ $label }}">Template Surat (tampilan dokumen .docx)</label>
                    <x-template-docx-editor>{{ old('template_surat', $jenis_surat->template_surat) }}</x-template-docx-editor>
                    @error('template_surat')
                        <p class="mt-1 text-xs text-danger-700">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-2 border-t border-line pt-4">
                    <x-button type="submit">Simpan</x-button>
                    <x-button href="{{ route('jenis_surat.index') }}" variant="secondary">Batal</x-button>
                </div>
            </form>
        </section>
    </div>
@endsection
