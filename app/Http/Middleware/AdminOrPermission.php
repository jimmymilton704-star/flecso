<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOrPermission
{
    public function handle(Request $request, Closure $next, string $permissions): Response
    {
        $user = auth()->user();

        if (!$user) {
            abort(403, 'Unauthorized access');
        }

        /*
        |--------------------------------------------------------------------------
        | Admin bypass
        |--------------------------------------------------------------------------
        */
        if ($user->role === 'admin') {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | Spatie admin role bypass
        |--------------------------------------------------------------------------
        */
        if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | Multiple permission support
        |--------------------------------------------------------------------------
        | Example:
        | admin_or_permission:sos_list|sos_view|sos_update
        |--------------------------------------------------------------------------
        */
        $permissionList = explode('|', $permissions);

        foreach ($permissionList as $permission) {
            $permission = trim($permission);

            if ($permission !== '' && $user->can($permission)) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized access');
    }
}