<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi {{ $document->nomor_surat ?? 'Dokumen' }} - Senat FT Unmul</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    @vite(['resources/css/app.css'])
</head>
<body class="flex min-h-full flex-col bg-paper antialiased">
    <header class="border-b border-line bg-surface">
        <div class="mx-auto flex h-[72px] max-w-page items-center gap-3 px-4 md:px-6">
            <a href="{{ route('welcome') }}" class="flex items-center gap-3">
                <img src="{{ asset('image.png') }}" alt="" class="h-9 w-auto object-contain">
                <span class="leading-tight">
                    <span class="block font-serif text-sm font-semibold text-ink">Senat Fakultas Teknik</span>
                    <span class="block text-xs text-ink-muted">Universitas Mulawarman</span>
                </span>
            </a>
        </div>
        <div class="h-0.5 bg-gold-600"></div>
    </header>

    <main class="mx-auto w-full max-w-2xl flex-1 px-4 py-8 md:py-12">
        <h1 class="font-serif text-2xl font-semibold text-ink">Hasil Verifikasi</h1>
        <p class="mt-1 text-sm text-ink-muted">Pemeriksaan keaslian dokumen resmi Senat FT Unmul.</p>

        @if (($state ?? 'sah') === 'sah')
            <div class="mt-6 rounded-md border border-line border-l-4 border-l-brand bg-surface p-6">
                <div class="flex items-start gap-3">
                    <x-icon name="shield-check" class="size-6 shrink-0 text-brand" />
                    <div>
                        <h2 class="font-serif text-xl font-semibold text-ink">Dokumen Sah</h2>
                        <p class="mt-1 text-sm text-ink-muted">Dokumen ini tercatat dan berstatus final di pangkalan data Senat Fakultas Teknik.</p>
                    </div>
                </div>

                <dl class="mt-6 divide-y divide-line text-sm">
                    <div class="grid grid-cols-3 gap-3 py-2.5">
                        <dt class="text-ink-muted">Jenis dokumen</dt>
                        <dd class="col-span-2 font-medium text-ink">{{ $document->jenis_label }}</dd>
                    </div>
                    <div class="grid grid-cols-3 gap-3 py-2.5">
                        <dt class="text-ink-muted">Nomor</dt>
                        <dd class="tnum col-span-2 font-medium text-ink">{{ $document->nomor_surat }}</dd>
                    </div>
                    <div class="grid grid-cols-3 gap-3 py-2.5">
                        <dt class="text-ink-muted">Tanggal</dt>
                        <dd class="tnum col-span-2 text-ink">{{ ($document->finalized_at ?? $document->created_at)->translatedFormat('d F Y') }}</dd>
                    </div>
                    <div class="grid grid-cols-3 gap-3 py-2.5">
                        <dt class="text-ink-muted">Status</dt>
                        <dd class="col-span-2"><x-status-badge :status="$document->status" /></dd>
                    </div>
                    <div class="grid grid-cols-3 gap-3 py-2.5">
                        <dt class="text-ink-muted">Diterbitkan oleh</dt>
                        <dd class="col-span-2 text-ink">{{ $document->creator->name }} <span class="tnum text-xs text-ink-muted">(NIP {{ $document->creator->nip }})</span></dd>
                    </div>
                </dl>

                <p class="mt-6 border-t border-line pt-4 text-xs leading-relaxed text-ink-muted">
                    Pemeriksaan ini hanya memastikan dokumen tercatat dan berstatus final. Isi dokumen tidak ditampilkan.
                    Diperiksa pada {{ now()->translatedFormat('d F Y, H:i') }} WITA.
                </p>
            </div>
        @elseif (($state ?? '') === 'dibatalkan')
            <div class="mt-6 rounded-md border border-line border-l-4 border-l-line-strong bg-surface p-6">
                <div class="flex items-start gap-3">
                    <x-icon name="ban" class="size-6 shrink-0 text-ink-muted" />
                    <div>
                        <h2 class="font-serif text-xl font-semibold text-ink">Dokumen Dibatalkan</h2>
                        <p class="mt-1 text-sm text-ink-muted">Dokumen ini pernah diterbitkan tetapi telah dibatalkan. Hubungi sekretariat untuk keterangan.</p>
                    </div>
                </div>

                <dl class="mt-6 divide-y divide-line text-sm">
                    <div class="grid grid-cols-3 gap-3 py-2.5">
                        <dt class="text-ink-muted">Jenis dokumen</dt>
                        <dd class="col-span-2 font-medium text-ink">{{ $document->jenis_label }}</dd>
                    </div>
                    <div class="grid grid-cols-3 gap-3 py-2.5">
                        <dt class="text-ink-muted">Nomor</dt>
                        <dd class="tnum col-span-2 font-medium text-ink">{{ $document->nomor_surat }}</dd>
                    </div>
                    <div class="grid grid-cols-3 gap-3 py-2.5">
                        <dt class="text-ink-muted">Status</dt>
                        <dd class="col-span-2"><x-status-badge :status="$document->status" /></dd>
                    </div>
                </dl>
            </div>
        @else
            <div class="mt-6 rounded-md border border-line border-l-4 border-l-line-strong bg-surface p-6">
                <div class="flex items-start gap-3">
                    <x-icon name="circle-alert" class="size-6 shrink-0 text-ink-muted" />
                    <div>
                        <h2 class="font-serif text-xl font-semibold text-ink">Dokumen Tidak Ditemukan</h2>
                        <p class="mt-1 text-sm text-ink-muted">Periksa kembali kode yang dimasukkan. Draf dan kode yang salah menampilkan pesan yang sama.</p>
                    </div>
                </div>
            </div>
        @endif

        <section class="mt-6 rounded-md border border-line bg-surface p-6">
            <h2 class="font-serif text-lg font-semibold text-ink">Periksa dokumen lain</h2>
            <form method="GET" action="{{ route('welcome') }}#verifikasi" class="mt-3 flex flex-col gap-2 sm:flex-row">
                <label for="nomor_surat" class="sr-only">Nomor surat atau kode verifikasi</label>
                <input id="nomor_surat" type="text" name="nomor_surat" required placeholder="Masukkan nomor surat"
                    class="tnum h-11 min-w-0 flex-1 rounded-md border border-control bg-surface px-3 text-base text-ink placeholder:text-ink-muted focus-visible:outline-2 focus-visible:outline-offset-0 focus-visible:outline-focus">
                <x-button type="submit" class="shrink-0">Periksa</x-button>
            </form>
        </section>

        <p class="mt-6 text-xs text-ink-muted">
            <a href="{{ route('welcome') }}" class="font-medium text-brand underline-offset-2 hover:underline">Kembali ke halaman utama</a>
        </p>
    </main>
</body>
</html>
