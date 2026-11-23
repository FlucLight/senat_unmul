@extends('layouts.index')

@section('content')
    <div class="container mt-5">
        <a href="{{ route('jenis_surat.index') }}" class="btn btn-dark mb-2">Kembali</a>
        <div>
            <h2>Tambah Template Surat</h2>
            <p class="text-muted">Template ini dipakai sebagai dasar saat Anda membuat surat baru.</p>
            <form action="{{ route('jenis_surat.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="nama_surat">Nama Surat</label>
                    <input type="text" class="form-control" id="nama_surat" name="nama_surat" value="{{ old('nama_surat') }}" required>
                    @error('nama_surat')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                @include('jenis_surat._form_fields')

                <div class="mt-2">
                    <label class="form-label">Template Surat (tampilan dokumen .docx)</label>
                    <x-template-docx-editor>{{ old('template_surat', $template) }}</x-template-docx-editor>
                    @error('template_surat')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary mt-2">Simpan</button>
            </form>
        </div>
    </div>
@endsection
