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

        if ($user->hasRole('admin')) return 'dashboard.admin';
        if ($user->hasRole('manager')) return 'dashboard.manager';
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
            'roleLabel' => ucfirst($expectedRole),
            'summaryCards' => $this->summaryCardsForRole($expectedRole),
            'user' => $user,
        ];
    }

    private function titleForRole(string $role): string
    {
        return match ($role) {
            'admin' => 'Admin Dashboard',
            'manager' => 'Manager Dashboard',
            'intern' => 'Intern Dashboard',
            default => 'Dashboard',
        };
    }

    private function descriptionForRole(string $role): string
    {
        return match ($role) {
            'admin' => 'Pantau operasional IMS, data peserta magang, dan kesiapan modul lanjutan.',
            'manager' => 'Kelola peserta bimbingan, review progress, dan siapkan monitoring kehadiran.',
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
            'admin' => [
                ['label' => 'Role Access', 'value' => '3 Roles', 'hint' => 'Admin, mentor, intern'],
                ['label' => 'Auth Status', 'value' => 'Active', 'hint' => 'Redirect berbasis role siap dipakai'],
                ['label' => 'Next Module', 'value' => 'Intern CRUD', 'hint' => 'Tahap berikutnya setelah review'],
            ],
            'manager' => [
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
