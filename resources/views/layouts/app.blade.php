<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Senat Fakultas Teknik')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full antialiased">
    <div class="min-h-full lg:pl-60">

        {{-- Backdrop for mobile drawer --}}
        <div id="sidebar-backdrop" class="fixed inset-0 z-30 hidden bg-ink-900/50 lg:hidden"></div>

        {{-- Sidebar --}}
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 flex w-60 -translate-x-full flex-col border-r border-frame bg-surface-muted transition-transform duration-200 lg:translate-x-0">
            <div class="flex items-center gap-3 border-b border-frame px-5 py-4">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-md bg-gold-600 font-display text-sm font-semibold text-white">
                    FT
                </div>
                <div class="min-w-0">
                    <p class="truncate font-display text-sm font-semibold text-ink-900">Senat Fakultas Teknik</p>
                    <p class="text-xs text-ink-400">Universitas Mulawarman</p>
                </div>
            </div>

            <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-5">
                <p class="px-3 pb-2 text-xs font-medium text-ink-400">Menu utama</p>

                <x-nav-link :href="route('beranda')" :active="request()->routeIs('beranda')" icon="home">
                    Beranda
                </x-nav-link>

                <x-nav-link :href="route('dokumen.index')" :active="request()->routeIs('dokumen.*')" icon="folder">
                    Daftar Isi
                </x-nav-link>

                <p class="px-3 pt-6 pb-2 text-xs font-medium text-ink-400">Buat dokumen</p>

                @foreach (\App\Models\Document::JENIS as $key => $label)
                    <x-nav-link :href="route('dokumen.baru', $key)" :active="request()->routeIs('dokumen.baru') && request()->jenis === $key">
                        {{ $label }}
                    </x-nav-link>
                @endforeach
            </nav>

            <div class="border-t border-frame px-5 py-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-md bg-green-100 text-xs font-semibold text-green-700">
                        {{ strtoupper(Str::substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-ink-900">{{ auth()->user()->name }}</p>
                        <p class="truncate text-xs text-ink-400">{{ auth()->user()->nip }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="w-full rounded-md border border-frame-strong bg-white px-3 py-2 text-sm font-medium text-ink-900 transition hover:bg-surface-muted">
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main column --}}
        <div class="flex min-h-screen flex-col">
            <header class="sticky top-0 z-20 flex items-center gap-4 border-b border-frame bg-paper/95 px-4 py-3 backdrop-blur sm:px-8">
                <button id="sidebar-toggle" type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-md text-ink-500 transition hover:bg-surface-muted hover:text-ink-900 lg:hidden" aria-label="Buka menu">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>

                <div class="min-w-0 flex-1">
                    <h1 class="truncate font-display text-lg font-semibold text-ink-900">@yield('title', 'Sistem Senat Fakultas Teknik')</h1>
                </div>

                <span class="hidden rounded-md bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700 sm:inline">
                    {{ auth()->user()->role }}
                </span>
            </header>

            {{-- Flash messages --}}
            @if (session('status'))
                <div class="mx-auto w-full max-w-[1200px] px-4 pt-4 sm:px-8">
                    <div class="flex items-center gap-2 rounded-md border border-green-100 bg-green-100 px-4 py-3 text-sm text-green-700">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                        {{ session('status') }}
                    </div>
                </div>
            @endif
            @if (session('error'))
                <div class="mx-auto w-full max-w-[1200px] px-4 pt-4 sm:px-8">
                    <div class="flex items-center gap-2 rounded-md border border-error-bg bg-error-bg px-4 py-3 text-sm text-error-text">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                        </svg>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <main class="mx-auto w-full max-w-[1200px] flex-1 px-4 py-8 sm:px-8">
                @yield('content')
            </main>

            <footer class="border-t border-frame px-4 py-4 text-center text-xs text-ink-400 sm:px-8">
                &copy; {{ date('Y') }} Senat Fakultas Teknik, Universitas Mulawarman.
            </footer>
        </div>
    </div>
</body>
</html>