<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk — Sistem Senat Fakultas Teknik</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-full flex-col items-center justify-center bg-paper px-4 py-12 antialiased">
    <main class="w-full max-w-[380px]">
        {{-- Tautan Kembali ke Halaman Utama --}}
        <div class="mb-4">
            <a href="{{ route('welcome') }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-ink-500 transition hover:text-green-700">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                Kembali ke halaman utama
            </a>
        </div>

        <div class="mb-6 text-center">
            <img src="{{ asset('image.png') }}" alt="Logo Senat Fakultas Teknik" class="mx-auto mb-3 h-14 w-auto object-contain">
            <h1 class="font-display text-xl font-semibold text-ink-900">Senat Fakultas Teknik</h1>
            <p class="mt-1 text-sm text-ink-400">Universitas Mulawarman</p>
        </div>

        <div class="overflow-hidden rounded-lg border border-frame bg-surface">
            <div class="h-1 bg-gold-600"></div>

            <div class="p-8">
                <h2 class="font-display text-lg font-semibold text-ink-900">Masuk</h2>
                <p class="mt-1 text-sm text-ink-400">Gunakan NIP dan password pengurus Senat FT.</p>

                @if ($errors->any())
                    <div class="mt-5 rounded-md bg-error-bg px-4 py-3 text-sm text-error-text" role="alert">
                        <p class="font-medium">Tidak dapat masuk.</p>
                        <ul class="mt-1 list-inside list-disc space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.attempt') }}" class="mt-6 space-y-5">
                    @csrf

                    <div>
                        <label for="nip" class="mb-1.5 block text-xs font-medium text-ink-700">NIP</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-ink-400">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                            </span>
                            <input
                                type="text"
                                name="nip"
                                id="nip"
                                value="{{ old('nip') }}"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="Masukkan NIP"
                                class="tnum w-full rounded-md border border-frame bg-surface py-2.5 pl-9 pr-3 text-sm text-ink-900 placeholder-ink-400 outline-none transition focus:border-gold-600 focus:ring-2 focus:ring-gold-100"
                            >
                        </div>
                    </div>

                    <div>
                        <label for="password" class="mb-1.5 block text-xs font-medium text-ink-700">Password</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-ink-400">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                </svg>
                            </span>
                            <input
                                type="password"
                                name="password"
                                id="password"
                                required
                                autocomplete="current-password"
                                placeholder="Masukkan password"
                                class="w-full rounded-md border border-frame bg-surface py-2.5 pl-9 pr-3 text-sm text-ink-900 placeholder-ink-400 outline-none transition focus:border-gold-600 focus:ring-2 focus:ring-gold-100"
                            >
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <label class="flex cursor-pointer items-center gap-2 text-ink-500">
                            <input
                                type="checkbox"
                                name="remember"
                                value="1"
                                class="h-4 w-4 rounded border-frame-strong text-gold-600 accent-gold-600 focus:ring-gold-100 focus:ring-offset-0"
                            >
                            Ingat saya
                        </label>
                    </div>

                    <div class="space-y-2">
                        <button
                            type="submit"
                            class="w-full rounded-md bg-gold-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-100 focus:ring-offset-1"
                        >
                            Masuk
                        </button>
                        <a
                            href="{{ route('welcome') }}"
                            class="block w-full rounded-md border border-frame-strong bg-surface py-2 text-center text-xs font-medium text-ink-700 transition hover:bg-surface-muted hover:text-ink-900"
                        >
                            Kembali
                        </a>
                    </div>
                </form>

                <p class="mt-6 text-center text-xs text-ink-400">
                    Lupa password? Hubungi admin Senat FT.
                </p>
            </div>
        </div>

        <p class="mt-6 text-center text-xs text-ink-400">
            &copy; {{ date('Y') }} Senat Fakultas Teknik, Universitas Mulawarman.
        </p>
    </main>
</body>
</html>
