<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->call(MenuPermissionSeeder::class);

        $allPermissions = Permission::query()
            ->where('guard_name', 'web')
            ->pluck('name')
            ->all();

        $superadminRole = Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'web']);
        $superadminRole->syncPermissions($allPermissions);

        $this->renameLegacyManagerRole();

        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions([
            'dashboard',
            'dashboard.admin',
            'admin.interns.index',
            'admin.interns.show',
            'admin.interns.edit',
            'admin.interns.update',
            'admin.interns.approve',
            'admin.interns.import',
            'admin.interns.template',
            'admin.interns.import.store',
            'admin.intern-documents.index',
            'admin.logbooks.index',
            'admin.logbooks.show',
            'admin.tasks.index',
            'admin.tasks.create',
            'admin.tasks.store',
            'admin.tasks.show',
            'admin.tasks.edit',
            'admin.tasks.update',
            'admin.tasks.destroy',
        ]);

        $mentorRole = Role::firstOrCreate(['name' => 'mentor', 'guard_name' => 'web']);
        $mentorRole->syncPermissions([
            'dashboard',
            'dashboard.mentor',
            'admin.interns.index',
            'admin.interns.show',
            'admin.intern-documents.index',
            'mentor.logbooks.index',
            'mentor.logbooks.show',
            'mentor.tasks.index',
            'mentor.tasks.create',
            'mentor.tasks.store',
            'mentor.tasks.show',
            'mentor.tasks.edit',
            'mentor.tasks.update',
            'mentor.tasks.destroy',
        ]);

        $internRole = Role::firstOrCreate(['name' => 'intern', 'guard_name' => 'web']);
        $internRole->syncPermissions([
            'dashboard',
            'dashboard.intern',
            'intern.profile.edit',
            'intern.profile.update',
            'intern.documents.edit',
            'intern.documents.update',
            'intern.logbooks.index',
            'intern.logbooks.create',
            'intern.logbooks.store',
            'intern.logbooks.show',
            'intern.logbooks.edit',
            'intern.logbooks.update',
            'intern.logbooks.destroy',
            'intern.tasks.index',
            'intern.tasks.show',
            'intern.tasks.update-status',
        ]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    private function renameLegacyManagerRole(): void
    {
        $legacyRole = Role::query()
            ->where('name', 'manager')
            ->where('guard_name', 'web')
            ->first();

        if (! $legacyRole) {
            return;
        }

        $mentorRole = Role::query()
            ->where('name', 'mentor')
            ->where('guard_name', 'web')
            ->first();

        if (! $mentorRole) {
            $legacyRole->forceFill(['name' => 'mentor'])->save();

            return;
        }

        $legacyRole->users->each(fn ($user) => $user->assignRole($mentorRole));
        $legacyRole->delete();
    }
}
