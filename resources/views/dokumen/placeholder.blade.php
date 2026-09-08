@extends('layouts.app')

@section('title', 'Buat '.$jenisLabel)

@section('content')
    <div class="mx-auto max-w-md">
        <div class="overflow-hidden rounded-lg border border-frame bg-surface">
            <div class="px-6 py-10 text-center">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-md bg-gold-100 text-draft-text">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <h1 class="mt-4 font-display text-lg font-semibold text-ink-900">
                    {{ $document ? 'Edit dokumen' : 'Buat dokumen baru' }} — {{ $jenisLabel }}
                </h1>
                <p class="mt-2 text-sm text-ink-400">
                    Halaman editor untuk jenis dokumen <strong class="font-medium text-ink-700">{{ $jenisLabel }}</strong> sedang dalam pengembangan dan akan tersedia pada modul berikutnya.
                </p>
                @if ($document)
                    <p class="mt-1 text-xs text-ink-400">Dokumen terkait: {{ $document->judul }}</p>
                @endif
                <a href="{{ route('dokumen.index') }}"
                    class="mt-6 inline-block rounded-md bg-gold-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-gold-500">
                    Kembali ke Daftar Isi
                </a>
            </div>
        </div>
    </div>
@endsection