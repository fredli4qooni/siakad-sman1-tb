<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OidcService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OidcController extends Controller
{
    protected OidcService $oidcService;

    public function __construct(OidcService $oidcService)
    {
        $this->oidcService = $oidcService;
    }

    /**
     * Endpoint Discovery OIDC (.well-known/openid-configuration).
     */
    public function discovery(): JsonResponse
    {
        $issuer = config('oidc.issuer', config('app.url', 'http://localhost:8000'));

        return response()->json([
            'issuer' => $issuer,
            'authorization_endpoint' => $issuer . '/oauth/authorize',
            'token_endpoint' => $issuer . '/oauth/token',
            'userinfo_endpoint' => $issuer . '/oauth/userinfo',
            'end_session_endpoint' => $issuer . '/auth/logout',
            'response_types_supported' => ['code'],
            'subject_types_supported' => ['public'],
            'id_token_signing_alg_values_supported' => ['HS256'],
            'scopes_supported' => ['openid', 'profile', 'email', 'role'],
            'token_endpoint_auth_methods_supported' => ['client_secret_post', 'client_secret_basic'],
            'claims_supported' => [
                'sub',
                'iss',
                'aud',
                'exp',
                'iat',
                'auth_time',
                'nama',
                'email',
                'role',
                'status_aktif',
            ],
        ]);
    }

    /**
     * Endpoint Authorize OIDC (/oauth/authorize).
     */
    public function authorizeEndpoint(Request $request): RedirectResponse
    {
        $clientId = $request->query('client_id');
        $redirectUri = $request->query('redirect_uri');
        $responseType = $request->query('response_type', 'code');
        $state = $request->query('state', '');
        $scope = $request->query('scope', 'openid profile email role');

        // Validasi parameter wajib
        if (!$clientId || !$redirectUri || $responseType !== 'code') {
            return redirect()->route('auth.login')->with('error', 'Parameter permintaan OIDC tidak valid.');
        }

        // Validasi client dan redirect_uri
        if (!$this->oidcService->validateRedirectUri($clientId, $redirectUri)) {
            return redirect()->route('auth.login')->with('error', 'Client ID atau Redirect URI tidak terdaftar.');
        }

        // Cek apakah pengguna sudah login di IdP (SSO Active)
        if (Auth::check()) {
            $user = Auth::user();

            if (!$user->status_aktif) {
                Auth::logout();
                return redirect()->route('auth.login')->with('error', 'Akun Anda dinonaktifkan.');
            }

            // Terbitkan authorization code
            $code = $this->oidcService->createAuthorizationCode(
                userId: $user->id,
                clientId: $clientId,
                redirectUri: $redirectUri,
                scope: $scope
            );

            // Redirect kembali ke Relying Party (RP) dengan code dan state
            $separator = str_contains($redirectUri, '?') ? '&' : '?';
            $callbackUrl = $redirectUri . $separator . http_build_query([
                'code' => $code,
                'state' => $state,
            ]);

            return redirect()->away($callbackUrl);
        }

        // Jika belum login di IdP, simpan permintaan OIDC ke session dan arahkan ke login
        session(['oidc_auth_request' => $request->all()]);

        return redirect()->route('auth.login');
    }

    /**
     * Endpoint Token OIDC (/oauth/token).
     */
    public function tokenEndpoint(Request $request): JsonResponse
    {
        $grantType = $request->input('grant_type');
        $clientId = $request->input('client_id') ?? $request->getUser();
        $clientSecret = $request->input('client_secret') ?? $request->getPassword();
        $code = $request->input('code');
        $redirectUri = $request->input('redirect_uri');

        if ($grantType !== 'authorization_code') {
            return response()->json([
                'error' => 'unsupported_grant_type',
                'error_description' => 'Hanya grant_type authorization_code yang didukung.',
            ], 400);
        }

        if (!$clientId || !$clientSecret || !$this->oidcService->validateClient($clientId, $clientSecret)) {
            return response()->json([
                'error' => 'invalid_client',
                'error_description' => 'Kredensial client tidak valid.',
            ], 401);
        }

        $authCodeData = $this->oidcService->consumeAuthorizationCode($code);
        if (!$authCodeData) {
            return response()->json([
                'error' => 'invalid_grant',
                'error_description' => 'Authorization code tidak valid atau telah kedaluwarsa.',
            ], 400);
        }

        if ($authCodeData['client_id'] !== $clientId || $authCodeData['redirect_uri'] !== $redirectUri) {
            return response()->json([
                'error' => 'invalid_grant',
                'error_description' => 'Client ID atau Redirect URI tidak cocok dengan kode otorisasi.',
            ], 400);
        }

        $user = User::find($authCodeData['user_id']);
        if (!$user || !$user->status_aktif) {
            return response()->json([
                'error' => 'invalid_user',
                'error_description' => 'Pengguna tidak ditemukan atau nonaktif.',
            ], 400);
        }

        $idToken = $this->oidcService->issueIdToken($user, $clientId);
        $accessToken = $this->oidcService->issueAccessToken($user, $clientId, $authCodeData['scope']);

        return response()->json([
            'access_token' => $accessToken,
            'token_type' => 'Bearer',
            'expires_in' => 3600,
            'id_token' => $idToken,
            'scope' => $authCodeData['scope'],
        ]);
    }

    /**
     * Endpoint Userinfo OIDC (/oauth/userinfo).
     */
    public function userinfoEndpoint(Request $request): JsonResponse
    {
        $authHeader = $request->header('Authorization', '');
        if (!str_starts_with($authHeader, 'Bearer ')) {
            return response()->json(['error' => 'unauthorized', 'error_description' => 'Bearer token diperlukan.'], 401);
        }

        $token = substr($authHeader, 7);
        $payload = $this->oidcService->verifyAccessToken($token);

        if (!$payload) {
            return response()->json(['error' => 'invalid_token', 'error_description' => 'Token tidak valid atau kedaluwarsa.'], 401);
        }

        $user = User::find($payload['sub']);
        if (!$user) {
            return response()->json(['error' => 'user_not_found'], 404);
        }

        return response()->json([
            'sub' => (string) $user->id,
            'nama' => $user->nama,
            'email' => $user->email,
            'role' => $user->role,
            'status_aktif' => (bool) $user->status_aktif,
        ]);
    }
}
