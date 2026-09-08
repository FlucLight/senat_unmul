<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DokumenController;
use App\Models\Document;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('beranda')
        : redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/beranda', function () {
        return view('beranda');
    })->name('beranda');

    Route::get('/dokumen', [DokumenController::class, 'index'])->name('dokumen.index');
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