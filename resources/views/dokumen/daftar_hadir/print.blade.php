<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $document->nomor_surat ?? 'Daftar Hadir' }} — {{ $document->judul }}</title>
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            @page {
                size: A4 portrait;
                margin: 1.5cm 1.5cm 2cm 1.5cm;
            }
            .no-print {
                display: none !important;
            }
            body {
                background: #ffffff !important;
                color: #000000 !important;
            }
            .print-border {
                border-color: #000000 !important;
            }
        }
    </style>
</head>
<body class="bg-slate-100 font-sans text-ink-900 antialiased print:bg-white print:p-0">
    {{-- Floating toolbar for printing / returning --}}
    <div class="no-print sticky top-0 z-50 border-b border-frame bg-surface px-4 py-3 shadow-sm">
        <div class="mx-auto flex max-w-4xl items-center justify-between">
            <a href="{{ route('dokumen.show', $document) }}" class="inline-flex items-center gap-1 text-xs font-medium text-ink-500 hover:text-green-700">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Detail Dokumen
            </a>

            <div class="flex items-center gap-2">
                <button type="button" onclick="window.print()" class="inline-flex items-center gap-1.5 rounded-md bg-gold-600 px-4 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-gold-500">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.656h10.5Z" />
                    </svg>
                    Cetak / Simpan PDF
                </button>
            </div>
        </div>
    </div>

    {{-- Kertas A4 Dokumen Resmi --}}
    <main class="mx-auto my-6 max-w-[21cm] bg-white p-8 sm:p-12 shadow-sm border border-frame print:m-0 print:border-0 print:p-0 print:shadow-none">
        {{-- Kop Surat Resmi Senat FT Unmul --}}
        <div class="border-b-2 border-ink-900 pb-3 text-center">
            <p class="text-xs font-semibold uppercase tracking-widest text-ink-700">Kementerian Pendidikan Tinggi, Sains, dan Teknologi</p>
            <p class="text-sm font-bold uppercase tracking-wider text-ink-900">Universitas Mulawarman</p>
            <p class="font-display text-base font-bold uppercase tracking-tight text-ink-900">Fakultas Teknik — Senat Fakultas</p>
            <p class="mt-0.5 text-[10px] text-ink-500">
                Jalan Sambaliung No. 9, Kampus Gunung Kelua, Samarinda 75119 &middot; Laman: senat.ft.unmul.ac.id
            </p>
        </div>

        {{-- Judul Dokumen & Nomor Surat --}}
        <div class="mt-6 text-center">
            <h1 class="font-display text-lg font-bold uppercase tracking-wide text-ink-900 underline decoration-1 underline-offset-4">
                Daftar Hadir
            </h1>
            <p class="tnum mt-1 text-xs text-ink-700">
                Nomor: {{ $document->nomor_surat ?? '— (Draf Sementara)' }}
            </p>
        </div>

        {{-- Info Acara --}}
        <div class="mt-6 text-xs text-ink-900">
            <table class="w-full">
                <tbody>
                    <tr>
                        <td class="w-36 py-1 font-medium text-ink-500">Acara / Rapat</td>
                        <td class="w-3 py-1 text-ink-400">:</td>
                        <td class="py-1 font-semibold text-ink-900">{{ $content['nama_acara'] ?? $document->judul }}</td>
                    </tr>
                    <tr>
                        <td class="py-1 font-medium text-ink-500">Hari / Tanggal</td>
                        <td class="py-1 text-ink-400">:</td>
                        <td class="tnum py-1">
                            @if (!empty($content['tanggal']))
                                {{ \Carbon\Carbon::parse($content['tanggal'])->translatedFormat('l, d F Y') }}
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="py-1 font-medium text-ink-500">Waktu</td>
                        <td class="py-1 text-ink-400">:</td>
                        <td class="tnum py-1">
                            {{ $content['waktu_mulai'] ?? '—' }} s/d {{ $content['waktu_selesai'] ?? '—' }} WITA
                        </td>
                    </tr>
                    <tr>
                        <td class="py-1 font-medium text-ink-500">Tempat</td>
                        <td class="py-1 text-ink-400">:</td>
                        <td class="py-1">{{ $content['tempat'] ?? '—' }}</td>
                    </tr>
                    @if (!empty($content['penyelenggara']))
                        <tr>
                            <td class="py-1 font-medium text-ink-500">Penyelenggara</td>
                            <td class="py-1 text-ink-400">:</td>
                            <td class="py-1">{{ $content['penyelenggara'] }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        {{-- Tabel Daftar Peserta dengan Kolom Tanda Tangan Basah --}}
        <div class="mt-6">
            <table class="w-full border-collapse border border-ink-900 text-xs text-ink-900">
                <thead>
                    <tr class="border-b border-ink-900 bg-slate-50 text-center font-semibold uppercase print:bg-transparent">
                        <th class="border-r border-ink-900 px-2 py-2 w-10">No.</th>
                        <th class="border-r border-ink-900 px-3 py-2 text-left">Nama Peserta</th>
                        <th class="border-r border-ink-900 px-3 py-2 text-left">Jabatan / Instansi</th>
                        <th class="px-3 py-2 w-44">Tanda Tangan</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $peserta = $content['peserta'] ?? [];
                    @endphp

                    @forelse ($peserta as $index => $item)
                        <tr class="border-b border-ink-900">
                            <td class="tnum border-r border-ink-900 px-2 py-3 text-center align-middle">
                                {{ $index + 1 }}
                            </td>
                            <td class="border-r border-ink-900 px-3 py-3 align-middle font-medium">
                                {{ $item['nama'] ?? '—' }}
                            </td>
                            <td class="border-r border-ink-900 px-3 py-3 align-middle text-ink-700">
                                {{ $item['jabatan_instansi'] ?? '—' }}
                            </td>
                            <td class="px-2 py-3 align-middle">
                                <div class="flex items-center justify-between text-[11px] text-ink-400">
                                    <span class="tnum">{{ $index + 1 }}.</span>
                                    <span class="inline-block w-24 border-b border-dashed border-ink-400 h-4"></span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-ink-400 italic">
                                Belum ada peserta terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Bagian Tanda Tangan & QR Verifikasi Resmi di Pojok Kanan Bawah --}}
        <div class="mt-10 flex items-end justify-between break-inside-avoid">
            {{-- Pojok Kiri Bawah: QR Code Verifikasi Keaslian Dokumen --}}
            <div class="flex items-center gap-3">
                @if ($document->isFinal() && $document->qr_verification_url)
                    @php
                        $qrApi = 'https://api.qrserver.com/v1/create-qr-code/?size=85x85&data=' . urlencode($document->qr_verification_url);
                    @endphp
                    <div class="rounded border border-frame p-1 bg-white">
                        <img src="{{ $qrApi }}" alt="QR Verifikasi Resmi" class="h-20 w-20" loading="eager">
                    </div>
                    <div class="text-[10px] leading-tight text-ink-500">
                        <p class="font-semibold text-green-700">DOKUMEN RESMI</p>
                        <p class="tnum">Finalisasi: {{ $document->finalized_at ? $document->finalized_at->format('d/m/Y H:i') : '—' }}</p>
                        <p class="mt-0.5 max-w-[140px] truncate text-[9px] text-ink-400">{{ $document->qr_verification_url }}</p>
                    </div>
                @else
                    <div class="text-[10px] text-amber-700 italic border border-amber-300 rounded p-2 bg-amber-50">
                        Draf Dokumen — Belum diterbitkan nomor surat dan QR resmi.
                    </div>
                @endif
            </div>

            {{-- Pojok Kanan Bawah: Pengesahan Rapat --}}
            <div class="w-60 text-center text-xs text-ink-900">
                <p class="tnum">Samarinda, {{ $document->finalized_at ? $document->finalized_at->translatedFormat('d F Y') : date('d F Y') }}</p>
                <p class="mt-1 font-medium">{{ $content['penyelenggara'] ?? 'Pimpinan Rapat / Ketua Senat' }}</p>
                <div class="h-20"></div>
                <p class="font-bold underline decoration-1">{{ $document->creator->name }}</p>
                <p class="tnum text-[11px] text-ink-500">NIP {{ $document->creator->nip }}</p>
            </div>
        </div>
    </main>
</body>
</html>
