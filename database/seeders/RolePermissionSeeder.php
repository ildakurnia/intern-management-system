<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Create Comprehensive Permissions List for Team Workflow
        $permissions = [
            'manage_divisions',   // Admin: create/edit/delete divisions
            'manage_interns',
            'upload_documents',
            'verify_documents',
            'submit_attendance',
            'manage_attendance',
            'calculate_allowance',
            'view_allowance',
            'manage_tasks',
            'update_task_progress',
            'submit_daily_log',
            'review_daily_log',
            'submit_issue_report',
            'resolve_issue',
            'view_history'
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // 2. Create Roles & Sync Permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions(Permission::all()); // Admin can access everything usually

        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $managerRole->syncPermissions([
            'manage_interns',
            'verify_documents',
            'manage_attendance',
            'manage_tasks',
            'review_daily_log',
            'resolve_issue',
            'view_history'
        ]);

        $internRole = Role::firstOrCreate(['name' => 'intern']);
        $internRole->syncPermissions([
            'upload_documents',
            'submit_attendance',
            'view_allowance',
            'update_task_progress',
            'submit_daily_log',
            'submit_issue_report'
        ]);
    }
}
