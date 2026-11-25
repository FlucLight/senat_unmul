# Editor & Template Dokumen (Tahap 2)

## Ringkasan

Dokumen baru (Undangan, Berita Acara, Surat Pengantar, Daftar Hadir) menggunakan alur: **pilih template -> edit di TinyMCE -> simpan draf -> pratinjau -> finalisasi**. Model `Document`, route name, permission, penomoran, relasi DB, dan endpoint verifikasi tetap tidak berubah.

## Format Konten

Dokumen baru disimpan di kolom JSON `content`:

```json
{
  "format": "html",
  "schema_version": 2,
  "template_key": "undangan.default",
  "body_html": "...",
  "meta": {
    "perihal": "...",
    "tanggal_surat": "YYYY-MM-DD"
  },
  "generator": {}
}
```

- `format: "html"` menandai dokumen baru. Konten tanpa `format` dianggap **legacy** dan dirender lewat view/kode lama.
- `meta.perihal` disinkronkan ke `documents.judul`.
- `generator` dipakai oleh Daftar Hadir untuk menyimpan data input pembuat ulang dokumen.

## Kompatibilitas Legacy

- Tidak ada migrasi data lama.
- Dokumen legacy tetap tampil, dicetak, dan diunduh lewat renderer lama.
- Draf legacy tetap bisa diedit lewat form lama sampai difinalisasi atau dihapus.
- Jangan hapus kode legacy sebelum semua data lama tidak lagi bergantung padanya.

## Token Placeholder

Token non-editable di dalam `body_html`:

```html
<span class="ph" data-ph="nomor_surat" contenteditable="false">...</span>
```

Token yang diizinkan:
- `nomor_surat`
- `tanggal_surat`
- `perihal`
- `kode_verifikasi`
- `qr_verifikasi`

Perilaku:
- **Draf/pratinjau**: token tampil sebagai label/placeholder (mis. nomor belum terbit, QR muncul setelah finalisasi).
- **Final/PDF**: token diganti nilai asli.
- Jika `qr_verifikasi` dihapus dari editor, renderer otomatis menambahkan blok QR + kode verifikasi di akhir dokumen final/PDF.

## Sanitasi HTML

Sanitasi dilakukan di server saat simpan dan saat render (`App\Services\HtmlSanitizer`, HTMLPurifier). Sanitasi TinyMCE hanya kenyamanan.

Tag yang diizinkan:
`p, br, strong, b, em, i, u, s, sub, sup, span, div, h1-h4, ul, ol, li, blockquote, hr, table, caption, colgroup, col, thead, tbody, tfoot, tr, th, td`

Atribut yang diizinkan:
- `class` dengan nilai terbatas: `ph`, `page-break`
- `data-ph` hanya untuk token yang diizinkan
- `data-table` hanya untuk `peserta`
- `colspan`, `rowspan`
- `style` terbatas

Properti CSS yang diizinkan:
`text-align, width, height, vertical-align, border*, border-collapse, padding*, margin-left, font-weight, font-style, text-decoration, font-size`

Semua atribut `on*`, URL `javascript:`, dan atribut/CSS di luar allowlist dibuang.

## Template

Template disimpan sebagai file repo, bukan DB:

- Registri: `config/document_templates.php`
- Berkas: `resources/document-templates/{type}/{key}.blade.php`

Setiap entri registri berisi: `key`, `label`, `description`, `default`, `path`.

### Menambah Template Baru

1. Tambahkan berkas Blade di `resources/document-templates/{type}/{key}.blade.php`.
2. Daftarkan di `config/document_templates.php` pada tipe dokumen yang sesuai.
3. Pastikan template menyertakan token yang dibutuhkan (`nomor_surat`, `tanggal_surat`, `perihal`, `qr_verifikasi` sesuai kebutuhan).
4. Untuk Daftar Hadir, tabel peserta harus memiliki `data-table="peserta"`.
5. Jangan menambahkan gambar, script, iframe, atau tautan aktif; akan dibuang oleh sanitizer.

## Daftar Hadir

Daftar Hadir memakai mode hybrid:
- Form generator: info acara + peserta + varian template.
- "Buat Dokumen" menghasilkan `body_html` dari generator, lalu membuka editor.
- `body_html` menjadi sumber kebenaran setelah dokumen dibuat.
- "Buat Ulang dari Data" menimpa suntingan manual setelah konfirmasi.

Varian:
1. `zigzag` - No | Nama | Jabatan/Instansi | Tanda Tangan (2 kolom), ganjil kiri/genap kanan.
2. `satu_kolom` - No | Nama | NIP/Identitas | Unit/Instansi | Paraf.
3. `blank` - No | Nama | Unit/Instansi | Tanda Tangan, baris kosong default 20, maks 100.

Helper editor hanya memodifikasi `table[data-table="peserta"]` dan selalu me-renumber baris.

## Editor

Komponen: `<x-rich-editor>` (`resources/views/components/rich-editor.blade.php`).

- TinyMCE 7 self-hosted via npm, dibundle Vite ke `resources/js/rich-editor.js`.
- `license_key: 'gpl'`.
- Toolbar: undo/redo, bold/italic/underline, align, list, indent/outdent, table, Halaman Baru, removeformat.
- Tanpa upload gambar, tanpa tautan, tanpa tampilan kode sumber.
- `noneditable` plugin tidak tersedia di paket npm TinyMCE 7; non-edit token `ph` dijaga dengan `contenteditable=false` + guard event editor.

## Finalisasi & Keamanan

- Draf dapat diedit; dokumen final tidak dapat diedit/dihapus (Policy + cek server).
- Finalisasi HTML memvalidasi perihal, tanggal, dan isi body tidak kosong.
- Daftar Hadir final wajib memiliki `table[data-table="peserta"]`.
- Semua HTML disanitasi ulang saat render/PDF.
