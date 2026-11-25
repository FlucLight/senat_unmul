<?php

return [
    'undangan' => [
        'default' => [
            'key' => 'default',
            'label' => 'Undangan Standar',
            'description' => 'Template undangan rapat resmi Senat FT.',
            'default' => true,
            'view' => 'document_templates.undangan.default',
            'path' => 'resources/document-templates/undangan/default.blade.php',
        ],
    ],
    'berita_acara' => [
        'default' => [
            'key' => 'default',
            'label' => 'Berita Acara Standar',
            'description' => 'Template berita acara rapat Senat FT.',
            'default' => true,
            'view' => 'document_templates.berita_acara.default',
            'path' => 'resources/document-templates/berita_acara/default.blade.php',
        ],
    ],
    'surat_pengantar' => [
        'default' => [
            'key' => 'default',
            'label' => 'Surat Pengantar Standar',
            'description' => 'Template surat pengantar resmi Senat FT.',
            'default' => true,
            'view' => 'document_templates.surat_pengantar.default',
            'path' => 'resources/document-templates/surat_pengantar/default.blade.php',
        ],
    ],
    'daftar_hadir' => [
        'zigzag' => [
            'key' => 'zigzag',
            'label' => 'Standar (TTD Zigzag)',
            'description' => 'Tanda tangan ganjil kiri, genap kanan.',
            'default' => true,
            'view' => 'document_templates.daftar_hadir.zigzag',
            'path' => 'resources/document-templates/daftar_hadir/zigzag.blade.php',
        ],
        'satu_kolom' => [
            'key' => 'satu_kolom',
            'label' => '1 Kolom TTD',
            'description' => 'Satu kolom paraf/tanda tangan.',
            'default' => false,
            'view' => 'document_templates.daftar_hadir.satu_kolom',
            'path' => 'resources/document-templates/daftar_hadir/satu_kolom.blade.php',
        ],
        'blank' => [
            'key' => 'blank',
            'label' => 'Blank Siap Cetak',
            'description' => 'Baris kosong untuk diisi manual saat acara.',
            'default' => false,
            'view' => 'document_templates.daftar_hadir.blank',
            'path' => 'resources/document-templates/daftar_hadir/blank.blade.php',
        ],
    ],
];
