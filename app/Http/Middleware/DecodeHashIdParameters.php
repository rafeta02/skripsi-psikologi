<?php

namespace App\Http\Middleware;

use App\Support\HashId;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Decode hashed route parameters on mahasiswa/dosen/frontend URLs
 * before SubstituteBindings resolves Eloquent models.
 */
class DecodeHashIdParameters
{
    public function handle(Request $request, Closure $next): Response
    {
        $route = $request->route();

        if (! $route) {
            return $next($request);
        }

        // Only for mahasiswa, dosen, and leftover frontend paths
        if (! $request->is('mahasiswa/*') && ! $request->is('dosen/*') && ! $request->is('frontend/*')) {
            return $next($request);
        }

        foreach ($route->parameters() as $key => $value) {
            if (! is_string($value) && ! is_int($value)) {
                continue;
            }

            // Already a model instance
            if (is_object($value)) {
                continue;
            }

            // Keep pure numeric for backward compatibility
            if (is_int($value) || (is_string($value) && ctype_digit($value))) {
                continue;
            }

            if (! is_string($value)) {
                continue;
            }

            $decoded = HashId::decode($value);
            if ($decoded !== null) {
                $route->setParameter($key, (string) $decoded);
            }
        }

        return $next($request);
    }
}
