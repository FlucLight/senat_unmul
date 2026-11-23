@extends('layouts.index')

@section('content')
    <div class="container mt-5">
        <a href="{{ route('surat.index') }}" class="btn btn-dark mb-2">Kembali</a>
        <h2>Edit Surat</h2>
        @include('surat._form', ['surat' => $surat])
    </div>
@endsection
