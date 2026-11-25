@extends('layouts.app')

@section('title', 'Daftar Surat')

@section('content')
    <div class="space-y-6">
        <x-breadcrumb :items="[['label' => 'Beranda', 'url' => route('beranda')], ['label' => 'Daftar Surat']]" />

        <x-page-header title="Daftar Surat" description="Buat surat dari template, lalu pratinjau, unduh, atau cetak ke PDF.">
            <x-slot:actions>
                <x-button href="{{ route('surat.create') }}">
                    <x-icon name="plus" class="size-4" /> Buat Surat Baru
                </x-button>
            </x-slot:actions>
        </x-page-header>

        @if ($surat->isEmpty())
            <x-empty-state icon="mail" title="Belum ada surat" description="Buat surat baru dari template yang tersedia.">
                <x-button href="{{ route('surat.create') }}" size="sm">Buat Surat Baru</x-button>
            </x-empty-state>
        @else
            <div class="hidden overflow-hidden rounded-md border border-line bg-surface md:block">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <caption class="sr-only">Daftar surat yang telah dibuat</caption>
                        <thead class="border-b border-line-strong bg-paper-200 text-xs font-semibold text-ink-muted">
                            <tr>
                                <th scope="col" class="px-4 py-3">No</th>
                                <th scope="col" class="px-4 py-3">Nomor Surat</th>
                                <th scope="col" class="px-4 py-3">Jenis Surat</th>
                                <th scope="col" class="px-4 py-3 text-right">Tanggal</th>
                                <th scope="col" class="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-line">
                            @foreach ($surat as $item)
                                <tr class="transition-colors hover:bg-paper">
                                    <td class="tnum px-4 py-3 text-ink-muted">{{ $loop->iteration }}</td>
                                    <td class="tnum px-4 py-3 font-medium text-ink">{{ $item->nomor_surat }}</td>
                                    <td class="px-4 py-3 text-ink-muted">{{ $item->jenisSurat->nama_surat }}</td>
                                    <td class="tnum whitespace-nowrap px-4 py-3 text-right text-ink-muted">{{ $item->tanggal_surat->format('d-m-Y') }}</td>
                                    <td class="whitespace-nowrap px-4 py-3">
                                        <div class="flex items-center justify-end gap-1">
                                            <a href="{{ route('surat.show', $item) }}" title="Detail" aria-label="Detail surat" class="icon-action">
                                                <x-icon name="file-text" class="size-4" />
                                            </a>
                                            <a href="{{ route('surat.edit', $item) }}" title="Edit" aria-label="Edit surat" class="icon-action">
                                                <x-icon name="pencil-line" class="size-4" />
                                            </a>
                                            <a href="{{ route('surat.pdf', $item) }}" target="_blank" rel="noopener" title="Lihat PDF" aria-label="Lihat PDF" class="icon-action">
                                                <x-icon name="printer" class="size-4" />
                                            </a>
                                            <a href="{{ route('surat.pdf.download', $item) }}" title="Unduh PDF" aria-label="Unduh PDF" class="icon-action">
                                                <x-icon name="download" class="size-4" />
                                            </a>
                                            <form action="{{ route('surat.destroy', $item) }}" method="POST"
                                                data-confirm="Hapus surat ini? Tindakan ini tidak dapat dibatalkan."
                                                data-confirm-title="Hapus Surat?"
                                                data-confirm-ok="Hapus"
                                                data-confirm-variant="danger">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Hapus" aria-label="Hapus surat"
                                                    class="inline-flex h-8 w-8 items-center justify-center rounded-sm text-ink-muted transition-colors hover:bg-danger-50 hover:text-danger-700">
                                                    <x-icon name="trash-2" class="size-4" />
                                                </button>
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
                    <section class="rounded-md border border-line bg-surface p-4">
                        <p class="tnum text-sm font-semibold text-ink">{{ $item->nomor_surat }}</p>
                        <p class="text-sm text-ink-muted">{{ $item->jenisSurat->nama_surat }}</p>
                        <p class="tnum mt-1 text-xs text-ink-muted">{{ $item->tanggal_surat->format('d-m-Y') }}</p>
                        <div class="mt-3 flex flex-wrap gap-2">
                            <x-button href="{{ route('surat.show', $item) }}" variant="secondary" size="sm">Detail</x-button>
                            <x-button href="{{ route('surat.edit', $item) }}" variant="secondary" size="sm">Edit</x-button>
                            <x-button href="{{ route('surat.pdf.download', $item) }}" size="sm">Unduh PDF</x-button>
                        </div>
                    </section>
                @endforeach
            </div>
        @endif
    </div>
@endsection
