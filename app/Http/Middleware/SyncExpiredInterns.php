<?php

namespace App\Http\Middleware;

use App\Models\Intern;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SyncExpiredInterns
{
    public function handle(Request $request, Closure $next): Response
    {
        Intern::query()
            ->where('status', 'active')
            ->whereDate('end_date', '<', today()->toDateString())
            ->update([
                'status' => 'completed',
            ]);

        return $next($request);
    }
}
