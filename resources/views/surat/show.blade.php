@extends('layouts.index')

@push('styles')
    <style>
        .pratinjau-surat {
            background: #fff;
            box-shadow: 0 0 4px rgba(0, 0, 0, .15);
            margin: 0 auto;
            max-width: 820px;
            min-height: 1160px;
            padding: 4rem;
        }

        .pratinjau-surat .new-break {
            border-top: 2px dashed #adb5bd;
            margin: 3rem -4rem;
        }
    </style>
@endpush

@section('content')
    <div class="container mt-5">
        <div class="d-flex align-items-center mb-3 flex-wrap gap-2">
            <a href="{{ route('surat.index') }}" class="btn btn-dark">Kembali</a>
            <a href="{{ route('surat.edit', $surat) }}" class="btn btn-primary">Edit</a>
            <a href="{{ route('surat.pdf', $surat) }}" target="_blank" class="btn btn-outline-success">Lihat PDF</a>
            <a href="{{ route('surat.pdf.download', $surat) }}" class="btn btn-success">Unduh PDF</a>
        </div>

        <h2>Pratinjau Surat</h2>
        <p class="text-muted mb-3">Pratinjau di web. Gunakan <strong>Unduh PDF</strong> untuk menyimpan file ke perangkat Anda.</p>

        <div class="pratinjau-surat">
            {!! $konten !!}
        </div>
    </div>
@endsection
