# PRD — Desain UI/Visual
## Sistem Manajemen & Validasi Dokumen Senat Fakultas Teknik Unmul

**Referensi brand:** unmul.ac.id (kuning emas, hijau, putih)
**Referensi rasa desain:** portal kampus/LMS resmi (mis. Moodle) — institusional, tenang, bukan produk startup
**Referensi layout konkret:** screenshot dashboard Moodle Sandbox (`sandbox.moodledemo.net`) — top navigation bar horizontal, panel putih dengan border tipis tanpa shadow, dropdown native sederhana, avatar+inisial di kanan atas. Struktur ini dipakai sebagai acuan utama supaya sistem terasa "dibuat manual oleh tim IT kampus", bukan hasil generate AI.
**Scope:** Palet warna, tipografi, komponen, layout untuk halaman Login, Beranda, Daftar Isi

> **Catatan penting:** Karena saya tidak bisa membuka unmul.ac.id secara langsung untuk mengambil kode hex resmi, palet di bawah adalah interpretasi warna kampus (kuning emas + hijau + putih) yang disusun agar seimbang dan tidak menyakitkan mata — bukan hasil color-pick dari situs asli. Sebelum dipakai final, cocokkan dengan style guide resmi Unmul kalau ada, atau ambil hex langsung dari logo/header situs.

---

## 1. Prinsip Desain

1. **Institusional, bukan startup** — tidak ada gradient dekoratif, tidak ada shadow lembut bertumpuk, tidak ada kartu seragam dengan radius sama semua tanpa alasan.
2. **Warna bicara seperlunya** — kuning emas dan hijau dipakai sebagai aksen dan penanda status, bukan mendominasi latar. Latar tetap didominasi putih/netral supaya dokumen dan tabel (isi utama) yang jadi fokus, bukan warna.
3. **Tegas tapi tidak tajam** — border radius kecil (bukan pil, bukan siku 0°) supaya terasa presisi/formal tanpa terasa kaku seperti form pemerintahan tahun 2010-an.
4. **Angka harus rapi** — tabel dokumen dan nomor surat memakai tabular figures (Inter mendukung ini secara native), supaya kolom angka sejajar sempurna.
5. **Tanpa emoji** — status dan aksi memakai ikon garis (line icon, mis. gaya Lucide/Feather: `check-circle`, `file-text`, `qr-code`, `download`, `pencil`, `trash-2`), konsisten satu keluarga ikon di seluruh sistem.
6. **Hindari template chrome generik** — tidak ada label eyebrow ALL-CAPS di atas tiap judul, tidak ada meta text dengan titik tengah (·), tidak ada tanda panah "→" di setiap tombol/link.
7. **Polos itu kekuatan, bukan kekurangan** — mengacu ke referensi layout Moodle: navigasi horizontal di atas (bukan sidebar dekoratif), panel datar dengan border tipis tanpa shadow sama sekali, dropdown native/standar. Kesan "sederhana dan fungsional seperti sistem kampus beneran" justru yang bikin tidak terlihat seperti hasil generate AI.

---

## 2. Palet Warna

### 2.1 Warna Inti (Brand)

| Nama Token | Hex | Peran |
|---|---|---|
| `gold-600` (primer) | `#B8860B` | Aksi utama, aksen brand, elemen aktif/selected |
| `gold-100` | `#F5EBD0` | Latar highlight lembut (mis. baris tabel ter-hover, badge draft) |
| `green-700` (sekunder) | `#2F5D45` | Aksen sekunder, status "final/terverifikasi", elemen navigasi aktif |
| `green-100` | `#E1EDE5` | Latar badge status "Final", latar sukses lembut |
| `white` | `#FFFFFF` | Latar utama card/kontainer |
| `paper` | `#FAF9F6` | Latar halaman (bukan putih polos, sedikit hangat supaya tidak silau) |

Kuning emas dan hijau **tidak dipakai berdampingan dalam proporsi sama** di satu elemen — pilih satu sebagai dominan per konteks (gold untuk aksi/branding, hijau untuk status positif/final) supaya tidak berebut perhatian.

### 2.2 Warna Netral (UI & Teks)

| Nama Token | Hex | Peran |
|---|---|---|
| `ink-900` | `#22221D` | Teks judul/heading |
| `ink-700` | `#4A4A42` | Teks body |
| `ink-400` | `#8B8B80` | Teks sekunder, placeholder, label kolom |
| `border` | `#DEDAD0` | Border kartu, input, garis pemisah tabel |
| `border-strong` | `#C7C2B5` | Border pada elemen fokus/aktif |
| `surface-muted` | `#F1EFE8` | Latar sidebar, latar header tabel |

### 2.3 Warna Semantik (Status Dokumen)

| Status | Warna Latar | Warna Teks | Alasan |
|---|---|---|---|
| Draft | `gold-100` (`#F5EBD0`) | `#8A6512` | Kuning emas = "belum selesai, perlu tindakan" |
| Final | `green-100` (`#E1EDE5`) | `green-700` (`#2F5D45`) | Hijau = "selesai, sah" |
| Gagal/Error | `#F7E5E1` | `#A23B2C` | Merah bata redup, bukan merah menyala — tetap senada dengan palet hangat |

**Prinsip kontras:** semua warna latar status di atas dipilih dengan saturasi rendah (tint 90–95%) supaya badge tidak "berteriak" di antara baris tabel, tapi teksnya tetap warna solid gelap untuk keterbacaan (rasio kontras ≥ 4.5:1).

---

## 3. Tipografi

| Peran | Font | Detail Pemakaian |
|---|---|---|
| Heading/Display | **Space Grotesk** | Judul halaman (H1/H2), nama sistem di header, angka besar di summary card dashboard. Weight 500–600, tidak pernah 700+ (biar tidak terasi terlalu berat/poster) |
| Body/UI | **Inter** | Body text, label form, isi tabel, tombol, navigasi. Aktifkan `font-feature-settings: "tnum"` (tabular numbers) khusus untuk kolom angka/nomor surat/tanggal di tabel |

### Skala Tipografi (disarankan)

| Level | Ukuran | Font | Contoh Pemakaian |
|---|---|---|---|
| H1 | 28px / 1.2 | Space Grotesk, 600 | Judul halaman ("Beranda", "Daftar Dokumen") |
| H2 | 20px / 1.3 | Space Grotesk, 500 | Judul section/card |
| Angka besar | 32px | Space Grotesk, 600 (tabular) | Angka statistik di summary card |
| Body | 14px / 1.5 | Inter, 400 | Isi tabel, deskripsi, form |
| Label | 12px / 1.4 | Inter, 500 | Label input, header kolom tabel |
| Caption | 12px / 1.4 | Inter, 400 | Timestamp, teks bantu di bawah input |

Line length body maksimal ±75 karakter per baris pada teks deskriptif (bukan tabel).

---

## 4. Layout, Spacing & Grid

- **Grid dasar:** unit spacing 4px (4, 8, 12, 16, 24, 32, 48px) — konsisten di seluruh margin/padding, tidak ada angka acak seperti 15px/22px.
- **Struktur halaman (desktop):** **top navigation bar horizontal** (bukan sidebar), tinggi ±60px, latar putih dengan border-bottom 1px `border`. Nama sistem/logo di kiri, menu utama (Beranda, Daftar Isi, Undangan, Berita Acara, Surat Pengantar, Daftar Hadir) sejajar horizontal di sebelahnya, dan di kanan: ikon pencarian, ikon notifikasi (opsional), lalu avatar inisial nama pengguna dalam lingkaran kecil + dropdown (nama, logout). Area konten utama di bawah top bar, max-width ±1200px, padding horizontal 32px.
- **Card/kontainer:** padding internal 20–24px, jarak antar card 16–24px.
- **Alignment:** left-aligned untuk semua teks & tabel (bukan center) — cocok untuk konten administratif/dokumen, bukan halaman marketing.

---

## 5. Border Radius & Elevasi

- **Radius kecil, tidak tajam 0° tapi juga bukan bulat penuh:**
  - Input, tombol, badge: `6px`
  - Card/kontainer besar: `8px`
  - Tidak ada elemen dengan radius > 8px (tidak ada tombol pill/full-round)
- **Elevasi:** hindari shadow lembut abu-abu generik di semua card — meniru referensi Moodle, sebagian besar panel/card **tidak pakai shadow sama sekali**, cukup border 1px solid (`border` token) sebagai pemisah. Shadow tipis (`0 2px 8px rgba(34,34,29,0.08)`) hanya dipakai untuk elemen yang benar-benar mengambang di atas layer lain: dropdown menu, panel notifikasi, dan modal konfirmasi hapus/finalisasi — bukan untuk card statis di halaman.

---

## 6. Komponen UI

### 6.1 Tombol
| Varian | Latar | Teks | Border | Pemakaian |
|---|---|---|---|---|
| Primary | `gold-600` | putih | none | Aksi utama: "Masuk", "Buat Dokumen" |
| Secondary | putih | `ink-900` | `border-strong` 1px | Aksi sekunder: "Batal", "Lihat Detail" |
| Danger (teks/ghost) | transparan | `#A23B2C` | none (underline saat hover) | "Hapus" — sengaja dibuat tidak mencolok sebagai tombol solid supaya tidak mudah ter-klik tanpa sadar |

Radius 6px, padding vertikal 10px horizontal 16px, tanpa efek gradient/glow.

### 6.2 Input & Form
- Border 1px `border`, radius 6px, padding 10–12px.
- Fokus: border berubah ke `gold-600` + ring tipis 2px `gold-100` di sekitarnya (bukan glow biru default browser).
- Label selalu di atas input (bukan placeholder-as-label), sentence case (bukan ALL CAPS).

### 6.3 Badge Status
- Bentuk pill kecil dengan radius 6px (bukan full-round), padding 4px 10px, font 12px medium.
- Warna sesuai token semantik di bagian 2.3.

### 6.4 Tabel (Daftar Isi)
- Header tabel: latar `surface-muted`, teks `ink-700`, uppercase **tidak dipakai** — sentence case biasa cukup tegas dengan weight 500.
- Baris zebra **tidak dipakai** (menghindari kesan "spreadsheet default") — cukup border-bottom 1px `border` antar baris.
- Hover baris: latar berubah ke `gold-100` sangat tipis (opacity rendah) untuk menandai baris yang sedang disorot.
- Kolom angka/tanggal/nomor surat: rata kanan, tabular figures.
- Aksi per baris: ikon-only dengan tooltip (bukan teks tombol berulang "Edit / Edit / Edit" di tiap baris yang bikin tabel ramai).

### 6.5 Top Navigation Bar
- Latar putih polos, border-bottom 1px `border`, tanpa shadow — persis kesan Moodle: navigasi terasa "menempel" bukan "mengambang".
- Struktur kiri→kanan: **Nama Sistem/Logo** → **menu utama sejajar horizontal** (teks saja, tanpa ikon di tiap item — mengikuti pola Moodle "Home / Dashboard / My courses") → spacer → **ikon pencarian** → **avatar inisial pengguna** (lingkaran kecil, latar `green-700`, teks putih, mis. "AH") dengan dropdown berisi nama lengkap, NIP, dan tombol Logout.
- Item menu aktif ditandai underline tipis 2px warna `green-700` di bawah teksnya (bukan latar penuh berwarna) — pola yang sama seperti underline biru pada tab "Dashboard" di referensi Moodle.
- Tidak perlu ikon di tiap item menu utama; ikon garis dipakai secukupnya hanya untuk aksi (pencarian, notifikasi, tabel), bukan navigasi utama — ini yang membuat top bar terasa lebih "polos/manual" dibanding sidebar dengan ikon berwarna-warni.

### 6.6 Summary Card (Dashboard)
- Latar putih, border 1px, radius 8px, tanpa shadow tebal.
- Angka besar (Space Grotesk) di atas, label kecil (Inter, `ink-400`) di bawah — bukan sebaliknya.
- Satu aksen warna kecil per card (garis tipis 3px di sisi kiri card atau ikon kecil), bukan seluruh card diwarnai penuh.

---

## 7. Ikonografi

- Gunakan satu keluarga ikon garis (outline), konsisten stroke width (mis. 1.5–2px), bukan campur filled + outline.
- Ikon dipakai fungsional: status dokumen, aksi tabel, navigasi sidebar — bukan dekorasi di headline atau di antara teks.
- **Tidak ada emoji** di UI, termasuk di pesan sukses/error/empty state.

---

## 8. Layout per Halaman

### 8.1 Login
```
┌─────────────────────────────────────┐
│              (latar `paper`)          │
│        ┌───────────────────┐         │
│        │   Logo/Nama Sistem │        │
│        │   ─────────────    │        │
│        │   Label: NIP        │        │
│        │   [ input ]         │        │
│        │   Label: Password   │        │
│        │   [ input ]         │        │
│        │   [x] Ingat saya    │        │
│        │   [  Masuk (gold) ] │        │
│        │   Lupa password?    │        │
│        │   hubungi admin     │        │
│        └───────────────────┘         │
└─────────────────────────────────────┘
```
- Card login di-center secara vertikal & horizontal, lebar maksimal 380px, latar halaman `paper` polos (tanpa ilustrasi besar/gradient) — kesan formal, fokus ke form.
- Aksen brand cukup lewat garis tipis warna gold di atas card atau logo kampus, tidak perlu background image besar.

### 8.2 Beranda/Dashboard
```
┌───────────────────────────────────────────────────────────┐
│ Senat FT   Beranda  Daftar Isi  Undangan  BA  ...   🔍  AH▾│
├───────────────────────────────────────────────────────────┤
│ Halo, [Nama Pengguna]                                       │
│ ┌────────┬────────┬────────┬────────┐                      │
│ │Undangan│ BA     │ Sr.Peng│ D.Hadir│                      │
│ │  12    │  8     │  5     │  20    │                      │
│ └────────┴────────┴────────┴────────┘                      │
│ Aksi Cepat: [Buat Undangan] [Buat BA] [Sr.Pengantar] [D.Hadir]│
│ Aktivitas Terbaru                                            │
│ ┌───────────────────────────────────────────────────────┐  │
│ │ tabel ringkas 5-10 dokumen terbaru                     │  │
│ └───────────────────────────────────────────────────────┘  │
└───────────────────────────────────────────────────────────┘
```

### 8.3 Daftar Isi
```
┌───────────────────────────────────────────────────────────┐
│ Senat FT   Beranda  Daftar Isi  Undangan  BA  ...   🔍  AH▾│
├───────────────────────────────────────────────────────────┤
│ Daftar Dokumen                                               │
│ [Cari...]  [Jenis▾]  [Status▾]  [Tanggal▾]                  │
│ ┌───────────────────────────────────────────────────────┐  │
│ │ No.Surat | Jenis | Judul | Tgl | Status | Pembuat | Aksi│  │
│ │ ...baris-baris dokumen...                              │  │
│ └───────────────────────────────────────────────────────┘  │
│                        [ pagination ]                        │
└───────────────────────────────────────────────────────────┘
```
*(catatan: `🔍` dan `AH▾` di wireframe cuma penanda posisi ikon pencarian & avatar pengguna — bukan berarti pakai emoji beneran di implementasi, tetap ikon garis + inisial teks.)*

---

## 9. Desain Responsif (Mobile)

- **Breakpoint:** `< 768px` dianggap mobile.
- **Top nav → hamburger drawer:** di mobile, menu horizontal disembunyikan, diganti ikon menu (garis tiga) di kiri top bar yang membuka drawer dari kiri berisi daftar menu vertikal. Logo/nama sistem tetap terlihat di top bar, avatar pengguna tetap di kanan.
- **Summary card dashboard:** grid 4 kolom di desktop → 2 kolom di tablet → 1 kolom (stack) di mobile, tetap dengan padding konsisten (16px).
- **Tabel Daftar Isi di mobile:** tabel lebar tidak dipaksa scroll horizontal sempit — ubah jadi **list card per dokumen**: satu card per baris berisi nomor surat + jenis (baris atas, bold Space Grotesk kecil), judul (body), lalu status badge dan tanggal sejajar di bawahnya, aksi berupa ikon di pojok kanan card.
- **Filter di mobile:** search box tetap di atas, filter jenis/status/tanggal disembunyikan dalam satu tombol "Filter" yang membuka bottom sheet, supaya tidak memenuhi layar sempit dengan 3–4 dropdown sekaligus.
- **Form login di mobile:** card memenuhi lebar layar dengan padding 24px kiri-kanan, tanpa margin card yang terlihat mengambang aneh di layar kecil.
- **Target sentuh:** semua tombol/ikon aksi minimal 40x40px area sentuh di mobile, meski ukuran visual ikon tetap kecil (16–20px), demi kemudahan tap.

---

## 10. Hal yang Dihindari (Anti-pattern)

- ❌ Gradient dekoratif di background/tombol.
- ❌ Shadow abu-abu lembut yang sama ditumpuk di semua card.
- ❌ Radius seragam besar (pil/full-round) di semua elemen tanpa hierarki.
- ❌ Label eyebrow ALL-CAPS di atas tiap judul section.
- ❌ Emoji di status, notifikasi, atau empty state.
- ❌ Warna kuning dan hijau dipakai 50:50 di elemen yang sama (bikin ramai/tidak fokus).
- ❌ Ikon panah "→" ditempel di akhir teks tombol/link secara default.
- ❌ Zebra striping tabel dan warna latar mencolok untuk baris biasa (bukan status).

---

## 11. Langkah Selanjutnya / Verifikasi

- [ ] Cocokkan hex `gold-600` dan `green-700` di atas dengan logo/header resmi unmul.ac.id (saya belum bisa mengambil warna asli situsnya secara langsung).
- [ ] Konfirmasi apakah kampus punya style guide/brand book resmi yang mengikat (kalau ada, palet & font di PRD ini menyesuaikan itu, bukan sebaliknya).
- [ ] Uji kontras warna badge status dengan tool aksesibilitas (WCAG AA) setelah hex final ditentukan.
