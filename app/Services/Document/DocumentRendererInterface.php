<?php

namespace App\Services\Document;

use App\Models\Document;
use Illuminate\Contracts\View\View;

interface DocumentRendererInterface
{
    public function preview(Document $document): View|string;

    public function print(Document $document): View;

    /**
     * @return array{view: string, data: array<string, mixed>}
     */
    public function pdfPayload(Document $document): array;
}
