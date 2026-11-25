<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk - Senat FT Unmul</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-surface antialiased">
    <a href="#konten" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-3 focus:z-50 focus:rounded-md focus:bg-surface focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-brand focus:shadow-popover">
        Lompat ke konten
    </a>

    <div class="grid min-h-screen grid-cols-1 lg:grid-cols-12">
        {{-- Keterangan institusional --}}
        <aside class="order-2 flex flex-col justify-between border-t border-line bg-paper px-4 py-12 md:px-8 lg:order-1 lg:col-span-5 lg:border-r lg:border-t-0 lg:px-12 lg:py-12">
            <div>
                <a href="{{ route('welcome') }}" class="inline-flex items-center gap-3">
                    <img src="{{ asset('image.png') }}" alt="" class="h-11 w-auto object-contain">
                    <span class="leading-tight">
                        <span class="block font-serif text-base font-semibold text-ink">Senat Fakultas Teknik</span>
                        <span class="block text-xs text-ink-muted">Universitas Mulawarman</span>
                    </span>
                </a>

                <h2 class="mt-12 max-w-md font-serif text-2xl font-semibold leading-snug text-ink">
                    Sistem Manajemen dan Validasi Dokumen
                </h2>
                <p class="mt-3 max-w-md text-sm leading-relaxed text-ink-muted">
                    Akses khusus pengurus Senat. Gunakan NIP dan kata sandi yang diberikan oleh admin.
                </p>
            </div>

            <p class="mt-12 text-xs leading-relaxed text-ink-muted">
                Pembuatan, penomoran, dan pemeriksaan keaslian dokumen resmi Senat Fakultas Teknik Universitas Mulawarman.
            </p>
        </aside>

        {{-- Form masuk --}}
        <main id="konten" class="order-1 flex items-center justify-center border-b-2 border-gold-600 px-4 py-12 lg:order-2 lg:col-span-7 lg:border-b-0 lg:px-12">
            <div class="w-full max-w-[400px]">
                <p class="text-xs font-semibold uppercase tracking-wider text-gold-700">Akses Pengurus</p>
                <h1 class="mt-2 font-serif text-2xl font-semibold text-ink">Masuk</h1>
                <p class="mt-1 text-sm text-ink-muted">Gunakan NIP dan kata sandi Anda.</p>

                @if ($errors->any())
                    <div class="mt-6">
                        <x-alert variant="error">
                            <p class="font-semibold">Tidak dapat masuk.</p>
                            <ul class="mt-1 list-inside list-disc space-y-1 text-xs">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </x-alert>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.attempt') }}" class="mt-6 space-y-6">
                    @csrf

                    <div>
                        <label for="nip" class="mb-1 block text-sm font-medium text-ink">NIP <span class="font-normal text-ink-muted">(wajib)</span></label>
                        <input
                            id="nip"
                            type="text"
                            name="nip"
                            value="{{ old('nip') }}"
                            required
                            autofocus
                            autocomplete="username"
                            inputmode="numeric"
                            aria-describedby="nip-hint"
                            class="tnum h-11 w-full rounded-md border border-control bg-surface px-3 text-base text-ink placeholder:text-ink-muted focus-visible:outline-2 focus-visible:outline-offset-0 focus-visible:outline-focus"
                        >
                        <p id="nip-hint" class="mt-1 text-sm text-ink-muted">Nomor induk pegawai tanpa spasi.</p>
                    </div>

                    <div>
                        <label for="password" class="mb-1 block text-sm font-medium text-ink">Kata sandi <span class="font-normal text-ink-muted">(wajib)</span></label>
                        <div class="relative">
                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                class="h-11 w-full rounded-md border border-control bg-surface px-3 pr-24 text-base text-ink focus-visible:outline-2 focus-visible:outline-offset-0 focus-visible:outline-focus"
                            >
                            <button type="button" id="toggle-password" aria-pressed="false"
                                class="absolute inset-y-0 right-0 flex items-center px-3 text-xs font-semibold text-brand hover:underline">
                                Tampilkan
                            </button>
                        </div>
                    </div>

                    <label class="flex min-h-11 cursor-pointer items-center gap-2 text-sm text-ink">
                        <input type="checkbox" name="remember" value="1" class="size-5 rounded-sm border border-control accent-brand">
                        Ingat saya
                    </label>

                    <x-button type="submit" size="lg" class="w-full">Masuk</x-button>
                </form>

                <p class="mt-4 text-sm leading-relaxed text-ink-muted">
                    Lupa kata sandi? Minta admin lain untuk mengatur ulang.
                </p>

                <a href="{{ route('welcome') }}" class="mt-6 inline-flex items-center gap-1.5 text-sm font-medium text-ink-muted hover:text-brand">
                    <x-icon name="arrow-left" class="size-4" /> Kembali ke halaman utama
                </a>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const button = document.getElementById('toggle-password');
            const input = document.getElementById('password');
            if (!button || !input) return;

            button.addEventListener('click', () => {
                const show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                button.textContent = show ? 'Sembunyikan' : 'Tampilkan';
                button.setAttribute('aria-pressed', String(show));
            });
        });
    </script>
</body>
</html>
