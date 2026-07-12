<?php

namespace App\Support;

use Hashids\Hashids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class HashId
{
    protected static ?Hashids $instance = null;

    public static function driver(): Hashids
    {
        if (static::$instance) {
            return static::$instance;
        }

        $salt = (string) config('hashid.salt', config('app.key'));
        $length = (int) config('hashid.min_length', 8);
        $alphabet = (string) config('hashid.alphabet', 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890');

        return static::$instance = new Hashids($salt, $length, $alphabet);
    }

    public static function encode(int|string $id): string
    {
        return static::driver()->encode((int) $id);
    }

    /**
     * Decode hash (or passthrough numeric) to integer id.
     */
    public static function decode(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_int($value) || (is_string($value) && ctype_digit($value))) {
            return (int) $value;
        }

        if (! is_string($value)) {
            return null;
        }

        $decoded = static::driver()->decode($value);

        return $decoded[0] ?? null;
    }

    public static function decodeOrFail(mixed $value): int
    {
        $id = static::decode($value);

        if ($id === null || $id < 1) {
            abort(404);
        }

        return $id;
    }

    public static function shouldHashRoute(?string $routeName): bool
    {
        if (! $routeName) {
            return false;
        }

        foreach (config('hashid.route_prefixes', ['frontend.', 'dosen.']) as $prefix) {
            if (str_starts_with($routeName, $prefix)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Encode numeric / model parameters for hashed frontend routes.
     *
     * @param  array<string|int, mixed>  $parameters
     * @return array<string|int, mixed>
     */
    public static function encodeParameters(array $parameters): array
    {
        $encoded = [];

        foreach ($parameters as $key => $value) {
            if ($value instanceof Model) {
                $encoded[$key] = static::encode((int) $value->getKey());
                continue;
            }

            if (is_int($value) || (is_string($value) && ctype_digit($value))) {
                $encoded[$key] = static::encode((int) $value);
                continue;
            }

            if (is_array($value)) {
                $encoded[$key] = static::encodeParameters($value);
                continue;
            }

            $encoded[$key] = $value;
        }

        return $encoded;
    }
}
