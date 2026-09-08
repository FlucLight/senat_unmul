<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WelcomeTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_access_welcome_page(): void
    {
        $response = $this->get(route('welcome'));

        $response->assertOk();
        $response->assertSee('Sistem Manajemen');
        $response->assertSee('Validasi Dokumen');
        $response->assertSee('Masuk');
        $response->assertSee('Verifikasi Dokumen');
        $response->assertSee('Tentang sistem');
        $response->assertSee('Jenis dokumen yang dikelola');
        $response->assertSee('Daftar Hadir');
        $response->assertSee('Alur kerja sistem');
    }

    public function test_authenticated_user_is_redirected_to_beranda(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('welcome'))
            ->assertRedirect(route('beranda'));
    }

    public function test_public_can_verify_valid_nomor_surat_on_welcome_page(): void
    {
        $user = User::factory()->create();
        $doc = Document::create([
            'jenis' => 'daftar_hadir',
            'judul' => 'Rapat Pleno Terbuka',
            'nomor_surat' => '005/DH/Senat-FT/IX/2026',
            'status' => Document::STATUS_FINAL,
            'created_by' => $user->id,
            'finalized_at' => now(),
            'content' => [
                'nama_acara' => 'Rapat Pleno Terbuka',
                'peserta' => [
                    ['nama' => 'Prof. Rahmad'],
                ],
            ],
        ]);

        $response = $this->get(route('welcome', ['nomor_surat' => '005/DH/Senat-FT/IX/2026']));

        $response->assertOk();
        $response->assertSee('Dokumen ini terverifikasi asli dari Senat FT');
        $response->assertSee('005/DH/Senat-FT/IX/2026');
        $response->assertSee('Rapat Pleno Terbuka');
        // W-5: Must not reveal attendee full list for privacy
        $response->assertDontSee('Prof. Rahmad');
    }

    public function test_public_searching_nonexistent_nomor_surat_shows_not_found(): void
    {
        $response = $this->get(route('welcome', ['nomor_surat' => '999/XYZ/Senat-FT/2026']));

        $response->assertOk();
        $response->assertSee('Nomor surat tidak ditemukan');
        $response->assertSee('999/XYZ/Senat-FT/2026');
    }

    public function test_login_button_links_to_login_page(): void
    {
        $response = $this->get(route('welcome'));

        $response->assertSee(route('login'));
    }
}
