<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
{
    public function handle(Request $request, Closure $next): Response
    {
        $forwardedProto = strtolower((string) $request->header('X-Forwarded-Proto', ''));

        if ($request->secure() || $forwardedProto === 'https') {
            return $next($request);
        }

        if ($request->isMethod('GET') || $request->isMethod('HEAD')) {
            return redirect()->secure($request->getRequestUri(), 301);
        }

        return $next($request);
    }
}
