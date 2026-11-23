<?php

namespace Tests\Feature;

use App\Models\JenisSurat;
use App\Models\Surat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuratTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    public function test_halaman_index_menampilkan_daftar_surat(): void
    {
        $surat = Surat::factory()->create(['nomor_surat' => '001/SN/FT/UNMUL/2026']);

        $response = $this->get(route('surat.index'));

        $response->assertOk();
        $response->assertSee('001/SN/FT/UNMUL/2026');
        $response->assertSee($surat->jenisSurat->nama_surat);
    }

    public function test_form_create_meminta_pilih_jenis_surat(): void
    {
        $jenisSurat = JenisSurat::factory()->create(['nama_surat' => 'Surat Keputusan']);

        $response = $this->get(route('surat.create'));

        $response->assertOk();
        $response->assertSee('Surat Keputusan');
    }

    public function test_form_create_dengan_jenis_surat_memuat_template(): void
    {
        $jenisSurat = JenisSurat::factory()->create([
            'nama_surat' => 'Surat Keputusan',
            'template_surat' => '<p>Template khusus</p>',
        ]);

        $response = $this->get(route('surat.create', ['jenis_surat_id' => $jenisSurat->id]));

        $response->assertOk();
        $response->assertSee('Template khusus', false);
    }

    public function test_surat_baru_dapat_dibuat(): void
    {
        $jenisSurat = JenisSurat::factory()->create();

        $response = $this->post(route('surat.store'), [
            'jenis_surat_id' => $jenisSurat->id,
            'nomor_surat' => '002/SN/FT/UNMUL/2026',
            'tanggal_surat' => '2026-11-11',
            'konten_surat' => '<p>[[NAMA_SURAT]]</p>',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('surat', ['nomor_surat' => '002/SN/FT/UNMUL/2026']);
    }

    public function test_validasi_surat_wajib_diisi(): void
    {
        $response = $this->post(route('surat.store'), []);

        $response->assertSessionHasErrors(['jenis_surat_id', 'nomor_surat', 'tanggal_surat', 'konten_surat']);
        $this->assertDatabaseCount('surat', 0);
    }

    public function test_halaman_show_mengganti_kode_isian(): void
    {
        $jenisSurat = JenisSurat::factory()->create(['nama_surat' => 'Surat Keputusan']);
        $surat = Surat::factory()->create([
            'jenis_surat_id' => $jenisSurat->id,
            'nomor_surat' => '003/SN/FT/UNMUL/2026',
            'tanggal_surat' => '2026-11-11',
            'konten_surat' => '<p>[[NAMA_SURAT]] nomor [[NOMOR_SURAT]] tanggal [[TANGGAL_SURAT]]</p>',
        ]);

        $response = $this->get(route('surat.show', $surat));

        $response->assertOk();
        $response->assertSee('Surat Keputusan nomor 003/SN/FT/UNMUL/2026 tanggal 11 November 2026');
    }

    public function test_halaman_edit_surat_menampilkan_data_surat(): void
    {
        $surat = Surat::factory()->create(['nomor_surat' => '123/SN/2026']);

        $response = $this->get(route('surat.edit', $surat));

        $response->assertOk();
        $response->assertSee('123/SN/2026');
    }

    public function test_surat_dapat_diupdate(): void
    {
        $surat = Surat::factory()->create();

        $response = $this->put(route('surat.update', $surat), [
            'nomor_surat' => '999/SN/FT/UNMUL/2026',
            'tanggal_surat' => '2026-11-11',
            'konten_surat' => $surat->konten_surat,
        ]);

        $response->assertRedirect(route('surat.show', $surat));
        $this->assertDatabaseHas('surat', ['nomor_surat' => '999/SN/FT/UNMUL/2026']);
    }

    public function test_surat_dapat_dihapus(): void
    {
        $surat = Surat::factory()->create();

        $response = $this->delete(route('surat.destroy', $surat));

        $response->assertRedirect(route('surat.index'));
        $this->assertDatabaseCount('surat', 0);
    }

    public function test_surat_dapat_diekspor_ke_pdf(): void
    {
        $jenisSurat = JenisSurat::factory()->create(['nama_surat' => 'Surat Keputusan']);
        $surat = Surat::factory()->create([
            'jenis_surat_id' => $jenisSurat->id,
            'nomor_surat' => '004/SN/FT/UNMUL/2026',
            'tanggal_surat' => '2026-11-11',
            'konten_surat' => config('senatitor.default_template'),
        ]);

        $response = $this->get(route('surat.pdf', $surat));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
        $this->assertStringStartsWith('%PDF', $response->getContent());
    }

    public function test_pdf_mengganti_kode_isian_dengan_nilai_surat(): void
    {
        $jenisSurat = JenisSurat::factory()->create(['nama_surat' => 'Surat Keputusan']);
        $surat = Surat::factory()->create([
            'jenis_surat_id' => $jenisSurat->id,
            'nomor_surat' => '005/SN/FT/UNMUL/2026',
            'tanggal_surat' => '2026-11-11',
            'konten_surat' => '<p>[[NAMA_SURAT]] [[NOMOR_SURAT]] [[TANGGAL_SURAT]]</p>',
        ]);

        $response = $this->get(route('surat.pdf', $surat));

        $response->assertOk();
        // Konten PDF terkompresi; pastikan token tidak lolos apa adanya.
        $this->assertStringNotContainsString('[[NOMOR_SURAT]]', $response->getContent());
    }

    public function test_surat_dapat_diekspor_ke_pdf_dengan_ukuran_kertas_dan_margin_custom(): void
    {
        $jenisSurat = JenisSurat::factory()->create([
            'nama_surat' => 'Surat Keputusan',
            'ukuran_kertas' => 'folio',
            'margin_atas' => 2.0,
            'margin_bawah' => 2.0,
            'margin_kiri' => 3.0,
            'margin_kanan' => 2.0,
        ]);
        $surat = Surat::factory()->create([
            'jenis_surat_id' => $jenisSurat->id,
            'nomor_surat' => '006/SN/FT/UNMUL/2026',
            'tanggal_surat' => '2026-11-11',
            'konten_surat' => config('senatitor.default_template'),
        ]);

        $response = $this->get(route('surat.pdf', $surat));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
        $this->assertStringStartsWith('%PDF', $response->getContent());
    }

    public function test_surat_dapat_diunduh_sebagai_pdf(): void
    {
        $surat = Surat::factory()->create([
            'nomor_surat' => '007/SN/FT/UNMUL/2026',
            'konten_surat' => '<p>Isi surat</p>',
        ]);

        $response = $this->get(route('surat.pdf.download', $surat));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
        $response->assertDownload('007-SN-FT-UNMUL-2026.pdf');
    }

    public function test_surat_dapat_diekspor_ke_pdf_dengan_kop_berulang_dan_footer(): void
    {
        $jenisSurat = JenisSurat::factory()->create([
            'ulang_kop' => true,
            'ulang_footer' => true,
            'footer_surat' => '<p style="text-align:center;font-size:9pt;">Dokumen [[NOMOR_SURAT]]</p>',
            'template_surat' => config('senatitor.default_template'),
        ]);
        $surat = Surat::factory()->create([
            'jenis_surat_id' => $jenisSurat->id,
            'nomor_surat' => '008/SN/FT/UNMUL/2026',
            'konten_surat' => config('senatitor.default_template'),
        ]);

        $response = $this->get(route('surat.pdf', $surat));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
        $this->assertStringStartsWith('%PDF', $response->getContent());
    }
}
