@extends('layouts.app')

@section('title', 'Daftar Dokumen')

@section('content')
    <div class="space-y-6">
        <x-breadcrumb :items="[['label' => 'Beranda', 'url' => route('beranda')], ['label' => 'Daftar Dokumen']]" />

        <x-page-header title="Daftar Dokumen" :description="'Cari dan akses seluruh dokumen Senat FT. ' . $documents->total() . ' dokumen.'" />

        {{-- Filter --}}
        <form method="GET" action="{{ route('dokumen.index') }}" class="space-y-4">
            <div class="flex gap-2 md:hidden">
                <div class="relative flex-1">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-ink-muted">
                        <x-icon name="search" class="size-4" />
                    </span>
                    <label for="m-q" class="sr-only">Cari nomor surat atau judul</label>
                    <input type="search" name="q" id="m-q" value="{{ $filters['q'] ?? '' }}"
                        placeholder="Cari nomor surat / judul"
                        class="h-11 w-full rounded-md border border-control bg-surface pl-8 pr-3 text-base text-ink placeholder:text-ink-muted focus-visible:outline-2 focus-visible:outline-offset-0 focus-visible:outline-focus">
                </div>
                <x-button type="button" id="filter-toggle" variant="secondary" size="md">
                    <x-icon name="list-filter" class="size-4" /> Filter
                </x-button>
            </div>

            <div class="hidden rounded-md border border-line bg-surface p-6 md:block">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-6">
                    @include('dokumen._filters', ['prefix' => 'd', 'showSearch' => true, 'withButtons' => true])
                </div>
            </div>

            {{-- Bottom sheet filter mobile --}}
            <div id="filter-backdrop" class="fixed inset-0 z-40 hidden bg-ink/50 md:hidden"></div>
            <div id="filter-sheet" class="fixed inset-x-0 bottom-0 z-50 max-h-[85vh] translate-y-full overflow-y-auto rounded-t-lg border-t border-line bg-surface p-6 transition-transform md:hidden">
                <div class="mb-6 flex items-center justify-between">
                    <h2 class="font-serif text-lg font-semibold text-ink">Filter Dokumen</h2>
                    <button type="button" id="filter-close" class="inline-flex h-10 w-10 items-center justify-center rounded-md text-ink-muted hover:bg-paper hover:text-ink" aria-label="Tutup">
                        <x-icon name="x" class="size-5" />
                    </button>
                </div>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    @include('dokumen._filters', ['prefix' => 'm', 'showSearch' => false, 'withButtons' => false])
                </div>
                <div class="mt-6 flex items-center gap-2">
                    <x-button type="submit" class="flex-1">Terapkan Filter</x-button>
                    <x-button href="{{ route('dokumen.index') }}" variant="ghost">Atur Ulang</x-button>
                </div>
            </div>
        </form>

        @if ($documents->isEmpty())
            <x-empty-state icon="file-text" title="Belum ada dokumen yang sesuai filter"
                description="Atur ulang filter atau buat dokumen baru untuk memulai.">
                <div class="flex flex-wrap gap-2">
                    <x-button href="{{ route('dokumen.index') }}" variant="secondary" size="sm">Atur Ulang Filter</x-button>
                    <x-button href="{{ route('dokumen.baru', 'undangan') }}" size="sm">Buat Dokumen</x-button>
                </div>
            </x-empty-state>
        @else
            <div class="flex flex-col gap-2 text-sm text-ink-muted sm:flex-row sm:items-center sm:justify-between">
                <p>Menampilkan {{ $documents->firstItem() }}–{{ $documents->lastItem() }} dari {{ $documents->total() }} dokumen</p>
                <p>Urutan: tanggal terbaru</p>
            </div>

            {{-- Tabel desktop --}}
            <div class="hidden overflow-hidden rounded-md border border-line bg-surface md:block">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <caption class="sr-only">Daftar dokumen Senat FT</caption>
                        <thead class="border-b border-line-strong bg-paper-200 text-xs font-semibold text-ink-muted">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-right">Nomor</th>
                                <th scope="col" class="px-4 py-3">Jenis</th>
                                <th scope="col" class="px-4 py-3">Judul</th>
                                <th scope="col" class="px-4 py-3 text-right">Tanggal</th>
                                <th scope="col" class="px-4 py-3">Pembuat</th>
                                <th scope="col" class="px-4 py-3">Status</th>
                                <th scope="col" class="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-line">
                            @foreach ($documents as $document)
                                <tr class="transition-colors hover:bg-paper">
                                    <td class="tnum whitespace-nowrap px-4 py-3 text-right text-ink">
                                        @if ($document->nomor_surat)
                                            {{ $document->nomor_surat }}
                                        @else
                                            <span class="text-ink-muted">Belum bernomor</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-3 text-ink-muted">{{ $document->jenis_label }}</td>
                                    <td class="max-w-[20rem] px-4 py-3">
                                        <a href="{{ route('dokumen.show', $document) }}" class="block truncate font-medium text-ink hover:text-brand hover:underline">{{ $document->judul }}</a>
                                    </td>
                                    <td class="tnum whitespace-nowrap px-4 py-3 text-right text-ink-muted">{{ $document->created_at->translatedFormat('d M Y') }}</td>
                                    <td class="whitespace-nowrap px-4 py-3 text-ink-muted">{{ $document->creator->name }}</td>
                                    <td class="whitespace-nowrap px-4 py-3"><x-status-badge :status="$document->status" /></td>
                                    <td class="whitespace-nowrap px-4 py-3 text-right">
                                        @include('dokumen._actions', ['document' => $document])
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($documents->hasPages())
                    <div class="border-t border-line px-4 py-4">{{ $documents->links() }}</div>
                @endif
            </div>

            {{-- Daftar mobile --}}
            <div class="md:hidden">
                <div class="overflow-hidden rounded-md border border-line bg-surface">
                    @foreach ($documents as $document)
                        <div class="border-b border-line p-4 last:border-b-0">
                            <div class="flex items-start justify-between gap-3">
                                <a href="{{ route('dokumen.show', $document) }}" class="min-w-0 flex-1 text-sm font-semibold text-ink">{{ $document->judul }}</a>
                                <x-status-badge :status="$document->status" class="shrink-0" />
                            </div>
                            <p class="mt-1 text-xs text-ink-muted">{{ $document->jenis_label }} · {{ $document->creator->name }}</p>
                            <p class="tnum mt-1 text-xs text-ink-muted">{{ $document->nomor_surat ?? 'Belum bernomor' }} · {{ $document->created_at->translatedFormat('d M Y') }}</p>
                            <div class="mt-3 flex items-center justify-between">
                                <a href="{{ route('dokumen.show', $document) }}" class="text-sm font-semibold text-brand hover:underline">Lihat</a>
                                @include('dokumen._actions', ['document' => $document])
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($documents->hasPages())
                    <div class="mt-4 overflow-hidden rounded-md border border-line bg-surface px-4 py-4">{{ $documents->links() }}</div>
                @endif
            </div>
        @endif
    </div>
@endsection
