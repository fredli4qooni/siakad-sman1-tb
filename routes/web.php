<?php

use App\Http\Controllers\Ppdb\PublicPpdbController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicPpdbController::class, 'index'])->name('home');
