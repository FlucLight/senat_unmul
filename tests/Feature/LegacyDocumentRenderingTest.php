<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LegacyDocumentRenderingTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsPengurus(): static
    {
        return $this->actingAs(User::factory()->create());
    }

    public function test_legacy_show_renders_preview_partial(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $doc = Document::create([
            'jenis' => 'undangan',
            'judul' => 'Undangan Legacy',
            'status' => Document::STATUS_DRAFT,
            'created_by' => $user->id,
            'content' => [
                'perihal' => 'Rapat Legacy',
                'tanggal_surat' => '2026-01-01',
                'tempat' => 'Ruang A',
            ],
        ]);

        $response = $this->get(route('dokumen.show', $doc));
        $response->assertOk();
        $response->assertSee('Undangan Legacy');
        $response->assertSee('Rapat Legacy');
        $response->assertSee('Ruang A');
    }

    public function test_legacy_print_views_render_for_all_types(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $cases = [
            ['jenis' => 'undangan', 'route' => 'dokumen.undangan.cetak', 'marker' => 'Undangan'],
            ['jenis' => 'berita_acara', 'route' => 'dokumen.berita-acara.cetak', 'marker' => 'Berita Acara'],
            ['jenis' => 'surat_pengantar', 'route' => 'dokumen.surat-pengantar.cetak', 'marker' => 'Surat Pengantar'],
            ['jenis' => 'daftar_hadir', 'route' => 'dokumen.cetak', 'marker' => 'Daftar Hadir'],
        ];

        foreach ($cases as $case) {
            $doc = Document::create([
                'jenis' => $case['jenis'],
                'judul' => 'Judul ' . $case['jenis'],
                'status' => Document::STATUS_FINAL,
                'nomor_surat' => '001/TST/' . strtoupper($case['jenis']) . '/2026',
                'created_by' => $user->id,
                'finalized_at' => now(),
                'qr_verification_url' => route('dokumen.verifikasi', 99999),
                'content' => [
                    'perihal' => 'Perihal ' . $case['jenis'],
                    'nama_acara' => 'Acara ' . $case['jenis'],
                    'tanggal_surat' => '2026-01-01',
                    'tanggal_acara' => '2026-01-02',
                    'tanggal_rapat' => '2026-01-02',
                    'tanggal' => '2026-01-02',
                    'tempat' => 'Tempat Uji',
                    'peserta' => [['nama' => 'Peserta Uji', 'jabatan_instansi' => 'Jabatan Uji']],
                    'undangan' => [['nama' => 'Undangan Uji', 'jabatan_instansi' => 'Jabatan Uji']],
                ],
            ]);

            $response = $this->get(route($case['route'], $doc));
            $response->assertOk();
            $response->assertSee($case['marker']);
            $response->assertSee($doc->nomor_surat);
        }
    }

    public function test_legacy_download_pdf_returns_pdf_for_final_document(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $doc = Document::create([
            'jenis' => 'surat_pengantar',
            'judul' => 'SP Legacy PDF',
            'status' => Document::STATUS_FINAL,
            'nomor_surat' => '010/SP/Senat-FT/I/2026',
            'created_by' => $user->id,
            'finalized_at' => now(),
            'qr_verification_url' => route('dokumen.verifikasi', 99999),
            'content' => [
                'perihal' => 'Perihal SP',
                'tanggal_surat' => '2026-01-01',
                'tujuan' => 'Dekan',
                'isi_ringkas' => 'Isi ringkas',
                'penandatangan' => 'Ketua',
            ],
        ]);

        $response = $this->get(route('dokumen.pdf', $doc));
        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
        $this->assertStringStartsWith('%PDF', $response->getContent());
    }
}
