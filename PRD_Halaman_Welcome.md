# PRD — Halaman Welcome (Landing Page Publik)
## Sistem Manajemen & Validasi Dokumen Senat Fakultas Teknik Unmul

**Terkait dokumen lain:** `PRD_Sistem_Senat_FT.md`, `PRD_Desain_UI_Sistem_Senat_FT.md`, `PRD_Daftar_Hadir.md`
**Sifat halaman:** **Publik, tanpa login** — ini satu-satunya halaman "wajah depan" sistem yang bisa dilihat siapa saja yang mengunjungi domain, sebelum masuk ke area internal.

---

## 1. Apakah Halaman Ini Perlu?

**Perlu, tapi dengan porsi secukupnya** — bukan versi panjang ala unmul.ac.id yang berisi berita/agenda/galeri fakultas (itu sudah jadi tugas web utama Unmul, bukan sistem internal Senat FT). Alasan halaman ini tetap dibuat:

1. Siapa pun yang scan QR dari surat fisik atau kebetulan membuka `senat.ft.unmul.ac.id` langsung butuh konteks: ini sistem resmi Senat FT, bukan halaman asing/mencurigakan.
2. Fitur **verifikasi dokumen** (cek nomor surat) bisa ditaruh di sini sebagai layanan publik, bukan hanya lewat scan QR satu-satu.
3. Tamu yang belum login butuh titik masuk yang jelas ke halaman Login, bukan disodori halaman kosong atau redirect langsung tanpa penjelasan.

Yang **tidak** dimasukkan: berita/pengumuman kampus, galeri foto kegiatan, agenda acara, profil pengurus satu-satu — itu di luar tujuan sistem ini (sistem persuratan, bukan company profile).

## 2. Aturan Routing

| Kondisi | Perilaku |
|---|---|
| Pengunjung belum login membuka domain root (`/`) | Tampilkan halaman Welcome ini |
| Pengunjung belum login mencoba akses halaman internal (Beranda, Daftar Isi, dst) | Redirect ke halaman Welcome atau langsung ke Login (lihat W-2 di bawah) |
| Pengguna sudah login membuka domain root (`/`) | Redirect otomatis ke Beranda/Dashboard — tidak perlu melihat Welcome lagi |
| Pengguna sudah login membuka Welcome secara manual | Tetap redirect ke Beranda (Welcome hanya relevan untuk tamu) |

## 3. Struktur Konten & Section

### 3.1 Top Navigation Bar (publik)
Mengikuti struktur top nav yang sudah ditetapkan (lihat PRD Desain UI bagian 6.5), versi publik:
- Kiri: Logo/lambang Unmul + nama sistem ("Sistem Persuratan Senat FT" atau sejenisnya)
- Tengah/kanan: link anchor ke section di halaman ini — "Tentang", "Jenis Dokumen", "Verifikasi Dokumen"
- Paling kanan: tombol **Masuk** (varian Primary/gold) — satu-satunya elemen yang "berbeda warna" di top bar, supaya jelas ini aksi utama untuk pengurus.

### 3.2 Hero Section
- Judul singkat (Space Grotesk, besar): nama sistem
- Sub-judul 1–2 kalimat: apa fungsinya (mis. "Pusat pembuatan dan validasi dokumen resmi Senat Fakultas Teknik Universitas Mulawarman")
- Dua tombol: **Masuk** (Primary, untuk pengurus) dan **Verifikasi Dokumen** (Secondary, scroll ke section 3.5) — bukan hero image besar/ilustrasi dekoratif, cukup teks + garis aksen gold tipis, konsisten dengan prinsip "institusional bukan startup" dari PRD Desain UI.

### 3.3 Tentang Sistem
- 2–3 paragraf pendek menjelaskan masalah & solusi (ringkasan dari latar belakang di PRD utama): dulu manual di Word/Excel, rawan nomor bentrok, sulit dibuktikan keasliannya → sekarang terpusat dengan penomoran otomatis dan QR verifikasi.
- Bahasa untuk publik awam (pengunjung dari luar Senat FT), bukan bahasa teknis developer.

### 3.4 Jenis Dokumen yang Dikelola
- 4 card sejajar (grid 4 kolom desktop / 2 kolom tablet / 1 kolom mobile), masing-masing untuk: Undangan, Berita Acara, Surat Pengantar, Daftar Hadir.
- Tiap card: ikon garis representatif + nama jenis dokumen + 1 kalimat deskripsi singkat.
- Tujuan section ini murni informatif (menjawab "sistem ini isinya apa aja"), bukan tombol aksi (aksi bikin dokumen tetap di dalam area login).

### 3.5 Verifikasi Dokumen (fitur publik, bukan cuma dekorasi)
- Kotak pencarian: input **Nomor Surat**, tombol "Cek Keaslian".
- Hasil pencarian menampilkan status sama seperti hasil scan QR: jenis dokumen, nomor surat, tanggal, status "Dokumen ini terverifikasi asli dari Senat FT" — atau pesan "Nomor surat tidak ditemukan" kalau tidak cocok.
- **Catatan teknis:** ini pada dasarnya mengarahkan ke logika/endpoint yang sama dengan halaman verifikasi hasil scan QR yang sudah disebut di PRD awal — section ini hanya menyediakan cara manual (tanpa perlu scan) untuk mengakses fungsi yang sama.

### 3.6 Cara Kerja Singkat (opsional, jika ingin edukatif ke publik)
Karena ini benar-benar berurutan (bukan numbering dipaksakan), boleh dipakai step singkat:
1. Pengurus membuat dokumen di sistem internal
2. Dokumen difinalisasi → dapat nomor surat resmi + QR code
3. Dicetak dan ditandatangani manual seperti biasa
4. Siapa pun bisa scan QR atau cek nomor surat di sini untuk memastikan keasliannya

### 3.7 Footer
- Nama resmi: Senat Fakultas Teknik, Universitas Mulawarman
- Alamat/lokasi fakultas (teks singkat, bukan peta interaktif — tidak perlu embed Google Maps untuk versi awal)
- Kontak (email/nomor yang relevan, jika ada)
- Baris kecil: "Bukan bagian dari unmul.ac.id — sistem internal Senat Fakultas Teknik" *(opsional, tapi membantu menghindari kesan mengklaim jadi web resmi utama kampus)*

## 4. Requirement Fungsional

| No | Requirement |
|---|---|
| W-1 | Halaman ini dapat diakses tanpa autentikasi (guest, public route) |
| W-2 | Middleware auth: pengguna yang sudah login yang membuka `/` diarahkan otomatis ke Beranda; pengguna yang belum login yang membuka halaman internal manapun diarahkan ke halaman ini (bukan langsung ke form Login) — supaya tamu selalu punya konteks dulu sebelum ketemu form kosong |
| W-3 | Tombol "Masuk" mengarah ke halaman Login (`PRD_Sistem_Senat_FT.md` bagian 6.1) |
| W-4 | Form verifikasi nomor surat di section 3.5 memanggil endpoint publik yang sama dengan yang dipakai halaman hasil scan QR — tidak boleh ada logika ganda yang bisa berbeda hasil |
| W-5 | Semua data yang ditampilkan di section 3.5 hanya info dasar (jenis, nomor, tanggal, status) — **tidak menampilkan isi lengkap dokumen** (nama peserta, isi surat, dsb) demi privasi, walau halaman ini publik |
| W-6 | Navigasi anchor link (Tentang, Jenis Dokumen, Verifikasi Dokumen) melakukan scroll halus ke section terkait dalam satu halaman (bukan pindah halaman terpisah) |

## 5. Gaya Visual (kombinasi Moodle + Unmul)

- **Dari Moodle:** top nav polos tanpa shadow, tipografi fungsional, tidak ada dekorasi berlebihan, section dipisah border tipis bukan warna blok besar.
- **Dari Unmul:** kehadiran identitas institusi lebih terasa dibanding halaman internal — logo lebih menonjol di header, ada garis aksen warna kuning emas/hijau di hero sebagai identitas visual, bahasa lebih formal-institusional di bagian "Tentang Sistem" dan footer.
- Tetap mengikuti token warna, tipografi (Space Grotesk/Inter), dan aturan radius/shadow yang sudah ditetapkan di `PRD_Desain_UI_Sistem_Senat_FT.md` — halaman ini bukan tema terpisah, hanya versi publik dari sistem yang sama.
- Panjang halaman: cukup untuk 6 section di atas (Hero, Tentang, Jenis Dokumen, Verifikasi, Cara Kerja, Footer) — ini akan terasa "cukup berisi" dan tidak polos tanpa perlu meniru panjang halaman unmul.ac.id yang penuh berita/agenda.

## 6. Responsive Mobile

- Top nav → hamburger drawer, tombol "Masuk" tetap terlihat di top bar (tidak disembunyikan ke dalam drawer, karena ini aksi paling penting).
- Hero: judul & sub-judul rata kiri, dua tombol ditumpuk full-width.
- Card jenis dokumen: 1 kolom stack.
- Form verifikasi: input dan tombol ditumpuk vertikal, full-width.
- Section "Cara Kerja": tetap numbered list vertikal (natural di mobile, tidak perlu diubah jadi horizontal steps).

## 7. Kriteria Sukses

- [ ] Pengunjung tanpa login yang membuka domain langsung melihat halaman ini, bukan error atau form login telanjang
- [ ] Pengguna yang sudah login tidak pernah "terjebak" di halaman ini — selalu diarahkan ke Beranda
- [ ] Fitur cek nomor surat di halaman ini menghasilkan output yang identik dengan hasil scan QR untuk dokumen yang sama
- [ ] Halaman tetap rapi & tidak terasa kosong di mobile maupun desktop tanpa harus mengisi section yang tidak relevan (berita/galeri/dll)

## 8. Pertanyaan Terbuka / Perlu Dicek Langsung ke unmul.ac.id

- Bagaimana logo dan lambang resmi Unmul ditampilkan (format, warna resmi, aturan penggunaan) — perlu cek langsung ke unmul.ac.id atau brand guideline kampus, karena saya belum bisa membuka situsnya secara langsung.
- Apakah ada redaksi/istilah resmi yang harus dipakai persis (misal penulisan "Universitas Mulawarman" vs singkatan "Unmul" di konteks formal) — sebaiknya disamakan dengan gaya bahasa resmi di unmul.ac.id.
- Apakah section "Cara Kerja" (3.6) memang diinginkan, atau cukup 5 section tanpa itu — opsional tergantung seberapa edukatif halaman ini ingin terasa untuk pengunjung awam.
