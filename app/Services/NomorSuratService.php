<?php

namespace App\Services;

use App\Models\Document;

class NomorSuratService
{
    public const KODE = [
        'daftar_hadir' => 'DH',
        'undangan' => 'UND',
        'berita_acara' => 'BA',
        'surat_pengantar' => 'SP',
    ];

    public function generate(string $jenis): string
    {
        $code = self::KODE[$jenis] ?? strtoupper(substr($jenis, 0, 2));

        $count = Document::where('jenis', $jenis)
            ->where('status', Document::STATUS_FINAL)
            ->count() + 1;

        $sequence = str_pad($count, 3, '0', STR_PAD_LEFT);

        $romans = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII',
        ];
        $month = $romans[now()->month] ?? 'IX';
        $year = now()->year;

        return "{$sequence}/{$code}/Senat-FT/{$month}/{$year}";
    }
}
