<?php

use Illuminate\Support\Facades\Route;

Route::prefix('ppdb')->name('ppdb.')->group(function () {
    Route::get('/alur', function () {
        return view('ppdb.alur');
    })->name('alur');

    Route::get('/pengumuman', function () {
        return view('ppdb.pengumuman');
    })->name('pengumuman');
});

Route::prefix('pendaftar')->name('pendaftar.')->group(function () {
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
