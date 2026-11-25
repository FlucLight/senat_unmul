@extends('layouts.app')

@section('title', 'Template Surat')

@section('content')
    <div class="space-y-6">
        <x-breadcrumb :items="[['label' => 'Beranda', 'url' => route('beranda')], ['label' => 'Template Surat']]" />

        <x-page-header title="Template Surat" description="Kelola format dan template yang dipakai sebagai dasar saat membuat surat.">
            <x-slot:actions>
                <x-button href="{{ route('jenis_surat.create') }}">
                    <x-icon name="plus" class="size-4" /> Tambah Template
                </x-button>
            </x-slot:actions>
        </x-page-header>

        @if ($jenis_surat->isEmpty())
            <x-empty-state icon="clipboard-list" title="Belum ada template surat" description="Tambahkan template pertama untuk mulai membuat surat.">
                <x-button href="{{ route('jenis_surat.create') }}" size="sm">Tambah Template</x-button>
            </x-empty-state>
        @else
            <div class="hidden overflow-hidden rounded-md border border-line bg-surface md:block">
                <table class="w-full text-left text-sm">
                    <caption class="sr-only">Daftar template surat</caption>
                    <thead class="border-b border-line-strong bg-paper-200 text-xs font-semibold text-ink-muted">
                        <tr>
                            <th scope="col" class="px-4 py-3">No</th>
                            <th scope="col" class="px-4 py-3">Nama Surat</th>
                            <th scope="col" class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line">
                        @foreach ($jenis_surat as $item)
                            <tr class="transition-colors hover:bg-paper">
                                <td class="tnum px-4 py-3 text-ink-muted">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 font-medium text-ink">{{ $item->nama_surat }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('jenis_surat.show', $item) }}" title="Detail" aria-label="Detail template" class="icon-action">
                                            <x-icon name="file-text" class="size-4" />
                                        </a>
                                        <a href="{{ route('jenis_surat.edit', $item) }}" title="Edit" aria-label="Edit template" class="icon-action">
                                            <x-icon name="pencil-line" class="size-4" />
                                        </a>
                                        <form action="{{ route('jenis_surat.destroy', $item) }}" method="POST"
                                            data-confirm="Hapus template surat ini? Tindakan ini tidak dapat dibatalkan."
                                            data-confirm-title="Hapus Template Surat?"
                                            data-confirm-ok="Hapus"
                                            data-confirm-variant="danger">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Hapus" aria-label="Hapus template"
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

            <div class="space-y-4 md:hidden">
                @foreach ($jenis_surat as $item)
                    <section class="rounded-md border border-line bg-surface p-4">
                        <p class="text-sm font-semibold text-ink">{{ $item->nama_surat }}</p>
                        <div class="mt-3 flex flex-wrap gap-2">
                            <x-button href="{{ route('jenis_surat.show', $item) }}" variant="secondary" size="sm">Detail</x-button>
                            <x-button href="{{ route('jenis_surat.edit', $item) }}" variant="secondary" size="sm">Edit</x-button>
                        </div>
                    </section>
                @endforeach
            </div>
        @endif
    </div>
@endsection
