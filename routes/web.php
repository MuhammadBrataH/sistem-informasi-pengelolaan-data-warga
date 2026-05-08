<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Routes untuk Kartu Keluarga (protected dengan auth middleware)
Route::middleware(['auth'])->group(function () {
    Route::resource('kartu-keluarga', App\Http\Controllers\KartuKeluargaController::class);
    
    // Routes untuk Tambah/Edit Warga (Input Baru)
    Route::get('warga/create', [App\Http\Controllers\WargaController::class, 'create'])
        ->name('warga.create');
    Route::post('warga/store', [App\Http\Controllers\WargaController::class, 'store'])
        ->name('warga.store');
    
    // Routes untuk Pilih & Pindahkan Warga Lama
    Route::get('warga/form-pilih-warga', [App\Http\Controllers\WargaController::class, 'formPilihWarga'])
        ->name('warga.form-pilih');
    Route::post('warga/pindahkan', [App\Http\Controllers\WargaController::class, 'pindahkanWarga'])
        ->name('warga.pindahkan');
    
    // Routes khusus untuk Mutasi Status Warga
    Route::patch('warga/{id}/lapor-meninggal', [App\Http\Controllers\WargaController::class, 'laporMeninggal'])
        ->name('warga.lapor-meninggal');
    Route::patch('warga/{id}/lapor-pindah', [App\Http\Controllers\WargaController::class, 'laporPindah'])
        ->name('warga.lapor-pindah');
    Route::patch('warga/{id}/kembalikan-hidup', [App\Http\Controllers\WargaController::class, 'kembalikanHidup'])
        ->name('warga.kembalikan-hidup');
    
    // Route untuk Cetak Surat Pengantar
    Route::get('warga/{id}/cetak-surat', [App\Http\Controllers\WargaController::class, 'cetakSuratPengantar'])
        ->name('warga.cetak-surat');

     // Routes untuk Warga
    Route::resource('warga', App\Http\Controllers\WargaController::class)->only(['index', 'show']);
});
