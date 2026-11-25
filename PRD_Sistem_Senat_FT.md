# Product Requirements Document (PRD)
## Sistem Manajemen & Validasi Dokumen Senat Fakultas Teknik Unmul

**Domain:** senat.ft.unmul.ac.id
**Versi Dokumen:** 1.0
**Scope PRD ini:** Halaman Login, Beranda/Dashboard, Daftar Isi (Daftar Dokumen)
**Di luar scope PRD ini:** Halaman Undangan, Berita Acara, Surat Pengantar, Daftar Hadir (form/editor spesifik per jenis dokumen — akan dikerjakan terpisah)

---

## 1. Latar Belakang & Masalah

Senat Fakultas Teknik saat ini membuat dokumen resmi (undangan, berita acara, surat pengantar, daftar hadir) secara manual menggunakan file Word/Excel yang tersebar di laptop masing-masing pengurus. Masalah yang timbul:

- **Nomor surat rawan bentrok** karena tidak ada penomoran terpusat.
- **File tersebar**, sulit dicari ulang, rawan hilang saat pergantian pengurus.
- **Tidak ada cara membuktikan keaslian surat** — tanda tangan hanya berupa gambar/scan yang mudah di-crop-paste ke dokumen palsu.

## 2. Tujuan Produk

1. Memusatkan pembuatan dan penyimpanan 4 jenis dokumen resmi Senat FT dalam satu sistem.
2. Menghilangkan konflik nomor surat lewat penomoran otomatis oleh sistem.
3. Menambah lapisan verifikasi keaslian dokumen lewat QR code yang bisa dipindai publik.
4. Mempermudah pengurus mencari kembali dokumen yang pernah dibuat tanpa membongkar folder lama.

## 3. Target Pengguna

| Peran | Jumlah Akun | Keterangan |
|---|---|---|
| Pengurus tetap Senat FT | 8 | Bisa membuat, mengedit draft, dan memfinalisasi dokumen |
| Akun cadangan | 2 | Disiapkan untuk pergantian pengurus, non-aktif sampai dibutuhkan |

- Login menggunakan **NIP**, bukan email, karena NIP adalah identitas resmi yang tidak berubah.
- **Tidak ada registrasi publik** — akun dibuat/dikelola manual oleh admin.
- **Tidak ada fitur "lupa password" otomatis** — jika lupa, admin yang mereset manual. Ini keputusan sadar karena jumlah user sangat kecil (10 akun) sehingga self-service password reset dianggap over-engineering.

## 4. Ruang Lingkup (Scope)

### 4.1 Termasuk dalam PRD ini
- Halaman **Login**
- Halaman **Beranda/Dashboard**
- Halaman **Daftar Isi** (daftar seluruh dokumen yang pernah dibuat, lintas 4 jenis)

### 4.2 Tidak termasuk (dikerjakan pihak lain, disebut hanya sebagai konteks)
- Editor/form untuk membuat Undangan, Berita Acara, Surat Pengantar, Daftar Hadir
- Halaman verifikasi publik (di luar web internal ini)
- Proses generate PDF, penomoran surat, dan penempelan QR (terjadi di halaman-halaman editor tersebut, bukan di 3 halaman ini)

## 5. Alur Pengguna Secara Umum (High-Level Flow)

```
[Login dengan NIP] 
      ↓
[Beranda/Dashboard] → ringkasan & aksi cepat
      ↓
[Daftar Isi] → cari/lihat dokumen lama, atau lanjut ke editor jenis dokumen tertentu (di luar scope ini)
```

---

## 6. Fitur & Spesifikasi Detail

### 6.1 Halaman Login

**Tujuan:** Autentikasi 10 akun tetap tanpa fitur publik yang tidak dibutuhkan.

**Elemen halaman:**
- Input **NIP** (bukan email/username bebas)
- Input **Password**
- Tombol **Masuk**
- Pesan kecil di bawah form, contoh: *"Lupa password? Hubungi admin Senat FT."* (bukan link fungsional, hanya instruksi)
- Tidak ada tombol "Daftar" / registrasi

**Behavior & Aturan:**
| No | Requirement |
|---|---|
| L-1 | Sistem memvalidasi kombinasi NIP + password terhadap tabel `users` |
| L-2 | Jika NIP tidak ditemukan atau password salah, tampilkan pesan generik ("NIP atau password salah") — jangan bedakan pesan error agar tidak bocor informasi akun mana yang valid |
| L-3 | Setelah login sukses, redirect ke Beranda/Dashboard |
| L-4 | Sesi login disimpan (session-based, standar Laravel auth) dengan opsi "Ingat saya" (remember me) |
| L-5 | Batasi percobaan login gagal (misal: lockout sementara setelah 5x gagal berturut-turut) untuk mencegah brute force, mengingat tidak ada 2FA |
| L-6 | Password disimpan ter-hash (bcrypt/Laravel default), tidak pernah plaintext |
| L-7 | Tidak ada halaman/endpoint registrasi yang bisa diakses publik |
| L-8 | Akun cadangan (2 akun) tetap bisa login kapan saja — tidak ada mekanisme "nonaktif otomatis", status aktif/nonaktif diatur manual oleh admin lewat database/panel admin (jika ada) |

**Non-functional:**
- Halaman login harus bisa diakses tanpa autentikasi (guest middleware)
- Redirect otomatis ke Beranda jika user yang sudah login mencoba membuka `/login` lagi

---

### 6.2 Halaman Beranda / Dashboard

**Tujuan:** Titik masuk utama setelah login — memberi gambaran cepat kondisi dokumen dan jalan pintas ke aksi yang paling sering dilakukan.

**Elemen halaman:**

1. **Header sambutan**
   - Menampilkan nama pengguna yang sedang login (dan NIP/role jika relevan)

2. **Ringkasan statistik (summary cards)**
   - Jumlah dokumen per jenis (Undangan, Berita Acara, Surat Pengantar, Daftar Hadir)
   - Jumlah dokumen berstatus **Draft** (belum difinalisasi) — ditonjolkan karena butuh tindak lanjut
   - Jumlah dokumen berstatus **Final** (sudah punya nomor surat & QR)
   - (Opsional) Total dokumen bulan berjalan

3. **Aksi cepat (quick actions)**
   - Tombol/kartu untuk langsung menuju pembuatan dokumen baru per jenis: "Buat Undangan", "Buat Berita Acara", "Buat Surat Pengantar", "Buat Daftar Hadir" (mengarah ke halaman editor masing-masing, di luar scope PRD ini — cukup sediakan link/route-nya)

4. **Aktivitas/dokumen terbaru**
   - Daftar singkat (misal 5–10 item) dokumen yang paling baru dibuat atau diedit
   - Tiap item menampilkan: jenis dokumen, judul/perihal, status, tanggal, dan link ke detail dokumen (mengarah ke Daftar Isi atau halaman detail dokumen)

5. **Navigasi utama (sidebar/navbar)**
   - Link ke: Beranda, Daftar Isi, dan 4 jenis dokumen (Undangan, Berita Acara, Surat Pengantar, Daftar Hadir)
   - Tombol Logout

**Behavior & Aturan:**
| No | Requirement |
|---|---|
| D-1 | Halaman hanya bisa diakses oleh user yang sudah login (auth middleware) |
| D-2 | Statistik dihitung real-time dari tabel dokumen (bisa di-cache singkat jika performa jadi isu, tapi tidak wajib di versi awal) |
| D-3 | Draft yang dibuat oleh user lain tetap terlihat di ringkasan (karena semua pengurus bekerja di sistem yang sama, bukan per-user silo) — kecuali diputuskan sebaliknya oleh tim |
| D-4 | Klik pada item "aktivitas terbaru" mengarah ke halaman detail/edit dokumen yang sesuai |

---

### 6.3 Halaman Daftar Isi (Daftar Dokumen)

**Tujuan:** Satu tempat untuk mencari, menyaring, dan mengakses seluruh dokumen yang pernah dibuat — menggantikan kebiasaan lama "bongkar folder lama".

**Elemen halaman:**

1. **Filter/Pencarian**
   - Search box: cari berdasarkan nomor surat atau judul/perihal
   - Filter jenis dokumen: Semua / Undangan / Berita Acara / Surat Pengantar / Daftar Hadir
   - Filter status: Semua / Draft / Final
   - Filter rentang tanggal (dibuat/difinalisasi)
   - Filter pembuat dokumen (dropdown nama pengurus)

2. **Tabel daftar dokumen**, kolom minimal:
   - Nomor Surat (kosong/"—" jika masih draft, karena nomor baru diberikan saat finalisasi)
   - Jenis Dokumen
   - Judul/Perihal
   - Tanggal (dibuat / tanggal surat)
   - Status (Draft / Final) — tampilkan sebagai badge berwarna
   - Dibuat oleh
   - Aksi

3. **Kolom Aksi per baris**, tergantung status:
   - **Lihat detail** — selalu tersedia
   - **Edit** — hanya untuk dokumen berstatus Draft
   - **Download PDF** — hanya untuk dokumen berstatus Final
   - **Lihat halaman verifikasi / salin link QR** — hanya untuk dokumen Final
   - **Hapus** — hanya untuk dokumen Draft, dengan dialog konfirmasi (dokumen Final tidak boleh dihapus untuk menjaga jejak/nomor surat tetap konsisten)

4. **Pagination** — daftar dokumen kemungkinan akan terus bertambah, wajib pakai pagination (bukan infinite scroll, untuk kemudahan implementasi)

5. **Empty state** — pesan yang jelas ketika belum ada dokumen atau hasil filter kosong

**Behavior & Aturan:**
| No | Requirement |
|---|---|
| DI-1 | Halaman hanya bisa diakses user yang sudah login |
| DI-2 | Data ditampilkan dari seluruh 4 jenis dokumen dalam satu tabel (bukan 4 tabel terpisah), dengan filter jenis sebagai penyaring |
| DI-3 | Dokumen Draft tidak boleh menampilkan tombol Download PDF atau info QR (karena keduanya baru ada setelah finalisasi) |
| DI-4 | Penghapusan dokumen Final tidak diperbolehkan lewat UI ini |
| DI-5 | Pencarian dan filter bekerja secara kombinasi (AND), bukan menggantikan satu sama lain |
| DI-6 | Setiap dokumen final menyimpan referensi ke QR/link verifikasi yang sudah digenerate di proses finalisasi (halaman ini hanya menampilkan, tidak membuat ulang QR) |

---

## 7. Kebutuhan Data (Gambaran Model, untuk konteks 3 halaman ini)

**Tabel `users`**
- `id`, `nip` (unik), `nama`, `password` (hashed), `role` (opsional: pengurus/admin), `is_active`

**Tabel `documents`** (disederhanakan, dipakai bersama oleh 4 jenis dokumen)
- `id`
- `jenis` (enum: `daftar_hadir`, `undangan`, `berita_acara`, `surat_pengantar`)
- `nomor_surat` (nullable, diisi saat finalisasi)
- `judul` / `perihal`
- `status` (enum: `draft`, `final`)
- `created_by` (FK ke `users`)
- `content` (JSON/HTML, isi dokumen — detail struktur ditentukan di PRD editor masing-masing jenis)
- `pdf_path` (nullable, path file PDF hasil generate saat final)
- `qr_verification_url` (nullable, diisi saat final)
- `created_at`, `updated_at`, `finalized_at`

> Catatan: struktur detail kolom `content` per jenis dokumen akan ditentukan di PRD/implementasi halaman editor masing-masing (di luar scope dokumen ini).

---

## 8. Kebutuhan Non-Fungsional

- **Tech stack:** Laravel (backend & routing), Tailwind (styling), auth manual berbasis session (bukan Breeze/Jetstream)
- **Akses tertutup:** seluruh 3 halaman ini (Login sebagai gerbang, Beranda & Daftar Isi setelah login) hanya untuk 10 akun internal — tidak ada akses publik
- **Keamanan:** hashing password, proteksi brute force di login, middleware auth di semua route selain login
- **Kompatibilitas:** desktop-first (karena dipakai pengurus untuk kerja administrasi), tapi tetap responsif untuk akses dari HP jika dibutuhkan sewaktu-waktu
- **Performa:** jumlah user & dokumen relatif kecil (organisasi internal fakultas), sehingga tidak perlu optimasi berat di awal — prioritaskan kesederhanaan dan kebenaran data

## 9. Kriteria Sukses (Definition of Done untuk 3 halaman ini)

- [ ] Pengurus bisa login memakai NIP + password, tidak bisa login tanpa akun terdaftar
- [ ] Setelah login, Beranda menampilkan ringkasan jumlah dokumen per jenis & status secara akurat
- [ ] Quick action di Beranda mengarah ke route pembuatan dokumen yang benar per jenis
- [ ] Daftar Isi menampilkan seluruh dokumen dari 4 jenis, bisa dicari dan difilter sesuai kombinasi kriteria
- [ ] Dokumen Draft hanya bisa diedit/dihapus, dokumen Final hanya bisa dilihat/diunduh, sesuai aturan di atas
- [ ] Tidak ada jalur akses (route) ke 3 halaman ini yang bisa diakses tanpa login

## 10. Asumsi & Pertanyaan Terbuka

- Diasumsikan semua pengurus punya hak akses yang sama (tidak ada role admin vs pengurus biasa yang membatasi fitur) — perlu dikonfirmasi apakah admin butuh menu tambahan (misal kelola user) di Beranda.
- Diasumsikan dokumen Draft bisa dilihat/diedit oleh pengurus manapun (kolaboratif), bukan hanya pembuatnya — perlu dikonfirmasi ke tim jika ternyata harus dibatasi per-pembuat.
- Format penomoran surat (misal: `001/Und/Senat-FT/IX/2026`) belum dibahas di sini karena logikanya ada di proses finalisasi (di luar scope 3 halaman ini), tapi kolom `nomor_surat` di atas mengasumsikan hasilnya berupa string.
