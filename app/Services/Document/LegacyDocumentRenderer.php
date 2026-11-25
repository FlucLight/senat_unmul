<?php

namespace App\Services\Document;

use App\Models\Document;
use App\Services\QrCodeService;
use Illuminate\Contracts\View\View;

class LegacyDocumentRenderer implements DocumentRendererInterface
{
    public function preview(Document $document): View|string
    {
        return view('dokumen._preview', ['document' => $document]);
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

        return [
            'view' => 'dokumen.' . $document->jenis . '.print',
            'data' => [
                'document' => $document,
                'content' => (array) $document->content,
                'qrDataUri' => $this->qrDataUri($document),
                'isPdf' => true,
            ],
        ];
    }

    protected function qrDataUri(Document $document): ?string
    {
        $url = $document->qr_verification_url ?: route('dokumen.verifikasi', $document->id);

        try {
            return app(QrCodeService::class)->dataUri($url);
        } catch (\Throwable) {
            return null;
        }
    }
}
