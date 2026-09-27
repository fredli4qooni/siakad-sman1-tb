<?php

use Illuminate\Support\Facades\Route;

// Rute Admin & Operator
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // PPDB Admin
    Route::prefix('ppdb')->name('ppdb.')->group(function () {
        Route::get('/periode', fn() => view('admin.ppdb.periode'))->name('periode.index');
        Route::get('/pendaftar', fn() => view('admin.ppdb.pendaftar'))->name('pendaftar.index');
        Route::get('/seleksi', fn() => view('admin.ppdb.seleksi'))->name('seleksi.index');
    });

    // Integrasi / Sync Logs
    Route::get('/sync', fn() => view('admin.sync.index'))->name('sync.index');

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

// Rute Portal Guru
Route::prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', fn() => view('guru.dashboard'))->name('dashboard');
    Route::get('/pengampu', fn() => view('guru.pengampu'))->name('pengampu.index');
    Route::get('/nilai', fn() => view('guru.nilai'))->name('nilai.index');
});

// Rute Portal Siswa SIAKAD
Route::prefix('siakad/siswa')->name('siakad.siswa.')->group(function () {
    Route::get('/kelas', fn() => view('siakad.siswa.kelas'))->name('kelas');
    Route::get('/nilai', fn() => view('siakad.siswa.nilai'))->name('nilai');
});
