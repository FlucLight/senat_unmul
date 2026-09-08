@extends('layouts.app')

@section('title', 'Daftar Dokumen')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div>
            <h2 class="font-display text-[28px] font-semibold leading-tight text-ink-900">Daftar dokumen</h2>
            <p class="mt-1 text-sm text-ink-400">Cari dan akses seluruh dokumen Senat FT. {{ $documents->total() }} dokumen.</p>
        </div>

        {{-- Filter --}}
        <form method="GET" action="{{ route('dokumen.index') }}" class="space-y-4">
            {{-- Mobile: pencarian + tombol filter --}}
            <div class="flex gap-2 md:hidden">
                <div class="relative flex-1">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-ink-400">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </span>
                    <input type="search" name="q" id="m-q" value="{{ $filters['q'] ?? '' }}"
                        placeholder="Cari nomor surat / judul"
                        class="w-full rounded-md border border-frame bg-surface py-2.5 pl-9 pr-3 text-sm text-ink-900 placeholder-ink-400 outline-none transition focus:border-gold-600 focus:ring-2 focus:ring-gold-100">
                </div>
                <button type="button" id="filter-toggle"
                    class="inline-flex items-center gap-2 rounded-md border border-frame-strong bg-surface px-4 py-2.5 text-sm font-medium text-ink-900 transition hover:bg-gold-50">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
                    </svg>
                    Filter
                </button>
            </div>

            {{-- Desktop: panel filter lengkap --}}
            <div class="hidden rounded-lg border border-frame bg-surface p-5 md:block">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-6">
                    @include('dokumen._filters', ['prefix' => 'd', 'showSearch' => true, 'withButtons' => true])
                </div>
            </div>

            {{-- Mobile: bottom sheet filter --}}
            <div id="filter-backdrop" class="fixed inset-0 z-40 hidden bg-ink-900/50 md:hidden"></div>
            <div id="filter-sheet" class="fixed inset-x-0 bottom-0 z-50 max-h-[85vh] translate-y-full overflow-y-auto rounded-t-lg border-t border-frame bg-surface p-5 transition-transform duration-200 md:hidden">
                <div class="mb-5 flex items-center justify-between">
                    <h3 class="font-display text-lg font-semibold text-ink-900">Filter dokumen</h3>
                    <button type="button" id="filter-close" class="inline-flex h-9 w-9 items-center justify-center rounded-md text-ink-400 transition hover:bg-surface-muted hover:text-ink-900" aria-label="Tutup">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    @include('dokumen._filters', ['prefix' => 'm', 'showSearch' => false, 'withButtons' => false])
                </div>
                <div class="mt-5 flex items-center gap-2">
                    <button type="submit" class="flex-1 rounded-md bg-gold-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-gold-500">
                        Terapkan filter
                    </button>
                    <a href="{{ route('dokumen.index') }}" class="rounded-md px-4 py-2.5 text-sm font-medium text-ink-500 transition hover:text-ink-900">
                        Reset
                    </a>
                </div>
            </div>
        </form>

        {{-- Empty state --}}
        @if ($documents->isEmpty())
            <div class="rounded-lg border border-frame bg-surface px-6 py-16 text-center">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-md bg-surface-muted text-ink-400">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                </div>
                <p class="mt-4 text-sm font-semibold text-ink-700">
                    {{ $filtered ? 'Tidak ada dokumen yang cocok dengan filter.' : 'Belum ada dokumen.' }}
                </p>
                <p class="mt-1 text-sm text-ink-400">
                    @if ($filtered)
                        Coba ubah kata kunci atau filter pencarian.
                    @else
                        Dokumen yang dibuat pengurus akan tampil di sini.
                    @endif
                </p>
            </div>
        @else
            {{-- Tabel (desktop) --}}
            <div class="hidden overflow-hidden rounded-lg border border-frame bg-surface md:block">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-frame text-sm">
                        <thead>
                            <tr class="bg-surface-muted text-left text-xs font-medium text-ink-700">
                                <th class="tnum px-5 py-3 text-right">Nomor surat</th>
                                <th class="px-5 py-3">Jenis</th>
                                <th class="px-5 py-3">Judul / perihal</th>
                                <th class="tnum px-5 py-3 text-right">Tanggal</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3">Dibuat oleh</th>
                                <th class="px-5 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-frame">
                            @foreach ($documents as $document)
                                <tr class="transition hover:bg-gold-50">
                                    <td class="tnum whitespace-nowrap px-5 py-3.5 text-right font-medium text-ink-900">
                                        {{ $document->nomor_surat ?? '—' }}
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-3.5 text-ink-700">
                                        {{ $document->jenis_label }}
                                    </td>
                                    <td class="max-w-[18rem] px-5 py-3.5">
                                        <a href="{{ route('dokumen.show', $document) }}" class="font-medium text-ink-900 transition hover:text-green-700 hover:underline">
                                            {{ $document->judul }}
                                        </a>
                                    </td>
                                    <td class="tnum whitespace-nowrap px-5 py-3.5 text-right text-ink-400">
                                        {{ $document->created_at->translatedFormat('d M Y') }}
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-3.5">
                                        <x-status-badge :status="$document->status" />
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-3.5 text-ink-400">
                                        {{ $document->creator->name }}
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-3.5">
                                        @include('dokumen._actions', ['document' => $document])
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($documents->hasPages())
                    <div class="border-t border-frame px-5 py-4">
                        {{ $documents->links() }}
                    </div>
                @endif
            </div>

            {{-- Kartu per dokumen (mobile) --}}
            <div class="space-y-4 md:hidden">
                @foreach ($documents as $document)
                    <div class="rounded-lg border border-frame bg-surface p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="truncate font-display text-sm font-semibold text-ink-900">{{ $document->jenis_label }}</p>
                                <p class="tnum mt-0.5 text-xs text-ink-400">{{ $document->nomor_surat ?? '—' }}</p>
                            </div>
                            <x-status-badge :status="$document->status" />
                        </div>
                        <a href="{{ route('dokumen.show', $document) }}" class="mt-2 block text-sm font-medium text-ink-900 transition hover:text-green-700">
                            {{ $document->judul }}
                        </a>
                        <div class="mt-3 flex items-center justify-between">
                            <p class="tnum text-xs text-ink-400">{{ $document->created_at->translatedFormat('d M Y') }}</p>
                            <div class="flex items-end">
                                @include('dokumen._actions', ['document' => $document])
                            </div>
                        </div>
                    </div>
                @endforeach

                @if ($documents->hasPages())
                    <div class="rounded-lg border border-frame bg-surface px-5 py-4">
                        {{ $documents->links() }}
                    </div>
                @endif
            </div>
        @endif
    </div>
@endsection