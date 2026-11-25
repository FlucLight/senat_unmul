<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Konfigurasi Senatitor
    |--------------------------------------------------------------------------
    |
    | Konfigurasi aplikasi pembuat surat senat universitas.
    |
    */

    // Logo kop surat default (relatif ke folder public).
    'logo' => 'images/logo.jpeg',

    // Ukuran kertas yang didukung untuk ekspor PDF.
    'paper_sizes' => [
        'a4' => 'A4 (210 × 297 mm)',
        'folio' => 'F4 / Folio (215 × 330 mm)',
        'letter' => 'Letter (216 × 279 mm)',
        'legal' => 'Legal (216 × 356 mm)',
    ],

    // Margin default surat (dalam cm): atas, bawah, kiri, kanan.
    'margins' => [
        'top' => 3.0,
        'bottom' => 3.0,
        'left' => 4.0,
        'right' => 3.0,
    ],

    // Kode isian yang dapat disisipkan ke editor template/surat.
    'fields' => [
        ['token' => 'NAMA_SURAT', 'label' => 'Nama Surat'],
        ['token' => 'NOMOR_SURAT', 'label' => 'Nomor Surat'],
        ['token' => 'TANGGAL_SURAT', 'label' => 'Tanggal Surat'],
        ['token' => 'logo', 'label' => 'Logo'],
    ],

    // Template contoh yang ditampilkan saat membuat jenis surat baru.
    // Struktur kop mengikuti format resmi FT Unmul (folder template).
    'default_template' => '<table style="border-collapse: collapse; width: 100%; margin: 0;">
    <tbody>
        <tr>
            <td style="width: 12%; text-align: center; vertical-align: middle; padding: 0; line-height: 0;">[[logo]]</td>
            <td style="width: 88%; text-align: center; vertical-align: middle; padding: 0;">
                <p style="margin: 0; line-height: 1.2; font-size: 14pt; font-weight: 600;">KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI</p>
                <p style="margin: 0; line-height: 1.2; font-size: 14pt; font-weight: 700;">UNIVERSITAS MULAWARMAN</p>
                <p style="margin: 0; line-height: 1.2; font-size: 14pt; font-weight: 700;">FAKULTAS TEKNIK</p>
                <p style="margin: 2px 0 0 0; line-height: 1.2; font-size: 10pt;">Alamat : Kampus Gunung Kelua, Jalan Sambaliung Nomor 9 Samarinda 75123 Telp. (0541) 736834, Fax (0541) 749315</p>
                <p style="margin: 0; line-height: 1.2; font-size: 10pt;">Email : dekan@ft.unmul.ac.id, Laman : http://ft.unmul.ac.id</p>
            </td>
        </tr>
    </tbody>
</table>
<hr style="border: none; border-top: 2px solid #000; margin: 4px 0 10px 0;">
<h4 style="margin: 0 0 4px 0; text-align: center; font-size: 14pt;"><span style="text-decoration: underline;">[[NAMA_SURAT]]</span></h4>
<p style="margin: 0 0 14px 0; text-align: center;">Nomor : [[NOMOR_SURAT]]</p>
<p style="margin: 0 0 10px 0; text-align: justify; text-indent: 30px; line-height: 1.5;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quasi ex vero necessitatibus aspernatur sequi sint nam itaque velit rem unde. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quasi ex vero necessitatibus aspernatur sequi sint nam itaque velit rem unde.</p>
<table style="border-collapse: collapse; width: 100%; margin-top: 20px;" border="0">
    <tbody>
        <tr>
            <td style="width: 55%; vertical-align: top;"></td>
            <td style="width: 45%; text-align: center;">Samarinda, [[TANGGAL_SURAT]]</td>
        </tr>
        <tr>
            <td style="width: 55%; vertical-align: top;"></td>
            <td style="width: 45%; text-align: center;">Ketua Senat Fakultas Teknik</td>
        </tr>
        <tr>
            <td style="width: 55%; vertical-align: top;"></td>
            <td style="width: 45%; height: 60px;"></td>
        </tr>
        <tr>
            <td style="width: 55%; vertical-align: top;"></td>
            <td style="width: 45%; text-align: center;">(Nama Pejabat)</td>
        </tr>
        <tr>
            <td style="width: 55%; vertical-align: top;"></td>
            <td style="width: 45%; text-align: center;">NIP. </td>
        </tr>
    </tbody>
</table>',
];
