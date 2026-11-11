@extends('layouts.index')

@section('content')
    <div class="container mt-5">
        <a href="{{ route('surat.index') }}" class="btn btn-dark mb-2">Kembali</a>

        @if ($jenisSurat)
            <h2>Buat Surat Baru</h2>
            <p class="text-muted">Isi detail surat berdasarkan template <strong>{{ $jenisSurat->nama_surat }}</strong>.</p>
            @include('surat._form', ['surat' => $surat ?? null])
        @else
            <h2>Buat Surat Baru</h2>
            <p class="text-muted">Langkah 1: pilih <strong>template surat</strong> yang akan digunakan. (Kelola template di menu <a href="{{ route('jenis_surat.index') }}">Template Surat</a>.)</p>

            @if ($jenisSuratList->isEmpty())
                <div class="alert alert-warning">
                    Belum ada template surat. <a href="{{ route('jenis_surat.create') }}">Tambah template surat terlebih dahulu</a>, lalu kembali ke sini untuk membuat surat.
                </div>
            @else
                <form method="GET" action="{{ route('surat.create') }}" class="d-flex gap-2 align-items-end">
                    <div class="flex-grow-1">
                        <label for="jenis_surat_id" class="form-label">Template Surat</label>
                        <select id="jenis_surat_id" name="jenis_surat_id" class="form-select" required>
                            <option value="">-- Pilih Template Surat --</option>
                            @foreach ($jenisSuratList as $item)
                                <option value="{{ $item->id }}">{{ $item->nama_surat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Lanjut Buat Surat</button>
                </form>
            @endif
        @endif
    </div>
@endsection
