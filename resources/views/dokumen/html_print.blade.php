<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $document->nomor_surat ?? $document->judul }} - {{ $document->judul }}</title>
    <style>
        @page { size: A4 portrait; margin: 1.8cm 2cm 2cm 2.2cm; }
        body { font-family: "Times New Roman", Times, serif; font-size: 12pt; line-height: 1.45; color: #111; margin: 0; }
        p { margin: 0 0 6pt 0; }
        table { width: 100%; border-collapse: collapse; }
        thead { display: table-header-group; }
        tr { page-break-inside: avoid; }
        th, td { vertical-align: top; }
        .kop { border-bottom: 3px double #111; padding-bottom: 8px; margin-bottom: 16px; }
        .kop td { vertical-align: middle; }
        .kop-logo { width: 78px; }
        .kop-logo img { width: 68px; height: auto; display: block; }
        .kop-text { text-align: center; line-height: 1.25; }
        .kop-text .l1 { font-size: 10pt; font-weight: bold; text-transform: uppercase; }
        .kop-text .l2 { font-size: 12pt; font-weight: bold; text-transform: uppercase; }
        .kop-text .l3 { font-size: 13pt; font-weight: bold; text-transform: uppercase; }
        .kop-text .l4 { font-size: 8.5pt; margin-top: 2px; }
        .document-body { font-size: 11.5pt; line-height: 1.45; }
        .document-body p:last-child { margin-bottom: 0; }
        .document-body table { width: 100%; border-collapse: collapse; }
        .document-body th, .document-body td { padding: 4px 6px; }
        .document-body table[data-table="peserta"],
        .document-body table[border="1"] { margin-top: 8pt; margin-bottom: 10pt; font-size: 10.5pt; }
        .document-body table[data-table="peserta"] th,
        .document-body table[data-table="peserta"] td,
        .document-body table[border="1"] th,
        .document-body table[border="1"] td { border: 1px solid #222; padding: 5px 7px; vertical-align: middle; }
        .document-body table[data-table="peserta"] th,
        .document-body table[border="1"] th { background-color: #f2f2f2; font-weight: bold; text-align: center; }
        .document-body table[data-table="peserta"] tbody td { height: 10mm; }
        .page-break { page-break-after: always; height: 0; margin: 0; border: 0; }
        .ph[data-ph="qr_verifikasi"] { display: block; margin-top: 12pt; }
        .no-print { margin-bottom: 12px; font-family: Arial, sans-serif; font-size: 12px; }
        .no-print a, .no-print button { display: inline-block; padding: 8px 12px; border: 1px solid #999; background: #fff; color: #111; text-decoration: none; cursor: pointer; margin-right: 6px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
@php
    $logoPath = public_path('image.png');
    $logoSrc = null;
    if (is_file($logoPath)) {
        $logoSrc = !empty($isPdf)
            ? $logoPath
            : 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
    }
@endphp

@if(empty($isPdf))
<div class="no-print">
    <a href="{{ route('dokumen.show', $document) }}">Kembali</a>
    <button onclick="window.print()">Cetak / Simpan PDF</button>
</div>
@endif

<table class="kop">
    <tr>
        <td class="kop-logo">@if($logoSrc)<img src="{{ $logoSrc }}" alt="">@endif</td>
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
