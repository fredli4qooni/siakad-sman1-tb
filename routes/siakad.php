<?php

use App\Http\Controllers\Auth\AdminUserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Ppdb\AdminPpdbController;
use App\Http\Controllers\Siakad\AdminSiakadController;
use App\Http\Controllers\Siakad\GuruNilaiController;
use App\Http\Controllers\Siakad\SiswaPortalController;
use App\Http\Controllers\Siakad\SyncLogController;
use Illuminate\Support\Facades\Route;

// Login Mandiri SIAKAD (Siswa Aktif, Guru, Admin)
Route::get('/siakad/login', [AuthController::class, 'showSiakadLoginForm'])->name('siakad.login');
Route::post('/siakad/login', [AuthController::class, 'loginSiakad'])->name('siakad.login.post');

// Rute Admin & Operator (Dilindungi Role: admin, operator)
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin,operator'])
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // SPMB & PPDB Admin
        $spmbRoutes = function () {
            Route::get('/periode', [AdminPpdbController::class, 'periodeIndex'])->name('periode.index');
            Route::get('/periode-alias', [AdminPpdbController::class, 'periodeIndex'])->name('periode');
            Route::post('/periode', [AdminPpdbController::class, 'simpanPeriode'])->name('periode.simpan');
            Route::post('/periode/{periode}/toggle', [AdminPpdbController::class, 'togglePeriode'])->name('periode.toggle');

            Route::get('/pendaftar', [AdminPpdbController::class, 'pendaftarIndex'])->name('pendaftar.index');
            Route::get('/pendaftar-alias', [AdminPpdbController::class, 'pendaftarIndex'])->name('pendaftar');
            Route::get('/pendaftar/{pendaftar}/verifikasi', [AdminPpdbController::class, 'showVerifikasi'])->name('verifikasi.show');
            Route::post('/pendaftar/{pendaftar}/verifikasi/{berkas}', [AdminPpdbController::class, 'verifikasiBerkas'])->name('verifikasi.berkas');
            Route::post('/pendaftar/{pendaftar}/finalisasi', [AdminPpdbController::class, 'finalisasiVerifikasi'])->name('verifikasi.finalisasi');
            Route::post('/pendaftar/{pendaftar}/jadwal-fisik', [AdminPpdbController::class, 'aturJadwalFisik'])->name('verifikasi.jadwal_fisik');

            Route::get('/seleksi', [AdminPpdbController::class, 'seleksiIndex'])->name('seleksi.index');
            Route::get('/seleksi-alias', [AdminPpdbController::class, 'seleksiIndex'])->name('seleksi');
            Route::post('/seleksi/{pendaftar}', [AdminPpdbController::class, 'tetapkanKelulusan'])->name('kelulusan.simpan');
        };

        Route::prefix('spmb')->name('spmb.')->group($spmbRoutes);
        Route::prefix('ppdb')->name('ppdb.')->group($spmbRoutes);

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

        // Manajemen Pengguna & Akun Admin (Khusus Admin)
        Route::middleware('role:admin')->group(function () {
            Route::get('/admins', [AdminUserController::class, 'index'])->name('admins.index');
            Route::post('/admins', [AdminUserController::class, 'store'])->name('admins.store');
            Route::post('/admins/{user}/toggle', [AdminUserController::class, 'toggleStatus'])->name('admins.toggle');
            Route::post('/admins/{user}/password', [AdminUserController::class, 'resetPassword'])->name('admins.password');
            Route::delete('/admins/{user}', [AdminUserController::class, 'destroy'])->name('admins.destroy');
        });

        Route::get('/users', [AdminUserController::class, 'indexAll'])->name('users.index');
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
        Route::get('/dashboard', [SiswaPortalController::class, 'dashboard'])->name('dashboard');
        Route::get('/kelas', [SiswaPortalController::class, 'kelas'])->name('kelas');
        Route::get('/nilai', [SiswaPortalController::class, 'nilai'])->name('nilai');
    });
