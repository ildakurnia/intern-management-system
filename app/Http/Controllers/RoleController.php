<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with(['permissions', 'users'])->get();
        $permissionMenus = $this->permissionMenus();

        return view('pages.roles.index', compact('roles', 'permissionMenus'));
    }

    public function create()
    {
        $permissionMenus = $this->permissionMenus();
        $unassignedPermissions = \App\Models\Permission::query()
            ->whereDoesntHave('menu')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        $rolePermissions = [];

        return view('pages.roles.create', compact('permissionMenus', 'unassignedPermissions', 'rolePermissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')
                    ->where(fn ($query) => $query->where('guard_name', 'web')),
            ],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::exists('permissions', 'name')],
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'guard_name' => 'web',
        ]);

        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect()->route('roles.index')->with('status', 'Role berhasil dibuat.');
    }

    public function edit(Role $role)
    {
        $permissionMenus = $this->permissionMenus();
        $unassignedPermissions = \App\Models\Permission::query()
            ->whereDoesntHave('menu')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('pages.roles.edit', compact('role', 'permissionMenus', 'unassignedPermissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')
                    ->ignore($role->id)
                    ->where(fn ($query) => $query->where('guard_name', $role->guard_name)),
            ],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::exists('permissions', 'name')],
        ]);

        $role->update(['name' => $validated['name']]);
        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect()->route('roles.index')->with('status', 'Role berhasil diperbarui.');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('status', 'Role berhasil dihapus.');
    }

    /**
     * @return Collection<int, Menu>
     */
    private function permissionMenus(): Collection
    {
        return Menu::query()
            ->with(['permissions' => fn ($query) => $query->orderBy('sort_order')->orderBy('name')])
            ->orderBy('order')
            ->orderBy('title')
            ->get();
    }
}
