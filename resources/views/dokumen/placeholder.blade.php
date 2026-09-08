@extends('layouts.app')

@section('title', 'Buat '.$jenisLabel)

@section('content')
    <div class="mx-auto max-w-md">
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-900/5">
            <div class="px-6 py-8 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-amber-50 text-amber-600">
                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <h1 class="mt-4 text-lg font-bold text-slate-900">
                    {{ $document ? 'Edit dokumen' : 'Buat dokumen baru' }} — {{ $jenisLabel }}
                </h1>
                <p class="mt-2 text-sm text-slate-500">
                    Halaman editor untuk jenis dokumen <strong>{{ $jenisLabel }}</strong> sedang dalam pengembangan dan akan tersedia pada modul berikutnya.
                </p>
                @if ($document)
                    <p class="mt-1 text-xs text-slate-400">Dokumen terkait: {{ $document->judul }}</p>
                @endif
                <a href="{{ route('dokumen.index') }}"
                    class="mt-6 inline-block rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800">
                    Kembali ke Daftar Isi
                </a>
            </div>
        </div>
    </div>
@endsection