<?php

namespace Tests\Unit;

use App\Services\HtmlSanitizer;
use Tests\TestCase;

class HtmlSanitizerTest extends TestCase
{
    public function test_it_removes_dangerous_tags_and_attributes(): void
    {
        $s = new HtmlSanitizer();
        $html = '<p onclick="alert(1)">Halo</p><script>alert(1)</script><iframe src="x"></iframe><img src=x onerror=alert(1)><a href="javascript:alert(1)">x</a>';
        $out = $s->sanitize($html);

        $this->assertStringNotContainsString('<script', $out);
        $this->assertStringNotContainsString('<iframe', $out);
        $this->assertStringNotContainsString('<img', $out);
        $this->assertStringNotContainsString('onclick', $out);
        $this->assertStringNotContainsString('onerror', $out);
        $this->assertStringNotContainsString('javascript:', $out);
        $this->assertStringContainsString('Halo', $out);
    }

    public function test_it_removes_disallowed_styles_and_keeps_allowed_styles(): void
    {
        $s = new HtmlSanitizer();
        $html = '<p style="text-align:center;color:red;position:absolute;border:1px solid #000;padding:4px">Tes</p>';
        $out = $s->sanitize($html);

        $this->assertStringContainsString('text-align', $out);
        $this->assertStringContainsString('border', $out);
        $this->assertStringContainsString('padding', $out);
        $this->assertStringNotContainsString('color:red', $out);
        $this->assertStringNotContainsString('position', $out);
    }

    public function test_it_keeps_allowed_tokens_and_removes_unknown_data_ph(): void
    {
        $s = new HtmlSanitizer();
        $html = '<p><span class="ph" data-ph="nomor_surat" contenteditable="false">[Nomor]</span> <span class="ph" data-ph="jahat" contenteditable="false">X</span></p>';
        $out = $s->sanitize($html);

        $this->assertStringContainsString('data-ph="nomor_surat"', $out);
        $this->assertStringNotContainsString('data-ph="jahat"', $out);
        $this->assertStringContainsString('[Nomor]', $out);
        $this->assertStringContainsString('X', $out);
    }

    public function test_it_preserves_zigzag_attendance_table(): void
    {
        $s = new HtmlSanitizer();
        $html = '<table data-table="peserta"><thead><tr><th>No</th><th>Nama</th><th colspan="2">Tanda Tangan</th></tr></thead><tbody>'
            . '<tr><td>1</td><td>A</td><td style="width:50%">1.</td><td style="width:50%"></td></tr>'
            . '<tr><td>2</td><td>B</td><td></td><td>2.</td></tr>'
            . '</tbody></table>';
        $out = $s->sanitize($html);

        $this->assertStringContainsString('data-table="peserta"', $out);
        $this->assertStringContainsString('colspan="2"', $out);
        $this->assertStringContainsString('>1.<', $out);
        $this->assertStringContainsString('>2.<', $out);
    }
}
