@extends('layouts.index')

@section('content')
    <div class="container mt-5">
        <h2>Daftar Surat</h2>
        <p class="text-muted">Buat surat baru dari template, lalu pratinjau, unduh, atau cetak ke PDF.</p>
        <a href="{{ route('surat.create') }}" class="btn btn-primary mb-2">Buat Surat Baru</a>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Nomor</th>
                    <th scope="col">Nomor Surat</th>
                    <th scope="col">Jenis Surat</th>
                    <th scope="col">Tanggal</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if ($surat->isEmpty())
                    <tr>
                        <td colspan="5">Data Kosong</td>
                    </tr>
                @endif
                @foreach ($surat as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->nomor_surat }}</td>
                        <td>{{ $item->jenisSurat->nama_surat }}</td>
                        <td>{{ $item->tanggal_surat->format('d-m-Y') }}</td>
                        <td>
                            <a href="{{ route('surat.show', $item->id) }}" class="btn btn-info btn-sm">Detail</a>
                            <a href="{{ route('surat.edit', $item->id) }}" class="btn btn-primary btn-sm">Edit</a>
                            <a href="{{ route('surat.pdf', $item->id) }}" target="_blank" class="btn btn-outline-success btn-sm">Lihat PDF</a>
                            <a href="{{ route('surat.pdf.download', $item->id) }}" class="btn btn-success btn-sm">Unduh PDF</a>
                            <form action="{{ route('surat.destroy', $item->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus surat ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
