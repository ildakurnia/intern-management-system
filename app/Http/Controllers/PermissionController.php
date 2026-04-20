<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissionMenus = $this->permissionMenus();

        return view('pages.permissions.index', compact('permissionMenus'));
    }

    public function create()
    {
        $menus = Menu::orderBy('order')->orderBy('title')->get();

        return view('pages.permissions.create', compact('menus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('permissions', 'name')
                    ->where(fn ($query) => $query->where('guard_name', $request->input('guard_name', 'web'))),
            ],
            'guard_name' => ['required', 'string', 'in:web'],
            'menu_id' => ['nullable', 'exists:menus,id'],
            'label' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        Permission::create([
            'name' => $validated['name'],
            'guard_name' => $validated['guard_name'],
            'menu_id' => $validated['menu_id'] ?? null,
            'label' => $validated['label'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('permissions.index')->with('status', 'Permission dibuat.');
    }

    public function edit(Permission $permission)
    {
        $menus = Menu::orderBy('order')->orderBy('title')->get();

        return view('pages.permissions.edit', compact('permission', 'menus'));
    }

    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('permissions', 'name')
                    ->ignore($permission->id)
                    ->where(fn ($query) => $query->where('guard_name', $request->input('guard_name', $permission->guard_name))),
            ],
            'guard_name' => ['required', 'string', 'in:web'],
            'menu_id' => ['nullable', 'exists:menus,id'],
            'label' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $permission->update([
            'name' => $validated['name'],
            'guard_name' => $validated['guard_name'],
            'menu_id' => $validated['menu_id'] ?? null,
            'label' => $validated['label'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('permissions.index')->with('status', 'Permission diperbarui.');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('permissions.index')->with('status', 'Permission dihapus.');
    }

    private function permissionMenus()
    {
        return Menu::query()
            ->with(['permissions' => fn ($query) => $query->orderBy('sort_order')->orderBy('name')])
            ->orderBy('order')
            ->orderBy('title')
            ->get();
    }
}
