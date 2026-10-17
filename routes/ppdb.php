<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Ppdb\BerkasController;
use App\Http\Controllers\Ppdb\DashboardPendaftarController;
use App\Http\Controllers\Ppdb\PendaftarController;
use App\Http\Controllers\Ppdb\PublicPpdbController;
use Illuminate\Support\Facades\Route;

// Login Mandiri SPMB / Pendaftaran Ulang
Route::get('/spmb/login', [AuthController::class, 'showSpmbLoginForm'])->name('spmb.login');
Route::post('/spmb/login', [AuthController::class, 'loginSpmb'])->name('spmb.login.post');
Route::get('/ppdb/login', [AuthController::class, 'showSpmbLoginForm'])->name('ppdb.login');
Route::post('/ppdb/login', [AuthController::class, 'loginSpmb'])->name('ppdb.login.post');
Route::get('/pendaftar/login', [AuthController::class, 'showSpmbLoginForm'])->name('pendaftar.login');
Route::post('/pendaftar/login', [AuthController::class, 'loginSpmb'])->name('pendaftar.login.post');

// Halaman Publik SPMB (dan alias PPDB)
Route::prefix('spmb')->name('spmb.')->group(function () {
    Route::get('/', [PublicPpdbController::class, 'index'])->name('index');
    Route::get('/alur', [PublicPpdbController::class, 'alur'])->name('alur');
    Route::get('/pengumuman', [PublicPpdbController::class, 'pengumuman'])->name('pengumuman');
});

Route::prefix('ppdb')->name('ppdb.')->group(function () {
    Route::get('/', [PublicPpdbController::class, 'index'])->name('index');
    Route::get('/alur', [PublicPpdbController::class, 'alur'])->name('alur');
    Route::get('/pengumuman', [PublicPpdbController::class, 'pengumuman'])->name('pengumuman');
});

// Portal Calon Siswa / Siswa (Dilindungi Auth & Role)
Route::prefix('pendaftar')
    ->name('pendaftar.')
    ->middleware(['auth', 'role:calon_siswa,siswa,admin,operator'])
    ->group(function () {
        Route::get('/dashboard', [DashboardPendaftarController::class, 'index'])->name('dashboard');
        Route::get('/kelulusan', [DashboardPendaftarController::class, 'kelulusan'])->name('kelulusan');
        Route::get('/cetak-bukti', [DashboardPendaftarController::class, 'cetakBukti'])->name('cetak_bukti');

        Route::get('/formulir', [PendaftarController::class, 'formulir'])->name('formulir');
        Route::post('/formulir', [PendaftarController::class, 'simpanFormulir'])->name('formulir.simpan');

        Route::get('/berkas', [BerkasController::class, 'index'])->name('berkas');
        Route::post('/berkas/upload', [BerkasController::class, 'upload'])->name('berkas.upload');
        Route::delete('/berkas/{berkas}', [BerkasController::class, 'destroy'])->name('berkas.hapus');
        Route::get('/berkas/{berkas}/preview', [BerkasController::class, 'preview'])->name('berkas.preview');
        Route::post('/berkas/kirim', [BerkasController::class, 'kirimVerifikasi'])->name('berkas.kirim');
    });
