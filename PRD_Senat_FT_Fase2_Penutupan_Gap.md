# PRD — Senat FT Fase 2: Penutupan Gap Fitur & Perapian Navigasi

**Proyek:** Sistem Manajemen & Validasi Dokumen Senat Fakultas Teknik Unmul
**Versi:** 2.0 (Fase 2)
**Status:** Draft untuk implementasi bertahap
**Stack:** Laravel 12 (PHP 8.3), SQLite, Blade, Tailwind CSS v4

---

## 1. Latar Belakang & Tujuan

Fase 1 telah menghasilkan modul Daftar Hadir yang lengkap (form, draft, finalisasi, nomor surat, verifikasi) serta infrastruktur umum (auth NIP, dashboard, daftar dokumen, verifikasi publik). Namun 3 dari 4 jenis dokumen (Undangan, Berita Acara, Surat Pengantar) masih berupa placeholder, form verifikasi landing page belum fungsional, dan navigasi memiliki nama yang mirip sehingga membingungkan.

**Tujuan Fase 2:**
1. Menyelesaikan editor untuk Undangan, Berita Acara, Surat Pengantar agar setara dengan Daftar Hadir.
2. Mengaktifkan verifikasi dokumen dari landing page (publik).
3. Merapikan navigasi agar tidak ada nama yang mirip/membingungkan.
4. Menyamakan alur penomoran surat, PDF, dan QR di semua jenis dokumen.
5. Menambahkan manajemen akun pengurus (admin) minimal.

---

## 2. Target Pengguna & Peran

| Peran | Hak Akses |
|---|---|
| `pengurus` | Membuat, mengedit draft, finalisasi dokumen milik siapa pun (kolaboratif) |
| `admin` (baru) | Semua hak pengurus + kelola akun pengurus (tambah/nonaktif/reset password) |

Login tetap menggunakan NIP. Tidak ada registrasi publik.

---

## 3. Ruang Lingkup

### In-Scope (Fase 2)
- Editor form untuk Undangan, Berita Acara, Surat Pengantar (draft & finalisasi).
- Verifikasi dokumen dari landing page (publik).
- Penomoran surat, PDF, QR yang konsisten untuk semua jenis.
- Perapian label & pengelompokan menu navigasi.
- Panel admin minimal (kelola akun pengurus).
- Template surat (Surat Keputusan) dilengkapi konten real.

### Out-of-Scope (Fase 3 / nanti)
- Editor teks bebas (TipTap) untuk paragraf panjang (Fase 2 pakai form terstruktur).
- Notifikasi email.
- Multi-tenant / multi-fakultas.

---

## 4. User Flow

### 4.1 Buat Dokumen (Undangan / Berita Acara / Surat Pengantar)
```
Beranda/Daftar Dokumen → klik "Buat {Jenis}"
  → Form info dokumen (judul/perihal, tanggal, dll)
  → [Simpan Draft] atau [Finalisasi]
  → (Finalisasi) modal konfirmasi → generate nomor surat + PDF + QR
  → redirect ke halaman detail (show)
```

### 4.2 Verifikasi dari Landing Page (Publik)
```
Pengunjung buka / → section "Verifikasi Dokumen"
  → input nomor surat → submit
  → hasil: jenis, nomor, tanggal, status "Terverifikasi asli" atau "Tidak ditemukan"
```

### 4.3 Kelola Akun (Admin)
```
Admin login → menu "Kelola Akun" (hanya role admin)
  → daftar user, tambah user, nonaktifkan, reset password
```

---

## 5. Spesifikasi Fungsional

### 5.1 Editor Undangan (U-*)
| No | Requirement |
|---|---|
| U-1 | Form berisi: Perihal/Judul, Tanggal Surat, Tanggal & Waktu Acara, Tempat, Pemimpin Rapat, Agenda/Ringkasan, Daftar Undangan (nama + jabatan) |
| U-2 | Validasi server: perihal, tanggal surat, tanggal acara, tempat wajib untuk finalisasi |
| U-3 | Minimal 1 nama undangan untuk finalisasi (draft boleh kosong) |
| U-4 | Draft bisa disimpan kapan saja meski form belum lengkap |
| U-5 | Finalisasi mengunci dokumen, generate nomor surat format `NNN/UND/Senat-FT/ROMAWI/TAHUN`, PDF, QR |
| U-6 | Setelah final, tunduk aturan Daftar Dokumen (tidak bisa edit/hapus) |

### 5.2 Editor Berita Acara (BA-*)
| No | Requirement |
|---|---|
| BA-1 | Form berisi: Perihal/Judul, Tanggal Surat, Tanggal Rapat, Tempat, Pemimpin Rapat, Ringkasan Pembahasan, Keputusan, Daftar Hadir (nama + jabatan) |
| BA-2 | Validasi server: perihal, tanggal surat, tanggal rapat, tempat wajib untuk finalisasi |
| BA-3 | Minimal 1 peserta untuk finalisasi |
| BA-4 | Draft boleh belum lengkap |
| BA-5 | Finalisasi generate nomor surat format `NNN/BA/Senat-FT/ROMAWI/TAHUN`, PDF, QR |
| BA-6 | Setelah final, tunduk aturan Daftar Dokumen |

### 5.3 Editor Surat Pengantar (SP-*)
| No | Requirement |
|---|---|
| SP-1 | Form berisi: Perihal/Judul, Tanggal Surat, Tujuan/Alamat, Isi Ringkas, Penandatangan |
| SP-2 | Validasi server: perihal, tanggal surat, tujuan wajib untuk finalisasi |
| SP-3 | Draft boleh belum lengkap |
| SP-4 | Finalisasi generate nomor surat format `NNN/SP/Senat-FT/ROMAWI/TAHUN`, PDF, QR |
| SP-5 | Setelah final, tunduk aturan Daftar Dokumen |

### 5.4 Verifikasi Landing Page (V-*)
| No | Requirement |
|---|---|
| V-1 | Form input nomor surat di section Verifikasi (landing) memanggil endpoint publik yang sama dengan hasil scan QR |
| V-2 | Hasil menampilkan: jenis dokumen, nomor surat, tanggal, status "Terverifikasi asli dari Senat FT" atau "Nomor surat tidak ditemukan" |
| V-3 | Tidak menampilkan isi lengkap dokumen (privasi) |
| V-4 | Tidak ada logika ganda; satu sumber kebenaran (controller/service verifikasi) |

### 5.5 Navigasi (NAV-*)
| No | Requirement |
|---|---|
| NAV-1 | Ubah label "Daftar Isi" → "Daftar Dokumen" |
| NAV-2 | Kelompokkan "Surat" dan "Template Surat" dalam dropdown "Pembuat Surat" |
| NAV-3 | Penanda aktif jelas pada submenu |
| NAV-4 | Menu jenis dokumen di navbar tetap mengarah ke "Buat baru" (bukan filter) |

### 5.6 Manajemen Akun (ADM-*)
| No | Requirement |
|---|---|
| ADM-1 | Hanya role `admin` yang bisa akses menu "Kelola Akun" |
| ADM-2 | Admin bisa menambah pengurus (NIP, nama, password, role, status aktif) |
| ADM-3 | Admin bisa menonaktifkan/mengaktifkan akun (is_active) |
| ADM-4 | Admin bisa reset password pengurus |
| ADM-5 | Akun nonaktif tidak bisa login |

### 5.7 Konsistensi Penomoran/PDF/QR (NUM-*)
| No | Requirement |
|---|---|
| NUM-1 | Nomor surat per jenis dengan kode singkatan: DH, UND, BA, SP, dan untuk Surat Keputusan: SK (atau SN sesuai existing) |
| NUM-2 | Sequence 3 digit, per jenis, per bulan/tahun (reset tiap bulan) — atau global per jenis; putuskan & konsisten |
| NUM-3 | Format bulan Romawi + tahun 4 digit |
| NUM-4 | PDF disimpan ke storage/public, path disimpan di `pdf_path` |
| NUM-5 | QR code berisi URL verifikasi (`route('dokumen.verifikasi', $id)`) ditempel di PDF (pojok kanan bawah) |
| NUM-6 | `qr_verification_url` diisi saat finalisasi |
| NUM-7 | Semua jenis dokumen final mengikuti alur yang sama (generate nomor, PDF, QR) |

---

## 6. Non-Fungsional

- Desktop-first, responsif mobile.
- Validasi client + server (jangan percaya client saja).
- Auth middleware di semua route internal; guest untuk publik.
- Brute force protection di login (sudah ada).
- Password ter-hash (bcrypt).
- Performa: data kecil, tidak perlu optimasi berat; gunakan pagination.

---

## 7. Kebutuhan Data (Model)

Tabel `documents` (existing) dipakai bersama semua jenis. Kolom `content` (JSON) berbeda per jenis.

### 7.1 Content JSON — Undangan
```json
{
  "perihal": "Undangan Rapat Pleno Senat FT",
  "tanggal_surat": "2026-09-10",
  "tanggal_acara": "2026-09-15",
  "waktu_mulai": "09:00",
  "waktu_selesai": "11:00",
  "tempat": "Ruang Rapat Dekanat FT",
  "pemimpin_rapat": "Ketua Senat FT",
  "agenda": "Pembahasan program kerja",
  "undangan": [
    { "nama": "Nama 1", "jabatan_instansi": "Dosen / Teknik Sipil" }
  ]
}
```

### 7.2 Content JSON — Berita Acara
```json
{
  "perihal": "Berita Acara Rapat Pleno Senat FT",
  "tanggal_surat": "2026-09-10",
  "tanggal_rapat": "2026-09-15",
  "tempat": "Ruang Rapat Dekanat FT",
  "pemimpin_rapat": "Ketua Senat FT",
  "pembahasan": "Ringkasan pembahasan...",
  "keputusan": "Keputusan yang disepakati...",
  "peserta": [
    { "nama": "Nama 1", "jabatan_instansi": "Dosen / Teknik Sipil" }
  ]
}
```

### 7.3 Content JSON — Surat Pengantar
```json
{
  "perihal": "Surat Pengantar Berkas Rekomendasi",
  "tanggal_surat": "2026-09-10",
  "tujuan": "Dekan Fakultas Teknik Unmul",
  "isi_ringkas": "Bersama ini kami sampaikan...",
  "penandatangan": "Ketua Senat Fakultas Teknik"
}
```

### 7.4 Tabel users (existing) + role admin
Kolom existing: `id, nip, name, email, password, role, is_active`. Tambahkan pengelolaan via panel admin.

---

## 8. Aturan Bisnis

- Draft boleh diedit/dihapus; Final tidak boleh diedit/dihapus.
- Final mengunci dokumen dan generate nomor surat + PDF + QR (sekali, tidak bisa diulang).
- Penghapusan final dilarang (menjaga konsistensi nomor surat).
- Verifikasi publik hanya menampilkan info dasar (jenis, nomor, tanggal, status).
- Nomor surat tidak boleh bentrok (sequence otomatis oleh sistem).

---

## 9. Route yang Diusulkan

| Method | URI | Nama Route | Controller@method |
|---|---|---|---|
| GET | / | welcome | WelcomeController@index |
| GET | /verifikasi/nomor | verifikasi.nomor | WelcomeController@verifikasiNomor (publik, query ?nomor_surat=) |
| GET | /login | login | AuthController@showLoginForm |
| POST | /login | login.attempt | AuthController@login |
| POST | /logout | logout | AuthController@logout |
| GET | /beranda | beranda | DashboardController@index |
| GET | /dokumen | dokumen.index | DokumenController@index |
| GET | /dokumen/{document} | dokumen.show | DokumenController@show |
| DELETE | /dokumen/{document} | dokumen.destroy | DokumenController@destroy |
| GET | /dokumen/{document}/pdf | dokumen.pdf | DokumenController@downloadPdf |
| GET | /dokumen/{jenis}/baru | dokumen.baru | DokumenController@createPlaceholder → redirect ke editor spesifik |
| GET | /dokumen/{jenis}/{document}/edit | dokumen.edit | DokumenController@editPlaceholder → redirect |
| GET | /dokumen/undangan/baru | dokumen.undangan.create | UndanganController@create |
| POST | /dokumen/undangan | dokumen.undangan.store | UndanganController@store |
| GET | /dokumen/undangan/{document}/edit | dokumen.undangan.edit | UndanganController@edit |
| PUT | /dokumen/undangan/{document} | dokumen.undangan.update | UndanganController@update |
| (analog untuk berita_acara dan surat_pengantar) | | | |
| GET | /dokumen/daftar_hadir/baru | dokumen.daftar-hadir.create | DaftarHadirController@create (existing) |
| ... (existing daftar hadir routes) | | | |
| GET | /dokumen/{document}/cetak | dokumen.cetak | DaftarHadirController@printView (existing) |
| GET | /verifikasi/{document} | dokumen.verifikasi | DaftarHadirController@verifikasi (generalisasi) |
| GET | /admin/users | admin.users.index | AdminUserController@index |
| POST | /admin/users | admin.users.store | AdminUserController@store |
| PUT | /admin/users/{user} | admin.users.update | AdminUserController@update |
| POST | /admin/users/{user}/reset-password | admin.users.resetPassword | AdminUserController@resetPassword |
| GET | /surat | surat.index | SuratController@index (existing) |
| ... (existing surat routes) | | | |
| GET | /jenis_surat | jenis_surat.index | JenisSuratController@index (existing) |
| ... (existing jenis_surat routes) | | | |

---

## 10. Kriteria Sukses (Definition of Done)

- [ ] Pengurus bisa membuat Undangan, Berita Acara, Surat Pengantar baru, simpan draft, finalisasi.
- [ ] Finalisasi menghasilkan nomor surat + PDF + QR untuk semua 4 jenis dokumen.
- [ ] Dokumen final tidak bisa diedit/dihapus; draft bisa.
- [ ] Verifikasi dari landing page menghasilkan output identik dengan scan QR.
- [ ] Navigasi tidak memiliki nama yang mirip/membingungkan.
- [ ] Admin bisa kelola akun pengurus (tambah, nonaktif, reset password).
- [ ] Template Surat Keputusan dilengkapi konten real (tidak lorem ipsum).
- [ ] Semua test existing tetap pass; tambah test untuk fitur baru.

---

## 11. Prioritas Pengerjaan (Bertahap)

| Tahap | Fokus | Estimasi |
|---|---|---|
| 1 | Perapian navigasi (label, dropdown, verifikasi landing) | 1 hari |
| 2 | Generalisasi service penomoran/PDF/QR (refactor dari Daftar Hadir) | 1–2 hari |
| 3 | Editor Undangan (form + draft/final) | 2 hari |
| 4 | Editor Berita Acara | 2 hari |
| 5 | Editor Surat Pengantar | 1–2 hari |
| 6 | Verifikasi publik dari landing page | 1 hari |
| 7 | Panel admin (kelola akun) | 2 hari |
| 8 | Lengkapi template Surat Keputusan | 1 hari |
| 9 | Test & review | 2 hari |

---

## 12. Pertanyaan Terbuka

1. Apakah penomoran sequence reset per bulan atau global per jenis? (Disarankan per bulan agar nomor tidak terlalu besar.)
2. Apakah PDF menggunakan Browsershot (butuh Node/Puppeteer) atau DomPDF? (Existing Daftar Hadir pakai print view; perlu keputusan.)
3. Apakah admin adalah role terpisah atau pengurus dengan flag `is_admin`? (Disarankan role `admin` terpisah.)
4. Apakah Surat Keputusan (modul Surat) digabung ke modul Dokumen atau tetap terpisah? (Disarankan tetap terpisah tapi konsisten penomoran.)

---

*Akhir dokumen PRD Fase 2.*
