<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Senat Fakultas Teknik')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans text-slate-800 antialiased">
    <div class="min-h-full lg:pl-64">

        {{-- Backdrop for mobile sidebar --}}
        <div id="sidebar-backdrop" class="fixed inset-0 z-30 hidden bg-slate-900/60 lg:hidden"></div>

        {{-- Sidebar --}}
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full flex-col bg-slate-900 text-slate-300 transition-transform duration-200 lg:translate-x-0">
            <div class="flex items-center gap-3 border-b border-slate-800 px-5 py-4">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-700 text-sm font-bold text-white">
                    FT
                </div>
                <div>
                    <p class="text-sm font-semibold leading-tight text-white">Sistem Senat FT</p>
                    <p class="text-xs text-slate-400">Unmul</p>
                </div>
            </div>

            <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
                <p class="px-3 pb-2 text-[11px] font-semibold uppercase tracking-wider text-slate-500">Menu Utama</p>

                <x-nav-link :href="route('beranda')" :active="request()->routeIs('beranda')" icon="home">
                    Beranda
                </x-nav-link>

                <x-nav-link :href="route('dokumen.index')" :active="request()->routeIs('dokumen.*')" icon="folder">
                    Daftar Isi
                </x-nav-link>

                <p class="px-3 pt-4 pb-2 text-[11px] font-semibold uppercase tracking-wider text-slate-500">Buat Dokumen</p>

                @foreach (\App\Models\Document::JENIS as $key => $label)
                    <x-nav-link :href="route('dokumen.baru', $key)" :active="request()->routeIs('dokumen.baru') && request()->jenis === $key" icon="plus">
                        {{ $label }}
                    </x-nav-link>
                @endforeach
            </nav>

            <div class="border-t border-slate-800 px-5 py-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-slate-700 text-xs font-semibold text-white">
                        {{ strtoupper(Str::substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                        <p class="truncate text-xs text-slate-400">{{ auth()->user()->nip }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="w-full rounded-lg bg-slate-800 px-3 py-2 text-sm font-medium text-slate-200 transition hover:bg-slate-700">
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main column --}}
        <div class="flex min-h-screen flex-col">
            <header class="sticky top-0 z-20 flex items-center gap-4 border-b border-slate-200 bg-white/90 px-4 py-3 backdrop-blur sm:px-6">
                <button id="sidebar-toggle" type="button" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 lg:hidden" aria-label="Buka menu">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>

                <div class="min-w-0 flex-1">
                    <h1 class="truncate text-base font-bold text-slate-900 sm:text-lg">@yield('title', 'Sistem Senat Fakultas Teknik')</h1>
                </div>

                <div class="hidden items-center gap-2 sm:flex">
                    <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700">
                        {{ auth()->user()->role }}
                    </span>
                </div>
            </header>

            {{-- Flash messages --}}
            @if (session('status'))
                <div class="px-4 pt-4 sm:px-6">
                    <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                        {{ session('status') }}
                    </div>
                </div>
            @endif
            @if (session('error'))
                <div class="px-4 pt-4 sm:px-6">
                    <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <main class="flex-1 px-4 py-6 sm:px-6">
                @yield('content')
            </main>

            <footer class="border-t border-slate-200 px-4 py-4 text-center text-xs text-slate-400 sm:px-6">
                &copy; {{ date('Y') }} Senat Fakultas Teknik, Universitas Mulawarman.
            </footer>
        </div>
    </div>
</body>
</html>