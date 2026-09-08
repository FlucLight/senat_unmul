<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DaftarIsiTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsPengurus(): static
    {
        return $this->actingAs(User::factory()->create());
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/dokumen')->assertRedirect(route('login'));
    }

    public function test_index_lists_documents(): void
    {
        $this->actingAsPengurus();

        $doc = Document::factory()->create(['judul' => 'Rapat Pleno Senat']);

        $this->get(route('dokumen.index'))
            ->assertOk()
            ->assertSee('Rapat Pleno Senat')
            ->assertSee('Draft');
    }

    public function test_search_by_judul(): void
    {
        $this->actingAsPengurus();

        Document::factory()->create(['judul' => 'Undangan Evaluasi Kurikulum']);
        Document::factory()->create(['judul' => 'Berita Acara Pengukuhan']);

        $this->get(route('dokumen.index', ['q' => 'Evaluasi']))
            ->assertOk()
            ->assertSee('Undangan Evaluasi Kurikulum')
            ->assertDontSee('Berita Acara Pengukuhan');
    }

    public function test_search_by_nomor_surat(): void
    {
        $this->actingAsPengurus();

        Document::factory()->final()->create(['judul' => 'Undangan Rapat A', 'nomor_surat' => '001/Und/Senat-FT/IX/2026']);
        Document::factory()->final()->create(['judul' => 'Undangan Rapat B', 'nomor_surat' => '002/Und/Senat-FT/IX/2026']);

        $this->get(route('dokumen.index', ['q' => '001/Und']))
            ->assertSee('Undangan Rapat A')
            ->assertDontSee('Undangan Rapat B');
    }

    public function test_filter_by_jenis(): void
    {
        $this->actingAsPengurus();

        Document::factory()->create(['jenis' => 'undangan', 'judul' => 'Dokumen Undangan']);
        Document::factory()->create(['jenis' => 'berita_acara', 'judul' => 'Dokumen Berita Acara']);

        $this->get(route('dokumen.index', ['jenis' => 'undangan']))
            ->assertSee('Dokumen Undangan')
            ->assertDontSee('Dokumen Berita Acara');
    }

    public function test_filter_by_status(): void
    {
        $this->actingAsPengurus();

        Document::factory()->create(['judul' => 'Draft A']);
        Document::factory()->final()->create(['judul' => 'Final A']);

        $this->get(route('dokumen.index', ['status' => 'final']))
            ->assertSee('Final A')
            ->assertDontSee('Draft A');
    }

    public function test_search_and_jenis_filter_combine_with_and(): void
    {
        $this->actingAsPengurus();

        Document::factory()->create(['jenis' => 'undangan', 'judul' => 'Undangan Rapat Senat']);
        Document::factory()->create(['jenis' => 'surat_pengantar', 'judul' => 'Undangan Kegiatan Bakti Sosial']);

        $this->get(route('dokumen.index', ['q' => 'Undangan', 'jenis' => 'undangan']))
            ->assertSee('Undangan Rapat Senat')
            ->assertDontSee('Undangan Kegiatan Bakti Sosial');
    }

    public function test_filter_by_creator(): void
    {
        $userA = User::factory()->create(['name' => 'Andi Profesor']);
        $userB = User::factory()->create(['name' => 'Budi Dosen']);

        $this->actingAsPengurus();

        Document::factory()->create(['judul' => 'Dokumen Andi', 'created_by' => $userA->id]);
        Document::factory()->create(['judul' => 'Dokumen Budi', 'created_by' => $userB->id]);

        $this->get(route('dokumen.index', ['pembuat' => $userA->id]))
            ->assertSee('Dokumen Andi')
            ->assertDontSee('Dokumen Budi');
    }

    public function test_draft_document_shows_edit_and_delete_but_not_pdf_or_qr(): void
    {
        $this->actingAsPengurus();

        $draft = Document::factory()->create(['judul' => 'Draft Rapat']);

        $response = $this->get(route('dokumen.index'));

        $response->assertSee(route('dokumen.edit', [$draft->jenis, $draft]));
        $response->assertSee('Hapus');
        $response->assertDontSee('Download PDF');
        $response->assertDontSee('copy-qr-link');
    }

    public function test_final_document_shows_pdf_and_copy_actions_but_not_edit(): void
    {
        $this->actingAsPengurus();

        $final = Document::factory()->final()->create([
            'judul' => 'Final Rapat',
            'pdf_path' => 'pdfs/test.pdf',
            'qr_verification_url' => 'https://verifikasi.example.test/abc123',
        ]);

        $response = $this->get(route('dokumen.index'));

        $response->assertSee(route('dokumen.pdf', $final));
        $response->assertSee('copy-qr-link');
        $response->assertDontSee('Edit draft');
    }

    public function test_draft_can_be_deleted(): void
    {
        $this->actingAsPengurus();

        $draft = Document::factory()->create();

        $this->delete(route('dokumen.destroy', $draft))
            ->assertRedirect(route('dokumen.index'));

        $this->assertDatabaseMissing('documents', ['id' => $draft->id]);
    }

    public function test_final_document_cannot_be_deleted(): void
    {
        $this->actingAsPengurus();

        $final = Document::factory()->final()->create();

        $this->delete(route('dokumen.destroy', $final))->assertForbidden();

        $this->assertDatabaseHas('documents', ['id' => $final->id]);
    }

    public function test_show_page_renders(): void
    {
        $this->actingAsPengurus();

        $doc = Document::factory()->create(['judul' => 'Detail Rapat']);

        $this->get(route('dokumen.show', $doc))
            ->assertOk()
            ->assertSee('Detail Rapat')
            ->assertSee('Kembali ke Daftar Isi');
    }

    public function test_pagination_is_used_when_many_documents(): void
    {
        $this->actingAsPengurus();

        Document::factory()->count(20)->create();

        $this->get(route('dokumen.index'))->assertSee('Berikutnya');
    }

    public function test_empty_state_is_shown_when_no_documents(): void
    {
        $this->actingAsPengurus();

        $this->get(route('dokumen.index'))
            ->assertSee('Belum ada dokumen');
    }

    public function test_invalid_filter_jenis_is_rejected(): void
    {
        $this->actingAsPengurus();

        $this->get(route('dokumen.index', ['jenis' => 'bogus']))
            ->assertSessionHasErrors('jenis');
    }
}