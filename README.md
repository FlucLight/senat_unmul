# Sistem Manajemen & Validasi Dokumen Senat FT Unmul

Aplikasi web internal untuk mengelola dokumen resmi **Senat Fakultas Teknik Universitas Mulawarman**. Sistem ini membantu pengurus membuat dokumen dari template, menyunting isi, menyimpan draf, melakukan finalisasi, menerbitkan nomor surat, menghasilkan PDF/QR, dan menyediakan verifikasi keaslian dokumen secara publik.

## Fungsi Utama

### 1. Halaman Publik
- **Welcome (`/`)**: halaman informasi sistem, jenis dokumen, alur penerbitan, dan panel **Periksa Keaslian Dokumen**.
- **Verifikasi Dokumen (`/verifikasi/{document}`)**: halaman publik untuk memeriksa keaslian dokumen. Hanya menampilkan metadata verifikasi (jenis, nomor, tanggal, status), bukan isi dokumen.
- Status verifikasi: **Sah** (final), **Dibatalkan**, atau **Tidak Ditemukan**.

### 2. Autentikasi
- Login menggunakan **NIP** dan **kata sandi**.
- Halaman internal hanya dapat diakses setelah login.

### 3. Beranda
- Ringkasan jumlah dokumen/draf/final.
- Daftar draf yang belum selesai.
- Aktivitas dokumen terbaru.

### 4. Daftar Dokumen
- Menampilkan seluruh dokumen dengan pencarian dan filter (jenis, status, pembuat, rentang tanggal).
- Aksi dokumen:
  - **Draf**: lihat, ubah, hapus.
  - **Final**: lihat, unduh PDF, cetak/pratinjau cetak.

### 5. Pembuatan Dokumen Senat
Jenis dokumen yang didukung:
- **Undangan**
- **Berita Acara**
- **Surat Pengantar**
- **Daftar Hadir**

Alur dokumen baru:
1. **Pilih/buat dari template**. Template default tersedia untuk tiap jenis.
2. **Sunting isi di editor TinyMCE** (self-hosted). Kop surat ditampilkan otomatis (read-only), token nomor/tanggal/perihal/QR dikelola sistem.
3. **Simpan Draf** untuk melanjutkan nanti.
4. **Pratinjau** untuk melihat hasil dokumen.
5. **Finalisasi** untuk menerbitkan nomor surat resmi, QR verifikasi, dan PDF.

Aturan penting:
- Dokumen **draf** dapat diubah dan dihapus.
- Dokumen **final** tidak dapat diubah atau dihapus (dikunci di server/policy).
- Dokumen final mendapat nomor surat otomatis dan URL verifikasi QR.
- Dokumen lama (format legacy) tetap dapat dilihat/diunduh; draf legacy masih dapat diedit lewat form lama sampai difinalisasi/dihapus.

#### Khusus Daftar Hadir
- Form generator: isi informasi acara, peserta, dan pilih varian template.
- Varian: **Zigzag (TTD ganjil/genap)**, **1 Kolom TTD**, **Blank siap cetak**.
- Setelah "Buat Dokumen", isi tabel dapat disunting di editor. Tersedia helper: tambah baris, +10 baris, tempel daftar nama, hapus baris kosong akhir, buat ulang dari data.

### 6. Pembuat Surat (Modul Surat)
- Membuat surat berdasarkan **Template Surat/Jenis Surat**.
- Mengisi nomor surat, tanggal, dan konten surat.
- Pratinjau dan ekspor/unduh PDF.

### 7. Template Surat
- Kelola template surat untuk modul Pembuat Surat.
- Pengaturan meliputi konten template, kop/footer, margin, ukuran kertas, dan logo sesuai fitur yang tersedia.

### 8. Kelola Akun (Operator)
- Menu **Kelola Akun** ditampilkan untuk role `operator`.
- Fungsi: tambah akun pengurus, ubah data/role/status aktif, reset kata sandi.

## Peran Pengguna

| Role | Akses utama |
|---|---|
| `admin` | Akses fitur dokumen internal dan modul terkait sesuai hak aplikasi. |
| `operator` | Akses fitur dokumen internal + Kelola Akun. |

## Cara Menggunakan

### A. Login
1. Buka aplikasi.
2. Klik **Masuk**.
3. Masukkan NIP dan kata sandi.
4. Setelah login, pengguna diarahkan ke Beranda.

### B. Membuat Dokumen (Undangan/Berita Acara/Surat Pengantar)
1. Buka menu **Buat Dokumen** atau masuk dari Daftar Dokumen.
2. Pilih jenis dokumen.
3. Isi **Perihal/Judul** dan **Tanggal Surat**.
4. Pilih template, lalu sunting isi di editor TinyMCE.
5. Klik **Simpan Draf** untuk menyimpan sementara, **Pratinjau** untuk melihat hasil, atau **Finalisasi** untuk menerbitkan dokumen resmi.
6. Setelah final, dokumen mendapat nomor surat, QR, dan dapat diunduh sebagai PDF.

### C. Membuat Daftar Hadir
1. Pilih **Daftar Hadir**.
2. Isi informasi acara dan daftar peserta.
3. Pilih varian template (Zigzag / 1 Kolom TTD / Blank).
4. Klik **Buat Dokumen** untuk menghasilkan dokumen ke editor.
5. Sunting tabel peserta di editor bila perlu.
6. Simpan draf, pratinjau, atau finalisasi.

### D. Verifikasi Dokumen Publik
1. Buka halaman Welcome atau scan QR pada dokumen final.
2. Sistem menampilkan status keaslian dokumen.
3. Jika sah, informasi yang tampil: jenis, nomor, tanggal, status, dan penerbit.

## Teknologi

- **Laravel** (PHP 8.3)
- **Blade** + **Tailwind CSS v4**
- **Vite**
- **SQLite** (default lokal)
- **DomPDF** (`barryvdh/laravel-dompdf`) untuk PDF
- **endroid/qr-code** untuk QR verifikasi
- **TinyMCE 7** (self-hosted, bundel Vite) untuk editor dokumen HTML
- **ezyang/htmlpurifier** untuk sanitasi HTML server-side

## Persyaratan

- PHP 8.3+
- Composer
- Node.js + npm
- Ekstensi PHP umum Laravel (termasuk `mbstring`, `openssl`, `pdo_sqlite/sqlite3`, `gd`/ekstensi gambar sesuai kebutuhan QR/PDF)

## Instalasi Lokal

1. Clone repository dan masuk ke folder proyek.

   ```bash
   git clone <repo-url>
   cd senat_ft
   ```

2. Install dependency PHP.

   ```bash
   composer install
   ```

3. Salin file environment dan generate app key.

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   Windows PowerShell:

   ```powershell
   Copy-Item .env.example .env
   php artisan key:generate
   ```

4. Siapkan database SQLite dan jalankan migrasi.

   ```bash
   php artisan migrate
   ```

   Jika perlu data awal, jalankan seeder sesuai kebutuhan proyek:

   ```bash
   php artisan db:seed
   ```

5. Install dependency frontend dan build asset.

   ```bash
   npm install
   npm run build
   ```

6. Jalankan aplikasi.

   ```bash
   php artisan serve
   ```

   Buka `http://127.0.0.1:8000`.

## Mode Pengembangan

Jalankan Vite saat mengubah CSS/JS/Blade yang memakai asset:

```bash
npm run dev
```

Jalankan Laravel di terminal lain:

```bash
php artisan serve
```

## Testing

Jalankan seluruh test:

```bash
php artisan test --compact
```

Jalankan test tertentu:

```bash
php artisan test --compact tests/Feature/DaftarHadirTest.php
```

## Struktur Penting

- `routes/web.php` - definisi route web.
- `app/Http/Controllers/` - controller auth, dokumen, surat, admin, dashboard.
- `app/Models/Document.php` - model dokumen utama.
- `app/Services/Document/` - renderer dokumen (legacy/html).
- `app/Services/HtmlSanitizer.php` - sanitasi HTML dokumen.
- `app/Services/PlaceholderResolver.php` - resolusi token dokumen/QR.
- `config/document_templates.php` - registri template dokumen HTML.
- `resources/document-templates/` - file template dokumen HTML.
- `resources/views/` - Blade views.
- `resources/js/rich-editor.js` - inisialisasi TinyMCE.
- `docs/EDITOR_TEMPLATE.md` - dokumentasi teknis template/token/sanitasi.
- `tests/Feature` & `tests/Unit` - pengujian otomatis.

## Catatan Keamanan & Perilaku Dokumen

- HTML dokumen disanitasi di server saat disimpan dan saat dirender.
- Token placeholder sensitif (nomor/QR/tanggal/perihal/kode verifikasi) dikendalikan sistem.
- QR/kode verifikasi otomatis ditambahkan pada dokumen final jika token QR dihapus dari isi editor.
- Dokumen final terkunci: tidak dapat diubah/dihapus melalui UI maupun request langsung.
- Halaman verifikasi publik tidak menampilkan isi dokumen.

## Setelah Pull Update

Jika menarik perubahan terbaru dari repo:

```bash
git pull origin main
composer install
npm install
npm run build
php artisan migrate
```
