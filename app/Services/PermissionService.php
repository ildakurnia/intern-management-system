<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Collection;

class PermissionService
{
    /**
     * Get permission groups for the index page
     */
    public function getPermissionGroups(string $search = ''): array
    {
        $permissionMenus = Menu::with(['permissions' => function ($query) use ($search) {
            if ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('label', 'like', "%{$search}%");
            }
        }])->orderBy('order')->get();

        $unassignedPermissions = Permission::whereDoesntHave('menu');
        if ($search) {
            $unassignedPermissions->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('label', 'like', "%{$search}%");
            });
        }
        $unassignedPermissions = $unassignedPermissions->get();

        return [
            'permissionMenus' => $permissionMenus,
            'unassignedPermissions' => $unassignedPermissions
        ];
    }
    /**
     * Create a new permission
     */
    public function createPermission(array $data): Permission
    {
        return Permission::create([
            'name' => $data['name'],
            'guard_name' => $data['guard_name'] ?? 'web',
            'menu_id' => $data['menu_id'] ?? null,
            'label' => $data['label'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);
    }

    /**
     * Update an existing permission
     */
    public function updatePermission(Permission $permission, array $data): bool
    {
        return $permission->update([
            'name' => $data['name'],
            'guard_name' => $data['guard_name'] ?? $permission->guard_name,
            'menu_id' => $data['menu_id'] ?? null,
            'label' => $data['label'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);
    }

    /**
     * Delete a permission
     */
    public function deletePermission(Permission $permission): bool
    {
        return $permission->delete();
    }
}
