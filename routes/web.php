<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BeritaAcaraController;
use App\Http\Controllers\DaftarHadirController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\JenisSuratController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\SuratPengantarController;
use App\Http\Controllers\UndanganController;
use App\Http\Controllers\WelcomeController;
use App\Models\Document;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Halaman verifikasi keaslian dokumen publik via QR code
Route::get('/verifikasi/{document}', [DaftarHadirController::class, 'verifikasi'])->name('dokumen.verifikasi');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/beranda', [DashboardController::class, 'index'])->name('beranda');

    Route::get('/dokumen', [DokumenController::class, 'index'])->name('dokumen.index');

    // Khusus Daftar Hadir
    Route::get('/dokumen/daftar_hadir/baru', [DaftarHadirController::class, 'create'])->name('dokumen.daftar-hadir.create');
    Route::post('/dokumen/daftar_hadir', [DaftarHadirController::class, 'store'])->name('dokumen.daftar-hadir.store');
    Route::get('/dokumen/daftar_hadir/{document}/edit', [DaftarHadirController::class, 'edit'])->name('dokumen.daftar-hadir.edit');
    Route::put('/dokumen/daftar_hadir/{document}', [DaftarHadirController::class, 'update'])->name('dokumen.daftar-hadir.update');

    // Undangan
    Route::get('/dokumen/undangan/baru', [UndanganController::class, 'create'])->name('dokumen.undangan.create');
    Route::post('/dokumen/undangan', [UndanganController::class, 'store'])->name('dokumen.undangan.store');
    Route::get('/dokumen/undangan/{document}/edit', [UndanganController::class, 'edit'])->name('dokumen.undangan.edit');
    Route::put('/dokumen/undangan/{document}', [UndanganController::class, 'update'])->name('dokumen.undangan.update');
    Route::get('/dokumen/undangan/{document}/cetak', [UndanganController::class, 'printView'])->name('dokumen.undangan.cetak');

    // Berita Acara
    Route::get('/dokumen/berita_acara/baru', [BeritaAcaraController::class, 'create'])->name('dokumen.berita-acara.create');
    Route::post('/dokumen/berita_acara', [BeritaAcaraController::class, 'store'])->name('dokumen.berita-acara.store');
    Route::get('/dokumen/berita_acara/{document}/edit', [BeritaAcaraController::class, 'edit'])->name('dokumen.berita-acara.edit');
    Route::put('/dokumen/berita_acara/{document}', [BeritaAcaraController::class, 'update'])->name('dokumen.berita-acara.update');
    Route::get('/dokumen/berita_acara/{document}/cetak', [BeritaAcaraController::class, 'printView'])->name('dokumen.berita-acara.cetak');

    // Surat Pengantar
    Route::get('/dokumen/surat_pengantar/baru', [SuratPengantarController::class, 'create'])->name('dokumen.surat-pengantar.create');
    Route::post('/dokumen/surat_pengantar', [SuratPengantarController::class, 'store'])->name('dokumen.surat-pengantar.store');
    Route::get('/dokumen/surat_pengantar/{document}/edit', [SuratPengantarController::class, 'edit'])->name('dokumen.surat-pengantar.edit');
    Route::put('/dokumen/surat_pengantar/{document}', [SuratPengantarController::class, 'update'])->name('dokumen.surat-pengantar.update');
    Route::get('/dokumen/surat_pengantar/{document}/cetak', [SuratPengantarController::class, 'printView'])->name('dokumen.surat-pengantar.cetak');

    Route::get('/dokumen/{document}/cetak', [DaftarHadirController::class, 'printView'])->name('dokumen.cetak');

    Route::get('/dokumen/{document}', [DokumenController::class, 'show'])->name('dokumen.show');
    Route::get('/dokumen/{document}/pdf', [DokumenController::class, 'downloadPdf'])->name('dokumen.pdf');
    Route::delete('/dokumen/{document}', [DokumenController::class, 'destroy'])->name('dokumen.destroy');

    Route::get('/dokumen/{jenis}/baru', [DokumenController::class, 'createPlaceholder'])
        ->whereIn('jenis', array_keys(Document::JENIS))
        ->name('dokumen.baru');

    Route::get('/dokumen/{jenis}/{document}/edit', [DokumenController::class, 'editPlaceholder'])
        ->whereIn('jenis', array_keys(Document::JENIS))
        ->name('dokumen.edit');

    // Modul pembuat surat (Senatitor) — ikut proteksi login NIP
    Route::view('/pembuat-surat', 'index')->name('pembuat-surat');
    Route::resource('jenis_surat', JenisSuratController::class);
    Route::resource('surat', SuratController::class);
    Route::get('surat/{surat}/pdf', [SuratController::class, 'exportPdf'])->name('surat.pdf');
    Route::get('surat/{surat}/pdf/download', [SuratController::class, 'downloadPdf'])->name('surat.pdf.download');
});
