<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $document->nomor_surat ?? $document->judul }} - {{ $document->judul }}</title>
    <style>
        @page { size: A4 portrait; margin: 2cm 2cm 2cm 2.5cm; }
        body { font-family: "Times New Roman", Times, serif; font-size: 12pt; line-height: 1.5; color: #111; margin: 0; }
        p { margin: 0 0 6pt 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { vertical-align: top; }
        .kop { border-bottom: 3px double #111; padding-bottom: 8px; margin-bottom: 18px; }
        .kop td { vertical-align: middle; }
        .kop-logo { width: 90px; }
        .kop-logo img { width: 75px; height: auto; }
        .kop-text { text-align: center; }
        .kop-text .l1 { font-size: 11pt; font-weight: bold; text-transform: uppercase; }
        .kop-text .l2 { font-size: 13pt; font-weight: bold; text-transform: uppercase; }
        .kop-text .l3 { font-size: 14pt; font-weight: bold; text-transform: uppercase; }
        .kop-text .l4 { font-size: 9pt; margin-top: 2px; }
        .document-body { font-size: 12pt; line-height: 1.5; }
        .document-body table { width: 100%; border-collapse: collapse; }
        .document-body th, .document-body td { padding: 3px 4px; }
        .page-break { page-break-after: always; break-after: page; height: 0; margin: 0; border: 0; }
        .ph[data-ph="qr_verifikasi"] { display: block; margin-top: 12pt; }
        .no-print { margin-bottom: 12px; font-family: Arial, sans-serif; font-size: 12px; }
        .no-print a, .no-print button { display: inline-block; padding: 8px 12px; border: 1px solid #999; background: #fff; color: #111; text-decoration: none; cursor: pointer; margin-right: 6px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
@php
    $logoPath = public_path('image.png');
    $logoData = is_file($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : null;
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

<div class="document-body">
    {!! $bodyHtml !!}
</div>
</body>
</html>
