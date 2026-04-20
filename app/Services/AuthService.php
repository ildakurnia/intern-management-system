<?php

namespace App\Services;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\Intern;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        return $this->dashboardService->resolveDashboardRouteName(Auth::user());
    }

    public function logout(Request $request): void
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
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
