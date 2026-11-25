<?php

namespace App\Services;

use App\Models\Document;
use Carbon\Carbon;
use DOMDocument;
use DOMElement;

class PlaceholderResolver
{
    public const MODE_DRAFT = 'draft';
    public const MODE_FINAL = 'final';

    /**
     * @param  array<string, mixed>  $context
     */
    public function resolve(string $html, Document $document, string $mode = self::MODE_DRAFT, array $context = []): string
    {
        $html = trim($html);
        if ($html === '') {
            return $html;
        }

        $qrDataUri = $context['qrDataUri'] ?? null;
        $values = $this->values($document, $mode, $qrDataUri);

        $dom = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="utf-8" ?><div id="__root">' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = $dom->getElementById('__root');
        if (! $root instanceof DOMElement) {
            return $html;
        }

        $hadQrToken = false;
        $spans = [];
        foreach ($root->getElementsByTagName('span') as $span) {
            if ($span instanceof DOMElement && $span->getAttribute('data-ph') !== '') {
                $spans[] = $span;
            }
        }

        foreach ($spans as $span) {
            $key = $span->getAttribute('data-ph');
            if (! in_array($key, HtmlSanitizer::ALLOWED_TOKENS, true)) {
                continue;
            }
            if ($key === 'qr_verifikasi') {
                $hadQrToken = true;
                $replacement = $this->qrNode($dom, $document, $mode, $qrDataUri);
                $span->parentNode?->replaceChild($replacement, $span);
                continue;
            }

            $replacement = $dom->createElement('span');
            $replacement->setAttribute('class', 'ph');
            $replacement->setAttribute('data-ph', $key);
            $replacement->setAttribute('contenteditable', 'false');
            $replacement->appendChild($dom->createTextNode($values[$key] ?? ''));
            $span->parentNode?->replaceChild($replacement, $span);
        }

        if ($mode === self::MODE_FINAL && ! $hadQrToken) {
            $root->appendChild($this->qrNode($dom, $document, $mode, $qrDataUri));
        }

        $out = '';
        foreach (iterator_to_array($root->childNodes) as $child) {
            $out .= $dom->saveHTML($child);
        }

        return $out;
    }

    /**
     * @return array<string, string>
     */
    private function values(Document $document, string $mode, ?string $qrDataUri): array
    {
        $content = (array) ($document->content ?? []);
        $meta = (array) ($content['meta'] ?? []);

        $tanggal = $meta['tanggal_surat'] ?? $document->created_at?->toDateString();
        $tanggalLabel = '[Tanggal surat]';
        if ($tanggal) {
            try {
                $tanggalLabel = Carbon::parse($tanggal)->locale('id')->translatedFormat('d F Y');
            } catch (\Throwable) {
                $tanggalLabel = (string) $tanggal;
            }
        }

        $nomor = $document->nomor_surat ?: '[Nomor diberikan saat finalisasi]';
        $perihal = $meta['perihal'] ?? $document->judul ?? '[Perihal]';
        $kode = $document->qr_verification_url ?: ($document->nomor_surat ?: '[Kode verifikasi diberikan saat finalisasi]');

        return [
            'nomor_surat' => $nomor,
            'tanggal_surat' => $mode === self::MODE_FINAL ? $tanggalLabel : $tanggalLabel,
            'perihal' => $perihal !== '' ? (string) $perihal : '[Perihal]',
            'kode_verifikasi' => $kode,
        ];
    }

    private function qrNode(DOMDocument $dom, Document $document, string $mode, ?string $qrDataUri): DOMElement
    {
        $wrapper = $dom->createElement('div');
        $wrapper->setAttribute('class', 'ph');
        $wrapper->setAttribute('data-ph', 'qr_verifikasi');
        $wrapper->setAttribute('contenteditable', 'false');

        if ($mode !== self::MODE_FINAL) {
            $box = $dom->createElement('div');
            $box->setAttribute('style', 'border: 1px dashed #b8b0a2; padding: 12px; display: inline-block; font-size: 10pt; color: #635f5a;');
            $box->appendChild($dom->createTextNode('QR muncul setelah finalisasi'));
            $wrapper->appendChild($box);

            return $wrapper;
        }

        $table = $dom->createElement('table');
        $table->setAttribute('style', 'width: 100%; border-collapse: collapse; margin-top: 18px;');
        $tr = $dom->createElement('tr');

        $tdQr = $dom->createElement('td');
        $tdQr->setAttribute('style', 'width: 95px; vertical-align: top;');
        if ($qrDataUri) {
            $img = $dom->createElement('img');
            $img->setAttribute('src', $qrDataUri);
            $img->setAttribute('alt', 'QR Verifikasi');
            $img->setAttribute('style', 'width: 85px; height: 85px;');
            $tdQr->appendChild($img);
        }
        $tr->appendChild($tdQr);

        $tdText = $dom->createElement('td');
        $tdText->setAttribute('style', 'vertical-align: top; font-size: 9pt; color: #333; padding-left: 8px;');
        $strong = $dom->createElement('strong');
        $strong->appendChild($dom->createTextNode('Dokumen Resmi Terverifikasi'));
        $tdText->appendChild($strong);
        $tdText->appendChild($dom->createElement('br'));
        $tdText->appendChild($dom->createTextNode('Pindai QR untuk memeriksa keaslian dokumen.'));
        $tdText->appendChild($dom->createElement('br'));
        $small = $dom->createElement('span');
        $small->setAttribute('style', 'font-size: 8pt; word-break: break-all;');
        $small->appendChild($dom->createTextNode((string) ($document->qr_verification_url ?? '')));
        $tdText->appendChild($small);
        $tr->appendChild($tdText);

        $table->appendChild($tr);
        $wrapper->appendChild($table);

        return $wrapper;
    }
}
