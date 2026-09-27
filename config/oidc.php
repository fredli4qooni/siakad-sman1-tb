<?php

return [
    /*
    |--------------------------------------------------------------------------
    | OpenID Connect (OIDC) Issuer & Signing
    |--------------------------------------------------------------------------
    */
    'issuer' => env('OIDC_ISSUER', env('APP_URL', 'http://localhost:8000')),
    'signing_key' => env('OIDC_SIGNING_KEY', env('APP_KEY', 'base64:is3PUqQKYDqkDgAc4lIOpVXjCYMxfAi/zTNvoEk/2nI=')),
    'signing_algorithm' => 'HS256',
    'token_ttl' => 3600, // 1 jam

    /*
    |--------------------------------------------------------------------------
    | Registered OIDC Clients (Relying Parties)
    |--------------------------------------------------------------------------
    | Dua client terdaftar: PPDB dan SIAKAD sesuai arsitektur SRS 3.1
    */
    'clients' => [
        'ppdb_client' => [
            'client_id' => 'ppdb_client',
            'client_secret' => env('PPDB_CLIENT_SECRET', 'ppdb_secret_key_sman1tb_2026'),
            'name' => 'Modul PPDB (Relying Party 1)',
            'redirect_uris' => [
                env('APP_URL', 'http://localhost:8000') . '/ppdb/sso/callback',
            ],
            'allowed_roles' => ['admin', 'operator', 'calon_siswa', 'siswa'],
        ],
        'siakad_client' => [
            'client_id' => 'siakad_client',
            'client_secret' => env('SIAKAD_CLIENT_SECRET', 'siakad_secret_key_sman1tb_2026'),
            'name' => 'Modul SIAKAD (Relying Party 2)',
            'redirect_uris' => [
                env('APP_URL', 'http://localhost:8000') . '/siakad/sso/callback',
            ],
            'allowed_roles' => ['admin', 'operator', 'guru', 'siswa'],
        ],
    ],
];
