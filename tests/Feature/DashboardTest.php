<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsPengurus(): static
    {
        return $this->actingAs(User::factory()->create());
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/beranda')->assertRedirect(route('login'));
    }

    public function test_dashboard_shows_welcome_header(): void
    {
        $user = User::factory()->create(['name' => 'Prof. Andi', 'role' => 'pengurus']);

        $this->actingAs($user)->get(route('beranda'))
            ->assertOk()
            ->assertSee('Halo, Prof. Andi')
            ->assertSee($user->nip);
    }

    public function test_dashboard_shows_quick_actions_for_all_four_jenis(): void
    {
        $this->actingAsPengurus();

        $this->get(route('beranda'))
            ->assertSee('Buat Undangan')
            ->assertSee('Buat Berita Acara')
            ->assertSee('Buat Surat Pengantar')
            ->assertSee('Buat Daftar Hadir');
    }

    public function test_dashboard_statistics_match_real_counts(): void
    {
        $this->actingAsPengurus();

        $draft = Document::factory()->create(['jenis' => 'undangan']);
        Document::factory()->create(['jenis' => 'berita_acara']);
        Document::factory()->final()->create(['jenis' => 'surat_pengantar']);

        $this->get(route('beranda'))
            ->assertSee('Undangan')
            ->assertSee('Berita Acara')
            ->assertSee('Surat Pengantar')
            ->assertSee('Draft')
            ->assertSee('Final');

        $this->assertSame(2, Document::where('status', 'draft')->count());
        $this->assertSame(1, Document::where('status', 'final')->count());
        $this->assertSame(1, Document::where('jenis', 'undangan')->count());
    }

    public function test_dashboard_shows_recent_documents(): void
    {
        $this->actingAsPengurus();

        $older = Document::factory()->create(['judul' => 'Dokumen Lama']);
        $newer = Document::factory()->create(['judul' => 'Dokumen Terbaru']);

        $response = $this->get(route('beranda'))
            ->assertSee('Dokumen Terbaru')
            ->assertSee('Dokumen Lama')
            ->assertSee(route('dokumen.show', $newer))
            ->assertSee(route('dokumen.show', $older));
    }

    public function test_dashboard_empty_state(): void
    {
        $this->actingAsPengurus();

        $this->get(route('beranda'))
            ->assertOk()
            ->assertSee('Belum ada dokumen');
    }

    public function test_dashboard_has_logout_and_navigation_links(): void
    {
        $this->actingAsPengurus();

        $this->get(route('beranda'))
            ->assertSee('Daftar Isi')
            ->assertSee('Keluar');
    }
}