<?php

use App\Http\Controllers\JenisSuratController;
use App\Http\Controllers\SuratController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('home');

Route::resource('jenis_surat', JenisSuratController::class);
Route::resource('surat', SuratController::class);
Route::get('surat/{surat}/pdf', [SuratController::class, 'exportPdf'])->name('surat.pdf');
Route::get('surat/{surat}/pdf/download', [SuratController::class, 'downloadPdf'])->name('surat.pdf.download');
