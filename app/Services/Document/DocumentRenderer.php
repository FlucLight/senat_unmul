<?php

namespace App\Services\Document;

use App\Models\Document;
use Illuminate\Contracts\View\View;

class DocumentRenderer
{
    public function __construct(
        private readonly LegacyDocumentRenderer $legacy,
        private readonly HtmlDocumentRenderer $html,
    ) {
    }

    public function for(Document $document): DocumentRendererInterface
    {
        $content = (array) ($document->content ?? []);

        return ($content['format'] ?? null) === 'html'
            ? $this->html
            : $this->legacy;
    }

    public function preview(Document $document): View|string
    {
        return $this->for($document)->preview($document);
    }

    public function print(Document $document): View
    {
        return $this->for($document)->print($document);
    }

    public function pdfPayload(Document $document): array
    {
        return $this->for($document)->pdfPayload($document);
    }
}
