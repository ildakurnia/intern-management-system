<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HasAnyRoleOrPermission
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $routeName = $request->route()?->getName();

        if (! $user) {
            abort(403, 'User belum login.');
        }

        if (! $routeName) {
            abort(403, 'Route ini belum memiliki nama permission.');
        }

        if ($user->hasRole('superadmin') || $user->can($routeName)) {
            return $next($request);
        }

        if ($routeName === 'intern.documents.preview' && $user->can('intern.documents.edit')) {
            return $next($request);
        }

        abort(403, 'Anda tidak memiliki akses ke fitur ini.');
    }
}
