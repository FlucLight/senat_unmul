<?php

namespace App\Services;

use Illuminate\Support\Carbon;

class SuratRenderer
{
    /**
     * Kode isian yang didukung beserta penggantinya.
     *
     * @param  array<string, string>  $values
     * @return array<string, string>
     */
    protected function replacements(array $values, ?string $logoPath = null): array
    {
        return [
            '[[NAMA_SURAT]]' => $values['nama_surat'] ?? '',
            '[[NOMOR_SURAT]]' => $values['nomor_surat'] ?? '',
            '[[TANGGAL_SURAT]]' => $values['tanggal_surat'] ?? '',
            '[[logo]]' => $this->logoTag($logoPath),
        ];
    }

    /**
     * Render HTML surat dengan mengganti kode isian dan penanda halaman baru.
     *
     * @param  array<string, string>  $values
     */
    public function render(string $template, array $values = [], ?string $logoPath = null): string
    {
        $replacements = $this->replacements($values, $logoPath);

        $html = str_replace(
            array_keys($replacements),
            $replacements,
            $template
        );

        return $this->convertPageBreaks($html);
    }

    /**
     * Pisahkan kop (header) dari isi jika template memakai struktur kop standar.
     * Mengembalikan ['header' => string|null, 'body' => string].
     *
     * @return array{header: ?string, body: string}
     */
    public function splitKop(string $html): array
    {
        // Pola: <table ...>...</table> diikuti <hr ...> sebagai kop surat.
        $pattern = '/^(?P<header>\s*<table\b.*?<\/table>\s*<hr\b[^>]*>\s*)(?P<body>.*)$/is';

        if (preg_match($pattern, $html, $m) === 1) {
            return ['header' => $m['header'], 'body' => $m['body']];
        }

        return ['header' => null, 'body' => $html];
    }

    /**
     * Ambil tabel paling bawah sebagai footer (blok tanda tangan) jika ada.
     * Nomor NIP dikosongkan otomatis.
     *
     * @return array{footer: ?string, body: string}
     */
    public function splitFooter(string $html): array
    {
        // Cari <table> terakhir dalam konten.
        if (preg_match_all('/<table\b.*?<\/table>/is', $html, $matches, PREG_OFFSET_CAPTURE) < 1) {
            return ['footer' => null, 'body' => $html];
        }

        $last = end($matches[0]);
        $footer = $last[0];
        $offset = $last[1];

        // Pastikan ini tabel tanda tangan (bukan tabel konten di tengah).
        // Heuristik: berada di dekat akhir dokumen.
        $after = trim(substr($html, $offset + strlen($footer)));
        $afterStripped = trim(strip_tags(str_replace(['&nbsp;', "\xc2\xa0"], '', $after)));
        if ($afterStripped !== '') {
            return ['footer' => null, 'body' => $html];
        }

        $body = substr($html, 0, $offset).$after;
        $footer = $this->kosongkanNip($footer);

        return ['footer' => $footer, 'body' => $body];
    }

    /**
     * Kosongkan nomor NIP pada blok tanda tangan/footer.
     */
    public function kosongkanNip(string $html): string
    {
        // Ganti "NIP. 123..." atau "NIP : 123..." dengan "NIP. " (nomor dikosongkan).
        return preg_replace('/(NIP[.\s:]*)([0-9][0-9\s.-]*)/iu', '$1', $html) ?? $html;
    }

    /**
     * Ubah komentar <!-- pagebreak --> menjadi elemen pindah halaman.
     */
    public function convertPageBreaks(string $html): string
    {
        return str_replace(
            ['<p><!-- pagebreak --></p>', '<!-- pagebreak -->'],
            '<div class="new-break" style="page-break-after: always;"></div>',
            $html
        );
    }

    /**
     * Format tanggal dalam bahasa Indonesia, misalnya "11 November 2026".
     */
    public function tanggalIndonesia(Carbon $tanggal): string
    {
        return $tanggal->locale('id')->translatedFormat('d F Y');
    }

    /**
     * Tag <img> logo kop surat sebagai data URI.
     */
    protected function logoTag(?string $logoPath = null): string
    {
        $relativePath = $logoPath ?: config('senatitor.logo', 'images/logo.jpeg');
        $path = public_path($relativePath);

        if (! is_file($path)) {
            return '';
        }

        $mime = mime_content_type($path) ?: 'image/jpeg';
        $data = base64_encode((string) file_get_contents($path));

        $maxHeight = 95;
        $maxWidth = 120;
        [$origWidth, $origHeight] = @getimagesize($path) ?: [0, 0];

        if ($origWidth > 0 && $origHeight > 0) {
            $scale = min(1, $maxHeight / $origHeight, $maxWidth / $origWidth);
            $height = max(1, (int) round($origHeight * $scale));
            $width = max(1, (int) round($origWidth * $scale));

            return sprintf(
                '<img src="data:%s;base64,%s" alt="Logo" width="%d" height="%d" style="display:block; margin:0 auto; width:%dpx; height:%dpx;">',
                $mime,
                $data,
                $width,
                $height,
                $width,
                $height
            );
        }

        return sprintf(
            '<img src="data:%s;base64,%s" alt="Logo" style="display:block; margin:0 auto; width:auto; height:%dpx;">',
            $mime,
            $data,
            $maxHeight
        );
    }
}
