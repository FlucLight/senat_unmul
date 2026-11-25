<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Manajemen dan Validasi Dokumen — Senat FT Unmul</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-full flex-col bg-paper antialiased">
    <a href="#konten" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-3 focus:z-50 focus:rounded-md focus:bg-surface focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-brand focus:shadow-popover">
        Lompat ke konten
    </a>

    {{-- Header publik --}}
    <header class="sticky top-0 z-30 border-b border-line bg-surface">
        <div class="mx-auto flex h-[72px] max-w-page items-center justify-between gap-6 px-4 md:px-6">
            <a href="{{ route('welcome') }}" class="flex items-center gap-3">
                <img src="{{ asset('image.png') }}" alt="" class="h-10 w-auto object-contain">
                <span class="leading-tight">
                    <span class="block font-serif text-base font-semibold text-ink">Senat Fakultas Teknik</span>
                    <span class="block text-xs text-ink-muted">Universitas Mulawarman</span>
                </span>
            </a>

            <nav aria-label="Navigasi publik" class="hidden items-center gap-6 text-sm font-medium md:flex">
                <a href="#tentang" class="text-ink transition-colors hover:text-brand">Tentang</a>
                <a href="#jenis-dokumen" class="text-ink transition-colors hover:text-brand">Jenis Dokumen</a>
                <a href="#alur" class="text-ink transition-colors hover:text-brand">Alur Penerbitan</a>
                <a href="#verifikasi" class="text-ink transition-colors hover:text-brand">Verifikasi</a>
            </nav>

            <a href="{{ route('login') }}"
                class="inline-flex h-10 items-center justify-center rounded-md border border-control bg-surface px-4 text-sm font-semibold text-ink transition-colors hover:bg-paper">
                Masuk
            </a>
        </div>
        <div class="h-0.5 bg-gold-600"></div>
    </header>

    <main id="konten" class="flex-1">
        {{-- Masthead + panel verifikasi --}}
        <section class="border-b border-line bg-surface">
            <div class="mx-auto grid max-w-page grid-cols-1 gap-8 px-4 py-12 md:px-6 md:py-16 lg:grid-cols-12 lg:gap-8">
                <div class="lg:col-span-7 lg:pr-8">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gold-700">Senat Fakultas Teknik · Universitas Mulawarman</p>
                    <h1 class="mt-4 max-w-2xl font-serif text-display font-semibold leading-tight text-ink">
                        Sistem Manajemen dan Validasi Dokumen Senat Fakultas Teknik
                    </h1>
                    <p class="mt-6 max-w-prose text-base leading-relaxed text-ink-muted">
                        Pembuatan, penomoran, dan pemeriksaan keaslian dokumen resmi Senat Fakultas Teknik Universitas Mulawarman.
                    </p>
                    <p class="mt-6 text-sm text-ink-muted">
                        Pengurus Senat?
                        <a href="{{ route('login') }}" class="font-semibold text-brand underline-offset-2 hover:underline">Masuk ke sistem</a>
                    </p>
                </div>

                <div class="lg:col-span-5">
                    <div id="verifikasi" class="rounded-md border border-line bg-surface p-6">
                        <h2 class="font-serif text-xl font-semibold text-ink">Periksa Keaslian Dokumen</h2>
                        <p class="mt-1 text-sm text-ink-muted">Masukkan kode verifikasi yang tercetak di bawah QR pada dokumen.</p>

                        <form method="GET" action="{{ route('welcome') }}#verifikasi" class="mt-4 flex flex-col gap-2 sm:flex-row lg:flex-col xl:flex-row">
                            <label for="nomor_surat" class="sr-only">Kode verifikasi atau nomor surat</label>
                            <input
                                id="nomor_surat"
                                type="text"
                                name="nomor_surat"
                                value="{{ $searchQuery }}"
                                required
                                placeholder="Contoh: 7K4M2-XP9QD"
                                class="tnum h-11 min-w-0 flex-1 rounded-md border border-control bg-surface px-3 text-base text-ink placeholder:text-ink-muted focus-visible:outline-2 focus-visible:outline-offset-0 focus-visible:outline-focus"
                            >
                            <x-button type="submit" size="md" class="shrink-0">Periksa Dokumen</x-button>
                        </form>

                        <p class="mt-3 text-xs text-ink-muted">Anda juga dapat memindai QR pada dokumen dengan kamera ponsel.</p>

                        @if ($searched)
                            <div class="mt-6">
                                @if ($searchResult)
                                    <div class="rounded-md border border-line border-l-4 border-l-brand p-4">
                                        <div class="flex items-center gap-2 text-brand">
                                            <x-icon name="shield-check" class="size-5" />
                                            <p class="text-sm font-semibold text-ink">Dokumen Sah</p>
                                        </div>
                                        <dl class="mt-3 divide-y divide-line text-sm">
                                            <div class="flex justify-between gap-3 py-2">
                                                <dt class="text-ink-muted">Jenis</dt>
                                                <dd class="text-right font-medium text-ink">{{ $searchResult->jenis_label }}</dd>
                                            </div>
                                            <div class="flex justify-between gap-3 py-2">
                                                <dt class="text-ink-muted">Nomor</dt>
                                                <dd class="tnum text-right font-medium text-ink">{{ $searchResult->nomor_surat }}</dd>
                                            </div>
                                            <div class="flex justify-between gap-3 py-2">
                                                <dt class="text-ink-muted">Tanggal</dt>
                                                <dd class="tnum text-right text-ink">{{ $searchResult->finalized_at?->translatedFormat('d F Y') ?? $searchResult->created_at->translatedFormat('d F Y') }}</dd>
                                            </div>
                                            <div class="flex justify-between gap-3 py-2">
                                                <dt class="text-ink-muted">Status</dt>
                                                <dd class="text-right"><x-status-badge :status="$searchResult->status" /></dd>
                                            </div>
                                        </dl>
                                        <p class="mt-3 text-xs leading-relaxed text-ink-muted">
                                            Pemeriksaan ini hanya memastikan dokumen tercatat dan berstatus final. Isi dokumen tidak ditampilkan.
                                        </p>
                                        <a href="{{ route('dokumen.verifikasi', $searchResult) }}" class="mt-3 inline-flex items-center gap-1 text-xs font-semibold text-brand hover:underline">
                                            Buka halaman verifikasi <x-icon name="arrow-right" class="size-3.5" />
                                        </a>
                                    </div>
                                @else
                                    <div class="rounded-md border border-line border-l-4 border-l-line-strong p-4">
                                        <div class="flex items-center gap-2">
                                            <x-icon name="circle-alert" class="size-5 text-ink-muted" />
                                            <p class="text-sm font-semibold text-ink">Dokumen Tidak Ditemukan</p>
                                        </div>
                                        <p class="mt-2 text-xs leading-relaxed text-ink-muted">
                                            Periksa kembali kode <span class="tnum font-semibold text-ink">{{ $searchQuery }}</span>. Draf dan kode yang salah menampilkan pesan yang sama.
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        {{-- 01 Tentang --}}
        <section id="tentang" class="border-b border-line">
            <div class="mx-auto grid max-w-page grid-cols-1 gap-6 px-4 py-12 md:grid-cols-12 md:px-6 md:py-16">
                <div class="md:col-span-3">
                    <p class="tnum text-xs font-semibold text-ink-muted">01</p>
                    <h2 class="mt-1 font-serif text-xl font-semibold text-ink">Tentang</h2>
                </div>
                <div class="space-y-4 md:col-span-9 md:max-w-prose">
                    <p class="text-base leading-relaxed text-ink">
                        Senat Fakultas Teknik adalah organ fakultas yang menjalankan fungsi pertimbangan, pengawasan, dan pengambilan keputusan akademik. Dokumen resminya perlu tercatat rapi, bernomor konsisten, dan dapat diperiksa keasliannya oleh publik.
                    </p>
                    <dl class="grid grid-cols-1 gap-4 border-t border-line pt-4 text-sm sm:grid-cols-3">
                        <div>
                            <dt class="font-semibold text-ink">Lembaga</dt>
                            <dd class="mt-1 text-ink-muted">Senat Fakultas Teknik Universitas Mulawarman.</dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-ink">Fungsi</dt>
                            <dd class="mt-1 text-ink-muted">Pembuatan, penomoran, pengarsipan, dan verifikasi dokumen.</dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-ink">Pengguna</dt>
                            <dd class="mt-1 text-ink-muted">Pengurus dan operator senat; publik hanya memeriksa keaslian.</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </section>

        {{-- 02 Jenis Dokumen --}}
        <section id="jenis-dokumen" class="border-b border-line bg-surface">
            <div class="mx-auto grid max-w-page grid-cols-1 gap-6 px-4 py-12 md:grid-cols-12 md:px-6 md:py-16">
                <div class="md:col-span-3">
                    <p class="tnum text-xs font-semibold text-ink-muted">02</p>
                    <h2 class="mt-1 font-serif text-xl font-semibold text-ink">Jenis Dokumen</h2>
                </div>
                <div class="md:col-span-9">
                    <p class="max-w-prose text-sm leading-relaxed text-ink-muted">
                        Empat instrumen administrasi resmi yang dikelola sistem. Setiap dokumen final memperoleh nomor surat dan kode verifikasi.
                    </p>
                    <div class="mt-6 overflow-x-auto rounded-md border border-line">
                        <table class="w-full text-left text-sm">
                            <caption class="sr-only">Jenis dokumen yang dikelola sistem</caption>
                            <thead class="border-b border-line-strong bg-paper-200 text-xs font-semibold text-ink-muted">
                                <tr>
                                    <th scope="col" class="px-4 py-3">Jenis</th>
                                    <th scope="col" class="px-4 py-3">Kegunaan</th>
                                    <th scope="col" class="px-4 py-3">Contoh nomor</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-line">
                                <tr class="transition-colors hover:bg-paper">
                                    <td class="px-4 py-3 font-medium text-ink">Daftar Hadir</td>
                                    <td class="px-4 py-3 text-ink-muted">Presensi peserta kegiatan resmi.</td>
                                    <td class="tnum px-4 py-3 text-ink-muted">001/DH/Senat-FT/IX/2026</td>
                                </tr>
                                <tr class="transition-colors hover:bg-paper">
                                    <td class="px-4 py-3 font-medium text-ink">Undangan</td>
                                    <td class="px-4 py-3 text-ink-muted">Pemanggilan anggota untuk rapat atau sidang.</td>
                                    <td class="tnum px-4 py-3 text-ink-muted">007/UND/Senat-FT/XII/2026</td>
                                </tr>
                                <tr class="transition-colors hover:bg-paper">
                                    <td class="px-4 py-3 font-medium text-ink">Berita Acara</td>
                                    <td class="px-4 py-3 text-ink-muted">Catatan keputusan dan hasil musyawarah.</td>
                                    <td class="tnum px-4 py-3 text-ink-muted">003/BA/Senat-FT/XI/2026</td>
                                </tr>
                                <tr class="transition-colors hover:bg-paper">
                                    <td class="px-4 py-3 font-medium text-ink">Surat Pengantar</td>
                                    <td class="px-4 py-3 text-ink-muted">Pengiriman berkas kepada Dekan atau Rektorat.</td>
                                    <td class="tnum px-4 py-3 text-ink-muted">012/SP/Senat-FT/X/2026</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        {{-- 03 Alur Penerbitan --}}
        <section id="alur" class="border-b border-line">
            <div class="mx-auto grid max-w-page grid-cols-1 gap-6 px-4 py-12 md:grid-cols-12 md:px-6 md:py-16">
                <div class="md:col-span-3">
                    <p class="tnum text-xs font-semibold text-ink-muted">03</p>
                    <h2 class="mt-1 font-serif text-xl font-semibold text-ink">Alur Penerbitan</h2>
                </div>
                <div class="md:col-span-9">
                    <ol class="grid grid-cols-1 gap-0 border-t border-line-strong sm:grid-cols-2 lg:grid-cols-4">
                        @php
                            $langkah = [
                                ['Draf', 'Pengurus menyusun data dokumen melalui sistem.'],
                                ['Finalisasi', 'Sistem menerbitkan nomor resmi dan kode verifikasi.'],
                                ['Pencetakan', 'Dokumen dicetak dengan kop resmi dan QR.'],
                                ['Verifikasi', 'Publik memeriksa keaslian melalui kode atau QR.'],
                            ];
                        @endphp
                        @foreach ($langkah as $i => [$judul, $teks])
                            <li class="border-b border-line py-6 sm:pr-6">
                                <p class="tnum font-serif text-2xl font-semibold text-ink-muted">0{{ $i + 1 }}</p>
                                <h3 class="mt-2 text-lg font-semibold text-ink">{{ $judul }}</h3>
                                <p class="mt-1 text-sm leading-relaxed text-ink-muted">{{ $teks }}</p>
                            </li>
                        @endforeach
                    </ol>
                </div>
            </div>
        </section>
    </main>

    {{-- Footer institusional --}}
    <footer class="border-t-2 border-gold-600 bg-green-900 text-paper">
        <div class="mx-auto grid max-w-page grid-cols-1 gap-8 px-4 py-12 md:grid-cols-3 md:px-6">
            <div>
                <div class="flex items-center gap-3">
                    <img src="{{ asset('image.png') }}" alt="" class="h-10 w-auto object-contain">
                    <p class="font-serif text-lg font-semibold">Senat Fakultas Teknik</p>
                </div>
                <p class="mt-3 text-sm leading-relaxed text-green-200">
                    Jalan Sambaliung No. 9, Kampus Gunung Kelua, Samarinda 75119, Kalimantan Timur.
                </p>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-green-200">Tautan</p>
                <ul class="mt-3 space-y-2 text-sm">
                    <li><a href="https://www.unmul.ac.id" class="underline-offset-2 hover:underline" rel="noopener">Universitas Mulawarman</a></li>
                    <li><a href="https://ft.unmul.ac.id" class="underline-offset-2 hover:underline" rel="noopener">Fakultas Teknik</a></li>
                    <li><a href="{{ route('login') }}" class="underline-offset-2 hover:underline">Masuk ke sistem</a></li>
                </ul>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-green-200">Kontak</p>
                <p class="mt-3 text-sm leading-relaxed text-green-200">
                    Sekretariat Senat Fakultas Teknik<br>
                    surel: senat.ft@unmul.ac.id
                </p>
            </div>
        </div>
        <div class="border-t border-green-800">
            <div class="mx-auto max-w-page px-4 py-4 text-xs text-green-200 md:px-6">
                &copy; {{ date('Y') }} Senat Fakultas Teknik, Universitas Mulawarman.
            </div>
        </div>
    </footer>
</body>
</html>
