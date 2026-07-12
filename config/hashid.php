<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Hashids salt
    |--------------------------------------------------------------------------
    | Digunakan untuk encode/decode ID di URL mahasiswa & dosen.
    | Jangan diganti setelah production live (link lama akan invalid).
    */
    'salt' => env('HASHID_SALT', env('APP_KEY')),

    'min_length' => (int) env('HASHID_MIN_LENGTH', 8),

    'alphabet' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',

    /*
    |--------------------------------------------------------------------------
    | Route name prefixes that should use hashed IDs in generated URLs
    |--------------------------------------------------------------------------
    */
    'route_prefixes' => [
        'frontend.',
        'dosen.',
    ],
];
