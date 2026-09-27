<?php

use App\Http\Controllers\Ppdb\AdminPpdbController;
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
            Route::get('/siswa', fn() => view('admin.siakad.siswa'))->name('siswa.index');
            Route::get('/kelas', fn() => view('admin.siakad.kelas'))->name('kelas.index');
            Route::get('/guru', fn() => view('admin.siakad.guru'))->name('guru.index');
            Route::get('/mapel', fn() => view('admin.siakad.mapel'))->name('mapel.index');
            Route::get('/pengampu', fn() => view('admin.siakad.pengampu'))->name('pengampu.index');
        });

        // Users
        Route::get('/users', fn() => view('admin.users.index'))->name('users.index');
    });

// Rute Portal Guru (Dilindungi Role: guru, admin)
Route::prefix('guru')
    ->name('guru.')
    ->middleware(['auth', 'role:guru,admin'])
    ->group(function () {
        Route::get('/dashboard', fn() => view('guru.dashboard'))->name('dashboard');
        Route::get('/pengampu', fn() => view('guru.pengampu'))->name('pengampu.index');
        Route::get('/nilai', fn() => view('guru.nilai'))->name('nilai.index');
    });

// Rute Portal Siswa SIAKAD (Dilindungi Role: siswa, admin)
Route::prefix('siakad/siswa')
    ->name('siakad.siswa.')
    ->middleware(['auth', 'role:siswa,admin'])
    ->group(function () {
        Route::get('/kelas', fn() => view('siakad.siswa.kelas'))->name('kelas');
        Route::get('/nilai', fn() => view('siakad.siswa.nilai'))->name('nilai');
    });
