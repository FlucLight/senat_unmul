@extends('layouts.index')

@section('content')
    <div class="container mt-5">
        <h1>Selamat Datang di Senatitor</h1>
        <p>Aplikasi pembuat surat resmi untuk senat universitas.</p>

        <div class="row mt-4 g-3">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Surat</h5>
                        <p class="card-text">Buat surat baru dari template yang tersedia, kelola, lalu lihat atau unduh sebagai PDF.</p>
                        <a href="{{ route('surat.index') }}" class="btn btn-success">Kelola &amp; Buat Surat</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Template Surat (Jenis Surat)</h5>
                        <p class="card-text">Atur template/format surat, kop, logo, ukuran kertas, dan margin yang dipakai saat membuat surat.</p>
                        <a href="{{ route('jenis_surat.index') }}" class="btn btn-outline-secondary">Kelola Template Surat</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
