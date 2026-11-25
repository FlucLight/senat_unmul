<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HtmlDocumentFlowTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsPengurus(): static
    {
        return $this->actingAs(User::factory()->create());
    }

    public function test_create_page_shows_html_editor_for_undangan(): void
    {
        $this->actingAsPengurus();

        $this->get(route('dokumen.undangan.create'))
            ->assertOk()
            ->assertSee('Buat Undangan')
            ->assertSee('Data Surat')
            ->assertSee('body_html');
    }

    public function test_can_save_html_draft_and_preview_and_finalize_undangan(): void
    {
        $this->actingAsPengurus();

        $payload = [
            'form_mode' => 'html',
            'action' => 'draft',
            'perihal' => 'Undangan Rapat HTML',
            'tanggal_surat' => '2026-11-25',
            'template_key' => 'default',
            'body_html' => '<p>Isi <span class="ph" data-ph="nomor_surat" contenteditable="false">[Nomor]</span></p><div><span class="ph" data-ph="qr_verifikasi" contenteditable="false">[QR]</span></div>',
        ];

        $response = $this->post(route('dokumen.undangan.store'), $payload);
        $doc = Document::where('judul', 'Undangan Rapat HTML')->firstOrFail();
        $response->assertRedirect(route('dokumen.undangan.edit', $doc));
        $this->assertSame('html', $doc->content['format']);
        $this->assertSame(Document::STATUS_DRAFT, $doc->status);

        $this->put(route('dokumen.undangan.update', $doc), array_merge($payload, ['action' => 'final']))
            ->assertRedirect(route('dokumen.show', $doc));

        $doc->refresh();
        $this->assertSame(Document::STATUS_FINAL, $doc->status);
        $this->assertNotNull($doc->nomor_surat);
        $this->assertStringContainsString('/UND/Senat-FT/', $doc->nomor_surat);
    }

    public function test_legacy_draft_still_opens_legacy_form(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $doc = Document::create([
            'jenis' => 'undangan',
            'judul' => 'Undangan Legacy Draft',
            'status' => Document::STATUS_DRAFT,
            'created_by' => $user->id,
            'content' => ['perihal' => 'Undangan Legacy Draft', 'agenda' => 'Agenda lama'],
        ]);

        $this->get(route('dokumen.undangan.edit', $doc))
            ->assertOk()
            ->assertSee('Agenda')
            ->assertSee('Agenda lama');
    }

    public function test_html_document_is_sanitized_on_save(): void
    {
        $this->actingAsPengurus();

        $this->post(route('dokumen.berita-acara.store'), [
            'form_mode' => 'html',
            'action' => 'draft',
            'perihal' => 'BA Sanitasi',
            'tanggal_surat' => '2026-11-25',
            'template_key' => 'default',
            'body_html' => '<p>Halo</p><script>alert(1)</script><img src=x onerror=alert(1)>',
        ])->assertRedirect();

        $doc = Document::where('judul', 'BA Sanitasi')->firstOrFail();
        $this->assertStringNotContainsString('<script', $doc->content['body_html']);
        $this->assertStringNotContainsString('<img', $doc->content['body_html']);
        $this->assertStringContainsString('Halo', $doc->content['body_html']);
    }
}
