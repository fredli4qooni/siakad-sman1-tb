<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\OidcController;
use App\Http\Controllers\Auth\SsoClientController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| OpenID Connect (OIDC) Identity Provider Endpoints
|--------------------------------------------------------------------------
*/
Route::get('/.well-known/openid-configuration', [OidcController::class, 'discovery'])->name('oidc.discovery');

Route::prefix('oauth')->name('oidc.')->group(function () {
    Route::get('/authorize', [OidcController::class, 'authorizeEndpoint'])->name('authorize');
    Route::post('/token', [OidcController::class, 'tokenEndpoint'])->name('token');
    Route::get('/userinfo', [OidcController::class, 'userinfoEndpoint'])->name('userinfo');
});

/*
|--------------------------------------------------------------------------
| Alur SSO Relying Party (PPDB & SIAKAD)
|--------------------------------------------------------------------------
*/
Route::prefix('ppdb/sso')->name('sso.ppdb.')->group(function () {
    Route::get('/login', [SsoClientController::class, 'loginPpdb'])->name('login');
    Route::get('/callback', [SsoClientController::class, 'callbackPpdb'])->name('callback');
});

Route::prefix('siakad/sso')->name('sso.siakad.')->group(function () {
    Route::get('/login', [SsoClientController::class, 'loginSiakad'])->name('login');
    Route::get('/callback', [SsoClientController::class, 'callbackSiakad'])->name('callback');
});

/*
|--------------------------------------------------------------------------
| Portal Autentikasi Terpusat (Modul Auth IdP)
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
