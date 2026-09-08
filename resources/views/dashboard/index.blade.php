@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <div class="space-y-8">
        {{-- Sambutan --}}
        <div>
            <p class="text-xs font-medium text-ink-400">Beranda</p>
            <h2 class="mt-1 font-display text-[28px] font-semibold leading-tight text-ink-900">
                Halo, {{ auth()->user()->name }}
            </h2>
            <p class="mt-1 text-sm text-ink-400">NIP {{ auth()->user()->nip }}, Peran: {{ auth()->user()->role }}</p>
        </div>

        {{-- Aksi cepat --}}
        <div>
            <h3 class="mb-4 font-display text-xl font-medium text-ink-900">Aksi cepat</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ($stats['per_jenis'] as $key => $item)
                    <a href="{{ route('dokumen.baru', $key) }}"
                        class="inline-flex items-center justify-center gap-2 rounded-md bg-gold-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-gold-500">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Buat {{ $item['label'] }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Ringkasan dokumen --}}
        <div>
            <h3 class="mb-4 font-display text-xl font-medium text-ink-900">Ringkasan dokumen</h3>
            <div class="grid grid-cols-2 gap-4 lg:grid-cols-7">
                @foreach ($stats['per_jenis'] as $key => $item)
                    <div class="rounded-lg border border-frame border-l-[3px] border-l-gold-600 bg-surface p-5">
                        <p class="tnum font-display text-[32px] font-semibold leading-none text-ink-900">{{ $item['total'] }}</p>
                        <p class="mt-2 text-xs font-medium text-ink-400">{{ $item['label'] }}</p>
                    </div>
                @endforeach

                <div class="rounded-lg border border-frame border-l-[3px] border-l-gold-600 bg-surface p-5">
                    <p class="tnum font-display text-[32px] font-semibold leading-none text-draft-text">{{ $stats['draft'] }}</p>
                    <p class="mt-2 text-xs font-medium text-ink-400">Draft (perlu tindak lanjut)</p>
                </div>

                <div class="rounded-lg border border-frame border-l-[3px] border-l-green-700 bg-surface p-5">
                    <p class="tnum font-display text-[32px] font-semibold leading-none text-green-700">{{ $stats['final'] }}</p>
                    <p class="mt-2 text-xs font-medium text-ink-400">Final (bernomor & QR)</p>
                </div>

                <div class="rounded-lg border border-frame p-5 bg-surface">
                    <p class="tnum font-display text-[32px] font-semibold leading-none text-ink-900">{{ $stats['bulan_ini'] }}</p>
                    <p class="mt-2 text-xs font-medium text-ink-400">Bulan ini</p>
                </div>
            </div>
        </div>

        {{-- Aktivitas terbaru --}}
        <div class="overflow-hidden rounded-lg border border-frame bg-surface">
            <div class="flex items-center justify-between border-b border-frame px-6 py-4">
                <h3 class="font-display text-xl font-medium text-ink-900">Aktivitas terbaru</h3>
                <a href="{{ route('dokumen.index') }}" class="text-sm font-medium text-green-700 transition hover:text-green-600 hover:underline">
                    Lihat semua
                </a>
            </div>

            @if ($recent->isEmpty())
                <div class="px-6 py-16 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-md bg-surface-muted text-ink-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
                        </svg>
                    </div>
                    <p class="mt-4 text-sm font-semibold text-ink-700">Belum ada dokumen</p>
                    <p class="mt-1 text-sm text-ink-400">Gunakan aksi cepat di atas untuk membuat dokumen pertama.</p>
                </div>
            @else
                <ul class="divide-y divide-frame">
                    @foreach ($recent as $document)
                        <li>
                            <a href="{{ route('dokumen.show', $document) }}" class="flex items-center gap-4 px-6 py-4 transition hover:bg-gold-50">
                                <span class="hidden h-10 w-10 shrink-0 items-center justify-center rounded-md bg-surface-muted text-ink-400 sm:flex">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                    </svg>
                                </span>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-medium text-ink-900">{{ $document->judul }}</p>
                                    <p class="mt-0.5 text-xs text-ink-400">
                                        {{ $document->jenis_label }} — dibuat oleh {{ $document->creator->name }}
                                    </p>
                                </div>
                                <div class="flex shrink-0 items-center gap-3">
                                    <x-status-badge :status="$document->status" />
                                    <span class="tnum hidden text-xs text-ink-400 sm:block">{{ $document->created_at->translatedFormat('d M Y') }}</span>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
@endsection