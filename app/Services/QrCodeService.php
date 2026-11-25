<?php

namespace App\Services;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class QrCodeService
{
    public function dataUri(string $url): string
    {
        $qr = new QrCode($url, size: 200, margin: 5);

        $writer = new PngWriter();
        $result = $writer->write($qr);

        return $result->getDataUri();
    }
}
