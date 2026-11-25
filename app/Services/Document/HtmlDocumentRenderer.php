<?php

namespace App\Services\Document;

use App\Models\Document;
use Illuminate\Contracts\View\View;

class HtmlDocumentRenderer implements DocumentRendererInterface
{
    public function __construct(private readonly LegacyDocumentRenderer $legacy)
    {
    }

    public function preview(Document $document): View|string
    {
        // C1 seam: HtmlRenderer will be implemented in later commits.
        // For now, fall back to legacy to avoid behavior change.
        return $this->legacy->preview($document);
    }

    public function print(Document $document): View
    {
        return $this->legacy->print($document);
    }

    public function pdfPayload(Document $document): array
    {
        return $this->legacy->pdfPayload($document);
    }
}
