<?php

namespace App\Services;

use App\Models\User;

class DashboardService
{
    public function resolveDashboardRouteName(?User $user): string
    {
        if (! $user) {
            return 'login';
        }

        if ($user->hasRole('superadmin')) return 'dashboard.admin';
        if ($user->hasRole('admin')) return 'dashboard.admin';
        if ($user->hasRole('mentor')) return 'dashboard.mentor';
        if ($user->hasRole('intern')) return 'dashboard.intern';

        return 'login';
    }

    /**
     * @return array<string, mixed>
     */
    public function buildPageData(User $user, string $expectedRole): array
    {
        return [
            'pageTitle' => $this->titleForRole($expectedRole),
            'pageDescription' => $this->descriptionForRole($expectedRole),
            'roleLabel' => $expectedRole === 'superadmin' ? 'Superadmin' : ucfirst($expectedRole),
            'summaryCards' => $this->summaryCardsForRole($expectedRole),
            'user' => $user,
        ];
    }

    private function titleForRole(string $role): string
    {
        return match ($role) {
            'superadmin' => 'Superadmin Dashboard',
            'admin' => 'Admin Dashboard',
            'mentor' => 'Mentor Dashboard',
            'intern' => 'Intern Dashboard',
            default => 'Dashboard',
        };
    }

    private function descriptionForRole(string $role): string
    {
        return match ($role) {
            'superadmin' => 'Kelola akses sistem, role, permission, dan fitur operasional IMS.',
            'admin' => 'Pantau operasional IMS, data peserta magang, dan kesiapan modul lanjutan.',
            'mentor' => 'Kelola peserta bimbingan, review progress, dan siapkan monitoring kehadiran.',
            'intern' => 'Akses ringkasan aktivitas magang, absensi, dan status administrasi secara terpusat.',
            default => 'Selamat datang di panel kontrol.',
        };
    }

    /**
     * @return list<array{label: string, value: string, hint: string}>
     */
    private function summaryCardsForRole(string $role): array
    {
        return match ($role) {
            'superadmin' => [
                ['label' => 'Role Access', 'value' => '4 Roles', 'hint' => 'Superadmin, admin, mentor, intern'],
                ['label' => 'RBAC Status', 'value' => 'Active', 'hint' => 'Role dan permission hanya untuk superadmin'],
                ['label' => 'Operational Access', 'value' => 'Full', 'hint' => 'Dapat masuk ke fitur operasional admin'],
            ],
            'admin' => [
                ['label' => 'Role Access', 'value' => 'Admin', 'hint' => 'Fokus akses operasional intern'],
                ['label' => 'Auth Status', 'value' => 'Active', 'hint' => 'Redirect berbasis role siap dipakai'],
                ['label' => 'Next Module', 'value' => 'Intern CRUD', 'hint' => 'Tahap berikutnya setelah review'],
            ],
            'mentor' => [
                ['label' => 'Access Scope', 'value' => 'Mentoring', 'hint' => 'Fokus pada peserta yang dibimbing'],
                ['label' => 'Attendance', 'value' => 'Planned', 'hint' => 'Absensi akan masuk tahap berikutnya'],
                ['label' => 'Allowance', 'value' => 'Pending', 'hint' => 'Menunggu data hadir tervalidasi'],
            ],
            'intern' => [
                ['label' => 'Portal Status', 'value' => 'Ready', 'hint' => 'Login dan dashboard per role aktif'],
                ['label' => 'Check In/Out', 'value' => 'Coming Soon', 'hint' => 'Akan ditambahkan pada modul attendance'],
                ['label' => 'Documents', 'value' => 'Coming Soon', 'hint' => 'Upload dokumen tahap berikutnya'],
            ],
            default => [],
        };
    }
}
