<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Division;
use App\Models\Logbook;
use App\Models\User;
use App\Models\Intern;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DashboardService
{
    public function __construct(
        private readonly AttendanceService $attendanceService,
    ) {
    }

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
        $base = [
            'pageTitle'       => $this->titleForRole($expectedRole),
            'pageDescription' => $this->descriptionForRole($expectedRole),
            'roleLabel'       => $expectedRole === 'superadmin' ? 'Superadmin' : ucfirst($expectedRole),
            'user'            => $user,
        ];

        // Data untuk admin/superadmin
        if ($expectedRole === 'superadmin') {
            $base = array_merge($base, $this->superadminStats());
        }

        if ($expectedRole === 'admin') {
            $base = array_merge($base, $this->adminStats($user));
        }

        // Data untuk intern
        if ($expectedRole === 'intern') {
            $base = array_merge($base, $this->internStats($user));
        }

        // Data untuk mentor
        if ($expectedRole === 'mentor') {
            $base = array_merge($base, $this->mentorStats($user));
        }

        return $base;
    }

    private function superadminStats(): array
    {
        $totalLogbooks = Logbook::count();
        $totalInterns = Intern::count();
        $logbookThisMonth = Logbook::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();

        // Logbook per 7 bulan terakhir
        $logbooksPerMonth = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $logbooksPerMonth[] = Logbook::whereMonth('tanggal', $date->month)
                ->whereYear('tanggal', $date->year)
                ->count();
        }

        // Distribusi intern per divisi
        $divisionData  = Intern::with('division')
            ->get()
            ->groupBy(fn($i) => $i->division->name ?? 'Lainnya');
        $divisionLabels = $divisionData->keys()->toArray();
        $divisionCounts = $divisionData->map->count()->values()->toArray();

        // Logbook terbaru
        $recentLogbooks = Logbook::with(['intern.user', 'intern.division'])
            ->latest('tanggal')
            ->take(5)
            ->get();

        // Roles
        $roles = Role::withCount('users')->get();

        return [
            'totalUsers'       => User::count(),
            'totalInterns'     => $totalInterns,
            'totalLogbooks'    => $totalLogbooks,
            'logbookThisMonth' => $logbookThisMonth,
            'logbooksPerMonth' => $logbooksPerMonth,
            'divisionLabels'   => $divisionLabels,
            'divisionCounts'   => $divisionCounts ?: [1],
            'recentLogbooks'   => $recentLogbooks,
            'roles'            => $roles,
            'totalRoles'       => $roles->count(),
            'totalPermissions' => Permission::count(),
        ];
    }

    private function adminStats(User $user): array
    {
        $totalInterns = Intern::count();
        $logbookThisMonth = Logbook::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();

        $onboardingRegister = Intern::where('registration_status', 'pending')->count();
        $onboardingCompleting = Intern::where('registration_status', 'approved')
            ->where(function ($query) {
                $query->whereNull('profile_completed_at')
                    ->orWhereNull('documents_completed_at');
            })
            ->count();
        $onboardingActive = Intern::where('registration_status', 'approved')
            ->whereNotNull('profile_completed_at')
            ->whereNotNull('documents_completed_at')
            ->count();

        return [
            'totalInterns' => $totalInterns,
            'logbookThisMonth' => $logbookThisMonth,
            'adminOnboarding' => [
                'register' => $onboardingRegister,
                'completing' => $onboardingCompleting,
                'active' => $onboardingActive,
            ],
            'adminAttendanceSummary' => $this->attendanceService->getMonitoringInternListSummary($user),
            'recentLogbooks' => Logbook::with(['intern.user', 'intern.division'])
                ->latest('tanggal')
                ->take(5)
                ->get(),
            'recentInterns' => Intern::with(['user', 'division'])
                ->latest()
                ->take(5)
                ->get(),
            'divisionSnapshots' => Division::query()
                ->withCount('interns')
                ->orderByDesc('interns_count')
                ->take(6)
                ->get(),
        ];
    }

    private function internStats(User $user): array
    {
        $intern = $user->intern;
        $logbookModel = \App\Models\Logbook::class;

        $totalLogbooks    = $intern ? $logbookModel::where('intern_id', $intern->id)->count() : 0;
        $logbookThisMonth = $intern ? $logbookModel::where('intern_id', $intern->id)
                                          ->whereMonth('tanggal', now()->month)
                                          ->whereYear('tanggal', now()->year)
                                          ->count() : 0;

        $logbooksPerMonth = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $logbooksPerMonth[] = $intern
                ? $logbookModel::where('intern_id', $intern->id)
                    ->whereMonth('tanggal', $date->month)
                    ->whereYear('tanggal', $date->year)
                    ->count()
                : 0;
        }

        $recentLogbooks = $intern
            ? $logbookModel::with(['intern.user', 'intern.division'])
                ->where('intern_id', $intern->id)
                ->latest('tanggal')
                ->take(5)
            ->get()
            : collect();

        $attendanceSummary = $this->attendanceService->getInternSummary($user);

        // New Intern specific data
        $daysPassed = 0;
        $totalDays = 0;
        $percentage = 0;

        if ($intern && $intern->start_date && $intern->end_date) {
            $start = \Carbon\Carbon::parse($intern->start_date);
            $end = \Carbon\Carbon::parse($intern->end_date);
            $now = now();
            
            $totalDays = (int) $start->copy()->startOfDay()->diffInDays($end->copy()->startOfDay());
            $daysPassed = (int) $start->copy()->startOfDay()->diffInDays($now->copy()->startOfDay());
            
            if ($now->isBefore($start)) {
                $daysPassed = 0;
            } elseif ($now->isAfter($end)) {
                $daysPassed = $totalDays;
            }
            
            $percentage = $totalDays > 0 ? round(($daysPassed / $totalDays) * 100) : 0;
        }

        // Calculate Profile Completeness
        $completeness = 0;
        $steps = [];
        
        if ($intern) {
            $completeness += 25; // Akun Dibuat
            $steps[] = ['title' => 'Akun Dibuat', 'desc' => 'Akun sistem Anda telah aktif', 'status' => 'completed'];

            if ($intern->hasCompletedProfile()) $completeness += 25;
            $steps[] = [
                'title' => 'Lengkapi Profil', 
                'desc' => 'Data diri, kontak, dan alamat', 
                'status' => $intern->hasCompletedProfile() ? 'completed' : 'pending'
            ];

            if ($intern->hasCompletedDocuments()) $completeness += 25;
            $steps[] = [
                'title' => 'Unggah Berkas', 
                'desc' => 'KTP, KTM, BPJS & Surat Pengantar', 
                'status' => $intern->hasCompletedDocuments() ? 'completed' : 'pending'
            ];

            if ($intern->registration_status === 'approved') $completeness += 25;
            $steps[] = [
                'title' => 'Verifikasi Akhir', 
                'desc' => 'Menunggu verifikasi admin/mentor', 
                'status' => $intern->registration_status === 'approved' ? 'completed' : 'pending'
            ];
        }

        return [
            'intern'           => $intern,
            'totalLogbooks'    => $totalLogbooks,
            'logbookThisMonth' => $logbookThisMonth,
            'logbooksPerMonth' => $logbooksPerMonth,
            'recentLogbooks'   => $recentLogbooks,
            'todayAttendance' => $attendanceSummary['todayAttendance'],
            'attendanceThisMonth' => $attendanceSummary['attendanceThisMonth'],
            'attendanceStatusCounts' => $attendanceSummary['attendanceStatusCounts'],
            'recentAttendances' => $attendanceSummary['recentAttendances'],
            'hasCompletedProfile'   => $intern ? $intern->hasCompletedProfile() : false,
            'hasCompletedDocuments' => $intern ? $intern->hasCompletedDocuments() : false,
            'profileCompleteness'   => round($completeness),
            'onboardingSteps'       => $steps,
            'internPeriod' => [
                'daysPassed' => $daysPassed,
                'totalDays'  => $totalDays,
                'percentage' => $percentage,
                'start'      => $intern?->start_date?->translatedFormat('d M Y'),
                'end'        => $intern?->end_date?->translatedFormat('d M Y'),
            ]
        ];
    }

    private function mentorStats(User $user): array
    {
        $divisionId = $user->division_id;

        $internQuery = Intern::query()
            ->where('division_id', $divisionId);

        $logbookQuery = Logbook::query()
            ->whereHas('intern', function ($query) use ($divisionId) {
                $query->where('division_id', $divisionId);
            });

        $onboardingRegister = (clone $internQuery)
            ->where('registration_status', 'pending')
            ->count();

        $onboardingCompleting = (clone $internQuery)
            ->where('registration_status', 'approved')
            ->where(function ($query) {
                $query->whereNull('profile_completed_at')
                    ->orWhereNull('documents_completed_at');
            })
            ->count();

        $onboardingActive = (clone $internQuery)
            ->where('registration_status', 'approved')
            ->whereNotNull('profile_completed_at')
            ->whereNotNull('documents_completed_at')
            ->count();

        return [
            'pageDescription' => 'Pantau aktivitas dan progres onboarding intern bimbingan Anda.',
            'mentorDivisionName' => $user->division?->name ?? 'Divisi Mentor',
            'totalInterns' => (clone $internQuery)->count(),
            'logbookThisMonth' => (clone $logbookQuery)
                ->whereMonth('tanggal', now()->month)
                ->whereYear('tanggal', now()->year)
                ->count(),
            'mentorOnboarding' => [
                'register' => $onboardingRegister,
                'completing' => $onboardingCompleting,
                'active' => $onboardingActive,
            ],
            'mentorAttendanceSummary' => $this->attendanceService->getMonitoringInternListSummary($user),
            'recentLogbooks' => (clone $logbookQuery)
                ->with(['intern.user', 'intern.division'])
                ->latest('tanggal')
                ->take(5)
                ->get(),
            'recentInterns' => (clone $internQuery)
                ->with(['user', 'division'])
                ->latest()
                ->take(5)
                ->get(),
        ];
    }

    private function titleForRole(string $role): string
    {
        return match ($role) {
            'superadmin' => 'Superadmin Dashboard',
            'admin'      => 'Admin Dashboard',
            'mentor'     => 'Mentor Dashboard',
            'intern'     => 'Intern Dashboard',
            default      => 'Dashboard',
        };
    }

    private function descriptionForRole(string $role): string
    {
        return match ($role) {
            'superadmin' => 'Kelola akses sistem, role, permission, dan fitur operasional IMS.',
            'admin'      => 'Pantau operasional IMS, data peserta magang, dan logbook harian.',
            'mentor'     => 'Kelola peserta bimbingan dan review progress logbook mereka.',
            'intern'     => 'Akses ringkasan aktivitas magang dan logbook harianmu di sini.',
            default      => 'Selamat datang di panel kontrol IMS.',
        };
    }
}
