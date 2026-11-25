# PRD — Halaman Daftar Hadir
## Sistem Manajemen & Validasi Dokumen Senat Fakultas Teknik Unmul

**Terkait dokumen lain:** `PRD_Sistem_Senat_FT.md` (Login, Beranda, Daftar Isi), `PRD_Desain_UI_Sistem_Senat_FT.md` (design system — palet, tipografi, komponen)
**Sifat halaman:** Form statis (bukan text editor bebas seperti Undangan/BA/Surat Pengantar) — isinya data terstruktur: info acara + tabel peserta.

---

## 1. Tujuan Fitur

Menggantikan daftar hadir manual (biasanya Excel/Word yang dicetak lalu ditandatangani basah) dengan form terstruktur yang bisa disimpan, dicari lagi, dan setelah difinalisasi mendapat nomor surat + QR verifikasi seperti 3 jenis dokumen lain.

## 2. Kenapa "Form Statis", Bukan Editor Teks

Berbeda dari Undangan/Berita Acara/Surat Pengantar yang isinya paragraf bebas (butuh TipTap), Daftar Hadir isinya **data tabular yang berulang bentuknya**: info acara di atas + daftar nama peserta di bawah. Tidak ada kebutuhan format teks bebas (bold/italic/list dsb), jadi cukup form input biasa + tabel dinamis — lebih cepat diisi, lebih konsisten hasilnya antar pengurus.

## 3. Struktur Data

Field yang diisi:

**Bagian Info Acara**
| Field | Tipe | Wajib |
|---|---|---|
| Nama Acara/Rapat | Teks pendek | Ya |
| Tanggal Acara | Tanggal | Ya |
| Waktu Mulai – Selesai | Jam | Ya |
| Tempat/Lokasi | Teks pendek | Ya |
| Penyelenggara/Pemimpin Rapat | Teks pendek | Opsional |

**Bagian Daftar Peserta** (tabel dinamis, baris bisa ditambah/dihapus)
| Kolom | Tipe | Keterangan |
|---|---|---|
| No. | Auto | Terisi otomatis sesuai urutan baris |
| Nama | Teks | Wajib diisi sebelum baris disimpan |
| Jabatan/Instansi | Teks | Opsional tapi disarankan diisi |
| (Kolom Tanda Tangan) | — | Tidak diisi di sistem — ini kolom kosong khusus di PDF hasil cetak, untuk ditandatangani manual di kertas |

**Representasi di `documents.content` (JSON), contoh struktur:**
```json
{
  "nama_acara": "Rapat Koordinasi Bulanan Senat FT",
  "tanggal": "2026-09-15",
  "waktu_mulai": "09:00",
  "waktu_selesai": "11:00",
  "tempat": "Ruang Rapat Dekanat FT",
  "penyelenggara": "Ketua Senat FT",
  "peserta": [
    { "nama": "Nama Peserta 1", "jabatan_instansi": "Dosen / Teknik Sipil" },
    { "nama": "Nama Peserta 2", "jabatan_instansi": "Staf / Tata Usaha" }
  ]
}
```

## 4. Alur Pengguna

```
Beranda / Daftar Isi
      ↓ klik "Buat Daftar Hadir"
[Form Info Acara] → isi nama acara, tanggal, waktu, tempat
      ↓
[Tabel Peserta] → tambah baris satu-satu (atau tempel banyak nama sekaligus — lihat 6.4)
      ↓
[Simpan Draft]  atau  [Finalisasi]
      ↓ (jika Finalisasi)
Sistem generate nomor surat + PDF + QR → dokumen pindah status "Final"
      ↓
Muncul di Daftar Isi, siap diunduh & dicetak
```

## 5. Requirement Fungsional

| No | Requirement |
|---|---|
| DH-1 | Form info acara wajib divalidasi (nama acara, tanggal, waktu, tempat tidak boleh kosong) sebelum bisa disimpan, baik sebagai draft maupun final |
| DH-2 | Tabel peserta minimal harus punya 1 baris terisi untuk bisa difinalisasi (draft boleh kosong/belum lengkap) |
| DH-3 | Baris peserta bisa ditambah tanpa batas jumlah, dan dihapus per baris (dengan ikon `trash-2`, tanpa perlu konfirmasi modal karena aksinya ringan/reversibel selama belum disimpan) |
| DH-4 | Urutan nomor (`No.`) otomatis menyesuaikan ulang saat ada baris dihapus/ditambah |
| DH-5 | Simpan Draft bisa dilakukan kapan saja meski form belum lengkap (memudahkan pengisian bertahap saat rapat berlangsung) |
| DH-6 | Finalisasi mengunci dokumen dari perubahan lebih lanjut, memicu proses yang sama seperti 3 jenis dokumen lain: generate nomor surat, generate PDF (Browsershot), tempel QR (endroid/qr-code) di pojok kanan bawah PDF |
| DH-7 | PDF hasil cetak menyediakan kolom tanda tangan kosong di sebelah nama tiap peserta, untuk ditandatangani manual saat/setelah rapat |
| DH-8 | Setelah final, dokumen ini tunduk pada aturan yang sama di Daftar Isi: tidak bisa diedit/dihapus, hanya bisa dilihat/diunduh/dilihat halaman verifikasinya |

## 6. Kebutuhan UI (mengikuti design system yang sudah ditetapkan)

### 6.1 Layout Halaman
```
┌────────┬────────────────────────────────────────────┐
│Sidebar │ Buat Daftar Hadir                            │
│        │ ┌──────────────────────────────────────────┐│
│        │ │ Nama Acara/Rapat   [ input ]              ││
│        │ │ Tanggal  [ input ]   Waktu [ - ]           ││
│        │ │ Tempat   [ input ]                          ││
│        │ │ Penyelenggara (opsional)  [ input ]        ││
│        │ └──────────────────────────────────────────┘│
│        │ Daftar Peserta                    [+ Tambah] │
│        │ ┌──────────────────────────────────────────┐│
│        │ │ No | Nama          | Jabatan/Instansi | ⋮ ││
│        │ │ 1  | [ input ]     | [ input ]        | 🗑││
│        │ │ 2  | [ input ]     | [ input ]        | 🗑││
│        │ └──────────────────────────────────────────┘│
│        │              [Simpan Draft]  [Finalisasi]    │
└────────┴────────────────────────────────────────────┘
```
*(ikon di atas mewakili ikon garis, bukan emoji — lihat design system bagian 7)*

### 6.2 Komponen yang Dipakai (rujuk PRD Desain UI)
- Input & label: sesuai token bagian 6.2 (border `border`, radius 6px, fokus ring `gold-100`)
- Tombol "Simpan Draft" = varian **Secondary**, tombol "Finalisasi" = varian **Primary** (gold) — supaya aksi yang tidak bisa diubah lagi (finalisasi) terasa lebih "berat"/disengaja dibanding sekadar menyimpan draft
- Baris tabel peserta: gunakan pola input inline di dalam sel tabel, bukan modal terpisah per baris — mempercepat pengisian saat rapat berjalan
- Tombol "+ Tambah Baris" ditempatkan di kanan atas judul section "Daftar Peserta", ikon `plus`, varian secondary kecil
- Ikon hapus baris (`trash-2`) memakai warna `ink-400` default, berubah ke warna danger (`#A23B2C`) saat hover — bukan merah solid dari awal, supaya tabel tidak terasa penuh peringatan

### 6.3 Status & Feedback
- Saat berhasil disimpan sebagai draft: notifikasi kecil non-intrusive (toast) "Draf disimpan" — bukan modal besar yang menghentikan alur.
- Saat Finalisasi ditekan: tampilkan **modal konfirmasi** (bukan langsung eksekusi), karena aksi ini mengunci dokumen dan generate nomor surat resmi — tidak bisa dibatalkan. Modal berisi ringkasan singkat (nama acara, jumlah peserta) + tombol "Batal" / "Ya, Finalisasi".

### 6.4 Kemudahan Input (opsional, nilai tambah)
Karena daftar peserta bisa berjumlah banyak (rapat besar), pertimbangkan fitur **tempel cepat**: tombol/opsi "Tempel dari daftar nama" yang menerima teks multi-baris (misal hasil copy dari WhatsApp/Excel, satu nama per baris) lalu otomatis memecahnya jadi baris-baris tabel. Ini opsional untuk versi awal, tapi sangat membantu dibanding input satu-satu untuk rapat dengan puluhan peserta.

### 6.5 Responsive Mobile
- Form info acara: field disusun stack 1 kolom penuh lebar layar.
- Tabel peserta di mobile: **tidak dipaksa scroll horizontal** — tiap baris peserta ditampilkan sebagai card kecil: baris atas "Nama" (input), baris bawah "Jabatan/Instansi" (input), ikon hapus di pojok kanan atas card. Tombol "+ Tambah Peserta" full-width di bawah daftar card.
- Tombol "Simpan Draft" dan "Finalisasi" di mobile ditumpuk (stack), "Finalisasi" tetap di atas sebagai aksi utama layar, mengikuti pola thumb-reachable di bagian bawah layar (sticky bottom bar), bukan di paling atas.

## 7. Non-Functional

- Validasi form terjadi di sisi client (langsung terasa) sekaligus di-cek ulang di server sebelum finalisasi (jangan percaya validasi client saja).
- Jumlah peserta tidak dibatasi secara sengaja oleh sistem, tapi UI harus tetap nyaman dipakai untuk skala puluhan hingga ratusan baris (tabel/list yang bisa di-scroll dalam card-nya sendiri, bukan bikin halaman jadi sangat panjang tanpa struktur).

## 8. Kriteria Sukses

- [ ] Pengurus bisa membuat Daftar Hadir baru, isi info acara + minimal 1 peserta, simpan sebagai draft
- [ ] Draft bisa dibuka kembali dan dilanjutkan pengisiannya
- [ ] Finalisasi menghasilkan PDF dengan nomor surat + QR, kolom tanda tangan kosong tersedia di sebelah tiap nama peserta
- [ ] Dokumen final Daftar Hadir muncul dan berperilaku sama seperti jenis dokumen lain di Daftar Isi (tidak bisa diedit/dihapus)
- [ ] Tampilan di mobile tetap rapi — tidak ada tabel yang terpotong/perlu scroll horizontal yang membingungkan

## 9. Pertanyaan Terbuka

- Apakah perlu batas minimum/maksimum jumlah peserta per Daftar Hadir? (Diasumsikan tidak ada batas keras.)
- Apakah fitur "tempel cepat" (6.4) masuk versi pertama atau menyusul? (Diasumsikan opsional/versi berikutnya, tidak wajib untuk MVP.)
- Apakah kolom "Jabatan/Instansi" perlu dropdown terhubung ke data pegawai (mengingat login juga pakai NIP), atau cukup teks bebas seperti sekarang? (Diasumsikan teks bebas untuk kesederhanaan, karena peserta rapat bisa dari luar unit/instansi lain.)
