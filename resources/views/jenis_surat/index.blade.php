@extends('layouts.app')

@section('title', 'Template Surat')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="font-display text-[28px] font-semibold leading-tight text-ink-900">Template Surat</h2>
                <p class="mt-1 text-sm text-ink-400">Kelola format/template yang dipakai sebagai dasar saat membuat surat.</p>
            </div>
            <a href="{{ route('jenis_surat.create') }}" class="inline-flex items-center justify-center rounded-md bg-gold-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-gold-500">
                Tambah Template Surat
            </a>
        </div>

        @if ($jenis_surat->isEmpty())
            <div class="rounded-lg border border-frame bg-surface px-6 py-16 text-center">
                <p class="text-sm font-semibold text-ink-700">Belum ada template surat.</p>
            </div>
        @else
            <div class="hidden overflow-hidden rounded-lg border border-frame bg-surface md:block">
                <table class="min-w-full divide-y divide-frame text-sm">
                    <thead>
                        <tr class="bg-surface-muted text-left text-xs font-medium text-ink-700">
                            <th class="px-5 py-3">No</th>
                            <th class="px-5 py-3">Nama Surat</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-frame">
                        @foreach ($jenis_surat as $item)
                            <tr class="transition hover:bg-gold-50">
                                <td class="px-5 py-3.5 text-ink-400">{{ $loop->iteration }}</td>
                                <td class="px-5 py-3.5 font-medium text-ink-900">{{ $item->nama_surat }}</td>
                                <td class="px-5 py-3.5">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('jenis_surat.show', $item) }}" class="rounded-md border border-frame-strong bg-surface px-3 py-1.5 text-xs font-medium text-ink-900 hover:bg-surface-muted">Detail</a>
                                        <a href="{{ route('jenis_surat.edit', $item) }}" class="rounded-md border border-frame-strong bg-surface px-3 py-1.5 text-xs font-medium text-ink-900 hover:bg-surface-muted">Edit</a>
                                        <form action="{{ route('jenis_surat.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jenis surat ini?')">
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

            <div class="space-y-4 md:hidden">
                @foreach ($jenis_surat as $item)
                    <div class="rounded-lg border border-frame bg-surface p-4">
                        <p class="text-sm font-semibold text-ink-900">{{ $item->nama_surat }}</p>
                        <div class="mt-3 flex flex-wrap gap-2">
                            <a href="{{ route('jenis_surat.show', $item) }}" class="rounded-md border border-frame-strong px-3 py-1.5 text-xs font-medium text-ink-900">Detail</a>
                            <a href="{{ route('jenis_surat.edit', $item) }}" class="rounded-md border border-frame-strong px-3 py-1.5 text-xs font-medium text-ink-900">Edit</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
