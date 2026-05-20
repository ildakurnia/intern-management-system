<?php

namespace App\Services;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\Intern;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(
        private readonly DashboardService $dashboardService,
    ) {
    }

    public function login(LoginRequest $request): string
    {
        $credentials = $request->credentials();
        $email = $this->resolveLoginEmail($credentials['login']);

        if (! $email || ! Auth::attempt(['email' => $email, 'password' => $credentials['password']], $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'login' => __('auth.failed'),
            ]);
        }

        $request->session()->regenerate();
        Cache::forget('ims.active-session-users.15');

        return $this->dashboardService->resolveDashboardRouteName(Auth::user());
    }

    public function logout(Request $request): void
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Cache::forget('ims.active-session-users.'.max((int) config('session.lifetime', 120), 15));
    }

    public function getActiveSessionUsersCount(int $minutes = 15): int
    {
        if (! Schema::hasTable('sessions')) {
            return 0;
        }

        $minutes = max($minutes, (int) config('session.lifetime', $minutes));

        return Cache::remember(
            "ims.active-session-users.{$minutes}",
            now()->addSeconds(30),
            function () use ($minutes): int {
                return (int) DB::table('sessions')
                    ->whereNotNull('user_id')
                    ->where('last_activity', '>=', now()->subMinutes($minutes)->timestamp)
                    ->distinct()
                    ->count('user_id');
            }
        );
    }

    private function resolveLoginEmail(string $login): ?string
    {
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            return $login;
        }

        return Intern::query()
            ->where(function ($query) use ($login): void {
                $query->where('nim', $login)
                    ->orWhere('nis', $login);
            })
            ->whereNotNull('user_id')
            ->with('user:id,email')
            ->first()
            ?->user
            ?->email;
    }
}
