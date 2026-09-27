<?php

use Illuminate\Support\Facades\Route;

// Halaman Publik PPDB
Route::prefix('ppdb')->name('ppdb.')->group(function () {
    Route::get('/alur', function () {
        return view('ppdb.alur');
    })->name('alur');

    Route::get('/pengumuman', function () {
        return view('ppdb.pengumuman');
    })->name('pengumuman');
});

// Portal Calon Siswa / Siswa (Dilindungi Auth & Role)
Route::prefix('pendaftar')
    ->name('pendaftar.')
    ->middleware(['auth', 'role:calon_siswa,siswa,admin,operator'])
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('ppdb.pendaftar.dashboard');
        })->name('dashboard');

        Route::get('/formulir', function () {
            return view('ppdb.pendaftar.formulir');
        })->name('formulir');

        Route::post('/formulir', function () {
            return redirect()->route('pendaftar.berkas')->with('success', 'Formulir berhasil disimpan!');
        });

        Route::get('/berkas', function () {
            return view('ppdb.pendaftar.berkas');
        })->name('berkas');

        Route::get('/kelulusan', function () {
            return view('ppdb.pendaftar.kelulusan');
        })->name('kelulusan');
    });
