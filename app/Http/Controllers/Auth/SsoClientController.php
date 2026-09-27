<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OidcService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SsoClientController extends Controller
{
    protected OidcService $oidcService;

    public function __construct(OidcService $oidcService)
    {
        $this->oidcService = $oidcService;
    }

    /**
     * Memulai alur OIDC Authorization Code dari Modul PPDB (Relying Party 1).
     */
    public function loginPpdb(Request $request): RedirectResponse
    {
        $state = Str::random(32);
        session(['oidc_ppdb_state' => $state]);

        $redirectUri = url('/ppdb/sso/callback');
        $authUrl = url('/oauth/authorize') . '?' . http_build_query([
            'response_type' => 'code',
            'client_id' => 'ppdb_client',
            'redirect_uri' => $redirectUri,
            'scope' => 'openid profile email role',
            'state' => $state,
        ]);

        return redirect()->away($authUrl);
    }

    /**
     * Callback penukaran authorization code menjadi token OIDC untuk Modul PPDB.
     */
    public function callbackPpdb(Request $request): RedirectResponse
    {
        $code = $request->query('code');
        $state = $request->query('state');
        $savedState = session('oidc_ppdb_state');

        if (!$code || !$state || $state !== $savedState) {
            return redirect()->route('auth.login')->with('error', 'Validasi status SSO gagal atau token kedaluwarsa.');
        }

        session()->forget('oidc_ppdb_state');

        // Tukar kode dengan Token via OidcService
        $client = config('oidc.clients.ppdb_client');
        $redirectUri = url('/ppdb/sso/callback');

        $authCodeData = $this->oidcService->consumeAuthorizationCode($code);
        if (!$authCodeData) {
            return redirect()->route('auth.login')->with('error', 'Kode otorisasi tidak valid.');
        }

        $user = User::find($authCodeData['user_id']);
        if (!$user || !$user->status_aktif) {
            return redirect()->route('auth.login')->with('error', 'Akun pengguna tidak aktif.');
        }

        // Terbitkan dan verifikasi ID Token
        $idToken = $this->oidcService->issueIdToken($user, 'ppdb_client');
        $verifiedClaims = $this->oidcService->verifyIdToken($idToken, 'ppdb_client');

        if (!$verifiedClaims) {
            return redirect()->route('auth.login')->with('error', 'Verifikasi tanda tangan ID Token OIDC gagal.');
        }

        // Login sesi di RP PPDB
        Auth::login($user);
        $request->session()->regenerate();
        session(['oidc_id_token' => $idToken]);

        // Arahkan ke dashboard berdasarkan peran
        if ($user->hasAdminAccess()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('pendaftar.dashboard');
    }

    /**
     * Memulai alur OIDC Authorization Code dari Modul SIAKAD (Relying Party 2).
     */
    public function loginSiakad(Request $request): RedirectResponse
    {
        $state = Str::random(32);
        session(['oidc_siakad_state' => $state]);

        $redirectUri = url('/siakad/sso/callback');
        $authUrl = url('/oauth/authorize') . '?' . http_build_query([
            'response_type' => 'code',
            'client_id' => 'siakad_client',
            'redirect_uri' => $redirectUri,
            'scope' => 'openid profile email role',
            'state' => $state,
        ]);

        return redirect()->away($authUrl);
    }

    /**
     * Callback penukaran authorization code menjadi token OIDC untuk Modul SIAKAD.
     */
    public function callbackSiakad(Request $request): RedirectResponse
    {
        $code = $request->query('code');
        $state = $request->query('state');
        $savedState = session('oidc_siakad_state');

        if (!$code || !$state || $state !== $savedState) {
            return redirect()->route('auth.login')->with('error', 'Validasi sesi SSO SIAKAD gagal.');
        }

        session()->forget('oidc_siakad_state');

        $authCodeData = $this->oidcService->consumeAuthorizationCode($code);
        if (!$authCodeData) {
            return redirect()->route('auth.login')->with('error', 'Kode otorisasi SIAKAD tidak valid.');
        }

        $user = User::find($authCodeData['user_id']);
        if (!$user || !$user->status_aktif) {
            return redirect()->route('auth.login')->with('error', 'Pengguna tidak ditemukan.');
        }

        $idToken = $this->oidcService->issueIdToken($user, 'siakad_client');
        $verifiedClaims = $this->oidcService->verifyIdToken($idToken, 'siakad_client');

        if (!$verifiedClaims) {
            return redirect()->route('auth.login')->with('error', 'ID Token SIAKAD tidak terverifikasi.');
        }

        Auth::login($user);
        $request->session()->regenerate();
        session(['oidc_id_token' => $idToken]);

        // Arahkan ke dashboard berdasarkan peran di SIAKAD
        if ($user->hasAdminAccess()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isGuru()) {
            return redirect()->route('guru.dashboard');
        } else {
            return redirect()->route('siakad.siswa.kelas');
        }
    }
}
