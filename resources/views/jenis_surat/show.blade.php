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
        <div class="d-flex align-items-center mb-3">
            <a href="{{ route('jenis_surat.index') }}" class="btn btn-dark">Kembali</a>
            <a href="{{ route('jenis_surat.edit', $jenis_surat) }}" class="btn btn-primary ms-2">Edit</a>
        </div>

        <h2>Pratinjau Template: {{ $jenis_surat->nama_surat }}</h2>

        <div class="pratinjau-surat">
            {!! $konten !!}
        </div>
    </div>
@endsection
