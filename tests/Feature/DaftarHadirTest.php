<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DaftarHadirTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsPengurus(): static
    {
        return $this->actingAs(User::factory()->create());
    }

    public function test_guest_cannot_access_create_form(): void
    {
        $this->get(route('dokumen.daftar-hadir.create'))->assertRedirect(route('login'));
    }

    public function test_user_can_view_create_form(): void
    {
        $this->actingAsPengurus();

        $this->get(route('dokumen.daftar-hadir.create'))
            ->assertOk()
            ->assertSee('Buat daftar hadir baru')
            ->assertSee('Informasi acara')
            ->assertSee('Daftar peserta');
    }

    public function test_user_can_save_draft_daftar_hadir(): void
    {
        $this->actingAsPengurus();

        $response = $this->post(route('dokumen.daftar-hadir.store'), [
            'action' => 'draft',
            'nama_acara' => 'Rapat Draf Awal',
            'tanggal' => '2026-09-15',
            'waktu_mulai' => '09:00',
            'waktu_selesai' => '11:00',
            'tempat' => 'Ruang Rapat Dekanat',
            'penyelenggara' => 'Ketua Senat',
            'peserta' => [
                ['nama' => 'Dr. Budi', 'jabatan_instansi' => 'Dosen Sipil'],
            ],
        ]);

        $doc = Document::where('judul', 'Rapat Draf Awal')->first();
        $this->assertNotNull($doc);
        $this->assertSame(Document::STATUS_DRAFT, $doc->status);
        $this->assertNull($doc->nomor_surat);

        $response->assertRedirect(route('dokumen.daftar-hadir.edit', $doc));
    }

    public function test_user_can_edit_and_update_draft(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $doc = Document::create([
            'jenis' => 'daftar_hadir',
            'judul' => 'Rapat Sebelum Diedit',
            'status' => Document::STATUS_DRAFT,
            'created_by' => $user->id,
            'content' => [
                'nama_acara' => 'Rapat Sebelum Diedit',
                'tempat' => 'Gedung A',
                'peserta' => [],
            ],
        ]);

        $this->get(route('dokumen.daftar-hadir.edit', $doc))
            ->assertOk()
            ->assertSee('Rapat Sebelum Diedit');

        $this->put(route('dokumen.daftar-hadir.update', $doc), [
            'action' => 'draft',
            'nama_acara' => 'Rapat Setelah Diedit',
            'tempat' => 'Gedung B',
            'peserta' => [
                ['nama' => 'Peserta Satu', 'jabatan_instansi' => 'Teknik Tambang'],
            ],
        ])->assertRedirect(route('dokumen.daftar-hadir.edit', $doc));

        $doc->refresh();
        $this->assertSame('Rapat Setelah Diedit', $doc->judul);
        $this->assertSame('Gedung B', $doc->content['tempat']);
        $this->assertCount(1, $doc->content['peserta']);
    }

    public function test_user_can_finalize_daftar_hadir(): void
    {
        $this->actingAsPengurus();

        $response = $this->post(route('dokumen.daftar-hadir.store'), [
            'action' => 'final',
            'nama_acara' => 'Rapat Pleno Senat Final',
            'tanggal' => '2026-09-20',
            'waktu_mulai' => '08:30',
            'waktu_selesai' => '11:30',
            'tempat' => 'Ruang Sidang Senat',
            'penyelenggara' => 'Ketua Senat FT',
            'peserta' => [
                ['nama' => 'Prof. Dr. Ir. Andi', 'jabatan_instansi' => 'Guru Besar Teknik Mesin'],
                ['nama' => 'Dr. Ir. Siti, M.T.', 'jabatan_instansi' => 'Dosen Teknik Kimia'],
            ],
        ]);

        $doc = Document::where('judul', 'Rapat Pleno Senat Final')->first();
        $this->assertNotNull($doc);
        $this->assertSame(Document::STATUS_FINAL, $doc->status);
        $this->assertNotNull($doc->nomor_surat);
        $this->assertStringContainsString('/DH/Senat-FT/', $doc->nomor_surat);
        $this->assertNotNull($doc->finalized_at);
        $this->assertNotNull($doc->qr_verification_url);

        $response->assertRedirect(route('dokumen.show', $doc));
    }

    public function test_cannot_finalize_without_participants(): void
    {
        $this->actingAsPengurus();

        $this->from(route('dokumen.daftar-hadir.create'))->post(route('dokumen.daftar-hadir.store'), [
            'action' => 'final',
            'nama_acara' => 'Rapat Tanpa Peserta',
            'tanggal' => '2026-09-20',
            'waktu_mulai' => '08:30',
            'waktu_selesai' => '11:30',
            'tempat' => 'Ruang Sidang',
            'peserta' => [
                ['nama' => '', 'jabatan_instansi' => ''],
            ],
        ])->assertSessionHasErrors('peserta');

        $this->assertDatabaseMissing('documents', ['judul' => 'Rapat Tanpa Peserta']);
    }

    public function test_finalized_daftar_hadir_cannot_be_edited(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $doc = Document::create([
            'jenis' => 'daftar_hadir',
            'judul' => 'Rapat Sudah Final',
            'nomor_surat' => '001/DH/Senat-FT/IX/2026',
            'status' => Document::STATUS_FINAL,
            'created_by' => $user->id,
            'finalized_at' => now(),
            'content' => [
                'nama_acara' => 'Rapat Sudah Final',
                'peserta' => [['nama' => 'Budi']],
            ],
        ]);

        $this->get(route('dokumen.daftar-hadir.edit', $doc))->assertForbidden();
        $this->put(route('dokumen.daftar-hadir.update', $doc), [
            'nama_acara' => 'Coba Ubah',
        ])->assertForbidden();
    }

    public function test_print_view_renders_correctly(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $doc = Document::create([
            'jenis' => 'daftar_hadir',
            'judul' => 'Rapat Cetak Presensi',
            'nomor_surat' => '002/DH/Senat-FT/IX/2026',
            'status' => Document::STATUS_FINAL,
            'created_by' => $user->id,
            'finalized_at' => now(),
            'content' => [
                'nama_acara' => 'Rapat Cetak Presensi',
                'tanggal' => '2026-09-20',
                'tempat' => 'Ruang Dekan',
                'peserta' => [
                    ['nama' => 'Prof. Ir. Hendra', 'jabatan_instansi' => 'Dekan FT'],
                ],
            ],
        ]);

        $this->get(route('dokumen.cetak', $doc))
            ->assertOk()
            ->assertSee('Rapat Cetak Presensi')
            ->assertSee('Prof. Ir. Hendra')
            ->assertSee('Tanda Tangan')
            ->assertSee('Fakultas Teknik — Senat Fakultas');
    }

    public function test_public_verification_page_renders_without_auth(): void
    {
        $user = User::factory()->create();

        $doc = Document::create([
            'jenis' => 'daftar_hadir',
            'judul' => 'Rapat Verifikasi QR',
            'nomor_surat' => '003/DH/Senat-FT/IX/2026',
            'status' => Document::STATUS_FINAL,
            'created_by' => $user->id,
            'finalized_at' => now(),
            'content' => [
                'nama_acara' => 'Rapat Verifikasi QR',
                'tanggal' => '2026-09-20',
                'tempat' => 'Samarinda',
                'peserta' => [
                    ['nama' => 'Ir. Dewi', 'jabatan_instansi' => 'Sekretaris'],
                ],
            ],
        ]);

        // Access publicly without login
        $this->get(route('dokumen.verifikasi', $doc))
            ->assertOk()
            ->assertSee('Dokumen Resmi Terverifikasi')
            ->assertSee('003/DH/Senat-FT/IX/2026')
            ->assertSee('Rapat Verifikasi QR');
    }

    public function test_generic_placeholder_serves_daftar_hadir_form(): void
    {
        $this->actingAsPengurus();

        $this->get(route('dokumen.baru', 'daftar_hadir'))
            ->assertOk()
            ->assertSee('Buat daftar hadir baru');
    }
}
