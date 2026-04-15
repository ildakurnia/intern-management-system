<?php

namespace App\Services;

use App\Enums\UserRoleEnum;
use App\Models\User;

class DashboardService
{
    public function resolveDashboardRouteName(?User $user): string
    {
        return $user?->role?->dashboardRouteName() ?? 'login';
    }

    /**
     * @return array<string, mixed>
     */
    public function buildPageData(User $user, UserRoleEnum $expectedRole): array
    {
        return [
            'pageTitle' => $this->titleForRole($expectedRole),
            'pageDescription' => $this->descriptionForRole($expectedRole),
            'roleLabel' => $expectedRole->label(),
            'summaryCards' => $this->summaryCardsForRole($expectedRole),
            'user' => $user,
        ];
    }

    private function titleForRole(UserRoleEnum $role): string
    {
        return match ($role) {
            UserRoleEnum::ADMIN => 'Admin Dashboard',
            UserRoleEnum::MENTOR => 'Mentor Dashboard',
            UserRoleEnum::INTERN => 'Intern Dashboard',
        };
    }

    private function descriptionForRole(UserRoleEnum $role): string
    {
        return match ($role) {
            UserRoleEnum::ADMIN => 'Pantau operasional IMS, data peserta magang, dan kesiapan modul lanjutan.',
            UserRoleEnum::MENTOR => 'Kelola peserta bimbingan, review progress, dan siapkan monitoring kehadiran.',
            UserRoleEnum::INTERN => 'Akses ringkasan aktivitas magang, absensi, dan status administrasi secara terpusat.',
        };
    }

    /**
     * @return list<array{label: string, value: string, hint: string}>
     */
    private function summaryCardsForRole(UserRoleEnum $role): array
    {
        return match ($role) {
            UserRoleEnum::ADMIN => [
                ['label' => 'Role Access', 'value' => '3 Roles', 'hint' => 'Admin, mentor, intern'],
                ['label' => 'Auth Status', 'value' => 'Active', 'hint' => 'Redirect berbasis role siap dipakai'],
                ['label' => 'Next Module', 'value' => 'Intern CRUD', 'hint' => 'Tahap berikutnya setelah review'],
            ],
            UserRoleEnum::MENTOR => [
                ['label' => 'Access Scope', 'value' => 'Mentoring', 'hint' => 'Fokus pada peserta yang dibimbing'],
                ['label' => 'Attendance', 'value' => 'Planned', 'hint' => 'Absensi akan masuk tahap berikutnya'],
                ['label' => 'Allowance', 'value' => 'Pending', 'hint' => 'Menunggu data hadir tervalidasi'],
            ],
            UserRoleEnum::INTERN => [
                ['label' => 'Portal Status', 'value' => 'Ready', 'hint' => 'Login dan dashboard per role aktif'],
                ['label' => 'Check In/Out', 'value' => 'Coming Soon', 'hint' => 'Akan ditambahkan pada modul attendance'],
                ['label' => 'Documents', 'value' => 'Coming Soon', 'hint' => 'Upload dokumen tahap berikutnya'],
            ],
        };
    }
}
