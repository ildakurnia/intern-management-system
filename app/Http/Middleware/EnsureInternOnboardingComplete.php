<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureInternOnboardingComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->hasRole('intern')) {
            return $next($request);
        }

        $intern = $user->intern;

        if (! $intern) {
            abort(403, 'Akun intern belum terhubung dengan data magang. Hubungi admin.');
        }

        if (! $intern->hasCompletedProfile() && ! $request->routeIs('intern.profile.*')) {
            return redirect()->route('intern.profile.edit');
        }

        if ($intern->hasCompletedProfile()
            && ! $intern->hasCompletedDocuments()
            && ! $request->routeIs('intern.documents.*')) {
            return redirect()->route('intern.documents.edit');
        }

        return $next($request);
    }
}
