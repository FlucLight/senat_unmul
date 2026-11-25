<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@hasSection('title')@yield('title') - Senat FT Unmul@else Senat FT Unmul @endif</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="flex min-h-full flex-col bg-paper antialiased">
    @php
        $nameParts = array_values(array_filter(explode(' ', trim(auth()->user()->name ?? ''))));
        $initials = count($nameParts) >= 2
            ? strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1))
            : strtoupper(substr($nameParts[0] ?? 'U', 0, 2));

        $isOperator = (auth()->user()->role ?? null) === 'operator';
        $buatAktif = request()->routeIs('dokumen.baru')
            || request()->routeIs('dokumen.undangan.*')
            || request()->routeIs('dokumen.berita-acara.*')
            || request()->routeIs('dokumen.surat-pengantar.*')
            || request()->routeIs('dokumen.daftar-hadir.*');
        $suratAktif = request()->routeIs('surat.*') || request()->routeIs('jenis_surat.*');
        $dokumenAktif = request()->routeIs('dokumen.index')
            || request()->routeIs('dokumen.show')
            || request()->routeIs('dokumen.edit');
    @endphp

    <a href="#konten" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-3 focus:z-50 focus:rounded-md focus:bg-surface focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-brand focus:shadow-popover">
        Lompat ke konten
    </a>

    <header class="sticky top-0 z-30 border-b border-line bg-surface">
        <div class="mx-auto flex h-16 max-w-page items-center justify-between gap-4 px-4 md:px-6">
            {{-- Kiri: brand + navigasi --}}
            <div class="flex h-full min-w-0 items-center gap-3 lg:gap-4">
                <button id="mobile-menu-toggle" type="button"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-md text-ink-muted transition-colors hover:bg-paper hover:text-ink lg:hidden"
                    aria-label="Buka menu navigasi" aria-controls="mobile-drawer" aria-expanded="false">
                    <x-icon name="menu" class="size-5" />
                </button>

                <a href="{{ route('welcome') }}" class="flex shrink-0 items-center gap-2.5">
                    <img src="{{ asset('image.png') }}" alt="" class="h-8 w-auto object-contain">
                    <span class="font-serif text-lg font-semibold leading-none text-ink">Senat FT Unmul</span>
                </a>

                <nav aria-label="Navigasi utama" class="hidden h-full items-center lg:flex">
                    <x-nav-link :href="route('beranda')" :active="request()->routeIs('beranda')">Beranda</x-nav-link>
                    <x-nav-link :href="route('dokumen.index')" :active="$dokumenAktif">Daftar Dokumen</x-nav-link>

                    <details class="relative h-full nav-dropdown">
                        <summary class="flex h-16 cursor-pointer list-none items-center gap-1 border-b-2 px-3 text-sm transition-colors {{ $buatAktif ? 'border-brand font-semibold text-brand-hover' : 'border-transparent font-medium text-ink hover:border-line-strong hover:bg-paper' }}">
                            Buat Dokumen
                            <x-icon name="chevron-down" class="size-3.5" />
                        </summary>
                        <div class="absolute left-0 z-50 mt-0 w-56 rounded-lg border border-line bg-surface p-1 shadow-popover">
                            @foreach (\App\Models\Document::JENIS as $key => $label)
                                <a href="{{ route('dokumen.baru', $key) }}"
                                    class="block rounded-sm px-3 py-2.5 text-sm {{ request()->routeIs('dokumen.baru') && request()->route('jenis') === $key ? 'border-l-2 border-brand bg-brand-subtle font-semibold text-brand-hover' : 'text-ink hover:bg-paper' }}">
                                    {{ $label }}
                                </a>
                            @endforeach
                        </div>
                    </details>

                    <details class="relative h-full nav-dropdown">
                        <summary class="flex h-16 cursor-pointer list-none items-center gap-1 border-b-2 px-3 text-sm transition-colors {{ $suratAktif ? 'border-brand font-semibold text-brand-hover' : 'border-transparent font-medium text-ink hover:border-line-strong hover:bg-paper' }}">
                            Pembuat Surat
                            <x-icon name="chevron-down" class="size-3.5" />
                        </summary>
                        <div class="absolute left-0 z-50 mt-0 w-56 rounded-lg border border-line bg-surface p-1 shadow-popover">
                            <a href="{{ route('surat.index') }}" class="block rounded-sm px-3 py-2.5 text-sm {{ request()->routeIs('surat.*') ? 'border-l-2 border-brand bg-brand-subtle font-semibold text-brand-hover' : 'text-ink hover:bg-paper' }}">Surat</a>
                            <a href="{{ route('jenis_surat.index') }}" class="block rounded-sm px-3 py-2.5 text-sm {{ request()->routeIs('jenis_surat.*') ? 'border-l-2 border-brand bg-brand-subtle font-semibold text-brand-hover' : 'text-ink hover:bg-paper' }}">Template Surat</a>
                        </div>
                    </details>

                    @if ($isOperator)
                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.*')">Admin</x-nav-link>
                    @endif
                </nav>
            </div>

            {{-- Kanan: menu pengguna --}}
            <div class="flex items-center gap-2">
                <a href="{{ route('dokumen.index') }}" aria-label="Cari dokumen" title="Cari dokumen"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-md text-ink-muted transition-colors hover:bg-paper hover:text-ink">
                    <x-icon name="search" class="size-5" />
                </a>

                <details class="relative" id="user-menu-dropdown">
                    <summary class="flex cursor-pointer list-none items-center gap-2 rounded-md px-1.5 py-1 transition-colors hover:bg-paper">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-brand text-xs font-semibold text-surface">{{ $initials }}</span>
                        <span class="hidden max-w-[10rem] truncate text-sm font-medium text-ink md:block">{{ auth()->user()->name }}</span>
                        <x-icon name="chevron-down" class="hidden size-3.5 text-ink-muted md:block" />
                    </summary>
                    <div class="absolute right-0 z-50 mt-2 w-64 rounded-lg border border-line bg-surface p-3 text-left shadow-popover">
                        <p class="truncate text-sm font-semibold text-ink">{{ auth()->user()->name }}</p>
                        <p class="tnum truncate text-xs text-ink-muted">NIP {{ auth()->user()->nip }}</p>
                        <span class="mt-2 inline-block rounded-sm border border-line-strong bg-paper-200 px-2 py-0.5 text-xs font-medium text-ink">{{ auth()->user()->role }}</span>

                        @if ($isOperator)
                            <div class="mt-3 border-t border-line pt-2">
                                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-2 rounded-sm px-2 py-2 text-sm text-ink hover:bg-paper">
                                    <x-icon name="settings" class="size-4 text-ink-muted" /> Kelola Akun
                                </a>
                            </div>
                        @endif

                        <div class="mt-2 border-t border-line pt-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex w-full items-center gap-2 rounded-sm px-2 py-2 text-sm text-ink hover:bg-paper">
                                    <x-icon name="log-out" class="size-4 text-ink-muted" /> Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </details>
            </div>
        </div>
    </header>

    {{-- Drawer navigasi mobile --}}
    <div id="mobile-drawer-backdrop" class="fixed inset-0 z-40 hidden bg-ink/50 lg:hidden"></div>
    <aside id="mobile-drawer" class="fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col border-r border-line bg-surface transition-transform lg:hidden">
        <div class="flex h-16 items-center justify-between border-b border-line px-4">
            <a href="{{ route('welcome') }}" class="flex items-center gap-2">
                <img src="{{ asset('image.png') }}" alt="" class="h-7 w-auto object-contain">
                <span class="font-serif text-sm font-semibold text-ink">Senat FT Unmul</span>
            </a>
            <button id="mobile-drawer-close" type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-md text-ink-muted hover:bg-paper hover:text-ink" aria-label="Tutup menu">
                <x-icon name="x" class="size-5" />
            </button>
        </div>

        <nav class="flex-1 space-y-1 overflow-y-auto p-4 text-sm">
            <a href="{{ route('beranda') }}" class="block rounded-sm px-3 py-2.5 {{ request()->routeIs('beranda') ? 'bg-brand-subtle font-semibold text-brand-hover' : 'text-ink hover:bg-paper' }}">Beranda</a>
            <a href="{{ route('dokumen.index') }}" class="block rounded-sm px-3 py-2.5 {{ $dokumenAktif ? 'bg-brand-subtle font-semibold text-brand-hover' : 'text-ink hover:bg-paper' }}">Daftar Dokumen</a>

            <p class="px-3 pb-1 pt-4 text-xs font-semibold text-ink-muted">Buat dokumen</p>
            @foreach (\App\Models\Document::JENIS as $key => $label)
                <a href="{{ route('dokumen.baru', $key) }}" class="block rounded-sm px-3 py-2.5 {{ request()->routeIs('dokumen.baru') && request()->route('jenis') === $key ? 'bg-brand-subtle font-semibold text-brand-hover' : 'text-ink hover:bg-paper' }}">{{ $label }}</a>
            @endforeach

            <p class="px-3 pb-1 pt-4 text-xs font-semibold text-ink-muted">Pembuat surat</p>
            <a href="{{ route('surat.index') }}" class="block rounded-sm px-3 py-2.5 {{ request()->routeIs('surat.*') ? 'bg-brand-subtle font-semibold text-brand-hover' : 'text-ink hover:bg-paper' }}">Surat</a>
            <a href="{{ route('jenis_surat.index') }}" class="block rounded-sm px-3 py-2.5 {{ request()->routeIs('jenis_surat.*') ? 'bg-brand-subtle font-semibold text-brand-hover' : 'text-ink hover:bg-paper' }}">Template Surat</a>

            @if ($isOperator)
                <p class="px-3 pb-1 pt-4 text-xs font-semibold text-ink-muted">Admin</p>
                <a href="{{ route('admin.users.index') }}" class="block rounded-sm px-3 py-2.5 {{ request()->routeIs('admin.*') ? 'bg-brand-subtle font-semibold text-brand-hover' : 'text-ink hover:bg-paper' }}">Kelola Akun</a>
            @endif
        </nav>

        <div class="border-t border-line p-4">
            <div class="flex items-center gap-3">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brand text-xs font-semibold text-surface">{{ $initials }}</span>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-semibold text-ink">{{ auth()->user()->name }}</p>
                    <p class="tnum truncate text-xs text-ink-muted">NIP {{ auth()->user()->nip }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button type="submit" class="w-full rounded-md border border-control bg-surface px-3 py-2 text-sm font-medium text-ink transition-colors hover:bg-paper">Keluar</button>
            </form>
        </div>
    </aside>

    {{-- Pesan flash --}}
    @if (session('status'))
        <div class="mx-auto w-full max-w-page px-4 pt-4 md:px-6">
            <x-alert variant="success">{{ session('status') }}</x-alert>
        </div>
    @endif
    @if (session('error'))
        <div class="mx-auto w-full max-w-page px-4 pt-4 md:px-6">
            <x-alert variant="error">{{ session('error') }}</x-alert>
        </div>
    @endif

    <main id="konten" class="mx-auto w-full max-w-page flex-1 px-4 py-8 md:px-6">
        @yield('content')
    </main>

    <footer class="border-t border-line bg-surface">
        <div class="mx-auto flex max-w-page flex-col gap-1 px-4 py-4 text-xs text-ink-muted sm:flex-row sm:items-center sm:justify-between md:px-6">
            <p>&copy; {{ date('Y') }} Senat Fakultas Teknik, Universitas Mulawarman.</p>
            <p>Sistem internal persuratan Senat FT Unmul.</p>
        </div>
    </footer>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('details.nav-dropdown, #user-menu-dropdown').forEach((dropdown) => {
                dropdown.addEventListener('toggle', () => {
                    if (!dropdown.open) return;
                    document.querySelectorAll('details.nav-dropdown, #user-menu-dropdown').forEach((other) => {
                        if (other !== dropdown) other.open = false;
                    });
                });
            });

            document.addEventListener('click', (event) => {
                document.querySelectorAll('details.nav-dropdown, #user-menu-dropdown').forEach((dropdown) => {
                    if (dropdown.open && !dropdown.contains(event.target)) {
                        dropdown.open = false;
                    }
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
