<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class MenuPermissionSeeder extends Seeder
{
    /**
     * Seed menu records and route-name permissions for role editor UI.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->renameLegacyManagerPermission();

        foreach ($this->menuPermissions() as $menuOrder => $menuData) {
            $menu = Menu::updateOrCreate(
                ['title' => $menuData['title']],
                [
                    'route_name' => $menuData['route_name'],
                    'icon' => $menuData['icon'],
                    'parent_id' => null,
                    'order' => $menuOrder + 1,
                ]
            );

            foreach ($menuData['permissions'] as $permissionOrder => $permissionData) {
                Permission::updateOrCreate(
                    [
                        'name' => $permissionData['name'],
                        'guard_name' => 'web',
                    ],
                    [
                        'menu_id' => $menu->id,
                        'label' => $permissionData['label'],
                        'sort_order' => $permissionOrder + 1,
                    ]
                );
            }
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    private function renameLegacyManagerPermission(): void
    {
        $legacyPermission = Permission::query()
            ->where('name', 'dashboard.manager')
            ->where('guard_name', 'web')
            ->first();

        if (! $legacyPermission) {
            return;
        }

        $mentorPermissionExists = Permission::query()
            ->where('name', 'dashboard.mentor')
            ->where('guard_name', 'web')
            ->exists();

        if ($mentorPermissionExists) {
            $legacyPermission->delete();

            return;
        }

        $legacyPermission->forceFill([
            'name' => 'dashboard.mentor',
            'label' => 'Mentor Dashboard',
        ])->save();
    }

    /**
     * @return list<array{title: string, route_name: string|null, icon: string|null, permissions: list<array{name: string, label: string}>}>
     */
    private function menuPermissions(): array
    {
        return [
            [
                'title' => 'Dashboard',
                'route_name' => 'dashboard',
                'icon' => 'DB',
                'permissions' => [
                    ['name' => 'dashboard', 'label' => 'Access Dashboard'],
                    ['name' => 'dashboard.admin', 'label' => 'Admin Dashboard'],
                    ['name' => 'dashboard.mentor', 'label' => 'Mentor Dashboard'],
                    ['name' => 'dashboard.intern', 'label' => 'Intern Dashboard'],
                ],
            ],
            [
                'title' => 'Data Intern',
                'route_name' => 'admin.interns.index',
                'icon' => 'DI',
                'permissions' => [
                    ['name' => 'admin.interns.index', 'label' => 'Read List'],
                    ['name' => 'admin.interns.show', 'label' => 'Detail'],
                    ['name' => 'admin.interns.import', 'label' => 'Import Excel'],
                    ['name' => 'admin.interns.import.store', 'label' => 'Save Import'],
                    ['name' => 'admin.interns.template', 'label' => 'Download Template'],
                ],
            ],
            [
                'title' => 'Berkas Intern',
                'route_name' => 'admin.intern-documents.index',
                'icon' => 'BI',
                'permissions' => [
                    ['name' => 'admin.intern-documents.index', 'label' => 'Read List'],
                ],
            ],
            [
                'title' => 'Profil Intern',
                'route_name' => 'intern.profile.edit',
                'icon' => 'PI',
                'permissions' => [
                    ['name' => 'intern.profile.edit', 'label' => 'Edit Profile'],
                    ['name' => 'intern.profile.update', 'label' => 'Update Profile'],
                ],
            ],
            [
                'title' => 'Berkas Saya',
                'route_name' => 'intern.documents.edit',
                'icon' => 'BS',
                'permissions' => [
                    ['name' => 'intern.documents.edit', 'label' => 'Upload Form'],
                    ['name' => 'intern.documents.update', 'label' => 'Save Upload'],
                ],
            ],
            [
                'title' => 'Logbook',
                'route_name' => 'intern.logbooks.index',
                'icon' => 'LB',
                'permissions' => [
                    ['name' => 'intern.logbooks.index', 'label' => 'Read Own List'],
                    ['name' => 'intern.logbooks.create', 'label' => 'Create Form'],
                    ['name' => 'intern.logbooks.store', 'label' => 'Save Logbook'],
                    ['name' => 'intern.logbooks.show', 'label' => 'Show Own Detail'],
                    ['name' => 'intern.logbooks.edit', 'label' => 'Edit Form'],
                    ['name' => 'intern.logbooks.update', 'label' => 'Update Logbook'],
                    ['name' => 'intern.logbooks.destroy', 'label' => 'Delete Logbook'],
                    ['name' => 'mentor.logbooks.index', 'label' => 'Mentor Read List'],
                    ['name' => 'mentor.logbooks.show', 'label' => 'Mentor Show Detail'],
                    ['name' => 'admin.logbooks.index', 'label' => 'Admin Read List'],
                    ['name' => 'admin.logbooks.show', 'label' => 'Admin Show Detail'],
                ],
            ],
            [
                'title' => 'Absensi Intern',
                'route_name' => null,
                'icon' => 'AI',
                'permissions' => [],
            ],
            [
                'title' => 'Uang Saku',
                'route_name' => null,
                'icon' => 'US',
                'permissions' => [],
            ],
            [
                'title' => 'Manajemen Role',
                'route_name' => 'roles.index',
                'icon' => 'RL',
                'permissions' => [
                    ['name' => 'roles.index', 'label' => 'Read List'],
                    ['name' => 'roles.create', 'label' => 'Create'],
                    ['name' => 'roles.store', 'label' => 'Save'],
                    ['name' => 'roles.edit', 'label' => 'Edit'],
                    ['name' => 'roles.update', 'label' => 'Update'],
                    ['name' => 'roles.destroy', 'label' => 'Delete'],
                ],
            ],
            [
                'title' => 'Manajemen Permission',
                'route_name' => 'permissions.index',
                'icon' => 'PL',
                'permissions' => [
                    ['name' => 'permissions.index', 'label' => 'Read List'],
                    ['name' => 'permissions.create', 'label' => 'Create'],
                    ['name' => 'permissions.store', 'label' => 'Save'],
                    ['name' => 'permissions.edit', 'label' => 'Edit'],
                    ['name' => 'permissions.update', 'label' => 'Update'],
                    ['name' => 'permissions.destroy', 'label' => 'Delete'],
                ],
            ],
        ];
    }
}
