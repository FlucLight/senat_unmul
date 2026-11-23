<?php

namespace Tests\Feature;

use App\Models\JenisSurat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class JenisSuratTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_index_menampilkan_daftar_jenis_surat(): void
    {
        $jenisSurat = JenisSurat::factory()->create(['nama_surat' => 'Surat Keputusan']);

        $response = $this->get(route('jenis_surat.index'));

        $response->assertOk();
        $response->assertSee('Surat Keputusan');
    }

    public function test_form_create_menampilkan_template_default(): void
    {
        $response = $this->get(route('jenis_surat.create'));

        $response->assertOk();
        $response->assertSee('[[NAMA_SURAT]]');
        $response->assertSee('[[TANGGAL_SURAT]]');
    }

    public function test_jenis_surat_baru_dapat_dibuat(): void
    {
        $response = $this->post(route('jenis_surat.store'), $this->validPayload([
            'nama_surat' => 'Surat Tugas',
            'template_surat' => '<p>Isi template surat tugas</p>',
        ]));

        $response->assertRedirect(route('jenis_surat.index'));
        $this->assertDatabaseHas('jenis_surat', [
            'nama_surat' => 'Surat Tugas',
            'ukuran_kertas' => 'a4',
        ]);
    }

    public function test_validasi_nama_surat_wajib_diisi(): void
    {
        $response = $this->post(route('jenis_surat.store'), [
            'template_surat' => '<p>Isi template</p>',
        ]);

        $response->assertSessionHasErrors(['nama_surat']);
        $this->assertDatabaseCount('jenis_surat', 0);
    }

    public function test_halaman_show_menampilkan_pratinjau_template(): void
    {
        $jenisSurat = JenisSurat::factory()->create([
            'nama_surat' => 'Surat Rekomendasi',
            'template_surat' => '<p>[[NAMA_SURAT]] dengan nomor [[NOMOR_SURAT]]</p>',
        ]);

        $response = $this->get(route('jenis_surat.show', $jenisSurat));

        $response->assertOk();
        $response->assertSee('Surat Rekomendasi');
        $response->assertSee('nomor (nomor surat)');
    }

    public function test_halaman_edit_menampilkan_jenis_surat(): void
    {
        $jenisSurat = JenisSurat::factory()->create(['nama_surat' => 'Surat Tugas']);

        $response = $this->get(route('jenis_surat.edit', $jenisSurat));

        $response->assertOk();
        $response->assertSee('Surat Tugas');
    }

    public function test_jenis_surat_dapat_diupdate(): void
    {
        $jenisSurat = JenisSurat::factory()->create(['nama_surat' => 'Nama Lama']);

        $response = $this->put(route('jenis_surat.update', $jenisSurat), $this->validPayload([
            'nama_surat' => 'Nama Baru',
            'template_surat' => $jenisSurat->template_surat,
            'ukuran_kertas' => 'folio',
            'margin_kiri' => 4.5,
        ]));

        $response->assertRedirect(route('jenis_surat.index'));
        $this->assertDatabaseHas('jenis_surat', [
            'id' => $jenisSurat->id,
            'nama_surat' => 'Nama Baru',
            'ukuran_kertas' => 'folio',
        ]);
    }

    public function test_jenis_surat_dapat_dihapus(): void
    {
        $jenisSurat = JenisSurat::factory()->create();

        $response = $this->delete(route('jenis_surat.destroy', $jenisSurat));

        $response->assertRedirect(route('jenis_surat.index'));
        $this->assertDatabaseCount('jenis_surat', 0);
    }

    public function test_jenis_surat_dapat_disimpan_dengan_logo_custom_dan_pengaturan_kertas(): void
    {
        $file = UploadedFile::fake()->image('logo-kop.png', 200, 200);

        $response = $this->post(route('jenis_surat.store'), $this->validPayload([
            'nama_surat' => 'Surat Rekomendasi',
            'ukuran_kertas' => 'legal',
            'margin_atas' => 2.5,
            'margin_bawah' => 2.5,
            'margin_kiri' => 3.5,
            'margin_kanan' => 2.5,
            'logo' => $file,
        ]));

        $response->assertRedirect(route('jenis_surat.index'));

        $jenisSurat = JenisSurat::where('nama_surat', 'Surat Rekomendasi')->firstOrFail();

        $this->assertSame('legal', $jenisSurat->ukuran_kertas);
        $this->assertEquals(2.5, $jenisSurat->margin_atas);
        $this->assertNotNull($jenisSurat->logo);
        $this->assertFileExists(public_path($jenisSurat->logo));

        @unlink(public_path($jenisSurat->logo));
    }

    public function test_logo_custom_dapat_dihapus_saat_update(): void
    {
        $dir = public_path('images/logos');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $path = 'images/logos/test-remove.png';
        file_put_contents(public_path($path), 'dummy');

        $jenisSurat = JenisSurat::factory()->create(['logo' => $path]);

        $response = $this->put(route('jenis_surat.update', $jenisSurat), $this->validPayload([
            'nama_surat' => $jenisSurat->nama_surat,
            'template_surat' => $jenisSurat->template_surat,
            'hapus_logo' => '1',
        ]));

        $response->assertRedirect(route('jenis_surat.index'));
        $this->assertDatabaseHas('jenis_surat', ['id' => $jenisSurat->id, 'logo' => null]);
        $this->assertFileDoesNotExist(public_path($path));
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    protected function validPayload(array $overrides = []): array
    {
        $defaults = config('senatitor.margins');

        return array_merge([
            'nama_surat' => 'Jenis Surat Uji',
            'template_surat' => '<p>Template uji</p>',
            'ukuran_kertas' => 'a4',
            'margin_atas' => $defaults['top'],
            'margin_bawah' => $defaults['bottom'],
            'margin_kiri' => $defaults['left'],
            'margin_kanan' => $defaults['right'],
            'ulang_kop' => '0',
            'ulang_footer' => '0',
        ], $overrides);
    }
}
