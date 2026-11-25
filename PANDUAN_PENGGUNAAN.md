# Panduan Penggunaan Sistem Senat FT

## 1. Akses Website
- Buka http://127.0.0.1:8000 (atau domain produksi).
- Pengunjung publik dapat melihat landing page dan verifikasi dokumen.
- Pengurus login via tombol "Masuk" dengan NIP + password.

## 2. Login
1. Klik "Masuk" di landing page atau buka /login.
2. Masukkan NIP dan password.
3. Klik "Masuk". Setelah sukses diarahkan ke Beranda.
4. Logout via avatar → "Keluar".

## 3. Beranda (Dashboard)
- Melihat sambutan, aksi cepat "Buat {Jenis Dokumen}", ringkasan jumlah per jenis, draft, final, bulan ini, dan aktivitas terbaru.
- Klik "Buat ..." untuk membuat dokumen baru.

## 4. Membuat Dokumen (Undangan / Berita Acara / Surat Pengantar / Daftar Hadir)
1. Klik "Buat {Jenis}" di Beranda atau menu navbar.
2. Isi form informasi dokumen (perihal wajib; field lain wajib saat finalisasi).
3. Untuk jenis dengan daftar (Undangan/Berita Acara/Daftar Hadir), isi nama & jabatan pada baris yang tersedia.
4. Klik "Simpan Draf" untuk menyimpan tanpa nomor surat.
5. Klik "Finalisasi Dokumen" → konfirmasi → sistem menerbitkan nomor surat resmi.
6. Setelah final, dokumen tidak bisa diedit/dihapus.

## 5. Daftar Dokumen
- Menu "Daftar Dokumen" menampilkan semua dokumen lintas jenis.
- Gunakan pencarian (nomor surat/perihal) dan filter (jenis, status, pembuat, tanggal).
- Aksi per baris: Lihat detail, Edit (draft), Hapus (draft), Download PDF (final), Cetak (final).

## 6. Verifikasi Dokumen (Publik)
- Di landing page section "Verifikasi Dokumen", masukkan nomor surat (contoh: 001/DH/Senat-FT/IX/2026).
- Klik "Cek Keaslian".
- Hasil: jenis, nomor, tanggal, status terverifikasi atau tidak ditemukan.
- Alternatif: scan QR pada dokumen fisik → mengarah ke /verifikasi/{id}.

## 7. Download / Cetak PDF
- Buka detail dokumen final → klik "Download PDF" atau "Cetak".
- PDF digenerate otomatis (termasuk nomor surat).

## 8. Template Surat (Surat Keputusan)
- Menu "Pembuat Surat" → "Template Surat" untuk mengelola template kop surat.
- Menu "Surat" untuk membuat surat berdasarkan template.

## 9. Tips
- Draft bisa disimpan kapan saja meski belum lengkap.
- Finalisasi tidak bisa dibatalkan; pastikan data benar.
- Nomor surat diterbitkan otomatis oleh sistem (jangan buat manual).
- Akun dibuat oleh admin; jika lupa password hubungi admin Senat FT.

## 10. Troubleshooting
- Jika tidak bisa login: pastikan NIP benar, akun aktif, dan tidak terkena lockout (tunggu 60 detik setelah 5x gagal).
- Jika halaman error, cek log di storage/logs/laravel.log.
