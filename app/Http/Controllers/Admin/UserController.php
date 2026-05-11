<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\Intern;
use App\Models\User;
use App\Services\InstitutionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct(
        private readonly InstitutionService $institutionService,
    ) {
    }

    public function index(Request $request)
    {
        $query = User::with(['roles', 'division'])->latest();

        // Filter by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->whereHas('roles', fn($q) => $q->where('name', $request->role));
        }

        // Filter by division
        if ($request->filled('division_id')) {
            $query->where('division_id', $request->division_id);
        }

        $users     = $query->get();
        $roles     = Role::all();
        $divisions = Division::where('is_active', true)->get();

        return view('pages.admin.users.index', compact('users', 'roles', 'divisions'));
    }

    public function create()
    {
        $divisions = Division::where('is_active', true)->get();
        $roles     = Role::all(); // Tampilkan semua role termasuk intern
        return view('pages.admin.users.create', compact('divisions', 'roles'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password'    => ['required', 'confirmed', Rules\Password::defaults()],
            'role'        => ['required', 'exists:roles,name'],
            'division_id' => ['nullable', 'exists:divisions,id'],
        ];

        // Jika role intern, validasi field tambahan
        if ($request->role === 'intern') {
            $rules = array_merge($rules, [
                'type'        => ['required', 'in:siswa,mahasiswa'],
                'institution_id' => ['nullable', 'exists:institutions,id'],
                'institution_manual_name' => ['required_without:institution_id', 'nullable', 'string', 'max:255'],
                'bank_account_number' => ['nullable', 'string', 'max:50'],
                'start_date'  => ['required', 'date'],
                'end_date'    => ['required', 'date', 'after:start_date'],
                'division_id' => ['required', 'exists:divisions,id'],
                'identification_number' => ['required', 'string', 'max:50'],
            ]);

            if ($this->institutionService->requiresBankAccount(
                $request->input('type'),
                $request->input('institution_id')
            )) {
                $rules['bank_account_number'] = ['required', 'string', 'max:50'];
            }
        }

        $request->validate($rules);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'        => $request->name,
                'email'       => $request->email,
                'password'    => Hash::make($request->password),
                'division_id' => $request->division_id,
            ]);

            $user->assignRole($request->role);

            // Jika intern, buat record Intern-nya juga
            if ($request->role === 'intern') {
                $institutionPayload = $this->institutionService->resolveSelection(
                    $request->input('institution_id'),
                    $request->input('institution_manual_name')
                );

                Intern::create([
                    'user_id'            => $user->id,
                    'name'               => $request->name,
                    'email'              => $request->email,
                    'division_id'        => $request->division_id,
                    'type'               => $request->type,
                    'institution'        => $institutionPayload['institution'],
                    'institution_id'     => $institutionPayload['institution_id'],
                    'institution_manual_name' => $institutionPayload['institution_manual_name'],
                    'bank_account_number' => $this->institutionService->requiresBankAccount(
                        $request->input('type'),
                        $institutionPayload['institution_id'] ?? null
                    ) ? $request->input('bank_account_number') : null,
                    'major'              => $request->major,
                    'start_date'         => $request->start_date,
                    'end_date'           => $request->end_date,
                    'nis'                => $request->type === 'siswa' ? $request->identification_number : null,
                    'nim'                => $request->type === 'mahasiswa' ? $request->identification_number : null,
                    'status'             => 'active',
                    'registration_status'=> 'registered',
                    'registered_at'      => now(),
                ]);
            }
        });

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan!');
    }

    public function edit(User $user)
    {
        $divisions = Division::where('is_active', true)->get();
        $roles     = Role::all();
        return view('pages.admin.users.edit', compact('user', 'divisions', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password'    => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role'        => ['required', 'exists:roles,name'],
            'division_id' => ['nullable', 'exists:divisions,id'],
        ]);

        $data = [
            'name'        => $request->name,
            'email'       => $request->email,
            'division_id' => $request->division_id,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        $user->syncRoles($request->role);

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus!');
    }
}
