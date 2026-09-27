<?php

use App\Http\Controllers\Ppdb\AdminPpdbController;
use App\Http\Controllers\Siakad\AdminSiakadController;
use App\Http\Controllers\Siakad\GuruNilaiController;
use App\Http\Controllers\Siakad\SiswaPortalController;
use App\Http\Controllers\Siakad\SyncLogController;
use Illuminate\Support\Facades\Route;

// Rute Admin & Operator (Dilindungi Role: admin, operator)
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin,operator'])
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // PPDB Admin
        Route::prefix('ppdb')->name('ppdb.')->group(function () {
            Route::get('/periode', [AdminPpdbController::class, 'periodeIndex'])->name('periode.index');
            Route::get('/periode-alias', [AdminPpdbController::class, 'periodeIndex'])->name('periode');
            Route::post('/periode', [AdminPpdbController::class, 'simpanPeriode'])->name('periode.simpan');
            Route::post('/periode/{periode}/toggle', [AdminPpdbController::class, 'togglePeriode'])->name('periode.toggle');

            Route::get('/pendaftar', [AdminPpdbController::class, 'pendaftarIndex'])->name('pendaftar.index');
            Route::get('/pendaftar-alias', [AdminPpdbController::class, 'pendaftarIndex'])->name('pendaftar');
            Route::get('/pendaftar/{pendaftar}/verifikasi', [AdminPpdbController::class, 'showVerifikasi'])->name('verifikasi.show');
            Route::post('/pendaftar/{pendaftar}/verifikasi/{berkas}', [AdminPpdbController::class, 'verifikasiBerkas'])->name('verifikasi.berkas');
            Route::post('/pendaftar/{pendaftar}/finalisasi', [AdminPpdbController::class, 'finalisasiVerifikasi'])->name('verifikasi.finalisasi');

            Route::get('/seleksi', [AdminPpdbController::class, 'seleksiIndex'])->name('seleksi.index');
            Route::get('/seleksi-alias', [AdminPpdbController::class, 'seleksiIndex'])->name('seleksi');
            Route::post('/seleksi/{pendaftar}', [AdminPpdbController::class, 'tetapkanKelulusan'])->name('kelulusan.simpan');
        });

        // Integrasi / Sync Logs
        Route::get('/sync', [SyncLogController::class, 'index'])->name('sync.index');
        Route::post('/sync/batch', [SyncLogController::class, 'batchSync'])->name('sync.batch');
        Route::post('/sync/{log}/retry', [SyncLogController::class, 'retry'])->name('sync.retry');

        // SIAKAD Admin
        Route::prefix('siakad')->name('siakad.')->group(function () {
            // 1. Siswa
            Route::get('/siswa', [AdminSiakadController::class, 'siswaIndex'])->name('siswa.index');
            Route::post('/siswa/{siswa}/ploting', [AdminSiakadController::class, 'updatePlotingSiswa'])->name('siswa.ploting');

            // 2. Kelas
            Route::get('/kelas', [AdminSiakadController::class, 'kelasIndex'])->name('kelas.index');
            Route::post('/kelas', [AdminSiakadController::class, 'simpanKelas'])->name('kelas.simpan');
            Route::post('/kelas/{kelas}/ploting', [AdminSiakadController::class, 'plotingMassal'])->name('kelas.ploting');

            // 3. Guru
            Route::get('/guru', [AdminSiakadController::class, 'guruIndex'])->name('guru.index');
            Route::post('/guru', [AdminSiakadController::class, 'simpanGuru'])->name('guru.simpan');

            // 4. Mapel
            Route::get('/mapel', [AdminSiakadController::class, 'mapelIndex'])->name('mapel.index');
            Route::post('/mapel', [AdminSiakadController::class, 'simpanMapel'])->name('mapel.simpan');

            // 5. Pengampu
            Route::get('/pengampu', [AdminSiakadController::class, 'pengampuIndex'])->name('pengampu.index');
            Route::post('/pengampu', [AdminSiakadController::class, 'simpanPengampu'])->name('pengampu.simpan');
            Route::delete('/pengampu/{pengampu}', [AdminSiakadController::class, 'hapusPengampu'])->name('pengampu.hapus');
        });

        // Users
        Route::get('/users', fn() => view('admin.users.index'))->name('users.index');
    });

// Rute Portal Guru (Dilindungi Role: guru, admin)
Route::prefix('guru')
    ->name('guru.')
    ->middleware(['auth', 'role:guru,admin'])
    ->group(function () {
        Route::get('/dashboard', [GuruNilaiController::class, 'dashboard'])->name('dashboard');
        Route::get('/pengampu', [GuruNilaiController::class, 'pengampuIndex'])->name('pengampu.index');
        Route::get('/nilai', [GuruNilaiController::class, 'dashboard'])->name('nilai.index');
        Route::get('/nilai/{pengampu}', [GuruNilaiController::class, 'inputNilai'])->name('nilai.input');
        Route::post('/nilai/{pengampu}', [GuruNilaiController::class, 'simpanNilai'])->name('nilai.simpan');
    });

// Rute Portal Siswa SIAKAD (Dilindungi Role: siswa, admin)
Route::prefix('siakad/siswa')
    ->name('siakad.siswa.')
    ->middleware(['auth', 'role:siswa,admin'])
    ->group(function () {
        Route::get('/kelas', [SiswaPortalController::class, 'kelas'])->name('kelas');
        Route::get('/nilai', [SiswaPortalController::class, 'nilai'])->name('nilai');
    });
