<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HasAnyRoleOrPermissionMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika user belum login, biarkan middleware 'auth' yang menangani
        if (!$request->user()) {
            return $next($request);
        }

        // Cek apakah user punya setidaknya 1 role (RBAC aktif)
        if ($request->user()->roles()->count() > 0) {
            return $next($request);
        }

        // Jika tidak punya hak akses sama sekali
        abort(403, 'User does not have any assigned roles.');
    }
}
