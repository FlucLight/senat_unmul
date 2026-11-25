<?php

namespace Tests\Unit;

use App\Models\Document;
use App\Services\PlaceholderResolver;
use PHPUnit\Framework\TestCase;

class PlaceholderResolverTest extends TestCase
{
    private function makeDocument(array $attrs = [], array $content = []): Document
    {
        $doc = new Document();
        $doc->forceFill(array_merge([
            'jenis' => 'undangan',
            'judul' => 'Judul Uji',
            'status' => Document::STATUS_DRAFT,
            'content' => $content,
        ], $attrs));

        return $doc;
    }

    public function test_draft_mode_shows_draft_labels(): void
    {
        $resolver = new PlaceholderResolver();
        $doc = $this->makeDocument([], [
            'format' => 'html',
            'meta' => ['perihal' => 'Rapat Uji', 'tanggal_surat' => '2026-11-25'],
            'body_html' => '',
        ]);

        $html = '<p><span class="ph" data-ph="nomor_surat" contenteditable="false">X</span></p>'
            . '<p><span class="ph" data-ph="qr_verifikasi" contenteditable="false">X</span></p>';
        $out = $resolver->resolve($html, $doc, PlaceholderResolver::MODE_DRAFT);

        $this->assertStringContainsString('[Nomor diberikan saat finalisasi]', $out);
        $this->assertStringContainsString('QR muncul setelah finalisasi', $out);
    }

    public function test_final_mode_replaces_tokens_with_real_values(): void
    {
        $resolver = new PlaceholderResolver();
        $doc = $this->makeDocument([
            'status' => Document::STATUS_FINAL,
            'nomor_surat' => '001/UND/Senat-FT/XI/2026',
            'qr_verification_url' => 'https://example.test/verifikasi/1',
        ], [
            'format' => 'html',
            'meta' => ['perihal' => 'Rapat Uji', 'tanggal_surat' => '2026-11-25'],
            'body_html' => '',
        ]);

        $html = '<p><span class="ph" data-ph="nomor_surat" contenteditable="false">X</span></p>'
            . '<p><span class="ph" data-ph="perihal" contenteditable="false">X</span></p>'
            . '<p><span class="ph" data-ph="qr_verifikasi" contenteditable="false">X</span></p>';
        $out = $resolver->resolve($html, $doc, PlaceholderResolver::MODE_FINAL, ['qrDataUri' => 'data:image/png;base64,abc']);

        $this->assertStringContainsString('001/UND/Senat-FT/XI/2026', $out);
        $this->assertStringContainsString('Rapat Uji', $out);
        $this->assertStringContainsString('data:image/png;base64,abc', $out);
        $this->assertStringContainsString('https://example.test/verifikasi/1', $out);
    }

    public function test_final_mode_auto_appends_qr_when_token_missing(): void
    {
        $resolver = new PlaceholderResolver();
        $doc = $this->makeDocument([
            'status' => Document::STATUS_FINAL,
            'nomor_surat' => '001/UND/Senat-FT/XI/2026',
            'qr_verification_url' => 'https://example.test/verifikasi/1',
        ], [
            'format' => 'html',
            'meta' => ['perihal' => 'Rapat Uji', 'tanggal_surat' => '2026-11-25'],
            'body_html' => '',
        ]);

        $html = '<p>Isi tanpa QR token</p>';
        $out = $resolver->resolve($html, $doc, PlaceholderResolver::MODE_FINAL, ['qrDataUri' => 'data:image/png;base64,abc']);

        $this->assertStringContainsString('Isi tanpa QR token', $out);
        $this->assertStringContainsString('Dokumen Resmi Terverifikasi', $out);
        $this->assertStringContainsString('data:image/png;base64,abc', $out);
    }
}
