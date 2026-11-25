<?php

namespace App\Services\Document;

use App\Models\Document;
use App\Services\HtmlSanitizer;
use App\Services\PlaceholderResolver;
use App\Services\QrCodeService;
use Illuminate\Contracts\View\View;

class HtmlDocumentRenderer implements DocumentRendererInterface
{
    public function __construct(
        private readonly HtmlSanitizer $sanitizer,
        private readonly PlaceholderResolver $resolver,
    ) {
    }

    public function preview(Document $document): View|string
    {
        $document->loadMissing('creator');

        return view('dokumen._html_preview', [
            'document' => $document,
            'bodyHtml' => $this->resolvedBody($document, PlaceholderResolver::MODE_DRAFT),
        ]);
    }

    public function print(Document $document): View
    {
        $payload = $this->pdfPayload($document);
        $payload['data']['isPdf'] = false;

        return view($payload['view'], $payload['data']);
    }

    public function pdfPayload(Document $document): array
    {
        $document->loadMissing('creator');
        $qrDataUri = $this->qrDataUri($document);

        return [
            'view' => 'dokumen.html_print',
            'data' => [
                'document' => $document,
                'bodyHtml' => $this->resolvedBody($document, PlaceholderResolver::MODE_FINAL, $qrDataUri),
                'qrDataUri' => $qrDataUri,
                'isPdf' => true,
            ],
        ];
    }

    private function resolvedBody(Document $document, string $mode, ?string $qrDataUri = null): string
    {
        $content = (array) ($document->content ?? []);
        $body = (string) ($content['body_html'] ?? '');
        $body = $this->sanitizer->sanitize($body);

        return $this->resolver->resolve($body, $document, $mode, ['qrDataUri' => $qrDataUri]);
    }

    private function qrDataUri(Document $document): ?string
    {
        $url = $document->qr_verification_url ?: route('dokumen.verifikasi', $document->id);
        try {
            return app(QrCodeService::class)->dataUri($url);
        } catch (\Throwable) {
            return null;
        }
    }
}
