@extends('layouts.app')

@section('title', 'Cetak Surat Pengantar')

@section('content')
    <div class="mx-auto max-w-3xl bg-white p-8 shadow">
        <h1 class="text-center font-display text-xl font-bold">{{ $content['perihal'] ?? '' }}</h1>
        <p class="mt-2 text-center text-sm">Nomor: {{ $document->nomor_surat ?? '—' }}</p>
        <p class="mt-1 text-center text-sm">Tanggal Surat: {{ $content['tanggal_surat'] ?? '' }}</p>
        <hr class="my-4">
        <p class="text-sm">Tujuan: {{ $content['tujuan'] ?? '' }}</p>
        <p class="mt-4 text-sm">{{ $content['isi_ringkas'] ?? '' }}</p>
        <p class="mt-6 text-sm">Penandatangan: {{ $content['penandatangan'] ?? '' }}</p>
    </div>
@endsection