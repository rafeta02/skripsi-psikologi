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

if (! function_exists('highlight_keywords')) {
    function highlight_keywords(?string $text, string $query): string
    {
        $escaped = e($text ?? '');
        $keywords = preg_split('/\s+/', mb_strtolower(trim($query)));

        foreach ($keywords as $keyword) {
            if (mb_strlen($keyword) < 2) {
                continue;
            }
            $escaped = preg_replace(
                '/(' . preg_quote($keyword, '/') . ')/iu',
                '<mark class="bg-warning px-1">$1</mark>',
                $escaped
            );
        }

        return $escaped;
    }
}
