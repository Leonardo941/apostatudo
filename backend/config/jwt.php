<?php

return [
    'secret' => env('JWT_SECRET'),

    'keys' => [
        'public' => env('JWT_PUBLIC_KEY'),
        'private' => env('JWT_PRIVATE_KEY'),
        'passphrase' => env('JWT_PASSPHRASE'),
    ],

    'ttl' => (int) env('JWT_TTL', 60),
    'refresh_iat' => false,
    'refresh_ttl' => (int) env('JWT_REFRESH_TTL', 20160),
    'algo' => env('JWT_ALGO', 'HS256'),

    'required_claims' => ['iss', 'iat', 'exp', 'nbf', 'sub', 'jti'],
    'persistent_claims' => [],

    'lock_subject' => true,
    'leeway' => 0,

    'blacklist_enabled' => false,
    'blacklist_grace_period' => 0,

    'decrypt_cookies' => false,
    'cookie_key_name' => 'token',

    'providers' => [
        'jwt' => PHPOpenSourceSaver\JWTAuth\Providers\JWT\Lcobucci::class,
        'auth' => PHPOpenSourceSaver\JWTAuth\Providers\Auth\Illuminate::class,
        'storage' => PHPOpenSourceSaver\JWTAuth\Providers\Storage\Illuminate::class,
    ],
];
