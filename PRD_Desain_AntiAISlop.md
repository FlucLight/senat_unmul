# PRD Desain — Sistem Manajemen & Validasi Dokumen Senat FT Unmul
## Arah visual "Institutional Editorial" (anti-AI-slop)

| | |
|---|---|
| Versi | 1.0 |
| Tanggal | 25 November 2026 |
| Stack | Laravel 12, Blade, Tailwind CSS v4, tanpa framework UI berat |
| Ruang lingkup | Presentasi saja (CSS, Blade component, copy). Tidak mengubah struktur data, route, atau backend. |
| Hubungan dengan PRD fungsional | Menggantikan butir NFR-11 pada `PRD_Senat_FT_Fase2_Penutupan_Gap.md`: pasangan font Space Grotesk + Inter diganti (lihat 3.2). Warna hijau-700 dan emas-600 dipertahankan, tetapi perannya diperketat (lihat 3.1). |

---

## 1. Design Thesis

Sistem ini adalah **arsip kerja sebuah lembaga akademik**, bukan produk yang dijual. Tampilannya harus seperti dokumen resmi yang ditata dengan baik: judul serif yang tenang, teks UI sans yang polos, garis tipis 1 px sebagai pemisah utama, latar kertas hangat, dan satu warna aksi yaitu hijau tua. Emas bukan warna aksi; ia hanya muncul sebagai garis identitas tipis (seperti garis kop surat) di header publik, footer, dan kop dokumen. Hierarki dibangun dari tipografi, jarak, dan penempatan asimetris (judul di kolom kiri, isi di kolom kanan), bukan dari bayangan, gradient, atau dekorasi. Pengguna adalah dosen dan staf yang butuh kejelasan dan kepastian, sehingga setiap layar menjawab tiga hal: *saya di mana, apa statusnya, apa langkah berikutnya*. Hasil yang dituju: orang yang melihat layar ini mengira ia dirancang oleh tim yang memahami persuratan kampus, bukan dihasilkan dari template.

---

## 2. Prinsip Desain & Anti-Pattern

### 2.1 Prinsip

| ID | Prinsip | Wujud konkret |
|---|---|---|
| P-1 | Garis sebelum bayangan | Pemisah memakai border 1 px; shadow hanya untuk elemen melayang (dropdown, modal, toast) |
| P-2 | Satu aksen | Hijau tua untuk semua hal interaktif; emas hanya garis identitas |
| P-3 | Hierarki dari tipografi | Serif untuk judul, sans untuk UI, skala ketat 7 tingkat |
| P-4 | Kertas, bukan layar | Latar `paper` hangat, panel putih, isi dokumen tampil seperti lembar surat |
| P-5 | Padat tetapi lapang | Tabel rapat (baris 44 px) dengan margin luar lapang; angka tabular |
| P-6 | Status terbaca tanpa warna | Setiap status = ikon + teks + warna, tidak pernah warna saja |
| P-7 | Tenang | Tanpa animasi dekoratif; transisi warna maksimal 150 ms |
| P-8 | Asimetris dengan tujuan | Landing dan detail memakai grid 12 kolom dengan judul di kiri dan isi di kanan; tidak semuanya di tengah |
| P-9 | Bahasa baku | Microcopy formal, ringkas, tanpa jargon pemasaran |
| P-10 | Konsisten oleh token | Warna, ukuran, jarak hanya dari token; tidak ada nilai acak di Blade |

### 2.2 Larangan keras dan padanan aturannya

| No | Larangan | Aturan yang dapat diperiksa |
|---|---|---|
| AP-1 | Gradient ungu/biru neon, aurora, mesh | Tidak ada `gradient` di CSS/Blade; tidak ada warna ungu/biru di token |
| AP-2 | Glassmorphism | Tidak ada `backdrop-blur`, `backdrop-filter`, atau latar semi-transparan dengan border putih |
| AP-3 | Emoji sebagai ikon | Tidak ada karakter emoji di Blade/JS/CSS; ikon hanya dari satu set stroke |
| AP-4 | Hero + 3 kartu fitur + screenshot melayang | Landing memakai masthead + panel verifikasi; jenis dokumen berupa tabel, bukan kartu |
| AP-5 | Copy hiperbola | Daftar kata terlarang di 7.4; tinjauan copy sebelum merge |
| AP-6 | `rounded-3xl` di mana-mana, shadow besar | Radius hanya 4/6/8 px; satu token shadow untuk elemen melayang |
| AP-7 | Dark mode otomatis | `color-scheme: light` tetap; tidak ada varian `dark:` |
| AP-8 | Animasi scroll/parallax | Tidak ada animasi berbasis scroll; hanya transisi warna 150 ms |
| AP-9 | Font dekoratif / kombinasi "AI default" | Source Serif 4 + Source Sans 3, keduanya self-host |
| AP-10 | Layout center-everything tanpa hierarki | Teks panjang rata kiri; pemusatan hanya untuk satu elemen tunggal (mis. kode pada panel verifikasi) |

### 2.3 Do dan Don't singkat

| Do | Don't |
|---|---|
| Judul serif, rata kiri, ukuran berbeda jelas dari isi | Judul sans bold besar di tengah dengan teks gradient |
| Panel putih + border 1 px + radius 6 px | Kartu dengan shadow tersebar dan radius besar |
| Badge persegi kecil: ikon + teks + border | Pil neon dengan titik berpendar |
| Tabel dengan garis header dan hover baris halus | Kartu per baris dengan bayangan |
| Ikon Lucide 1,5 px stroke, selalu berpasangan dengan label atau `aria-label` | Emoji, ikon berwarna-warni di lingkaran |
| Teks kosong menjelaskan apa yang bisa dilakukan | Ilustrasi lucu dan teks "Oops!" |

---

## 3. Design Tokens

Semua token berada di satu berkas (`resources/css/tokens.css`) dalam blok `@theme` Tailwind v4. Palet bawaan Tailwind **dikosongkan** agar warna di luar token tidak mungkin dipakai.

### 3.1 Warna

**Aturan peran:** hijau = aksi, tautan, fokus, status aktif. Emas = hanya garis identitas dan kop. Merah dan kuning = hanya status semantik. Netral = sisanya.

| Token | Hex | Peran | Kontras (perkiraan) |
|---|---|---|---|
| `paper` | `#F7F5F0` | Latar halaman | ink di atasnya 15:1 |
| `paper-200` | `#EEEBE3` | Latar header tabel, isian ringan | ink 12:1 |
| `surface` | `#FFFFFF` | Panel, input, tabel | — |
| `ink` | `#1B1F1C` | Teks utama | 16:1 di surface |
| `ink-muted` | `#5B635D` | Teks sekunder, placeholder | 6,2:1 di surface; 5,7:1 di paper |
| `line` | `#DDD9CE` | Garis hairline dekoratif | dekoratif |
| `line-strong` | `#B8B3A5` | Garis header tabel, pemisah kuat | dekoratif |
| `control` | `#85806F` | Border input/select/checkbox | 3,9:1 di surface (syarat 3:1) |
| `green-50` | `#EEF5F0` | Latar item aktif, badge Final | — |
| `green-200` | `#B3D1BD` | Border badge Final | dekoratif |
| `green-600` | `#1F6B45` | Cincin fokus | 5,9:1 di paper |
| `green-700` | `#17573A` | **Aksen utama (`brand`)**: tombol primer, tautan | 8,5:1 dengan teks putih |
| `green-800` | `#124530` | Hover, teks aktif (`brand-hover`) | 10:1 |
| `green-900` | `#0D3223` | Active tombol, latar footer (`brand-active`) | 12,8:1 dengan teks paper |
| `gold-600` | `#B07A1A` | **Hanya** garis identitas (header publik, footer, kop) | dekoratif, tidak untuk teks |
| `gold-700` | `#8A5E10` | Teks kecil bergaris emas bila perlu (eyebrow kop) | 5,7:1 di surface |
| `danger-50` | `#FBEFEE` | Latar badge Dibatalkan, alert galat | — |
| `danger-200` | `#E9C5C2` | Border | dekoratif |
| `danger-700` | `#A12D2D` | Teks galat, tombol Batalkan | 7,2:1 dengan teks putih |
| `warning-50` | `#FFF6E0` | Latar peringatan | — |
| `warning-200` | `#EBD08A` | Border | dekoratif |
| `warning-700` | `#7A4E00` | Teks peringatan | 6,7:1 di warning-50 |

Alias semantik yang dipakai di Blade: `brand` (green-700), `brand-hover` (green-800), `brand-active` (green-900), `brand-subtle` (green-50), `focus` (green-600). Komponen memakai alias, bukan skala mentah.

Angka kontras adalah perhitungan awal; verifikasi akhir dengan alat (axe/Lighthouse) saat QA.

**Status dokumen:**

| Status | Latar | Teks | Border | Ikon |
|---|---|---|---|---|
| Draf | `paper-200` | `ink` | `line-strong` | `pencil-line` |
| Final | `green-50` | `green-800` | `green-200` | `file-check` |
| Dibatalkan | `danger-50` | `danger-700` | `danger-200` | `ban` |

### 3.2 Tipografi

Dua keluarga saja, keduanya di-self-host (paket `@fontsource-variable/source-serif-4` dan `@fontsource-variable/source-sans-3`, subset latin, `font-display: swap`, preload). Pasangan ini satu superfamily rancangan Adobe: serif berkarakter editorial untuk judul, sans humanis yang netral dan memiliki angka tabular untuk UI. Tidak ada font ketiga; kode verifikasi dan kata sandi sementara memakai sans dengan `tabular-nums` dan jarak huruf lebar.

| Token | Font | Ukuran / tinggi baris | Berat | Pemakaian |
|---|---|---|---|---|
| `text-display` | Serif | 36/44 px (mobile 28/36) | 600 | H1 landing saja |
| `text-2xl` | Serif | 28/36 px | 600 | H1 halaman aplikasi |
| `text-xl` | Serif | 22/30 px | 600 | H2 bagian |
| `text-lg` | Sans | 18/28 px | 600 | H3, judul panel |
| `text-base` | Sans | 16/24 px | 400 | Isi, input (16 px agar iOS tidak memperbesar) |
| `text-sm` | Sans | 14/20 px | 400 / 500 | Tabel, label, meta, tombol |
| `text-xs` | Sans | 12/16 px | 500 | Badge, caption, legal footer |

Aturan: hanya berat 400, 500, 600. Panjang baris teks bacaan 65–75 karakter (`max-w-prose`). Angka di tabel, nomor surat, dan statistik memakai `tabular-nums`. Tanpa huruf kapital semua untuk label atau judul.

Fallback: serif `Georgia, "Times New Roman", serif`; sans `system-ui, "Segoe UI", Roboto, sans-serif`.

Pratinjau dokumen (lembar surat di halaman Detail) memakai font yang sama dengan PDF (keputusan Q14 pada PRD fungsional), bukan font UI.

### 3.3 Spacing dan grid

| Item | Nilai |
|---|---|
| Skala jarak yang diizinkan | 4, 8, 12, 16, 24, 32, 48, 64 px (kelas `1, 2, 3, 4, 6, 8, 12, 16`) |
| Pengecualian | Ukuran kontrol dan ikon (h-10, h-11, h-12, size-4, size-5, size-6) bukan jarak |
| Grid | 12 kolom, `gap-6` (24 px) desktop, `gap-4` (16 px) mobile |
| Lebar konten | `max-w-page` = 75rem (1200 px), `mx-auto` |
| Gutter halaman | 16 px (mobile), 24 px (md ke atas) |
| Ritme vertikal | Antar bagian halaman 48 px (aplikasi) dan 64 px (landing); antar grup form 32 px; antar field 16 px |

### 3.4 Radius, border, shadow

| Token | Nilai | Dipakai untuk |
|---|---|---|
| `rounded-sm` | 4 px | Badge, chip, kbd |
| `rounded-md` | 6 px | Tombol, input, panel, tabel, toast |
| `rounded-lg` | 8 px | Modal, dropdown |
| `rounded-full` | — | **Hanya** avatar inisial dan spinner |
| Border standar | 1 px `line` | Panel, tabel, pemisah |
| Border kontrol | 1 px `control` | Input, select, checkbox |
| Garis identitas | 2 px `gold-600` | Bawah header publik dan login, atas footer |
| Garis kop | 3 px `green-800` + 1 px `line` | Judul halaman Detail (rujukan kop surat), opsional |
| `shadow-popover` | `0 4px 12px rgb(27 31 28 / 0.12)` | **Satu-satunya** shadow: dropdown, modal, toast |

### 3.5 Ikonografi

- Satu set: **Lucide**, gaya garis. Disajikan lewat komponen `<x-icon name="...">` (paket Blade Lucide atau SVG inline).
- Stroke 1,5 px; ukuran 16 (inline teks), 20 (tombol, navbar), 24 (empty state). `currentColor`, tidak pernah warna sendiri.
- Ikon dekoratif `aria-hidden="true"`; ikon tanpa teks wajib `aria-label` dan tooltip.
- Tidak ada ikon dalam lingkaran berwarna atau latar gradient.

| Fungsi | Ikon |
|---|---|
| Dokumen / Final | `file-text` / `file-check` |
| Draf / ubah | `pencil-line` / `pencil` |
| Hapus | `trash-2` |
| Batalkan | `ban` |
| Unduh / Cetak | `download` / `printer` |
| Cari / Filter | `search` / `list-filter` |
| Verifikasi / QR | `shield-check` / `qr-code` |
| Duplikat / Salin | `copy` |
| Tambah / Tutup | `plus` / `x` |
| Pengguna / Keluar | `user` / `log-out` |
| Peringatan / Galat / Info | `triangle-alert` / `circle-alert` / `info` |
| Menu / Panah | `menu` / `chevron-down`, `chevron-right`, `arrow-right` |

### 3.6 Gerak

Hanya `transition-colors` 150 ms pada hover/fokus. Tidak ada animasi masuk, scroll, atau parallax. Spinner kecil hanya di dalam tombol saat memproses. Di bawah `prefers-reduced-motion: reduce` semua transisi dan animasi dinonaktifkan; spinner diganti teks "Memproses…".

### 3.7 Berkas token (contoh, bukan implementasi lengkap)

```css
@import "tailwindcss";
@import "@fontsource-variable/source-serif-4";
@import "@fontsource-variable/source-sans-3";

@theme {
  /* Kosongkan bawaan: hanya token di bawah yang boleh dipakai */
  --color-*: initial;
  --font-*: initial;
  --radius-*: initial;
  --shadow-*: initial;
  --text-*: initial;

  --font-sans: "Source Sans 3 Variable", system-ui, "Segoe UI", Roboto, sans-serif;
  --font-serif: "Source Serif 4 Variable", Georgia, "Times New Roman", serif;

  --color-paper: #F7F5F0;      --color-paper-200: #EEEBE3;
  --color-surface: #FFFFFF;
  --color-ink: #1B1F1C;        --color-ink-muted: #5B635D;
  --color-line: #DDD9CE;       --color-line-strong: #B8B3A5;
  --color-control: #85806F;

  --color-green-50: #EEF5F0;   --color-green-200: #B3D1BD;
  --color-green-600: #1F6B45;  --color-green-700: #17573A;
  --color-green-800: #124530;  --color-green-900: #0D3223;
  --color-gold-600: #B07A1A;   --color-gold-700: #8A5E10;
  --color-danger-50: #FBEFEE;  --color-danger-200: #E9C5C2; --color-danger-700: #A12D2D;
  --color-warning-50: #FFF6E0; --color-warning-200: #EBD08A; --color-warning-700: #7A4E00;

  /* Alias semantik */
  --color-brand: var(--color-green-700);
  --color-brand-hover: var(--color-green-800);
  --color-brand-active: var(--color-green-900);
  --color-brand-subtle: var(--color-green-50);
  --color-focus: var(--color-green-600);

  --text-display: 2.25rem;  --text-display--line-height: 2.75rem;
  --text-2xl: 1.75rem;      --text-2xl--line-height: 2.25rem;
  --text-xl: 1.375rem;      --text-xl--line-height: 1.875rem;
  --text-lg: 1.125rem;      --text-lg--line-height: 1.75rem;
  --text-base: 1rem;        --text-base--line-height: 1.5rem;
  --text-sm: 0.875rem;      --text-sm--line-height: 1.25rem;
  --text-xs: 0.75rem;       --text-xs--line-height: 1rem;

  --radius-sm: 4px;  --radius-md: 6px;  --radius-lg: 8px;
  --shadow-popover: 0 4px 12px rgb(27 31 28 / 0.12);
  --container-page: 75rem;
}

@layer base {
  html { color-scheme: light; font-family: var(--font-sans); background: var(--color-paper); color: var(--color-ink); }
  :focus-visible { outline: 2px solid var(--color-focus); outline-offset: 2px; }
  @media (prefers-reduced-motion: reduce) {
    *, ::before, ::after { transition-duration: 0.01ms !important; animation-duration: 0.01ms !important; scroll-behavior: auto !important; }
  }
}
```

---

## 4. Spesifikasi per Halaman

Semua halaman aplikasi memakai kerangka: navbar, area konten `max-w-page`, judul halaman (serif `text-2xl`) dengan breadcrumb di atasnya, lalu isi. Footer aplikasi sederhana (satu baris, teks `text-xs`).

### 4.1 Landing publik

**Tujuan:** menjelaskan lembaga dan sistem secara singkat, dan menjadi pintu verifikasi publik yang paling cepat dijangkau.

```
[ Logo Unmul | Senat Fakultas Teknik / Universitas Mulawarman ]          [ Masuk ]
=================================== garis emas 2 px =============================
Senat Fakultas Teknik                         | Periksa Keaslian Dokumen
Sistem Manajemen dan Validasi Dokumen          | Kode Verifikasi  [______________]
(serif 36, rata kiri)                          | Kode tercetak di bawah QR.
Satu paragraf penjelasan, maks. 65 karakter.   | [ Periksa Dokumen ]
Pengurus Senat? Masuk ke sistem →              | atau pindai QR dengan kamera ponsel
--------------------------------------------------------------------------------
01  Tentang          | paragraf + daftar definisi
--------------------------------------------------------------------------------
02  Jenis Dokumen    | tabel: Jenis | Kegunaan | Contoh nomor
--------------------------------------------------------------------------------
03  Alur Penerbitan  | 4 langkah bernomor, garis pemisah tipis
=================================================================================
[ Footer hijau tua: identitas | tautan | kontak ]
```

| Bagian | Spesifikasi |
|---|---|
| Header | Putih, tinggi 72 px, border bawah 1 px `line` lalu garis emas 2 px. Logo Unmul resmi (aset dari institusi, tidak digambar ulang) + nama dua baris. Satu tombol sekunder "Masuk" di kanan. |
| Masthead | Grid 12 kolom: teks 7 kolom, panel verifikasi 5 kolom. Tanpa gambar, tanpa screenshot. Panel verifikasi adalah panel putih berborder dengan form (GET) yang sama dengan endpoint publik; ini menggantikan section Verifikasi lama, dan anchor `#verifikasi` menuju panel ini. |
| Bagian bernomor | Tiap bagian memakai grid 12 kolom: label dan judul serif di 3 kolom kiri ("01 Tentang"), isi di 9 kolom kanan. Dipisah garis 1 px `line-strong` penuh lebar. |
| Tentang | Teks pendek dari pihak Senat (konten dibutuhkan) + daftar definisi (Lembaga, Fungsi, Pengguna sistem). |
| Jenis Dokumen | Tabel, bukan kartu: Daftar Hadir, Undangan, Berita Acara, Surat Pengantar, Surat Keputusan, dengan kolom kegunaan satu kalimat dan contoh format nomor. Memberi kesan kredibel sekaligus informatif. |
| Alur Penerbitan | Empat langkah vertikal atau horizontal bergaris: Draf, Finalisasi dan penomoran, Pencetakan dengan QR, Verifikasi. Angka serif besar di kiri, teks di kanan. |
| Footer | Latar `green-900`, garis emas 2 px di atas, teks `paper`. Tiga kolom: identitas dan alamat resmi, tautan (Universitas Mulawarman, Fakultas Teknik, Masuk), kontak. Baris hak cipta di bawah. |

### 4.2 Login

**Tujuan:** masuk dengan NIP dan kata sandi tanpa distraksi.

| Bagian | Spesifikasi |
|---|---|
| Layout | Desktop: dua kolom. Kiri (5 kolom, latar `paper`): logo, nama sistem (serif), dua kalimat keterangan "Akses khusus pengurus Senat". Kanan (7 kolom, panel putih berborder, lebar form maks. 400 px, rata kiri): form. Mobile: ditumpuk, form di atas keterangan. |
| Field | NIP (`autocomplete="username"`, `inputmode="numeric"`), Kata sandi (`autocomplete="current-password"`) dengan tombol "Tampilkan" (`aria-pressed`), kotak centang "Ingat saya". |
| Aksi | Tombol primer ukuran besar (h-12) "Masuk" penuh lebar. Tautan teks: "Lupa kata sandi? Minta admin lain untuk mengatur ulang." |
| Galat | Alert inline di atas form (`danger-50`, border kiri 4 px) dan `aria-describedby` pada field. Pesan batas percobaan menampilkan sisa detik. |
| Larangan | Tidak ada ilustrasi, tidak ada sambutan "Selamat datang kembali!", tidak ada login sosial. |

### 4.3 Dashboard

**Tujuan:** menjawab "apa yang perlu saya lakukan sekarang".

```
Breadcrumb: Beranda
Beranda  (serif 28)                      Selamat datang, {Nama}. Selasa, 25 November 2026.
-------------------------------------------------------------------------------
Buat Dokumen                                (serif 22)
[ Daftar Hadir      > ] [ Undangan       > ]
[ Berita Acara      > ] [ Surat Pengantar > ]
-------------------------------------------------------------------------------
Ringkasan   Total 134 | Draf 6 | Final 124 | Dibatalkan 4 | Bulan ini 11
-------------------------------------------------------------------------------
Draf Belum Selesai (tabel ringkas)       |   Aktivitas Terbaru (tabel ringkas)
```

| Bagian | Spesifikasi |
|---|---|
| Buat Dokumen | Grid 2 x 2 (desktop) / 1 kolom (mobile) berisi baris-tautan berborder: ikon 20 px, nama jenis (`text-lg`), satu kalimat kegunaan (`text-sm`, muted), panah di kanan. Seluruh baris dapat diklik (min. 64 px tinggi). Bukan kartu berwarna. |
| Ringkasan | Satu panel dengan lima ukuran sejajar dipisah garis vertikal 1 px. Angka `text-2xl` tabular (sans), label `text-sm` muted. Tidak ada ikon berwarna, tidak ada grafik dekoratif. Item "Draf" dan "Dibatalkan" dapat diklik ke Daftar Dokumen dengan filter terkait. |
| Draf Belum Selesai | Maks. 5 baris: judul, jenis, terakhir diubah, tautan "Lanjutkan". |
| Aktivitas Terbaru | Maks. 8 baris: waktu, pengguna, aksi, dokumen. Tabel ringkas tanpa avatar. |
| Empty state | Jika belum ada dokumen: satu kalimat dan tombol "Buat Dokumen". |

### 4.4 Daftar Dokumen

```
Beranda / Daftar Dokumen
Daftar Dokumen (serif 28)
+- Filter ------------------------------------------------------------------+
| Cari [________]  Jenis [v]  Status [v]  Pembuat [v]  Tanggal [__] s.d. [__]|
| [ Terapkan ]  Atur ulang                                                   |
+---------------------------------------------------------------------------+
Menampilkan 1–20 dari 134 dokumen              Urutkan: Tanggal terbaru [v]
| Nomor | Jenis | Judul | Tanggal | Pembuat | Status | Aksi |
...
Pagination
```

| Bagian | Spesifikasi |
|---|---|
| Filter | Panel putih berborder; grid 12 kolom: cari 4, lainnya 2 kolom masing-masing. Label di atas input, tanpa placeholder sebagai pengganti label. Filter aktif tampil sebagai chip persegi (`rounded-sm`) di bawah panel dengan tombol hapus. Label filter "Jenis Dokumen" tidak menavigasi (sesuai NAV-4). |
| Tabel | Kolom: Nomor (tabular; draf menampilkan "Belum bernomor" muted), Jenis, Judul (satu baris, dipotong dengan elipsis, tautan), Tanggal, Pembuat, Status (badge), Aksi. Header `paper-200` dengan garis bawah `line-strong`. Baris 44 px, hover `paper`, tanpa zebra. |
| Aksi baris | Tombol ikon 32 px (44 px pada layar sentuh) dengan `aria-label` dan tooltip: Lihat; Unduh PDF (Final); Hapus (Draf saja). Dokumen Final/Dibatalkan tidak menampilkan Hapus. |
| Pagination | Teks rentang di kiri; kontrol di kanan. Lihat 5.10. |
| Mobile | Tabel menjadi daftar: tiap dokumen satu blok berborder bawah: judul (tautan), baris meta (jenis, tanggal), nomor, badge status; aksi lewat satu tombol "Aksi" atau tautan "Lihat". |
| Empty | "Belum ada dokumen yang sesuai filter." + tautan "Atur ulang filter". |

### 4.5 Form Dokumen (Daftar Hadir, Undangan, Berita Acara, Surat Pengantar)

```
Beranda / Buat Dokumen / Undangan
Buat Undangan (serif 28)
+-------- Form (8 kolom) ---------------------------+ +-- Panel aksi (4 kolom, sticky) --+
| Informasi Surat   (serif 22 + garis)              | | Status: [Draf]                  |
|  Perihal, Kepada, Lampiran, Tanggal surat          | | Nomor diberikan saat finalisasi |
| Acara                                              | | Terakhir disimpan 10.42 WITA    |
|  Nama, Hari/tanggal, Waktu, Tempat, Agenda (daftar)| | [ Simpan Draf ]  [ Pratinjau ]  |
| Isi                                                | | [ Finalisasi ]                  |
|  Editor teks                                       | +---------------------------------+
| Penandatangan | Tembusan                           |
+----------------------------------------------------+
```

| Bagian | Spesifikasi |
|---|---|
| Struktur | Form dibagi menjadi bagian bernama (Informasi Surat, Acara, Isi, Penandatangan, Tembusan) dengan judul serif `text-xl` dan garis 1 px di bawahnya; jarak 32 px antar bagian, 16 px antar field. Satu kolom isian per baris; dua kolom hanya untuk pasangan pendek (tanggal + waktu). |
| Label | Di atas input, `text-sm` 500. Penanda "(wajib)" berupa teks muted, bukan tanda bintang merah. Teks bantuan di bawah input. |
| Daftar dinamis | Tabel isian (Peserta, Lampiran, Pembahasan, Keputusan, Agenda): kolom No otomatis, tombol hapus baris (ikon `trash-2`, `aria-label`), tombol ghost "Tambah baris" di bawah. Pada mobile, tiap baris menjadi blok bertumpuk dengan judul "Baris 3". |
| Editor | Toolbar di dalam panel berborder: tombol ikon 36 px (44 px sentuh), dikelompokkan dengan pemisah tipis; area teks memakai font dokumen (serif) pada lebar baca ideal. |
| Panel aksi | Sticky di kolom kanan desktop; pada mobile menjadi bilah tetap di bawah dengan dua tombol (Simpan Draf, Finalisasi). Finalisasi satu-satunya tombol primer. |
| Validasi | Ringkasan galat di atas form: "Terdapat 3 isian yang perlu diperbaiki", dengan tautan ke tiap field; fokus dipindah ke ringkasan. Galat per field di bawah input. |
| Modal finalisasi | Judul "Finalisasi Dokumen"; isi: "Setelah difinalisasi, dokumen mendapat nomor resmi dan tidak dapat diubah atau dihapus."; tombol "Batal" dan "Finalisasi". |
| Template Surat | Daftar Template dan form Template mengikuti pola Daftar Dokumen dan Form ini; tidak ada pola baru. |

### 4.6 Detail Dokumen

```
Beranda / Daftar Dokumen / Undangan / 007/UND/Senat-FT/XII/2026
===== garis kop 3 px hijau + 1 px =====
Undangan Rapat Pleno Senat (serif 28)        [ Unduh PDF ] [ Duplikat ] [ Batalkan ]
007/UND/Senat-FT/XII/2026 · Undangan · 1 Desember 2026 · [Final]
-----------------------------------------------------------------------------
+------ Lembar dokumen (8 kolom) ------+  +--- Informasi (4 kolom) ---------+
| (pratinjau serif, kertas putih,      |  | Dibuat oleh, Dibuat pada         |
|  border 1 px, rasio A4)               |  | Difinalisasi oleh/pada           |
|                                       |  | Kode verifikasi  7K4M2-XP9QD [salin]
|                                       |  | [ QR ]  Lihat halaman verifikasi |
+---------------------------------------+  | Riwayat (daftar bergaris)        |
```

| Bagian | Spesifikasi |
|---|---|
| Kepala | Judul serif; baris meta berisi nomor (tabular), jenis, tanggal, badge status. Aksi di kanan: Unduh PDF (primer, hanya Final/Dibatalkan), Duplikat (sekunder), Batalkan Dokumen (ghost merah, hanya Final), Edit dan Hapus (hanya Draf). |
| Lembar dokumen | Panel putih berborder tipis dengan padding lega dan lebar baca ideal; tampil seperti halaman surat (kop sederhana, isi, tanda tangan). Tanpa shadow. Draf: watermark teks "DRAF" (`ink` 8% opasitas, miring) hanya di pratinjau layar. Dibatalkan: watermark "DIBATALKAN" serupa. Watermark tidak ada di PDF final. |
| Banner Dibatalkan | Di atas konten: `danger-50`, border kiri 4 px `danger-700`, ikon `ban`; teks "Dokumen ini dibatalkan pada {tanggal} oleh {nama}. Alasan: {alasan}." |
| Informasi | Daftar definisi (istilah di atas, nilai di bawah). Kode verifikasi dengan tombol salin; SHA-256 dipotong dengan tombol salin (Lanjutan). QR 128 px di atas panel berborder. |
| Riwayat | Daftar vertikal dengan garis kiri 1 px: waktu, pengguna, aksi (dari `audit_logs`). |
| Mobile | Lembar dokumen pindah ke bawah informasi; aksi menjadi tombol penuh lebar bertumpuk atau menu "Aksi". |

### 4.7 Verifikasi publik

Halaman ini dibuka lewat QR di ponsel; mobile-first, ringkas, dan bernada netral. Header publik yang sama dengan landing (logo, garis emas), tanpa navigasi.

```
Hasil Verifikasi (serif 28)
+-------------------------------------------------------+
| [shield-check]  Dokumen Sah                          |
|  Jenis dokumen    Undangan                           |
|  Nomor            007/UND/Senat-FT/XII/2026          |
|  Tanggal          1 Desember 2026                    |
|  Status           Final                              |
|-------------------------------------------------------|
| Pemeriksaan ini hanya memastikan dokumen tercatat      |
| dan berstatus final. Isi dokumen tidak ditampilkan.    |
| Diperiksa pada 25 November 2026, 10.42 WITA.           |
+-------------------------------------------------------+
Periksa dokumen lain  [ Kode Verifikasi ] [ Periksa ]
```

| Kondisi | Tampilan |
|---|---|
| Sah (Final) | Panel putih, border kiri 4 px `green-700`, ikon `shield-check` 24 px berwarna `brand`, judul "Dokumen Sah" (`text-xl` serif). Daftar definisi di bawahnya. Tanpa animasi, tanpa konfeti, tanpa tanda centang raksasa. |
| Dibatalkan | Border kiri `danger-700`, ikon `ban`, judul "Dokumen Dibatalkan". Teks: "Dokumen ini pernah diterbitkan, tetapi telah dibatalkan dan tidak berlaku." Alasan pembatalan tidak ditampilkan. |
| Tidak ditemukan | Border kiri `line-strong` (netral), ikon `circle-alert`, judul "Dokumen Tidak Ditemukan". Teks: "Periksa kembali kode. Kode terdiri dari 10 karakter huruf dan angka." Draf dan kode salah menampilkan teks yang sama. |
| Format kode salah | Galat inline pada input, tanpa pindah halaman. |
| Terlalu banyak percobaan | Alert `warning-50`: "Terlalu banyak pemeriksaan. Coba lagi dalam beberapa saat." |

Kode verifikasi ditampilkan dengan `text-lg`, `tabular-nums`, `tracking-wider`, bergaya `XXXXX-XXXXX`.

### 4.8 Panel Admin

| Halaman | Spesifikasi |
|---|---|
| Daftar Pengguna | Pola yang sama dengan Daftar Dokumen. Kolom: NIP (tabular), Nama, Jabatan, Status (badge Aktif/Nonaktif), Login terakhir, Aksi (Edit, Atur Ulang Kata Sandi, Nonaktifkan). Mobile menjadi daftar. |
| Form Pengguna | Satu kolom, lebar maks. 480 px, rata kiri: NIP, Nama, Jabatan. NIP tidak dapat diubah saat edit (tampil sebagai teks terkunci dengan penjelasan). |
| Kata sandi sementara | Setelah tambah atau reset: panel `warning-50` berborder dengan kata sandi (`text-lg`, `tabular-nums`, `tracking-wider`), tombol "Salin", dan teks "Kata sandi ini hanya ditampilkan sekali. Sampaikan kepada pengguna yang bersangkutan." |
| Konfirmasi | Nonaktifkan dan atur ulang memakai modal yang menyebut nama pengguna dan akibatnya. |
| Ganti Kata Sandi | Form tunggal berpola Login (satu kolom, tiga field, satu tombol). Wajib dilalui bila `must_change_password`; navbar disederhanakan hingga selesai. |
| Riwayat Aktivitas | Opsional: tabel `audit_logs` dengan pola tabel standar. |

---

## 5. Komponen

Semua komponen berupa Blade component (`resources/views/components`) dengan kelas Tailwind yang menggunakan token semantik. Tidak ada `@apply` berantai; kelas ditulis di komponen agar mudah ditelusuri.

### 5.1 Navbar

| Aspek | Spesifikasi |
|---|---|
| Struktur | Putih, tinggi 64 px, border bawah 1 px `line`. Kiri: logo kecil + "Senat FT Unmul" (serif `text-lg`). Tengah: Beranda, Buat Dokumen ▾, Daftar Dokumen, Pembuat Surat ▾, Admin ▾. Kanan: menu pengguna (avatar inisial + nama, berisi Ganti Kata Sandi dan Keluar). |
| Item aktif | Teks `brand-hover` 600 dengan garis bawah 2 px `brand`; induk dropdown ikut aktif bila anaknya aktif; `aria-current="page"`. |
| Dropdown | Panel putih, border 1 px, `rounded-lg`, `shadow-popover`, lebar min. 224 px. Item tinggi 40 px (44 px sentuh); item aktif: border kiri 2 px `brand`, latar `brand-subtle`, `aria-current="page"`. Buka dengan Enter/Spasi, panah atas/bawah berpindah, Esc menutup dan mengembalikan fokus. |
| Mobile | Ikon `menu` membuka panel penuh lebar; dropdown menjadi akordeon; indikator aktif tetap terlihat. |

```html
<header class="border-b border-line bg-surface">
  <div class="mx-auto flex h-16 max-w-page items-center gap-6 px-4 md:px-6">
    <a href="/dashboard" class="flex items-center gap-3">
      <img src="/logo-unmul.svg" alt="" class="size-8">
      <span class="font-serif text-lg font-semibold text-ink">Senat FT Unmul</span>
    </a>
    <nav aria-label="Navigasi utama" class="hidden items-stretch gap-1 self-stretch lg:flex">
      <a href="/dokumen" aria-current="page"
         class="inline-flex items-center border-b-2 border-brand px-3 text-sm font-semibold text-brand-hover">
        Daftar Dokumen
      </a>
      <a href="/dashboard"
         class="inline-flex items-center border-b-2 border-transparent px-3 text-sm font-medium text-ink transition-colors hover:border-line-strong hover:bg-paper">
        Beranda
      </a>
    </nav>
  </div>
</header>
```

### 5.2 Tombol

| Varian | Fungsi | Kelas inti |
|---|---|---|
| Primer | Aksi utama halaman (maks. satu per tampilan) | `bg-brand text-surface hover:bg-brand-hover active:bg-brand-active` |
| Sekunder | Aksi pendukung | `border border-control bg-surface text-ink hover:bg-paper` |
| Ghost | Aksi tersier, tautan aksi | `text-brand hover:bg-brand-subtle` |
| Danger | Aksi merusak | `bg-danger-700 text-surface hover:opacity-90` untuk konfirmasi; ghost merah `text-danger-700 hover:bg-danger-50` untuk pemicu |

Ukuran: sm 32 px (hanya tabel, 44 px pada sentuh), md 40 px (44 px sentuh), lg 48 px (Login, Verifikasi).

```html
<!-- Primer -->
<button class="inline-flex h-10 items-center justify-center gap-2 rounded-md bg-brand px-4 text-sm font-semibold text-surface transition-colors hover:bg-brand-hover active:bg-brand-active disabled:cursor-not-allowed disabled:bg-line-strong disabled:text-ink-muted pointer-coarse:h-11">Finalisasi</button>

<!-- Sekunder -->
<button class="inline-flex h-10 items-center justify-center gap-2 rounded-md border border-control bg-surface px-4 text-sm font-semibold text-ink transition-colors hover:bg-paper disabled:cursor-not-allowed disabled:text-ink-muted pointer-coarse:h-11">Simpan Draf</button>

<!-- Ghost -->
<button class="inline-flex h-10 items-center gap-2 rounded-md px-3 text-sm font-semibold text-brand transition-colors hover:bg-brand-subtle pointer-coarse:h-11">Tambah baris</button>

<!-- Danger (konfirmasi) -->
<button class="inline-flex h-10 items-center justify-center gap-2 rounded-md bg-danger-700 px-4 text-sm font-semibold text-surface transition-colors hover:opacity-90 pointer-coarse:h-11">Batalkan Dokumen</button>
```

Catatan: `pointer-coarse:` tersedia di Tailwind v4.1 ke atas; bila versi lebih lama, gunakan media query `(pointer: coarse)` pada token ukuran kontrol.

### 5.3 Input, select, textarea

```html
<div>
  <label for="nip" class="mb-1 block text-sm font-medium text-ink">
    NIP <span class="font-normal text-ink-muted">(wajib)</span>
  </label>
  <input id="nip" name="nip" inputmode="numeric" autocomplete="username"
         aria-describedby="nip-hint nip-error" aria-invalid="true"
         class="h-10 w-full rounded-md border border-control bg-surface px-3 text-base text-ink placeholder:text-ink-muted
                focus-visible:outline-2 focus-visible:outline-offset-0 focus-visible:outline-focus
                aria-[invalid=true]:border-danger-700 disabled:bg-paper-200 disabled:text-ink-muted pointer-coarse:h-11">
  <p id="nip-hint" class="mt-1 text-sm text-ink-muted">Isi dengan nomor induk pegawai tanpa spasi.</p>
  <p id="nip-error" class="mt-1 flex items-start gap-1 text-sm text-danger-700">
    <x-icon name="circle-alert" class="mt-0.5 size-4 shrink-0"/> NIP wajib diisi.
  </p>
</div>
```

Select memakai kelas yang sama dengan `appearance-none` dan ikon `chevron-down` di kanan. Textarea: `min-h-24 py-2`. Kotak centang/radio: 20 px, border `control`, `accent-brand`, label dapat diklik (area 44 px).

### 5.4 Tabel

```html
<div class="overflow-x-auto rounded-md border border-line bg-surface">
  <table class="w-full text-left text-sm">
    <caption class="sr-only">Daftar dokumen</caption>
    <thead class="border-b border-line-strong bg-paper-200 text-xs font-semibold text-ink-muted">
      <tr>
        <th scope="col" class="px-4 py-3">Nomor</th>
        <th scope="col" class="px-4 py-3">Judul</th>
        <th scope="col" class="px-4 py-3">Status</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-line">
      <tr class="transition-colors hover:bg-paper">
        <td class="px-4 py-3 tabular-nums text-ink">007/UND/Senat-FT/XII/2026</td>
        <td class="px-4 py-3"><a class="font-medium text-brand underline-offset-2 hover:underline" href="#">Undangan Rapat Pleno Senat</a></td>
        <td class="px-4 py-3"><x-badge status="final"/></td>
      </tr>
    </tbody>
  </table>
</div>
```

Aturan: angka dan nomor memakai `tabular-nums`; kolom numerik murni rata kanan; kolom aksi rata kanan; tidak ada zebra; baris 44 px.

### 5.5 Badge status

```html
<!-- Final -->
<span class="inline-flex items-center gap-1 rounded-sm border border-green-200 bg-brand-subtle px-2 py-1 text-xs font-medium text-brand-hover">
  <x-icon name="file-check" class="size-4"/> Final
</span>
<!-- Draf -->
<span class="inline-flex items-center gap-1 rounded-sm border border-line-strong bg-paper-200 px-2 py-1 text-xs font-medium text-ink">
  <x-icon name="pencil-line" class="size-4"/> Draf
</span>
<!-- Dibatalkan -->
<span class="inline-flex items-center gap-1 rounded-sm border border-danger-200 bg-danger-50 px-2 py-1 text-xs font-medium text-danger-700">
  <x-icon name="ban" class="size-4"/> Dibatalkan
</span>
```

Persegi (`rounded-sm`), bukan pil; tidak ada titik berpendar; selalu ada ikon dan teks.

### 5.6 Panel (pengganti "card")

```html
<section class="rounded-md border border-line bg-surface p-6">
  <h2 class="font-serif text-xl font-semibold text-ink">Informasi Dokumen</h2>
  <dl class="mt-4 grid grid-cols-1 gap-4 text-sm">
    <div><dt class="text-ink-muted">Dibuat oleh</dt><dd class="mt-1 text-ink">Nama Pengguna</dd></div>
  </dl>
</section>
```

Tanpa shadow, tanpa hover-lift. Panel yang dapat diklik (baris Buat Dokumen) hanya berubah `bg-paper` saat hover.

### 5.7 Modal

```html
<dialog aria-labelledby="m-title" class="w-full max-w-md rounded-lg border border-line bg-surface p-0 shadow-popover backdrop:bg-ink/40">
  <div class="p-6">
    <h2 id="m-title" class="font-serif text-xl font-semibold text-ink">Finalisasi Dokumen</h2>
    <p class="mt-2 text-base text-ink">Setelah difinalisasi, dokumen mendapat nomor resmi dan tidak dapat diubah atau dihapus.</p>
  </div>
  <div class="flex justify-end gap-3 border-t border-line bg-paper p-4">
    <button class="...sekunder...">Batal</button>
    <button class="...primer...">Finalisasi</button>
  </div>
</dialog>
```

Memakai elemen `<dialog>` native (fokus terjebak, Esc menutup). Latar belakang digelapkan sederhana, tanpa blur. Tombol konfirmasi merusak memakai varian Danger. Fokus awal pada tombol "Batal" untuk aksi merusak.

### 5.8 Toast dan alert

| Jenis | Kegunaan | Perilaku |
|---|---|---|
| Toast | Konfirmasi non-blokir ("Draf disimpan.") | Kanan atas di bawah navbar, `role="status"`, hilang otomatis 6 detik, dapat ditutup, tidak menutupi tombol aksi |
| Alert inline | Galat, peringatan, informasi penting | Di dalam konten, tetap tampil sampai teratasi; `role="alert"` untuk galat |

```html
<div role="status" class="flex w-80 items-start gap-3 rounded-md border border-line border-l-4 border-l-brand bg-surface p-4 text-sm text-ink shadow-popover">
  <x-icon name="file-check" class="mt-0.5 size-5 shrink-0 text-brand"/>
  <p>Draf disimpan pada 10.42 WITA.</p>
  <button class="ml-auto -m-2 p-2 text-ink-muted hover:text-ink" aria-label="Tutup"><x-icon name="x" class="size-4"/></button>
</div>
```

Galat tidak pernah hanya berupa toast; galat selalu berada di dekat sumbernya.

### 5.9 Breadcrumb

```html
<nav aria-label="Breadcrumb" class="text-sm text-ink-muted">
  <ol class="flex flex-wrap items-center gap-2">
    <li><a class="text-brand hover:underline" href="/dashboard">Beranda</a></li>
    <li aria-hidden="true"><x-icon name="chevron-right" class="size-4"/></li>
    <li><a class="text-brand hover:underline" href="/dokumen">Daftar Dokumen</a></li>
    <li aria-hidden="true"><x-icon name="chevron-right" class="size-4"/></li>
    <li aria-current="page" class="text-ink">007/UND/Senat-FT/XII/2026</li>
  </ol>
</nav>
```

### 5.10 Pagination

Kiri: "Menampilkan 1–20 dari 134 dokumen" (`text-sm`, muted). Kanan: "Sebelumnya", angka halaman, "Berikutnya". Tombol 36 px (44 px sentuh), `rounded-md`, border `control`. Halaman aktif: `border-brand bg-brand-subtle font-semibold text-brand-hover` dengan `aria-current="page"`. Elipsis untuk rentang panjang. Mobile: hanya Sebelumnya, "Halaman 2 dari 7", Berikutnya.

### 5.11 Empty state

Panel berborder putus-putus dilarang; gunakan panel biasa. Isi rata kiri atau tengah tunggal: ikon 24 px `ink-muted`, judul `text-lg`, satu kalimat penjelasan, satu aksi.

```html
<div class="rounded-md border border-line bg-surface p-8">
  <x-icon name="file-text" class="size-6 text-ink-muted"/>
  <h3 class="mt-3 text-lg font-semibold text-ink">Belum ada dokumen</h3>
  <p class="mt-1 max-w-prose text-sm text-ink-muted">Dokumen yang Anda buat akan tampil di sini.</p>
  <a href="#" class="mt-4 inline-flex h-10 items-center rounded-md bg-brand px-4 text-sm font-semibold text-surface hover:bg-brand-hover">Buat Dokumen</a>
</div>
```

---

## 6. State

| State | Aturan | Contoh |
|---|---|---|
| Default | Sesuai spesifikasi komponen | — |
| Hover | Hanya perubahan warna latar atau teks; tanpa naik, tanpa shadow | Tombol primer ke `brand-hover`; baris tabel ke `paper` |
| Active (ditekan) | Satu tingkat lebih gelap | `brand-active` |
| Focus | Outline 2 px `focus`, offset 2 px; pada latar hijau tua gunakan `paper`; tidak pernah dihilangkan | Seluruh elemen interaktif |
| Disabled | Warna berubah nyata (bukan hanya opasitas), kursor `not-allowed`, `disabled` atau `aria-disabled`; disertai teks alasan bila tidak jelas | Tombol Finalisasi nonaktif: "Lengkapi isian wajib terlebih dahulu." |
| Loading (tombol) | Tombol `disabled`, spinner 16 px di kiri, label berubah ("Menyimpan…"); reduced motion: hanya teks | "Memproses…" |
| Loading (halaman/tabel) | Teks "Memuat…" atau baris kerangka statis bergaris; tanpa layar penuh berputar | Detail memuat |
| Empty | Judul + penjelasan + aksi (5.11) | Daftar kosong |
| Error (field) | Border `danger-700`, ikon + teks di bawah, `aria-invalid`, `aria-describedby` | "NIP wajib diisi." |
| Error (halaman) | Alert inline + ringkasan; 403/404/500 memakai halaman polos berisi judul, penjelasan, tautan "Kembali ke Beranda" | "Halaman tidak ditemukan." |
| Success | Toast untuk aksi non-blokir; halaman detail untuk hasil finalisasi | "Dokumen berhasil difinalisasi." |
| Selected/aktif | Navbar: garis bawah `brand`; filter aktif: chip | — |
| Read-only (Final) | Field tidak ditampilkan sebagai input; konten tampil sebagai teks dengan label | Detail Final |

---

## 7. Aturan Copywriting UI

### 7.1 Nada dan gaya
- Bahasa Indonesia baku, kalimat aktif, ringkas, netral. Sapaan "Anda" (huruf besar di awal kalimat hanya bila di awal).
- Tombol memakai kata kerja: Simpan Draf, Pratinjau, Finalisasi, Unduh PDF, Batalkan Dokumen, Periksa Dokumen.
- Tanpa tanda seru, tanpa emoji, tanpa singkatan gaul, tanpa pertanyaan retoris.
- Galat: sebutkan apa yang terjadi, lalu cara memperbaikinya, tanpa menyalahkan pengguna.
- Konfirmasi merusak: sebut objek dan akibatnya.
- Format: tanggal "25 November 2026"; waktu "10.42 WITA"; angka ribuan dengan titik; nomor surat apa adanya.

### 7.2 Istilah baku (konsisten di seluruh UI)

| Gunakan | Hindari |
|---|---|
| Daftar Dokumen | Daftar Isi, List, Arsip |
| Draf | Draft, Konsep (campur) |
| Finalisasi | Publish, Submit, Kirim |
| Kata sandi | Password |
| Unduh | Download |
| Pratinjau | Preview |
| Dokumen Sah / Dibatalkan | Valid, Verified!, Hoax |
| Pengguna | User, Akun pengguna (campur) |

Catatan: "Draf" mengikuti KBBI; nilai internal di kode dan database tetap `draft`.

### 7.3 Contoh kalimat

| Konteks | Contoh |
|---|---|
| Hero landing | "Sistem Manajemen dan Validasi Dokumen Senat Fakultas Teknik" |
| Deskripsi landing | "Pembuatan, penomoran, dan pemeriksaan keaslian dokumen resmi Senat Fakultas Teknik Universitas Mulawarman." |
| Label panel verifikasi | "Periksa Keaslian Dokumen" |
| Bantuan kode | "Kode tercetak di bawah QR pada dokumen. Contoh: 7K4M2-XP9QD." |
| Sukses simpan | "Draf disimpan pada 10.42 WITA." |
| Sukses finalisasi | "Dokumen berhasil difinalisasi dengan nomor 007/UND/Senat-FT/XII/2026." |
| Galat field | "Perihal wajib diisi." / "Tanggal tidak valid. Gunakan format hari-bulan-tahun." |
| Galat login | "NIP atau kata sandi tidak sesuai." |
| Batas percobaan | "Terlalu banyak percobaan masuk. Coba lagi dalam 45 detik." |
| Konfirmasi hapus draf | "Hapus draf “Undangan Rapat Pleno Senat”? Draf yang dihapus tidak dapat dikembalikan." |
| Konfirmasi batalkan | "Batalkan dokumen 007/UND/Senat-FT/XII/2026? Pembatalan tidak dapat dikembalikan dan nomor tidak akan dipakai lagi. Alasan pembatalan wajib diisi." |
| Empty daftar | "Belum ada dokumen yang sesuai dengan filter. Atur ulang filter atau buat dokumen baru." |
| Verifikasi sah | "Dokumen Sah" / "Dokumen ini tercatat dan berstatus final." |
| Verifikasi tidak ada | "Dokumen Tidak Ditemukan" / "Periksa kembali kode yang dimasukkan." |
| 404 | "Halaman tidak ditemukan. Alamat mungkin salah atau halaman telah dipindahkan." |

### 7.4 Kata dan pola terlarang
revolusioner, canggih, mulus (seamless), super, otomatis ajaib, "tingkatkan produktivitas", "sekali klik", "dalam sekejap", "nikmati", "yuk", "oops", "hore", tanda seru, emoji.

---

## 8. Aturan Responsif

Pendekatan desktop-first dalam desain, tetapi kelas ditulis mobile-first di Tailwind.

| Breakpoint | Lebar | Perubahan |
|---|---|---|
| Base | < 640 px | Satu kolom; navbar hamburger; tabel menjadi daftar; panel aksi form menjadi bilah bawah; gutter 16 px; `text-display` 28/36 |
| `sm` | ≥ 640 | Filter dua kolom; tombol dapat berdampingan |
| `md` | ≥ 768 | Gutter 24 px; form dua kolom untuk pasangan pendek; grid Buat Dokumen 2 x 2 |
| `lg` | ≥ 1024 | Navbar penuh; grid 12 kolom aktif; form + panel aksi sticky; Detail dua kolom; tabel penuh |
| `xl` | ≥ 1280 | Konten dikunci `max-w-page` (1200 px) dan dipusatkan; tidak ada perubahan tata letak lain |

| Komponen | Perilaku mobile |
|---|---|
| Tabel | Menjadi daftar berblok dengan judul, meta, badge; tidak ada gulir horizontal pada daftar dokumen dan pengguna |
| Tabel isian (peserta, lampiran) | Baris menjadi blok bertumpuk berjudul "Baris n" |
| Dropdown navbar | Akordeon di panel hamburger |
| Modal | Lebar penuh dengan margin 16 px; tombol bertumpuk, aksi utama di atas |
| Toast | Lebar penuh di bawah navbar |
| Verifikasi | Satu kolom; panel hasil penuh lebar; input dan tombol bertumpuk |
| Target sentuh | Semua kontrol interaktif ≥ 44 x 44 px pada `pointer: coarse` |
| Zoom | Tampilan tetap utuh pada zoom 200% tanpa gulir horizontal |

---

## 9. Aksesibilitas

| Area | Aturan |
|---|---|
| Kontras | Teks ≥ 4,5:1 (WCAG AA); komponen UI dan border kontrol ≥ 3:1; kombinasi token yang dipakai ada di 3.1; tautan dalam teks bacaan bergaris bawah |
| Fokus | `:focus-visible` outline 2 px `focus`, offset 2 px, tidak pernah dihapus; urutan fokus mengikuti urutan visual |
| Keyboard | Semua fungsi dapat dioperasikan tanpa mouse; dropdown: Enter/Spasi buka, panah berpindah, Esc tutup + fokus kembali; modal menjebak fokus dan mengembalikannya |
| Lompat konten | Tautan "Lompat ke konten" di awal setiap halaman, tampak saat fokus |
| Landmark | `header`, `nav` (dengan `aria-label`), `main`, `footer`; satu `h1` per halaman, hierarki heading berurutan |
| Bahasa | `<html lang="id">`; judul halaman unik: "Daftar Dokumen — Senat FT Unmul" |
| Formulir | `label for` pada setiap input; `aria-describedby` untuk bantuan dan galat; `aria-invalid`; ringkasan galat menerima fokus; `autocomplete` dan `inputmode` yang tepat; tidak memblokir tempel (paste) |
| Tabel | `<caption>` (boleh `sr-only`), `th scope`; tombol ikon punya `aria-label`; status memakai teks, bukan warna saja |
| ARIA | `aria-current="page"` pada navigasi, breadcrumb, pagination; `role="status"` untuk toast; `role="alert"` untuk galat; `aria-pressed` pada tombol tampilkan kata sandi; `aria-live="polite"` untuk hasil yang berubah |
| Gerak | `prefers-reduced-motion` menonaktifkan semua transisi; tidak ada konten berkedip |
| Gambar | QR memiliki `alt` ("QR verifikasi dokumen, kode 7K4M2-XP9QD"); logo dekoratif `alt=""` bila nama lembaga tertulis di sebelahnya |
| Sentuh | Target ≥ 44 px (lihat 8) |
| Verifikasi | Diuji dengan pembaca layar (NVDA atau VoiceOver) pada Login, Form Undangan, dan Verifikasi |

---

## 10. Checklist Review Anti-Slop (sebelum merge)

Jawab semua "ya". Satu "tidak" berarti perbaiki dulu.

1. Apakah halaman ini terlihat seperti sistem milik lembaga akademik, bukan landing page SaaS?
2. Apakah hanya ada satu warna aksi (hijau), dan emas hanya sebagai garis identitas?
3. Apakah tidak ada gradient, blur, atau transparansi dekoratif?
4. Apakah semua ikon berasal dari Lucide stroke dan tidak ada emoji?
5. Apakah pemisah utama berupa border 1 px, dan shadow hanya pada elemen melayang?
6. Apakah radius hanya 4/6/8 px?
7. Apakah hierarki terbaca dari tipografi dan jarak, bukan dari dekorasi?
8. Apakah judul serif dan UI sans, tanpa font ketiga?
9. Apakah teks panjang rata kiri dan tidak ada layout center-everything?
10. Apakah tabel dan angka memakai `tabular-nums` dan baris 44 px?
11. Apakah badge status memakai ikon + teks (tidak hanya warna)?
12. Apakah copy formal, ringkas, tanpa tanda seru atau kata terlarang?
13. Apakah warna, jarak, dan ukuran semuanya dari token (tidak ada nilai acak)?
14. Apakah tidak ada animasi selain transisi warna 150 ms, dan reduced motion dihormati?
15. Apakah tidak ada dark mode otomatis atau varian `dark:`?
16. Apakah empty, loading, error, dan disabled state sudah dirancang?
17. Apakah kontras lulus AA dan fokus terlihat jelas?
18. Apakah halaman rapi di 375 px, 768 px, dan 1280 px, dengan target sentuh ≥ 44 px?
19. Jika layar ini ditutup logonya, apakah orang masih menyebutnya "formal dan kredibel", bukan "template"?
20. Apakah perubahan hanya presentasi (tidak menyentuh data/backend)?

---

## 11. Acceptance Criteria Visual

Semua butir dapat dicek dengan pencarian kode, alat otomatis, atau tinjauan tangkapan layar.

| ID | Kriteria | Cara cek |
|---|---|---|
| AC-1 | Nol gradient | `rg -i "gradient" resources/` = 0 hasil |
| AC-2 | Nol blur/glass | `rg "backdrop-(blur\|filter)" resources/` = 0 |
| AC-3 | Nol emoji di tampilan | Pindai Unicode emoji pada `resources/views` dan `resources/js` = 0 |
| AC-4 | Warna hanya dari token | Tidak ada `#hex` atau `rgb(` di Blade; tidak ada kelas warna arbitrer `[#...]`; palet bawaan Tailwind dikosongkan (kelas seperti `bg-purple-500` tidak menghasilkan CSS) |
| AC-5 | Radius sah | Tidak ada `rounded-(xl\|2xl\|3xl)`; `rounded-full` hanya pada avatar dan spinner |
| AC-6 | Shadow terkendali | Satu-satunya shadow adalah `shadow-popover`, hanya pada dropdown, modal, toast |
| AC-7 | Jarak sah | Tidak ada kelas jarak di luar 1/2/3/4/6/8/12/16 (kecuali ukuran kontrol/ikon). Contoh pemeriksa: `rg "\b(p\|m\|gap\|space)-(x-\|y-\|t-\|b-\|l-\|r-)?(5\|7\|9\|10\|14)\b" resources/views` = 0 |
| AC-8 | Dua font saja | Jaringan memuat hanya Source Serif 4 dan Source Sans 3, self-host, total ≤ 200 KB, tidak ada permintaan ke domain font eksternal |
| AC-9 | Satu aksen | Elemen interaktif memakai hijau; emas hanya pada garis identitas (header publik, login, footer, kop) |
| AC-10 | Landing bukan template | Tidak ada baris tiga kartu fitur; panel verifikasi terlihat tanpa menggulir pada 1280 x 720 |
| AC-11 | Kontras | Lighthouse/axe: skor aksesibilitas ≥ 95 dan nol pelanggaran kontras pada 8 halaman utama |
| AC-12 | Fokus | Setiap elemen interaktif menampilkan outline fokus yang terlihat saat Tab |
| AC-13 | Target sentuh | Pada lebar 375 px dan `pointer: coarse`, semua kontrol ≥ 44 x 44 px |
| AC-14 | Responsif | Tidak ada gulir horizontal pada 360 px (halaman daftar dan form), tabel berubah menjadi daftar |
| AC-15 | Status | Setiap badge status memuat ikon + teks; Draf, Final, Dibatalkan dapat dibedakan pada tangkapan layar hitam-putih |
| AC-16 | Gerak | Tidak ada animasi scroll; hanya transisi warna ≤ 150 ms; dengan reduced motion tidak ada transisi |
| AC-17 | Tanpa dark mode | `color-scheme: light` tetap; `rg "dark:" resources/` = 0 |
| AC-18 | Copy | Tidak ada tanda seru dan kata terlarang (7.4) di berkas view dan terjemahan; istilah baku (7.2) konsisten |
| AC-19 | Komponen konsisten | Semua tombol, input, badge, tabel di aplikasi berasal dari Blade component; tidak ada tombol dengan kelas ad hoc |
| AC-20 | Verifikasi publik | Hasil Sah, Dibatalkan, dan Tidak ditemukan tampil sesuai 4.7 dan hanya memuat jenis, nomor, tanggal, status |
| AC-21 | Tinjauan akhir | Tangkapan layar 8 halaman pada 1280, 768, dan 375 px lulus checklist 10 oleh dua peninjau |

---

# Lampiran A — Sebelum vs Sesudah

| Aspek | Gaya AI-generik (sebelum) | Gaya institusional (sesudah) |
|---|---|---|
| Hero landing | Judul besar di tengah dengan teks gradient, dua tombol, mockup dashboard melayang | Masthead rata kiri: judul serif + panel "Periksa Keaslian Dokumen" |
| Fitur | Tiga kartu berikon dalam lingkaran berwarna | Tabel "Jenis Dokumen": jenis, kegunaan, contoh nomor |
| Palet | Ungu ke biru, blob aurora | Hijau tua + netral kertas; emas hanya garis tipis |
| Kartu | `rounded-3xl`, shadow besar, hover mengangkat | Panel putih, border 1 px, radius 6 px, tanpa shadow |
| Tombol | Pil gradient dengan glow | Persegi 6 px, solid hijau, hover lebih gelap |
| Ikon | Emoji dan ikon warna-warni | Lucide garis 1,5 px, satu warna mengikuti teks |
| Font | Space Grotesk + Inter | Source Serif 4 (judul) + Source Sans 3 (UI) |
| Statistik dashboard | Empat kartu berwarna dengan ikon besar | Satu panel, lima angka tabular dipisah garis vertikal |
| Tabel | Kartu per baris, zebra, shadow | Tabel dengan header bergaris, hover halus, angka tabular |
| Badge | Pil neon dengan titik berpendar | Persegi 4 px: ikon + teks + border |
| Verifikasi | Tanda centang raksasa beranimasi, konfeti | Panel daftar definisi dengan status teks dan catatan privasi |
| Empty state | Ilustrasi lucu, "Oops, belum ada apa-apa!" | Ikon kecil, judul, satu kalimat, satu aksi |
| Navigasi | Sidebar gelap dengan ikon besar dan lencana | Navbar putih polos, garis bawah hijau pada item aktif |
| Dark mode | Otomatis mengikuti sistem, tombol ganti tema | Tidak ada; tampilan satu tema kertas |
| Animasi | Fade-up saat scroll, parallax | Hanya transisi warna 150 ms |
| Footer | "Dibuat dengan cinta", ikon sosial | Footer institusional: identitas, alamat, tautan Unmul, kontak |
| Copy | "Revolusikan pengelolaan surat Anda!" | "Pembuatan, penomoran, dan pemeriksaan keaslian dokumen resmi Senat." |
| Notifikasi | Toast melayang besar berwarna cerah | Toast kecil berborder kiri; galat inline di dekat sumber |

---

# Lampiran B — Organisasi Berkas

```
resources/
  css/
    app.css            @import tailwindcss, token, base layer
    tokens.css         blok @theme (satu-satunya sumber nilai)
  views/
    components/
      icon.blade.php           pembungkus Lucide
      button.blade.php         variant: primary|secondary|ghost|danger, size: sm|md|lg
      field.blade.php          label + input + hint + error
      input.blade.php  select.blade.php  textarea.blade.php  checkbox.blade.php
      badge.blade.php          status: draft|final|dibatalkan|aktif|nonaktif
      panel.blade.php  table.blade.php  pagination.blade.php
      modal.blade.php  toast.blade.php  alert.blade.php
      breadcrumb.blade.php  empty-state.blade.php  page-header.blade.php
      nav/navbar.blade.php  nav/dropdown.blade.php  nav/link.blade.php
    layouts/
      public.blade.php         header publik + footer institusional
      app.blade.php            navbar + konten + footer ringkas
```

# Lampiran C — Urutan Penerapan

| Langkah | Isi | Hasil |
|---|---|---|
| 1 | Token (`tokens.css`), font self-host, base layer, kosongkan palet bawaan | Fondasi; kelas di luar token tidak lagi bekerja |
| 2 | Komponen dasar: button, field/input, badge, panel, table, alert | Perpustakaan komponen |
| 3 | Navbar + layout publik/aplikasi + footer; ikon | Kerangka seluruh halaman |
| 4 | Halaman: Landing, Login, Verifikasi (publik) | Wajah publik |
| 5 | Halaman: Dashboard, Daftar Dokumen, Detail, Form, Admin | Wajah aplikasi |
| 6 | Audit AC-1 sampai AC-21, perbaikan, tinjauan tangkapan layar | Siap rilis |

Langkah 1 hingga 3 selaras dengan Fase F0 (navigasi) pada PRD fungsional dan sebaiknya dikerjakan lebih dulu, sehingga editor baru (Fase F3 dan F4) langsung memakai komponen dan token ini.
