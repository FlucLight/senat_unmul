@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <div class="space-y-6">
        {{-- Sambutan --}}
        <div class="rounded-2xl bg-gradient-to-r from-blue-700 to-blue-600 px-6 py-6 text-white shadow-lg shadow-blue-700/20 sm:px-8">
            <p class="text-sm font-medium text-blue-100">Beranda</p>
            <h2 class="mt-1 text-2xl font-bold">Selamat datang, {{ auth()->user()->name }}</h2>
            <p class="mt-1 text-sm text-blue-100">
                NIP {{ auth()->user()->nip }} &middot; Peran: {{ auth()->user()->role }}
            </p>
        </div>

        {{-- Aksi cepat --}}
        <div>
            <h3 class="mb-3 text-sm font-semibold uppercase tracking-wider text-slate-500">Aksi cepat</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ($stats['per_jenis'] as $key => $item)
                    <a href="{{ route('dokumen.baru', $key) }}"
                        class="group rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-900/5 transition hover:shadow-md hover:ring-blue-200">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-semibold text-slate-900">Buat {{ $item['label'] }}</p>
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 text-blue-700 transition group-hover:bg-blue-700 group-hover:text-white">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </span>
                        </div>
                        <p class="mt-1 text-xs text-slate-400">Buka editor untuk jenis dokumen ini</p>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Ringkasan statistik --}}
        <div>
            <h3 class="mb-3 text-sm font-semibold uppercase tracking-wider text-slate-500">Ringkasan dokumen</h3>
            <div class="grid grid-cols-2 gap-4 lg:grid-cols-7">
                @foreach ($stats['per_jenis'] as $item)
                    <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-900/5">
                        <p class="text-2xl font-bold text-slate-900">{{ $item['total'] }}</p>
                        <p class="mt-1 text-xs font-medium text-slate-500">{{ $item['label'] }}</p>
                    </div>
                @endforeach

                <div class="rounded-2xl bg-amber-50 p-4 shadow-sm ring-1 ring-amber-200">
                    <p class="text-2xl font-bold text-amber-700">{{ $stats['draft'] }}</p>
                    <p class="mt-1 text-xs font-medium text-amber-600">Draft (perlu tindak lanjut)</p>
                </div>

                <div class="rounded-2xl bg-emerald-50 p-4 shadow-sm ring-1 ring-emerald-200">
                    <p class="text-2xl font-bold text-emerald-700">{{ $stats['final'] }}</p>
                    <p class="mt-1 text-xs font-medium text-emerald-600">Final (bernomor & QR)</p>
                </div>

                <div class="rounded-2xl bg-slate-50 p-4 shadow-sm ring-1 ring-slate-200">
                    <p class="text-2xl font-bold text-slate-800">{{ $stats['bulan_ini'] }}</p>
                    <p class="mt-1 text-xs font-medium text-slate-500">Bulan ini</p>
                </div>
            </div>
        </div>

        {{-- Aktivitas terbaru --}}
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-900/5">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <h3 class="text-sm font-semibold text-slate-900">Aktivitas terbaru</h3>
                <a href="{{ route('dokumen.index') }}" class="text-sm font-medium text-blue-700 transition hover:text-blue-800 hover:underline">
                    Lihat semua
                </a>
            </div>

            @if ($recent->isEmpty())
                <div class="px-5 py-12 text-center">
                    <p class="text-sm font-semibold text-slate-700">Belum ada dokumen</p>
                    <p class="mt-1 text-sm text-slate-500">Gunakan aksi cepat di atas untuk membuat dokumen pertama.</p>
                </div>
            @else
                <ul class="divide-y divide-slate-100">
                    @foreach ($recent as $document)
                        <li>
                            <a href="{{ route('dokumen.show', $document) }}" class="flex items-center gap-4 px-5 py-4 transition hover:bg-slate-50">
                                <span class="hidden h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-700 sm:flex">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
                                    </svg>
                                </span>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-medium text-slate-900">{{ $document->judul }}</p>
                                    <p class="mt-0.5 text-xs text-slate-500">
                                        {{ $document->jenis_label }} &middot; dibuat oleh {{ $document->creator->name }}
                                    </p>
                                </div>
                                <div class="flex shrink-0 items-center gap-3">
                                    @if ($document->isFinal())
                                        <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700">Final</span>
                                    @else
                                        <span class="rounded-full bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-700">Draft</span>
                                    @endif
                                    <span class="hidden text-xs text-slate-400 sm:block">{{ $document->created_at->translatedFormat('d M Y') }}</span>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
@endsection