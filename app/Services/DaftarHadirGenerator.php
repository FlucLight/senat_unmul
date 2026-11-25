<?php

namespace App\Services;

class DaftarHadirGenerator
{
    /**
     * @param  array<string, mixed>  $input
     */
    public function generate(array $input): string
    {
        $variant = $input['variant'] ?? 'zigzag';
        $peserta = array_values(array_filter(array_map(function ($p) {
            return [
                'nama' => trim((string) ($p['nama'] ?? '')),
                'jabatan_instansi' => trim((string) ($p['jabatan_instansi'] ?? '')),
                'identitas' => trim((string) ($p['identitas'] ?? '')),
            ];
        }, (array) ($input['peserta'] ?? [])), fn ($p) => $p['nama'] !== '' || $p['jabatan_instansi'] !== '' || $p['identitas'] !== ''));

        $blankRows = max(1, min(100, (int) ($input['blank_rows'] ?? 20)));

        $info = $this->infoBlock($input, $variant === 'blank');
        $table = match ($variant) {
            'satu_kolom' => $this->tableSatuKolom($peserta),
            'blank' => $this->tableBlank($blankRows),
            default => $this->tableZigzag($peserta),
        };
        $signature = $this->signatureBlock($variant === 'blank');

        return $info . "\n" . $table . "\n" . $signature;
    }

    /**
     * @param  array<string, mixed>  $input
     */
    private function infoBlock(array $input, bool $blank): string
    {
        $acara = $blank ? '..............................................................' : e((string) ($input['nama_acara'] ?? '[Nama acara]'));
        $tanggal = $blank ? '..............................................................' : e($this->formatTanggal($input['tanggal'] ?? null));
        $waktu = $blank ? '..............................................................' : e($this->formatWaktu($input['waktu_mulai'] ?? null, $input['waktu_selesai'] ?? null));
        $tempat = $blank ? '..............................................................' : e((string) ($input['tempat'] ?? '-'));
        $perihal = $blank
            ? '..............................................................'
            : '<span class="ph" data-ph="perihal" contenteditable="false">' . e((string) ($input['nama_acara'] ?? '[Nama acara]')) . '</span>';

        return '<p style="text-align:center; font-weight:bold; font-size:14pt; text-decoration:underline; margin: 0 0 2pt 0;">DAFTAR HADIR</p>'
            . '<p style="text-align:center; margin: 0 0 12pt 0;">Nomor: <span class="ph" data-ph="nomor_surat" contenteditable="false">[Nomor diberikan saat finalisasi]</span></p>'
            . '<table style="width:100%; border-collapse:collapse; margin: 6pt 0 10pt 0;">'
            . '<tr><td style="width:140px; padding:2px 0;">Hari/Tanggal</td><td style="width:12px; padding:2px 0;">:</td><td style="padding:2px 0;">' . $tanggal . '</td></tr>'
            . '<tr><td style="padding:2px 0;">Waktu</td><td style="padding:2px 0;">:</td><td style="padding:2px 0;">' . $waktu . '</td></tr>'
            . '<tr><td style="padding:2px 0;">Tempat</td><td style="padding:2px 0;">:</td><td style="padding:2px 0;">' . $tempat . '</td></tr>'
            . '<tr><td style="padding:2px 0;">Acara</td><td style="padding:2px 0;">:</td><td style="padding:2px 0;">' . $perihal . '</td></tr>'
            . '</table>';
    }

    /**
     * @param  list<array<string,string>>  $peserta
     */
    private function tableZigzag(array $peserta): string
    {
        $rows = '';
        $items = $peserta ?: [['nama' => '', 'jabatan_instansi' => '', 'identitas' => '']];
        foreach (array_values($items) as $i => $p) {
            $n = $i + 1;
            $left = $n % 2 === 1 ? $n . '.' : '';
            $right = $n % 2 === 0 ? $n . '.' : '';
            $rows .= '<tr>'
                . '<td style="text-align:center;">' . $n . '</td>'
                . '<td>' . e($p['nama']) . '</td>'
                . '<td>' . e($p['jabatan_instansi']) . '</td>'
                . '<td style="width:90px;">' . $left . '</td>'
                . '<td style="width:90px;">' . $right . '</td>'
                . '</tr>';
        }

        return '<table data-table="peserta" style="width:100%; border-collapse:collapse;" border="1" cellpadding="5">'
            . '<thead><tr>'
            . '<th style="width:36px; text-align:center;">No</th><th>Nama</th><th>Jabatan/Instansi</th><th colspan="2" style="width:180px; text-align:center;">Tanda Tangan</th>'
            . '</tr></thead><tbody>' . $rows . '</tbody></table>';
    }

    /**
     * @param  list<array<string,string>>  $peserta
     */
    private function tableSatuKolom(array $peserta): string
    {
        $rows = '';
        $items = $peserta ?: [['nama' => '', 'jabatan_instansi' => '', 'identitas' => '']];
        foreach (array_values($items) as $i => $p) {
            $n = $i + 1;
            $rows .= '<tr>'
                . '<td style="text-align:center;">' . $n . '</td>'
                . '<td>' . e($p['nama']) . '</td>'
                . '<td>' . e($p['identitas']) . '</td>'
                . '<td>' . e($p['jabatan_instansi']) . '</td>'
                . '<td></td>'
                . '</tr>';
        }

        return '<table data-table="peserta" style="width:100%; border-collapse:collapse;" border="1" cellpadding="5">'
            . '<thead><tr>'
            . '<th style="width:36px; text-align:center;">No</th><th>Nama</th><th style="width:140px;">NIP/Identitas</th><th>Unit/Instansi</th><th style="width:110px; text-align:center;">Paraf</th>'
            . '</tr></thead><tbody>' . $rows . '</tbody></table>';
    }

    private function tableBlank(int $count): string
    {
        $rows = '';
        for ($i = 1; $i <= $count; $i++) {
            $rows .= '<tr><td style="text-align:center;">' . $i . '</td><td></td><td></td><td></td></tr>';
        }

        return '<table data-table="peserta" style="width:100%; border-collapse:collapse;" border="1" cellpadding="5">'
            . '<thead><tr>'
            . '<th style="width:36px; text-align:center;">No</th><th>Nama</th><th>Unit/Instansi</th><th style="width:160px; text-align:center;">Tanda Tangan</th>'
            . '</tr></thead><tbody>' . $rows . '</tbody></table>';
    }

    private function signatureBlock(bool $blank): string
    {
        $tanggal = $blank ? '................................' : '<span class="ph" data-ph="tanggal_surat" contenteditable="false">[Tanggal surat]</span>';

        return '<table style="width:100%; border-collapse:collapse; margin-top: 24pt;">'
            . '<tr><td style="width:55%; vertical-align:top;"><span class="ph" data-ph="qr_verifikasi" contenteditable="false">[QR verifikasi]</span></td>'
            . '<td style="width:45%; text-align:center; vertical-align:top;">'
            . '<p>Samarinda, ' . $tanggal . '</p>'
            . '<p>[Pimpinan rapat / Ketua Senat]</p>'
            . '<div style="height:72px;"></div>'
            . '<p><strong><u>[Nama penandatangan]</u></strong></p>'
            . '<p>NIP. [NIP]</p>'
            . '</td></tr></table>';
    }

    private function formatTanggal(mixed $value): string
    {
        if (! $value) {
            return '-';
        }
        try {
            return \Carbon\Carbon::parse($value)->locale('id')->translatedFormat('l, d F Y');
        } catch (\Throwable) {
            return (string) $value;
        }
    }

    private function formatWaktu(mixed $mulai, mixed $selesai): string
    {
        $m = trim((string) $mulai);
        $s = trim((string) $selesai);
        if ($m === '' && $s === '') {
            return '-';
        }
        $w = trim($m . ($m && $s ? ' s/d ' : '') . $s);

        return $w . ' WITA';
    }
}
