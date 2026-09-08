<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Senat Fakultas Teknik')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-full flex-col bg-paper antialiased">
    @php
        $nameParts = array_values(array_filter(explode(' ', trim(auth()->user()->name ?? ''))));
        $initials = count($nameParts) >= 2
            ? strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1))
            : strtoupper(substr($nameParts[0] ?? 'U', 0, 2));
    @endphp

    {{-- Top Navigation Bar Horizontal (Gaya Moodle) --}}
    <header class="sticky top-0 z-30 h-[60px] border-b border-frame bg-surface">
        <div class="mx-auto flex h-full max-w-[1200px] items-center justify-between px-4 sm:px-8">
            {{-- Kiri: Hamburger (mobile) + Brand + Menu Horizontal (desktop) --}}
            <div class="flex h-full items-center gap-4 lg:gap-6">
                {{-- Hamburger mobile --}}
                <button id="mobile-menu-toggle" type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-md text-ink-700 hover:bg-surface-muted md:hidden" aria-label="Buka menu navigasi">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>

                {{-- Logo / Nama Sistem --}}
                <a href="{{ route('beranda') }}" class="flex items-center gap-2">
                    <span class="flex h-8 w-8 items-center justify-center rounded bg-gold-600 font-display text-xs font-semibold text-white">FT</span>
                    <span class="font-display text-base font-semibold text-ink-900">Senat FT</span>
                </a>

                {{-- Navigasi Desktop (teks saja, underline 2px green-700 saat aktif) --}}
                <nav class="hidden h-full items-center md:flex">
                    <x-nav-link :href="route('beranda')" :active="request()->routeIs('beranda')">
                        Beranda
                    </x-nav-link>

                    <x-nav-link :href="route('dokumen.index')" :active="request()->routeIs('dokumen.index') || request()->routeIs('dokumen.show')">
                        Daftar Isi
                    </x-nav-link>

                    @foreach (\App\Models\Document::JENIS as $key => $label)
                        <x-nav-link :href="route('dokumen.baru', $key)" :active="request()->routeIs('dokumen.baru') && request()->route('jenis') === $key">
                            {{ $label }}
                        </x-nav-link>
                    @endforeach
                </nav>
            </div>

            {{-- Kanan: Ikon pencarian + Avatar Inisial Pengguna --}}
            <div class="flex items-center gap-2 sm:gap-3">
                <a href="{{ route('dokumen.index') }}" title="Cari dokumen" class="inline-flex h-9 w-9 items-center justify-center rounded-md text-ink-500 transition hover:bg-surface-muted hover:text-ink-900">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </a>

                {{-- Dropdown Native Sederhana Avatar Inisial Pengguna --}}
                <details class="relative" id="user-menu-dropdown">
                    <summary class="flex cursor-pointer list-none items-center gap-1.5 rounded-full p-0.5 outline-none focus:ring-2 focus:ring-green-700/20">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-green-700 text-xs font-semibold text-white">
                            {{ $initials }}
                        </span>
                        <svg class="h-3.5 w-3.5 text-ink-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </summary>
                    <div class="absolute right-0 z-50 mt-2 w-60 rounded-md border border-frame bg-surface p-4 text-left shadow-[0_2px_8px_rgba(34,34,29,0.08)]">
                        <p class="truncate text-sm font-semibold text-ink-900">{{ auth()->user()->name }}</p>
                        <p class="truncate text-xs text-ink-400">NIP {{ auth()->user()->nip }}</p>
                        <div class="mt-2">
                            <span class="inline-block rounded bg-green-100 px-2 py-0.5 text-[11px] font-medium text-green-700">
                                {{ auth()->user()->role }}
                            </span>
                        </div>
                        <div class="my-3 border-t border-frame"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full rounded border border-frame-strong bg-surface px-3 py-1.5 text-center text-xs font-medium text-ink-900 transition hover:bg-surface-muted">
                                Keluar
                            </button>
                        </form>
                    </div>
                </details>
            </div>
        </div>
    </header>

    {{-- Mobile Drawer Navigasi (terbuka dari kiri) --}}
    <div id="mobile-drawer-backdrop" class="fixed inset-0 z-40 hidden bg-ink-900/50 md:hidden"></div>
    <aside id="mobile-drawer" class="fixed inset-y-0 left-0 z-50 flex w-64 -translate-x-full flex-col border-r border-frame bg-surface transition-transform duration-200 md:hidden">
        <div class="flex h-[60px] items-center justify-between border-b border-frame px-4">
            <div class="flex items-center gap-2">
                <span class="flex h-7 w-7 items-center justify-center rounded bg-gold-600 font-display text-xs font-semibold text-white">FT</span>
                <span class="font-display text-sm font-semibold text-ink-900">Senat FT</span>
            </div>
            <button id="mobile-drawer-close" type="button" class="inline-flex h-8 w-8 items-center justify-center rounded-md text-ink-400 hover:bg-surface-muted hover:text-ink-900" aria-label="Tutup menu">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <nav class="flex-1 space-y-1 overflow-y-auto p-4 text-sm font-medium">
            <p class="px-2 pb-1 text-xs font-medium text-ink-400">Menu</p>
            <a href="{{ route('beranda') }}" class="block rounded px-3 py-2 {{ request()->routeIs('beranda') ? 'bg-green-100 text-green-700' : 'text-ink-700 hover:bg-surface-muted' }}">
                Beranda
            </a>
            <a href="{{ route('dokumen.index') }}" class="block rounded px-3 py-2 {{ request()->routeIs('dokumen.index') || request()->routeIs('dokumen.show') ? 'bg-green-100 text-green-700' : 'text-ink-700 hover:bg-surface-muted' }}">
                Daftar Isi
            </a>

            <div class="my-3 border-t border-frame"></div>
            <p class="px-2 pb-1 text-xs font-medium text-ink-400">Buat dokumen</p>
            @foreach (\App\Models\Document::JENIS as $key => $label)
                <a href="{{ route('dokumen.baru', $key) }}" class="block rounded px-3 py-2 {{ request()->routeIs('dokumen.baru') && request()->route('jenis') === $key ? 'bg-green-100 text-green-700' : 'text-ink-700 hover:bg-surface-muted' }}">
                    {{ $label }}
                </a>
            @endforeach
        </nav>

        <div class="border-t border-frame p-4">
            <div class="flex items-center gap-3">
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-green-700 text-xs font-semibold text-white">
                    {{ $initials }}
                </span>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-xs font-semibold text-ink-900">{{ auth()->user()->name }}</p>
                    <p class="truncate text-[11px] text-ink-400">NIP {{ auth()->user()->nip }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button type="submit" class="w-full rounded border border-frame bg-surface px-3 py-1.5 text-center text-xs font-medium text-ink-900 hover:bg-surface-muted">
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- Flash messages --}}
    @if (session('status'))
        <div class="mx-auto w-full max-w-[1200px] px-4 pt-4 sm:px-8">
            <div class="flex items-center gap-2 rounded-md border border-green-100 bg-green-100 px-4 py-3 text-sm text-green-700">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                </svg>
                {{ session('status') }}
            </div>
        </div>
    @endif
    @if (session('error'))
        <div class="mx-auto w-full max-w-[1200px] px-4 pt-4 sm:px-8">
            <div class="flex items-center gap-2 rounded-md border border-error-bg bg-error-bg px-4 py-3 text-sm text-error-text">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>
                {{ session('error') }}
            </div>
        </div>
    @endif

    {{-- Konten Utama --}}
    <main class="mx-auto w-full max-w-[1200px] flex-1 px-4 py-8 sm:px-8">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="border-t border-frame bg-surface px-4 py-4 text-center text-xs text-ink-400 sm:px-8">
        &copy; {{ date('Y') }} Senat Fakultas Teknik, Universitas Mulawarman.
    </footer>
</body>
</html>
