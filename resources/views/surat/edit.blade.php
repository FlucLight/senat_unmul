@extends('layouts.app')

@section('title', 'Edit Surat')

@section('content')
    <div class="space-y-6">
        <x-breadcrumb :items="[
            ['label' => 'Beranda', 'url' => route('beranda')],
            ['label' => 'Daftar Surat', 'url' => route('surat.index')],
            ['label' => 'Edit Surat'],
        ]" />

        <x-page-header title="Edit Surat" description="Perbarui nomor, tanggal, atau isi surat." />

        <section class="rounded-md border border-line bg-surface p-6">
            @include('surat._form', ['surat' => $surat])
        </section>
    </div>
@endsection
