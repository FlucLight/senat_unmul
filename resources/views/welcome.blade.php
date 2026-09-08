<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Dokumen Senat Fakultas Teknik — Universitas Mulawarman</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-full flex-col bg-paper text-ink-700 antialiased">
    {{-- 1. Top Navigation Bar (Publik) --}}
    <header class="sticky top-0 z-30 h-[60px] border-b border-frame bg-surface">
        <div class="mx-auto flex h-full max-w-[1200px] items-center justify-between px-4 sm:px-8">
            {{-- Kiri: Brand --}}
            <div class="flex items-center gap-3">
                <a href="{{ route('welcome') }}" class="flex items-center gap-2.5">
                    <span class="flex h-8 w-8 items-center justify-center rounded bg-gold-600 font-display text-xs font-semibold text-white">FT</span>
                    <div>
                        <p class="font-display text-sm font-semibold leading-tight text-ink-900">Senat Fakultas Teknik</p>
                        <p class="text-[11px] text-ink-400">Universitas Mulawarman</p>
                    </div>
                </a>
            </div>

            {{-- Tengah: Menu Anchor (Desktop) --}}
            <nav class="hidden items-center gap-6 text-xs font-medium md:flex">
                <a href="#tentang" class="text-ink-700 transition hover:text-green-700">Tentang</a>
                <a href="#jenis-dokumen" class="text-ink-700 transition hover:text-green-700">Jenis Dokumen</a>
                <a href="#verifikasi" class="text-ink-700 transition hover:text-green-700">Verifikasi Dokumen</a>
                <a href="#cara-kerja" class="text-ink-700 transition hover:text-green-700">Cara Kerja</a>
            </nav>

            {{-- Kanan: Tombol Masuk (Selalu terlihat di desktop & mobile) + Menu mobile --}}
            <div class="flex items-center gap-2 sm:gap-3">
                <a href="{{ route('login') }}"
                    class="rounded-md bg-gold-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-gold-500">
                    Masuk
                </a>

                {{-- Hamburger Mobile --}}
                <button id="welcome-menu-toggle" type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-md text-ink-500 hover:bg-surface-muted md:hidden" aria-label="Buka menu navigasi">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile Drawer Navigasi --}}
        <div id="welcome-drawer-backdrop" class="fixed inset-0 z-40 hidden bg-ink-900/50 md:hidden"></div>
        <aside id="welcome-drawer" class="fixed inset-y-0 left-0 z-50 flex w-64 -translate-x-full flex-col border-r border-frame bg-surface transition-transform duration-200 md:hidden">
            <div class="flex h-[60px] items-center justify-between border-b border-frame px-4">
                <div class="flex items-center gap-2">
                    <span class="flex h-7 w-7 items-center justify-center rounded bg-gold-600 font-display text-xs font-semibold text-white">FT</span>
                    <span class="font-display text-sm font-semibold text-ink-900">Senat FT</span>
                </div>
                <button id="welcome-drawer-close" type="button" class="inline-flex h-8 w-8 items-center justify-center rounded-md text-ink-400 hover:bg-surface-muted" aria-label="Tutup menu">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <nav class="space-y-1 p-4 text-sm font-medium">
                <a href="#tentang" class="welcome-nav-item block rounded px-3 py-2 text-ink-700 hover:bg-surface-muted">Tentang</a>
                <a href="#jenis-dokumen" class="welcome-nav-item block rounded px-3 py-2 text-ink-700 hover:bg-surface-muted">Jenis Dokumen</a>
                <a href="#verifikasi" class="welcome-nav-item block rounded px-3 py-2 text-ink-700 hover:bg-surface-muted">Verifikasi Dokumen</a>
                <a href="#cara-kerja" class="welcome-nav-item block rounded px-3 py-2 text-ink-700 hover:bg-surface-muted">Cara Kerja</a>
                <div class="my-3 border-t border-frame"></div>
                <a href="{{ route('login') }}" class="block rounded bg-gold-600 px-3 py-2 text-center text-xs font-semibold text-white">
                    Masuk ke Sistem
                </a>
            </nav>
        </aside>
    </header>

    <main class="flex-1">
        {{-- 2. Hero Section --}}
        <section class="border-b border-frame bg-surface py-14 sm:py-20">
            <div class="mx-auto max-w-[1200px] px-4 sm:px-8">
                <div class="max-w-2xl">
                    <div class="h-1 w-12 bg-gold-600 mb-6"></div>
                    <h1 class="font-display text-3xl font-semibold leading-tight text-ink-900 sm:text-4xl">
                        Sistem Manajemen & Validasi Dokumen Senat Fakultas Teknik
                    </h1>
                    <p class="mt-4 text-base leading-relaxed text-ink-500">
                        Pusat pembuatan, penomoran terpusat, dan verifikasi keaslian dokumen resmi Senat Fakultas Teknik Universitas Mulawarman.
                    </p>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center">
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center justify-center rounded-md bg-gold-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-gold-500">
                            Masuk Sistem
                        </a>
                        <a href="#verifikasi"
                            class="inline-flex items-center justify-center rounded-md border border-frame-strong bg-surface px-5 py-2.5 text-sm font-medium text-ink-900 transition hover:bg-surface-muted">
                            Verifikasi Dokumen
                        </a>
                    </div>
                </div>
            </div>
        </section>

        {{-- 3. Section Tentang Sistem --}}
        <section id="tentang" class="border-b border-frame py-12 sm:py-16">
            <div class="mx-auto max-w-[1200px] px-4 sm:px-8">
                <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                    <div>
                        <h2 class="font-display text-2xl font-semibold text-ink-900">Tentang sistem</h2>
                        <p class="mt-1 text-xs text-ink-400">Pembaruan tata kelola administrasi Senat FT.</p>
                    </div>
                    <div class="space-y-4 text-sm leading-relaxed text-ink-700 md:col-span-2">
                        <p>
                            Sebelumnya, penerbitan dokumen resmi Senat Fakultas Teknik seperti undangan, berita acara, surat pengantar, dan daftar hadir dikelola secara manual melalui berkas yang tersebar. Hal ini berisiko memicu bentrok nomor surat, hilangnya arsip saat pergantian kepengurusan, serta rentan terhadap pemalsuan tanda tangan.
                        </p>
                        <p>
                            Sistem ini memusatkan seluruh administrasi persuratan senat dalam satu pangkalan data terintegrasi. Penomoran surat dilakukan secara otomatis dan konsisten oleh sistem, sedangkan keaslian setiap dokumen dapat dibuktikan oleh publik melalui kode QR verifikasi resmi.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        {{-- 4. Section Jenis Dokumen --}}
        <section id="jenis-dokumen" class="border-b border-frame bg-surface py-12 sm:py-16">
            <div class="mx-auto max-w-[1200px] px-4 sm:px-8">
                <div class="mb-8">
                    <h2 class="font-display text-2xl font-semibold text-ink-900">Jenis dokumen yang dikelola</h2>
                    <p class="mt-1 text-xs text-ink-400">Empat jenis instrumen administrasi resmi persidangan dan koordinasi Senat FT.</p>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    {{-- 1. Undangan --}}
                    <div class="rounded-lg border border-frame bg-surface p-5 transition hover:border-frame-strong">
                        <span class="flex h-10 w-10 items-center justify-center rounded-md bg-surface-muted text-green-700">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                            </svg>
                        </span>
                        <h3 class="mt-4 font-display text-base font-semibold text-ink-900">Undangan</h3>
                        <p class="mt-1 text-xs leading-relaxed text-ink-400">
                            Pemanggilan resmi anggota senat untuk rapat rutin, sidang pleno, maupun rapat koordinasi komisi.
                        </p>
                    </div>

                    {{-- 2. Berita Acara --}}
                    <div class="rounded-lg border border-frame bg-surface p-5 transition hover:border-frame-strong">
                        <span class="flex h-10 w-10 items-center justify-center rounded-md bg-surface-muted text-green-700">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                        </span>
                        <h3 class="mt-4 font-display text-base font-semibold text-ink-900">Berita Acara</h3>
                        <p class="mt-1 text-xs leading-relaxed text-ink-400">
                            Catatan keputusan, pertimbangan akademik, dan hasil kesepakatan musyawarah rapat senat.
                        </p>
                    </div>

                    {{-- 3. Surat Pengantar --}}
                    <div class="rounded-lg border border-frame bg-surface p-5 transition hover:border-frame-strong">
                        <span class="flex h-10 w-10 items-center justify-center rounded-md bg-surface-muted text-green-700">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                            </svg>
                        </span>
                        <h3 class="mt-4 font-display text-base font-semibold text-ink-900">Surat Pengantar</h3>
                        <p class="mt-1 text-xs leading-relaxed text-ink-400">
                            Pengiriman berkas pertimbangan atau rekomendasi senat kepada Dekan maupun Rektorat.
                        </p>
                    </div>

                    {{-- 4. Daftar Hadir --}}
                    <div class="rounded-lg border border-frame bg-surface p-5 transition hover:border-frame-strong">
                        <span class="flex h-10 w-10 items-center justify-center rounded-md bg-surface-muted text-green-700">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375" />
                            </svg>
                        </span>
                        <h3 class="mt-4 font-display text-base font-semibold text-ink-900">Daftar Hadir</h3>
                        <p class="mt-1 text-xs leading-relaxed text-ink-400">
                            Presensi peserta kegiatan resmi dengan penomoran terstruktur dan kolom tanda tangan basah.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        {{-- 5. Section Verifikasi Dokumen Publik --}}
        <section id="verifikasi" class="border-b border-frame py-12 sm:py-16">
            <div class="mx-auto max-w-[1200px] px-4 sm:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="font-display text-2xl font-semibold text-ink-900">Verifikasi keaslian dokumen</h2>
                    <p class="mt-1 text-xs text-ink-400">
                        Masukkan nomor surat resmi yang tertera pada dokumen fisik untuk memeriksa keabsahannya.
                    </p>

                    {{-- Form Pencarian Nomor Surat --}}
                    <form method="GET" action="{{ route('welcome') }}#verifikasi" class="mt-6 flex flex-col gap-2 sm:flex-row">
                        <div class="relative flex-1">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-ink-400">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                </svg>
                            </span>
                            <input
                                type="text"
                                name="nomor_surat"
                                value="{{ $searchQuery }}"
                                required
                                placeholder="Contoh: 001/DH/Senat-FT/IX/2026"
                                class="tnum w-full rounded-md border border-frame bg-surface py-2.5 pl-9 pr-3 text-sm text-ink-900 placeholder-ink-400 outline-none transition focus:border-gold-600 focus:ring-2 focus:ring-gold-100"
                            >
                        </div>
                        <button
                            type="submit"
                            class="rounded-md bg-gold-600 px-5 py-2.5 text-xs font-semibold text-white transition hover:bg-gold-500">
                            Cek Keaslian
                        </button>
                    </form>

                    {{-- Hasil Verifikasi --}}
                    @if ($searched)
                        <div class="mt-6 text-left">
                            @if ($searchResult)
                                <div class="rounded-lg border border-green-700/20 bg-surface p-5 sm:p-6">
                                    <div class="flex items-center gap-3 rounded-md bg-green-50 p-3.5 text-green-700">
                                        <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                        </svg>
                                        <div>
                                            <p class="font-display text-sm font-bold uppercase tracking-wide">Dokumen ini terverifikasi asli dari Senat FT</p>
                                            <p class="text-xs text-green-700/80">Tercatat resmi dalam pangkalan data arsip Senat Fakultas Teknik Unmul.</p>
                                        </div>
                                    </div>

                                    <dl class="mt-4 divide-y divide-frame text-xs">
                                        <div class="py-2 sm:grid sm:grid-cols-3">
                                            <dt class="text-ink-400">Nomor Surat</dt>
                                            <dd class="tnum font-semibold text-ink-900 sm:col-span-2">{{ $searchResult->nomor_surat }}</dd>
                                        </div>
                                        <div class="py-2 sm:grid sm:grid-cols-3">
                                            <dt class="text-ink-400">Jenis Dokumen</dt>
                                            <dd class="font-medium text-ink-900 sm:col-span-2">{{ $searchResult->jenis_label }}</dd>
                                        </div>
                                        <div class="py-2 sm:grid sm:grid-cols-3">
                                            <dt class="text-ink-400">Perihal / Acara</dt>
                                            <dd class="font-medium text-ink-900 sm:col-span-2">{{ $searchResult->judul }}</dd>
                                        </div>
                                        <div class="py-2 sm:grid sm:grid-cols-3">
                                            <dt class="text-ink-400">Diterbitkan Oleh</dt>
                                            <dd class="text-ink-900 sm:col-span-2">
                                                {{ $searchResult->creator->name }}
                                                <span class="tnum text-ink-400">(NIP {{ $searchResult->creator->nip }})</span>
                                            </dd>
                                        </div>
                                        <div class="py-2 sm:grid sm:grid-cols-3">
                                            <dt class="text-ink-400">Waktu Finalisasi</dt>
                                            <dd class="tnum text-ink-900 sm:col-span-2">
                                                {{ $searchResult->finalized_at ? $searchResult->finalized_at->translatedFormat('d F Y, H:i') : '—' }} WITA
                                            </dd>
                                        </div>
                                    </dl>

                                    <div class="mt-4 pt-3 border-t border-frame text-right">
                                        <a href="{{ route('dokumen.verifikasi', $searchResult) }}"
                                            class="inline-flex items-center gap-1 text-xs font-medium text-green-700 hover:underline">
                                            Buka halaman verifikasi penuh &rarr;
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="rounded-lg border border-error-bg bg-surface p-5">
                                    <div class="flex items-start gap-3 rounded-md bg-error-bg p-3.5 text-error-text">
                                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                                        </svg>
                                        <div>
                                            <p class="font-semibold text-sm">Nomor surat tidak ditemukan</p>
                                            <p class="mt-0.5 text-xs">
                                                Nomor surat <code class="tnum font-mono font-semibold">{{ $searchQuery }}</code> tidak cocok dengan dokumen resmi yang terdaftar. Pastikan format penulisan sudah benar.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </section>

        {{-- 6. Section Cara Kerja Singkat --}}
        <section id="cara-kerja" class="border-b border-frame bg-surface py-12 sm:py-16">
            <div class="mx-auto max-w-[1200px] px-4 sm:px-8">
                <div class="mb-8">
                    <h2 class="font-display text-2xl font-semibold text-ink-900">Alur kerja sistem</h2>
                    <p class="mt-1 text-xs text-ink-400">Empat langkah penerbitan dan validasi dokumen resmi Senat FT.</p>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="rounded-lg border border-frame bg-surface p-5">
                        <span class="tnum inline-flex h-7 w-7 items-center justify-center rounded bg-surface-muted font-display text-xs font-bold text-ink-900">1</span>
                        <h3 class="mt-3 font-display text-sm font-semibold text-ink-900">Penyusunan Draf</h3>
                        <p class="mt-1 text-xs leading-relaxed text-ink-400">
                            Pengurus menyusun data dokumen secara bertahap melalui sistem internal terpusat.
                        </p>
                    </div>

                    <div class="rounded-lg border border-frame bg-surface p-5">
                        <span class="tnum inline-flex h-7 w-7 items-center justify-center rounded bg-surface-muted font-display text-xs font-bold text-ink-900">2</span>
                        <h3 class="mt-3 font-display text-sm font-semibold text-ink-900">Finalisasi &amp; Nomor</h3>
                        <p class="mt-1 text-xs leading-relaxed text-ink-400">
                            Dokumen difinalisasi, sistem otomatis menerbitkan nomor surat resmi dan kode QR unik.
                        </p>
                    </div>

                    <div class="rounded-lg border border-frame bg-surface p-5">
                        <span class="tnum inline-flex h-7 w-7 items-center justify-center rounded bg-surface-muted font-display text-xs font-bold text-ink-900">3</span>
                        <h3 class="mt-3 font-display text-sm font-semibold text-ink-900">Pencetakan Berkas</h3>
                        <p class="mt-1 text-xs leading-relaxed text-ink-400">
                            Dokumen dicetak dengan format kop resmi untuk ditandatangani manual pimpinan rapat.
                        </p>
                    </div>

                    <div class="rounded-lg border border-frame bg-surface p-5">
                        <span class="tnum inline-flex h-7 w-7 items-center justify-center rounded bg-surface-muted font-display text-xs font-bold text-ink-900">4</span>
                        <h3 class="mt-3 font-display text-sm font-semibold text-ink-900">Verifikasi Publik</h3>
                        <p class="mt-1 text-xs leading-relaxed text-ink-400">
                            Siapa pun dapat memindai kode QR atau mengecek nomor surat untuk membuktikan keasliannya.
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    {{-- 7. Footer --}}
    <footer class="border-t border-frame bg-surface py-8 text-xs text-ink-400">
        <div class="mx-auto flex max-w-[1200px] flex-col justify-between gap-4 px-4 sm:flex-row sm:items-center sm:px-8">
            <div>
                <p class="font-display font-semibold text-ink-900">Senat Fakultas Teknik &middot; Universitas Mulawarman</p>
                <p class="mt-0.5">Jalan Sambaliung No. 9, Kampus Gunung Kelua, Samarinda 75119</p>
                <p class="mt-0.5 text-[11px] text-ink-400">Sistem internal persuratan Senat Fakultas Teknik Universitas Mulawarman</p>
            </div>
            <div class="text-left sm:text-right">
                <p>&copy; {{ date('Y') }} Senat Fakultas Teknik Unmul.</p>
            </div>
        </div>
    </footer>

    {{-- Script Drawer Navigasi Mobile --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const drawer = document.getElementById('welcome-drawer');
            const backdrop = document.getElementById('welcome-drawer-backdrop');
            const toggle = document.getElementById('welcome-menu-toggle');
            const close = document.getElementById('welcome-drawer-close');

            function openDrawer() {
                if (drawer && backdrop) {
                    drawer.classList.remove('-translate-x-full');
                    backdrop.classList.remove('hidden');
                }
            }

            function closeDrawer() {
                if (drawer && backdrop) {
                    drawer.classList.add('-translate-x-full');
                    backdrop.classList.add('hidden');
                }
            }

            if (toggle) toggle.addEventListener('click', openDrawer);
            if (close) close.addEventListener('click', closeDrawer);
            if (backdrop) backdrop.addEventListener('click', closeDrawer);

            document.querySelectorAll('.welcome-nav-item').forEach(item => {
                item.addEventListener('click', closeDrawer);
            });
        });
    </script>
</body>
</html>
