<?php

use App\Http\Middleware\EnsureUserHasRole;
use App\Services\DashboardService;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => EnsureUserHasRole::class,
        ]);

        $middleware->redirectGuestsTo(fn () => route('login'));
        $middleware->redirectUsersTo(
            fn (Request $request) => route(app(DashboardService::class)->resolveDashboardRouteName($request->user()))
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
