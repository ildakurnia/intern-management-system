<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Models\Menu;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $data = $this->permissionService->getPermissionGroups($search ?? '');
        $permissionMenus = $data['permissionMenus'];
        $unassignedPermissions = $data['unassignedPermissions'];

        return view('pages.permissions.index', compact('permissionMenus', 'unassignedPermissions', 'search'));
    }

    public function create()
    {
        $menus = Menu::orderBy('order')->orderBy('title')->get();
        return view('pages.permissions.create', compact('menus'));
    }

    public function store(StorePermissionRequest $request)
    {
        $this->permissionService->createPermission($request->validated());

        return redirect()->route('permissions.index')->with('status', 'Permission dibuat.');
    }

    public function edit(Permission $permission)
    {
        $menus = Menu::orderBy('order')->orderBy('title')->get();
        return view('pages.permissions.edit', compact('permission', 'menus'));
    }

    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        $this->permissionService->updatePermission($permission, $request->validated());

        return redirect()->route('permissions.index')->with('status', 'Permission diperbarui.');
    }

    public function destroy(Permission $permission)
    {
        $this->permissionService->deletePermission($permission);
        return redirect()->route('permissions.index')->with('status', 'Permission dihapus.');
    }
}
