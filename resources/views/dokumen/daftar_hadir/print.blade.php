<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $document->nomor_surat ?? 'Daftar Hadir' }} - {{ $document->judul }}</title>
    <style>
        @page { size: A4 portrait; margin: 1.8cm 1.8cm 2cm 2cm; }
        body { font-family: "Times New Roman", Times, serif; font-size: 11pt; line-height: 1.4; color: #111; margin: 0; }
        p { margin: 0 0 4pt 0; } table { width: 100%; border-collapse: collapse; }
        .kop { border-bottom: 3px double #111; padding-bottom: 8px; margin-bottom: 16px; }
        .kop td { vertical-align: middle; } .kop-logo { width: 85px; } .kop-logo img { width: 72px; height: auto; }
        .kop-text { text-align: center; }
        .kop-text .l1 { font-size: 10pt; font-weight: bold; text-transform: uppercase; }
        .kop-text .l2 { font-size: 12pt; font-weight: bold; text-transform: uppercase; }
        .kop-text .l3 { font-size: 13pt; font-weight: bold; text-transform: uppercase; }
        .kop-text .l4 { font-size: 9pt; margin-top: 2px; }
        .judul { text-align: center; margin: 14px 0 12px; }
        .judul h1 { font-size: 14pt; margin: 0; text-decoration: underline; text-transform: uppercase; letter-spacing: 0.5px; }
        .judul .nomor { font-size: 11pt; margin-top: 2px; }
        .meta td { padding: 1px 0; vertical-align: top; font-size: 11pt; }
        .meta .label { width: 130px; } .meta .sep { width: 14px; }
        .daftar { margin-top: 14px; font-size: 10pt; }
        .daftar th, .daftar td { border: 1px solid #222; padding: 5px 6px; vertical-align: middle; }
        .daftar th { background: #f2f2f2; text-align: center; font-weight: bold; font-size: 10pt; }
        .daftar .no { width: 34px; text-align: center; }
        .daftar .ttd-col { width: 150px; }
        .ttd-area { margin-top: 24px; }
        .ttd-area td { vertical-align: top; font-size: 10pt; }
        .qr-box img { width: 80px; height: 80px; }
        .sign { width: 45%; text-align: center; font-size: 11pt; }
        .no-print { margin-bottom: 12px; font-family: Arial, sans-serif; font-size: 12px; }
        .no-print a, .no-print button { display: inline-block; padding: 8px 12px; border: 1px solid #999; background: #fff; color: #111; text-decoration: none; cursor: pointer; margin-right: 6px; }
        @media print { .no-print { display: none; } body { -webkit-print-color-adjust: exact; print-color-adjust: exact; } }
    </style>
</head>
<body>
@php
    $logoPath = public_path('image.png');
    $logoData = is_file($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : null;
    $tgl = !empty($content['tanggal']) ? \Carbon\Carbon::parse($content['tanggal'])->translatedFormat('l, d F Y') : '-';
    $waktu = trim(($content['waktu_mulai'] ?? '') . (($content['waktu_mulai'] ?? '') && ($content['waktu_selesai'] ?? '') ? ' s/d ' : '') . ($content['waktu_selesai'] ?? ''));
    $finalTgl = ($document->finalized_at ?? $document->created_at)->translatedFormat('d F Y');
    $peserta = $content['peserta'] ?? [];
@endphp

@if(empty($isPdf))
<div class="no-print">
    <a href="{{ route('dokumen.show', $document) }}">Kembali</a>
    <button onclick="window.print()">Cetak / Simpan PDF</button>
</div>
@endif

<table class="kop"><tr>
    <td class="kop-logo">@if($logoData)<img src="{{ $logoData }}" alt="">@endif</td>
    <td class="kop-text">
        <div class="l1">Kementerian Pendidikan Tinggi, Sains, dan Teknologi</div>
        <div class="l2">Universitas Mulawarman</div>
        <div class="l3">Fakultas Teknik — Senat Fakultas</div>
        <div class="l4">Jalan Sambaliung No. 9, Kampus Gunung Kelua, Samarinda 75119</div>
    </td>
    <td class="kop-logo"></td>
</tr></table>

<div class="judul">
    <h1>Daftar Hadir</h1>
    <div class="nomor">Nomor: {{ $document->nomor_surat ?? 'Belum bernomor' }}</div>
</div>

<table class="meta">
    <tr><td class="label">Acara / Rapat</td><td class="sep">:</td><td>{{ $content['nama_acara'] ?? $document->judul }}</td></tr>
    <tr><td class="label">Hari / Tanggal</td><td class="sep">:</td><td>{{ $tgl }}</td></tr>
    <tr><td class="label">Waktu</td><td class="sep">:</td><td>{{ $waktu ? $waktu . ' WITA' : '-' }}</td></tr>
    <tr><td class="label">Tempat</td><td class="sep">:</td><td>{{ $content['tempat'] ?? '-' }}</td></tr>
    @if(!empty($content['penyelenggara']))
    <tr><td class="label">Penyelenggara</td><td class="sep">:</td><td>{{ $content['penyelenggara'] }}</td></tr>
    @endif
</table>

<table class="daftar">
    <thead>
        <tr>
            <th class="no">No.</th>
            <th style="text-align:left;">Nama Peserta</th>
            <th style="text-align:left;">Jabatan / Instansi</th>
            <th class="ttd-col">Tanda Tangan</th>
        </tr>
    </thead>
    <tbody>
        @forelse($peserta as $i => $item)
        <tr>
            <td class="no">{{ $i + 1 }}</td>
            <td>{{ $item['nama'] ?? '-' }}</td>
            <td>{{ $item['jabatan_instansi'] ?? '-' }}</td>
            <td style="font-size:9pt; color:#555;">{{ $i + 1 }}. .......................</td>
        </tr>
        @empty
        <tr><td colspan="4" style="text-align:center; padding:18px; color:#666;">Belum ada peserta terdaftar.</td></tr>
        @endforelse
    </tbody>
</table>

<table class="ttd-area">
    <tr>
        <td class="qr-box" style="width:55%;">
            @if(!empty($qrDataUri))
                <table style="width:auto;"><tr>
                    <td style="width:90px; vertical-align:top;"><img src="{{ $qrDataUri }}" alt="QR Verifikasi"></td>
                    <td style="vertical-align:top; font-size:9pt; color:#333; padding-left:8px;">
                        <strong>Dokumen Resmi Terverifikasi</strong><br>
                        Finalisasi: {{ $document->finalized_at ? $document->finalized_at->format('d/m/Y H:i') : '-' }}<br>
                        <span style="font-size:8pt; word-break:break-all;">{{ $document->qr_verification_url }}</span>
                    </td>
                </tr></table>
            @elseif($document->isFinal() && $document->qr_verification_url)
                <span style="font-size:9pt; color:#333;">Verifikasi: {{ $document->qr_verification_url }}</span>
            @else
                <span style="font-size:9pt; color:#666; font-style:italic;">Draf dokumen - belum diterbitkan QR resmi.</span>
            @endif
        </td>
        <td class="sign">
            <p>Samarinda, {{ $finalTgl }}</p>
            <p>{{ $content['penyelenggara'] ?? 'Pimpinan Rapat / Ketua Senat' }}</p>
            <div style="height:64px;"></div>
            <p><strong><u>{{ $document->creator->name }}</u></strong></p>
            <p style="font-size:10pt;">NIP {{ $document->creator->nip }}</p>
        </td>
    </tr>
</table>
</body>
</html>
