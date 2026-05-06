<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateBroadcast
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
{
    // ✅ Check ADMIN (sanctum)
    if (auth('sanctum')->check()) {
        return $next($request);
    }

    // ✅ Check DRIVER
    if (auth('driver')->check()) {
        return $next($request);
    }

    return response()->json(['message' => 'Unauthenticated'], 401);
}
}
