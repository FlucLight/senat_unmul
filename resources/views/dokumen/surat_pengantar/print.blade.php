<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $document->nomor_surat ?? 'Surat Pengantar' }} - {{ $document->judul }}</title>
    <style>
        @page { size: A4 portrait; margin: 2cm 2cm 2cm 2.5cm; }
        body { font-family: "Times New Roman", Times, serif; font-size: 12pt; line-height: 1.5; color: #111; margin: 0; }
        p { margin: 0 0 6pt 0; }
        table { width: 100%; border-collapse: collapse; }
        .kop { border-bottom: 3px double #111; padding-bottom: 8px; margin-bottom: 18px; }
        .kop td { vertical-align: middle; }
        .kop-logo { width: 90px; }
        .kop-logo img { width: 75px; height: auto; }
        .kop-text { text-align: center; }
        .kop-text .l1 { font-size: 11pt; font-weight: bold; text-transform: uppercase; }
        .kop-text .l2 { font-size: 13pt; font-weight: bold; text-transform: uppercase; }
        .kop-text .l3 { font-size: 14pt; font-weight: bold; text-transform: uppercase; }
        .kop-text .l4 { font-size: 9pt; margin-top: 2px; }
        .judul { text-align: center; margin: 18px 0 14px; }
        .judul h1 { font-size: 14pt; margin: 0; text-decoration: underline; text-transform: uppercase; letter-spacing: 0.5px; }
        .judul .nomor { font-size: 11pt; margin-top: 2px; }
        .meta td { padding: 1px 0; vertical-align: top; font-size: 11pt; }
        .meta .label { width: 140px; }
        .meta .sep { width: 14px; }
        .isi { margin-top: 14px; text-align: justify; font-size: 11pt; }
        .ttd { margin-top: 28px; width: 100%; }
        .ttd td { vertical-align: top; font-size: 11pt; }
        .ttd .right { width: 45%; text-align: center; }
        .qr { margin-top: 24px; font-size: 9pt; color: #333; }
        .qr img { width: 85px; height: 85px; }
        .no-print { margin-bottom: 12px; font-family: Arial, sans-serif; font-size: 12px; }
        .no-print a, .no-print button { display: inline-block; padding: 8px 12px; border: 1px solid #999; background: #fff; color: #111; text-decoration: none; cursor: pointer; margin-right: 6px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
@php
    $logoPath = public_path('image.png');
    $logoData = is_file($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : null;
    $tglSurat = !empty($content['tanggal_surat']) ? \Carbon\Carbon::parse($content['tanggal_surat'])->translatedFormat('d F Y') : '-';
    $finalTgl = ($document->finalized_at ?? $document->created_at)->translatedFormat('d F Y');
@endphp

<div class="no-print">
    <a href="{{ route('dokumen.show', $document) }}">Kembali</a>
    <button onclick="window.print()">Cetak / Simpan PDF</button>
</div>

<table class="kop">
    <tr>
        <td class="kop-logo">@if($logoData)<img src="{{ $logoData }}" alt="Logo">@endif</td>
        <td class="kop-text">
            <div class="l1">Kementerian Pendidikan Tinggi, Sains, dan Teknologi</div>
            <div class="l2">Universitas Mulawarman</div>
            <div class="l3">Fakultas Teknik — Senat Fakultas</div>
            <div class="l4">Jalan Sambaliung No. 9, Kampus Gunung Kelua, Samarinda 75119</div>
        </td>
        <td class="kop-logo"></td>
    </tr>
</table>

<div class="judul">
    <h1>Surat Pengantar</h1>
    <div class="nomor">Nomor: {{ $document->nomor_surat ?? 'Belum bernomor' }}</div>
</div>

<table class="meta">
    <tr><td class="label">Tanggal</td><td class="sep">:</td><td>{{ $tglSurat }}</td></tr>
    <tr><td class="label">Perihal</td><td class="sep">:</td><td>{{ $content['perihal'] ?? $document->judul }}</td></tr>
    <tr><td class="label">Tujuan</td><td class="sep">:</td><td>{{ $content['tujuan'] ?? '-' }}</td></tr>
</table>

<div class="isi">
    {!! nl2br(e($content['isi_ringkas'] ?? '-')) !!}
</div>

<table class="ttd">
    <tr>
        <td></td>
        <td class="right">
            <p>Samarinda, {{ $finalTgl }}</p>
            <p>{{ $content['penandatangan'] ?: 'Penandatangan' }}</p>
            <div style="height:70px;"></div>
            <p><strong><u>{{ $document->creator->name ?? '-' }}</u></strong></p>
            <p>NIP {{ $document->creator->nip ?? '-' }}</p>
        </td>
    </tr>
</table>

@if(!empty($qrDataUri))
<table class="qr">
    <tr>
        <td style="width:95px;"><img src="{{ $qrDataUri }}" alt="QR Verifikasi"></td>
        <td>
            <strong>Dokumen Resmi Terverifikasi</strong><br>
            Pindai QR untuk memeriksa keaslian dokumen.<br>
            <span style="font-size:8pt; word-break:break-all;">{{ $document->qr_verification_url }}</span>
        </td>
    </tr>
</table>
@endif
</body>
</html>
