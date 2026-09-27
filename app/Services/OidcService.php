<?php

namespace App\Services;

use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class OidcService
{
    protected string $issuer;
    protected string $signingKey;
    protected string $algorithm;
    protected int $tokenTtl;
    protected array $clients;

    public function __construct()
    {
        $this->issuer = config('oidc.issuer', config('app.url', 'http://localhost:8000'));
        $this->signingKey = config('oidc.signing_key', config('app.key'));
        $this->algorithm = config('oidc.signing_algorithm', 'HS256');
        $this->tokenTtl = (int) config('oidc.token_ttl', 3600);
        $this->clients = config('oidc.clients', []);
    }

    /**
     * Dapatkan detail client berdasarkan client_id.
     */
    public function getClient(string $clientId): ?array
    {
        return $this->clients[$clientId] ?? null;
    }

    /**
     * Validasi kredensial client (client_id & client_secret).
     */
    public function validateClient(string $clientId, string $clientSecret): bool
    {
        $client = $this->getClient($clientId);
        if (!$client) {
            return false;
        }

        return hash_equals($client['client_secret'], $clientSecret);
    }

    /**
     * Validasi redirect_uri client.
     */
    public function validateRedirectUri(string $clientId, string $redirectUri): bool
    {
        $client = $this->getClient($clientId);
        if (!$client) {
            return false;
        }

        return in_array($redirectUri, $client['redirect_uris'], true);
    }

    /**
     * Terbitkan authorization code baru dengan masa berlaku 5 menit.
     */
    public function createAuthorizationCode(int $userId, string $clientId, string $redirectUri, ?string $scope = null): string
    {
        $code = Str::random(40);
        $payload = [
            'user_id' => $userId,
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'scope' => $scope ?? 'openid profile email role',
            'created_at' => now()->timestamp,
        ];

        Cache::put("oidc_code_{$code}", $payload, now()->addMinutes(5));

        return $code;
    }

    /**
     * Konsumsi (ambil & hapus) authorization code (one-time use).
     */
    public function consumeAuthorizationCode(string $code): ?array
    {
        $key = "oidc_code_{$code}";
        $data = Cache::get($key);
        if ($data) {
            Cache::forget($key);
            return $data;
        }

        return null;
    }

    /**
     * Terbitkan signed ID Token (JWT) standar OIDC.
     */
    public function issueIdToken(User $user, string $clientId): string
    {
        $now = time();
        $payload = [
            'iss' => $this->issuer,
            'sub' => (string) $user->id,
            'aud' => $clientId,
            'exp' => $now + $this->tokenTtl,
            'iat' => $now,
            'auth_time' => $now,
            'nama' => $user->nama,
            'email' => $user->email,
            'role' => $user->role,
            'status_aktif' => (bool) $user->status_aktif,
        ];

        return JWT::encode($payload, $this->signingKey, $this->algorithm);
    }

    /**
     * Terbitkan Access Token (JWT Bearer Token).
     */
    public function issueAccessToken(User $user, string $clientId, string $scope = 'openid'): string
    {
        $now = time();
        $payload = [
            'iss' => $this->issuer,
            'sub' => (string) $user->id,
            'aud' => $clientId,
            'scope' => $scope,
            'exp' => $now + $this->tokenTtl,
            'iat' => $now,
            'jti' => Str::random(32),
        ];

        return JWT::encode($payload, $this->signingKey, $this->algorithm);
    }

    /**
     * Verifikasi & decode ID Token JWT.
     */
    public function verifyIdToken(string $idToken, string $clientId): ?array
    {
        try {
            $decoded = JWT::decode($idToken, new Key($this->signingKey, $this->algorithm));
            $payload = (array) $decoded;

            // Validasi issuer & audience
            if (($payload['iss'] ?? null) !== $this->issuer) {
                return null;
            }

            if (($payload['aud'] ?? null) !== $clientId) {
                return null;
            }

            return $payload;
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Verifikasi & decode Access Token.
     */
    public function verifyAccessToken(string $accessToken): ?array
    {
        try {
            $decoded = JWT::decode($accessToken, new Key($this->signingKey, $this->algorithm));
            $payload = (array) $decoded;

            if (($payload['iss'] ?? null) !== $this->issuer) {
                return null;
            }

            return $payload;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
