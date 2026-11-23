@extends('layouts.app')

@section('title', 'Daftar Surat')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="font-display text-[28px] font-semibold leading-tight text-ink-900">Daftar surat</h2>
                <p class="mt-1 text-sm text-ink-400">Buat surat dari template, lalu pratinjau, unduh, atau cetak ke PDF.</p>
            </div>
            <a href="{{ route('surat.create') }}" class="inline-flex items-center justify-center rounded-md bg-gold-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-gold-500">
                Buat Surat Baru
            </a>
        </div>

        @if ($surat->isEmpty())
            <div class="rounded-lg border border-frame bg-surface px-6 py-16 text-center">
                <p class="text-sm font-semibold text-ink-700">Belum ada surat.</p>
                <p class="mt-1 text-sm text-ink-400">Buat surat baru dari template yang tersedia.</p>
            </div>
        @else
            <div class="hidden overflow-hidden rounded-lg border border-frame bg-surface md:block">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-frame text-sm">
                        <thead>
                            <tr class="bg-surface-muted text-left text-xs font-medium text-ink-700">
                                <th class="px-5 py-3">No</th>
                                <th class="px-5 py-3">Nomor Surat</th>
                                <th class="px-5 py-3">Jenis Surat</th>
                                <th class="tnum px-5 py-3 text-right">Tanggal</th>
                                <th class="px-5 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-frame">
                            @foreach ($surat as $item)
                                <tr class="transition hover:bg-gold-50">
                                    <td class="px-5 py-3.5 text-ink-400">{{ $loop->iteration }}</td>
                                    <td class="px-5 py-3.5 font-medium text-ink-900">{{ $item->nomor_surat }}</td>
                                    <td class="px-5 py-3.5 text-ink-700">{{ $item->jenisSurat->nama_surat }}</td>
                                    <td class="tnum whitespace-nowrap px-5 py-3.5 text-right text-ink-400">{{ $item->tanggal_surat->format('d-m-Y') }}</td>
                                    <td class="px-5 py-3.5">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('surat.show', $item) }}" class="rounded-md border border-frame-strong bg-surface px-3 py-1.5 text-xs font-medium text-ink-900 hover:bg-surface-muted">Detail</a>
                                            <a href="{{ route('surat.edit', $item) }}" class="rounded-md border border-frame-strong bg-surface px-3 py-1.5 text-xs font-medium text-ink-900 hover:bg-surface-muted">Edit</a>
                                            <a href="{{ route('surat.pdf', $item) }}" target="_blank" class="rounded-md border border-green-700/20 bg-green-100 px-3 py-1.5 text-xs font-medium text-green-700 hover:bg-green-100/70">Lihat PDF</a>
                                            <a href="{{ route('surat.pdf.download', $item) }}" class="rounded-md bg-gold-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-gold-500">Unduh PDF</a>
                                            <form action="{{ route('surat.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus surat ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-md border border-error-bg bg-error-bg px-3 py-1.5 text-xs font-medium text-error-text hover:opacity-90">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="space-y-4 md:hidden">
                @foreach ($surat as $item)
                    <div class="rounded-lg border border-frame bg-surface p-4">
                        <p class="text-sm font-semibold text-ink-900">{{ $item->nomor_surat }}</p>
                        <p class="text-xs text-ink-500">{{ $item->jenisSurat->nama_surat }}</p>
                        <p class="tnum mt-1 text-xs text-ink-400">{{ $item->tanggal_surat->format('d-m-Y') }}</p>
                        <div class="mt-3 flex flex-wrap gap-2">
                            <a href="{{ route('surat.show', $item) }}" class="rounded-md border border-frame-strong px-3 py-1.5 text-xs font-medium text-ink-900">Detail</a>
                            <a href="{{ route('surat.edit', $item) }}" class="rounded-md border border-frame-strong px-3 py-1.5 text-xs font-medium text-ink-900">Edit</a>
                            <a href="{{ route('surat.pdf.download', $item) }}" class="rounded-md bg-gold-600 px-3 py-1.5 text-xs font-medium text-white">Unduh PDF</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
