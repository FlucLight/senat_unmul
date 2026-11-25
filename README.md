# Sistem Senat FT Unmul

Aplikasi web internal untuk pengelolaan dokumen resmi **Senat Fakultas Teknik Universitas Mulawarman**. Sistem ini mendukung pembuatan draf, finalisasi dokumen bernomor, pratinjau/unduh PDF, serta verifikasi keaslian dokumen melalui kode/QR.

## Fitur Utama

- **Halaman publik (Welcome)**: informasi sistem, jenis dokumen, alur penerbitan, dan panel **Periksa Keaslian Dokumen**.
- **Verifikasi dokumen**: pemeriksaan status dokumen (Sah/Dibatalkan/Tidak ditemukan) berdasarkan nomor/kode verifikasi, termasuk halaman verifikasi publik via QR.
- **Autentikasi pengurus**: login menggunakan NIP dan kata sandi.
- **Beranda**: ringkasan dokumen, draf belum selesai, dan aktivitas terbaru.
- **Daftar Dokumen**: pencarian/filter dokumen, lihat detail, ubah/hapus draf, unduh/cetak PDF dokumen final.
- **Buat & kelola dokumen**:
  - Undangan
  - Berita Acara
  - Surat Pengantar
  - Daftar Hadir
  - Alur: simpan draf → finalisasi → nomor surat/QR/verifikasi.
- **Pembuat Surat**: buat surat dari template, pratinjau, lihat/unduh PDF.
- **Template Surat**: kelola template/kop/margin/logo/ukuran kertas.
- **Kelola Akun** (role `operator`): tambah akun, aktif/nonaktif, atur ulang kata sandi.

## Peran Pengguna

| Role | Akses |
|---|---|
| `admin` | Akses dokumen dan fitur internal sesuai hak aplikasi. |
| `operator` | Akses dokumen + menu Kelola Akun/Admin untuk manajemen akun pengurus. |

> Tampilan menu Admin/Kelola Akun di navbar ditampilkan untuk role `operator`.

## Teknologi

- **Laravel 12** (PHP 8.3)
- **Blade** + **Tailwind CSS v4**
- **Vite**
- **SQLite** (default lokal)
- **DomPDF** (`barryvdh/laravel-dompdf`) untuk PDF
- **endroid/qr-code** untuk QR verifikasi

## Persyaratan

- PHP 8.3+
- Composer
- Node.js + npm
- Ekstensi PHP umum Laravel (mbstring, openssl, pdo_sqlite/sqlite3, dll.)

## Instalasi Lokal

1. Clone repository dan masuk ke folder proyek.
2. Install dependency PHP:

   ```bash
   composer install
   ```

3. Salin env dan generate app key:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   Di Windows PowerShell, jika `cp` tidak tersedia:

   ```powershell
   Copy-Item .env.example .env
   php artisan key:generate
   ```

4. Siapkan database SQLite (default):

   ```bash
   php artisan migrate
   ```

5. Install dependency frontend dan build asset:

   ```bash
   npm install
   npm run build
   ```

6. Jalankan aplikasi:

   ```bash
   php artisan serve
   ```

   Buka `http://127.0.0.1:8000`.

## Pengembangan

Jalankan Vite dev server saat mengubah tampilan:

```bash
npm run dev
```

Jalankan server Laravel di terminal lain:

```bash
php artisan serve
```

## Testing

```bash
php artisan test --compact
```

## Struktur Fitur (ringkas)

- `routes/web.php` — definisi route web.
- `app/Http/Controllers/` — controller autentikasi, dashboard, dokumen, surat, admin user.
- `app/Models/Document.php` — model dokumen utama.
- `resources/views/` — Blade views (welcome, auth, dashboard, dokumen, surat, admin).
- `resources/css/tokens.css` — token desain "Institutional Editorial".
- `resources/css/app.css` — base style & komponen CSS.
- `tests/Feature/` — feature tests.

## Catatan

- Dokumen **draf** dapat diubah/dihapus; dokumen **final** tidak dapat diubah/dihapus dan memiliki nomor/verifikasi.
- Halaman verifikasi publik hanya menampilkan metadata verifikasi (jenis, nomor, tanggal, status), bukan isi dokumen.
- File/direktori PRD/agent tooling internal diabaikan dari git melalui `.gitignore`.
