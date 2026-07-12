<?php

use App\Support\HashId;

if (! function_exists('hid')) {
    /**
     * Encode numeric ID for mahasiswa/dosen URLs.
     */
    function hid(int|string|null $id): ?string
    {
        if ($id === null || $id === '') {
            return null;
        }

        return HashId::encode((int) $id);
    }
}

if (! function_exists('hid_decode')) {
    /**
     * Decode hashed ID (or passthrough numeric) to integer.
     */
    function hid_decode(mixed $value): ?int
    {
        return HashId::decode($value);
    }
}
