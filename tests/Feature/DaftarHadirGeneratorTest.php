<?php

namespace Tests\Feature;

use App\Services\DaftarHadirGenerator;
use PHPUnit\Framework\TestCase;

class DaftarHadirGeneratorTest extends TestCase
{
    public function test_zigzag_variant_generates_correct_signature_pattern_for_30_rows(): void
    {
        $gen = new DaftarHadirGenerator();
        $peserta = [];
        for ($i = 1; $i <= 30; $i++) {
            $peserta[] = ['nama' => 'Peserta ' . $i, 'jabatan_instansi' => 'Jabatan ' . $i];
        }
        $html = $gen->generate([
            'variant' => 'zigzag',
            'nama_acara' => 'Rapat',
            'tanggal' => '2026-11-25',
            'tempat' => 'Ruang A',
            'peserta' => $peserta,
        ]);

        $this->assertStringContainsString('data-table="peserta"', $html);
        for ($i = 1; $i <= 30; $i++) {
            $this->assertStringContainsString('>Peserta ' . $i . '<', $html);
            if ($i % 2 === 1) {
                $this->assertMatchesRegularExpression('/<td[^>]*>' . $i . '\.<\/td>\s*<td[^>]*><\/td>/', $html);
            } else {
                $this->assertMatchesRegularExpression('/<td[^>]*><\/td>\s*<td[^>]*>' . $i . '\.<\/td>/', $html);
            }
        }
    }

    public function test_satu_kolom_and_blank_variants(): void
    {
        $gen = new DaftarHadirGenerator();
        $satu = $gen->generate(['variant' => 'satu_kolom', 'nama_acara' => 'Rapat', 'peserta' => [['nama' => 'A', 'jabatan_instansi' => 'B']]]);
        $this->assertStringContainsString('NIP/Identitas', $satu);
        $this->assertStringContainsString('Paraf', $satu);

        $blank = $gen->generate(['variant' => 'blank', 'nama_acara' => 'Rapat', 'blank_rows' => 20]);
        $this->assertStringContainsString('data-table="peserta"', $blank);
        $this->assertSame(20, substr_count($blank, '<tr><td style="text-align:center;">'));
    }

    public function test_blank_rows_are_clamped_to_100(): void
    {
        $gen = new DaftarHadirGenerator();
        $html = $gen->generate(['variant' => 'blank', 'blank_rows' => 200]);
        $this->assertSame(100, substr_count($html, '<tr><td style="text-align:center;">'));
    }
}
