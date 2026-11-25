@extends('layouts.app')

@section('title', 'Edit Template Surat')

@section('content')
    <div class="space-y-6">
        <div>
            <a href="{{ route('jenis_surat.index') }}" class="text-sm font-medium text-green-700 hover:underline">&larr; Kembali</a>
            <h2 class="mt-2 font-display text-[28px] font-semibold leading-tight text-ink-900">Edit Template Surat</h2>
        </div>

        <div class="rounded-lg border border-frame bg-surface p-6">
            <form action="{{ route('jenis_surat.update', $jenis_surat) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')
                <div>
                    <label for="nama_surat" class="mb-1 block text-sm font-medium text-ink-700">Nama Surat</label>
                    <input type="text" id="nama_surat" name="nama_surat" value="{{ old('nama_surat', $jenis_surat->nama_surat) }}" required
                        class="w-full rounded-md border border-frame bg-surface px-3 py-2.5 text-sm text-ink-900 outline-none focus:border-gold-600 focus:ring-2 focus:ring-gold-100">
                    @error('nama_surat')
                        <p class="mt-1 text-xs text-error-text">{{ $message }}</p>
                    @enderror
                </div>

                @include('jenis_surat._form_fields')

                <div>
                    <label class="mb-1 block text-sm font-medium text-ink-700">Template Surat (tampilan dokumen .docx)</label>
                    <x-template-docx-editor>{{ old('template_surat', $jenis_surat->template_surat) }}</x-template-docx-editor>
                    @error('template_surat')
                        <p class="mt-1 text-xs text-error-text">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="rounded-md bg-gold-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-gold-500">Simpan</button>
                    <a href="{{ route('jenis_surat.index') }}" class="rounded-md border border-frame-strong bg-surface px-5 py-2.5 text-sm font-medium text-ink-900 hover:bg-surface-muted">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
