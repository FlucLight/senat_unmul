@extends('layouts.app')

@section('title', 'Cetak Berita Acara')

@section('content')
    <div class="mx-auto max-w-3xl bg-white p-8 shadow">
        <h1 class="text-center font-display text-xl font-bold">{{ $content['perihal'] ?? '' }}</h1>
        <p class="mt-2 text-center text-sm">Nomor: {{ $document->nomor_surat ?? '—' }}</p>
        <p class="mt-1 text-center text-sm">Tanggal Surat: {{ $content['tanggal_surat'] ?? '' }}</p>
        <hr class="my-4">
        <p class="text-sm">Tanggal Rapat: {{ $content['tanggal_rapat'] ?? '' }}</p>
        <p class="text-sm">Tempat: {{ $content['tempat'] ?? '' }}</p>
        <p class="text-sm">Pemimpin Rapat: {{ $content['pemimpin_rapat'] ?? '' }}</p>
        <h3 class="mt-4 font-semibold text-sm">Pembahasan:</h3>
        <p class="text-sm">{{ $content['pembahasan'] ?? '' }}</p>
        <h3 class="mt-4 font-semibold text-sm">Keputusan:</h3>
        <p class="text-sm">{{ $content['keputusan'] ?? '' }}</p>
        <h3 class="mt-4 font-semibold text-sm">Peserta:</h3>
        <ol class="list-decimal pl-6 text-sm">
            @foreach (($content['peserta'] ?? []) as $p)
                <li>{{ $p['nama'] ?? '' }} — {{ $p['jabatan_instansi'] ?? '' }}</li>
            @endforeach
        </ol>
    </div>
@endsection