<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Dokumen — {{ $document->nomor_surat }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="flex min-h-full flex-col items-center justify-center bg-paper px-4 py-12 antialiased">
    <main class="w-full max-w-lg">
        <div class="mb-6 text-center">
            <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-md bg-gold-600 font-display text-sm font-bold text-white">
                FT
            </div>
            <h1 class="font-display text-xl font-bold text-ink-900">Senat Fakultas Teknik</h1>
            <p class="text-xs text-ink-400">Universitas Mulawarman &middot; Portal Verifikasi Dokumen</p>
        </div>

        <div class="overflow-hidden rounded-lg border border-frame bg-surface p-6 sm:p-8">
            {{-- Verified Badge --}}
            <div class="mb-6 flex items-center gap-3 rounded-md border border-green-700/20 bg-green-50 p-4 text-green-700">
                <svg class="h-8 w-8 shrink-0 text-green-700" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                </svg>
                <div>
                    <h2 class="font-display text-sm font-bold uppercase tracking-wide text-green-700">Dokumen Resmi Terverifikasi</h2>
                    <p class="text-xs text-green-700/80">Dokumen ini sah dan diterbitkan secara resmi oleh Senat Fakultas Teknik Universitas Mulawarman.</p>
                </div>
            </div>

            {{-- Metadata Table --}}
            <dl class="divide-y divide-frame text-xs">
                <div class="py-2.5 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="font-medium text-ink-400">Nomor Surat</dt>
                    <dd class="tnum mt-1 font-semibold text-ink-900 sm:col-span-2 sm:mt-0">{{ $document->nomor_surat }}</dd>
                </div>
                <div class="py-2.5 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="font-medium text-ink-400">Jenis Dokumen</dt>
                    <dd class="mt-1 font-medium text-ink-900 sm:col-span-2 sm:mt-0">{{ $document->jenis_label }}</dd>
                </div>
                <div class="py-2.5 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="font-medium text-ink-400">Nama Acara</dt>
                    <dd class="mt-1 font-semibold text-ink-900 sm:col-span-2 sm:mt-0">{{ $content['nama_acara'] ?? $document->judul }}</dd>
                </div>
                <div class="py-2.5 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="font-medium text-ink-400">Tanggal Acara</dt>
                    <dd class="tnum mt-1 text-ink-900 sm:col-span-2 sm:mt-0">
                        {{ !empty($content['tanggal']) ? \Carbon\Carbon::parse($content['tanggal'])->translatedFormat('l, d F Y') : '—' }}
                    </dd>
                </div>
                <div class="py-2.5 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="font-medium text-ink-400">Waktu & Tempat</dt>
                    <dd class="mt-1 text-ink-900 sm:col-span-2 sm:mt-0">
                        {{ $content['waktu_mulai'] ?? '' }} - {{ $content['waktu_selesai'] ?? '' }} &middot; {{ $content['tempat'] ?? '—' }}
                    </dd>
                </div>
                <div class="py-2.5 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="font-medium text-ink-400">Jumlah Peserta</dt>
                    <dd class="tnum mt-1 font-medium text-ink-900 sm:col-span-2 sm:mt-0">{{ count($content['peserta'] ?? []) }} orang</dd>
                </div>
                <div class="py-2.5 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="font-medium text-ink-400">Diterbitkan Oleh</dt>
                    <dd class="mt-1 text-ink-900 sm:col-span-2 sm:mt-0">
                        {{ $document->creator->name }} <span class="tnum text-ink-400">(NIP {{ $document->creator->nip }})</span>
                    </dd>
                </div>
                <div class="py-2.5 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="font-medium text-ink-400">Waktu Finalisasi</dt>
                    <dd class="tnum mt-1 text-ink-900 sm:col-span-2 sm:mt-0">
                        {{ $document->finalized_at ? $document->finalized_at->translatedFormat('d F Y, H:i') : '—' }} WITA
                    </dd>
                </div>
            </dl>
        </div>

        <p class="mt-6 text-center text-xs text-ink-400">
            &copy; {{ date('Y') }} Senat Fakultas Teknik, Universitas Mulawarman.
        </p>
    </main>
</body>
</html>
