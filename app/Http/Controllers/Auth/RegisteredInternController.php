<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Intern;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisteredInternController extends Controller
{
    public function create(): View
    {
        return view('auth.register-intern');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'identifier' => ['required', 'string', 'max:50'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $intern = Intern::query()
            ->where('email', $validated['email'])
            ->where(function ($query) use ($validated): void {
                $query->where('nim', $validated['identifier'])
                    ->orWhere('nis', $validated['identifier']);
            })
            ->first();

        if (! $intern) {
            return back()
                ->withErrors(['email' => 'Email dan NIM/NIS tidak cocok dengan data intern yang diimport admin.'])
                ->onlyInput('email', 'identifier');
        }

        if ($intern->user_id !== null) {
            return back()
                ->withErrors(['email' => 'Data intern ini sudah pernah registrasi. Silakan login.'])
                ->onlyInput('email', 'identifier');
        }

        DB::transaction(function () use ($intern, $validated): void {
            $user = User::create([
                'name' => $intern->name,
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'division_id' => $intern->division_id,
            ]);

            $user->assignRole('intern');

            $intern->forceFill([
                'user_id' => $user->id,
                'registration_status' => 'registered',
                'registered_at' => now(),
            ])->save();
        });

        return redirect()
            ->route('login')
            ->with('status', 'Registrasi berhasil. Silakan login dengan email atau NIM/NIS.');
    }
}
