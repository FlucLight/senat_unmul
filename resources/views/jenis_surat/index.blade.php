@extends('layouts.index')

@section('content')
    <div class="container mt-5">
        <h2>Template Surat</h2>
        <p class="text-muted">Kelola format/template yang digunakan sebagai dasar saat membuat surat.</p>
        <a href="{{ route('jenis_surat.create') }}" class="btn btn-primary mb-2">Tambah Template Surat</a>
        <table class="table">
            <thead>
                <tr>
                <th scope="col">Nomor</th>
                <th scope="col">Nama Surat</th>
                <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if ($jenis_surat->isEmpty())
                    <tr>
                        <td colspan="3">Data Kosong</td>
                    </tr>
                @endif
                @foreach ($jenis_surat as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->nama_surat }}</td>
                        <td>
                            <a href="{{ route('jenis_surat.show', $item->id) }}" class="btn btn-info">Detail</a>
                            <a href="{{ route('jenis_surat.edit', $item->id) }}" class="btn btn-primary">Edit</a>
                            <form action="{{ route('jenis_surat.destroy', $item->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus jenis surat ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach 
            </tbody>
        </table>
    </div>
@endsection

