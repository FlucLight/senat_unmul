@extends('layouts.app')

@section('title', 'Cetak Undangan')

@section('content')
    <div class="mx-auto max-w-3xl bg-white p-8 shadow">
        <h1 class="text-center font-display text-xl font-bold">{{ $content['perihal'] ?? '' }}</h1>
        <p class="mt-2 text-center text-sm">Nomor: {{ $document->nomor_surat ?? '—' }}</p>
        <p class="mt-1 text-center text-sm">Tanggal: {{ $content['tanggal_surat'] ?? '' }}</p>
        <hr class="my-4">
        <p class="text-sm">Acara: {{ $content['perihal'] ?? '' }}</p>
        <p class="text-sm">Tanggal Acara: {{ $content['tanggal_acara'] ?? '' }} {{ $content['waktu_mulai'] ?? '' }} - {{ $content['waktu_selesai'] ?? '' }}</p>
        <p class="text-sm">Tempat: {{ $content['tempat'] ?? '' }}</p>
        <p class="text-sm">Pemimpin Rapat: {{ $content['pemimpin_rapat'] ?? '' }}</p>
        <p class="mt-4 text-sm">{{ $content['agenda'] ?? '' }}</p>
        <h3 class="mt-4 font-semibold text-sm">Daftar Undangan:</h3>
        <ol class="list-decimal pl-6 text-sm">
            @foreach (($content['undangan'] ?? []) as $u)
                <li>{{ $u['nama'] ?? '' }} — {{ $u['jabatan_instansi'] ?? '' }}</li>
            @endforeach
        </ol>
        @if (!empty($qrDataUri))
            <div class="mt-6 text-center">
                <img src="{{ $qrDataUri }}" alt="QR Verifikasi" class="mx-auto h-24 w-24">
                <p class="mt-1 text-xs text-ink-400">Pindai untuk verifikasi keaslian</p>
            </div>
        @endif
    </div>
@endsection