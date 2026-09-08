<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DaftarHadirController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DokumenController;
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
});
