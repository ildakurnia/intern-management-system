<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            $menu = [];
            $addSection = static function (array &$menu, string $header, array $items): void {
                if ($items === []) {
                    return;
                }

                $menu[] = (object) ['menuHeader' => $header];

                foreach ($items as $item) {
                    $menu[] = (object) $item;
                }
            };

            if (! auth()->check()) {
                $view->with('menuData', [(object) ['menu' => $menu]]);

                return;
            }

            $user = auth()->user();

            $addSection($menu, 'Menu Utama', [[
                'name' => 'Dashboard',
                'url' => 'dashboard',
                'icon' => 'menu-icon tf-icons ri ri-dashboard-horizontal-line',
                'slug' => 'dashboard',
            ]]);

            if ($user->hasAnyRole(['superadmin', 'admin'])) {
                $internManagementItems = [];

                if ($user->can('admin.interns.index')) {
                    $internManagementItems[] = [
                        'name' => 'Data Intern',
                        'url' => 'admin/interns',
                        'icon' => 'menu-icon tf-icons ri ri-group-3-line',
                        'slug' => 'admin.interns',
                    ];
                }

                if ($user->can('admin.logbooks.index')) {
                    $internManagementItems[] = [
                        'name' => 'Logbook Intern',
                        'url' => 'admin/logbooks',
                        'icon' => 'menu-icon tf-icons ri ri-book-open-line',
                        'slug' => 'admin.logbooks',
                    ];
                }

                if ($user->can('admin.attendances.index')) {
                    $internManagementItems[] = [
                        'name' => 'Absensi Intern',
                        'url' => 'admin/attendances',
                        'icon' => 'menu-icon tf-icons ri ri-calendar-check-line',
                        'slug' => 'admin.attendances',
                    ];
                }

                if ($user->can('admin.allowances.index')) {
                    $internManagementItems[] = [
                        'name' => 'Uang Saku',
                        'url' => 'admin/allowances',
                        'icon' => 'menu-icon tf-icons ri ri-money-dollar-circle-line',
                        'slug' => 'admin.allowances',
                    ];
                }

                $addSection($menu, 'Manajemen Intern', $internManagementItems);

                $administrationItems = [];

                if ($user->can('admin.attendance-locations.index')) {
                    $administrationItems[] = [
                        'name' => 'Lokasi Absensi',
                        'url' => 'admin/attendance-locations',
                        'icon' => 'menu-icon tf-icons ri ri-map-pin-2-line',
                        'slug' => 'admin.attendance-locations',
                    ];
                }

                if ($user->can('admin.divisions.index')) {
                    $administrationItems[] = [
                        'name' => 'Manajemen Divisi',
                        'url' => 'admin/divisions',
                        'icon' => 'menu-icon tf-icons ri ri-git-branch-line',
                        'slug' => 'admin.divisions',
                    ];
                }

                if ($user->can('admin.users.index')) {
                    $administrationItems[] = [
                        'name' => 'Manajemen Pengguna',
                        'url' => 'admin/users',
                        'icon' => 'menu-icon tf-icons ri ri-team-line',
                        'slug' => 'admin.users',
                    ];
                }

                $addSection($menu, 'Administrasi', $administrationItems);

                if ($user->hasRole('superadmin')) {
                    $accessControlItems = [];

                    if ($user->can('roles.index')) {
                        $accessControlItems[] = [
                            'name' => 'Manajemen Role',
                            'url' => 'roles',
                            'icon' => 'menu-icon tf-icons ri ri-shield-user-line',
                            'slug' => 'roles',
                        ];
                    }

                    if ($user->can('permissions.index')) {
                        $accessControlItems[] = [
                            'name' => 'Daftar Permission',
                            'url' => 'permissions',
                            'icon' => 'menu-icon tf-icons ri ri-lock-password-line',
                            'slug' => 'permissions',
                        ];
                    }

                    $addSection($menu, 'Kontrol Akses', $accessControlItems);
                }

                if ($user->hasRole('superadmin')) {
                    $addSection($menu, 'Akun', [[
                        'name' => 'Pengaturan',
                        'url' => 'settings',
                        'icon' => 'menu-icon tf-icons ri ri-settings-3-line',
                        'slug' => 'settings.index',
                    ]]);
                }
            } elseif ($user->hasRole('mentor')) {
                $mentorItems = [];

                if ($user->can('mentor.logbooks.index')) {
                    $mentorItems[] = [
                        'name' => 'Logbook Mentee',
                        'url' => 'mentor/logbooks',
                        'icon' => 'menu-icon tf-icons ri ri-book-open-line',
                        'slug' => 'mentor.logbooks',
                    ];
                }

                if ($user->can('mentor.attendances.index')) {
                    $mentorItems[] = [
                        'name' => 'Monitoring Absensi',
                        'url' => 'mentor/attendances',
                        'icon' => 'menu-icon tf-icons ri ri-calendar-check-line',
                        'slug' => 'mentor.attendances',
                    ];
                }

                $addSection($menu, 'Monitoring Intern', $mentorItems);

            } elseif ($user->hasRole('intern')) {
                $internItems = [];

                if ($user->can('intern.documents.edit')) {
                    $internItems[] = [
                        'name' => 'Berkas Saya',
                        'url' => 'intern/documents',
                        'icon' => 'menu-icon tf-icons ri ri-file-upload-line',
                        'slug' => 'intern.documents',
                    ];
                }

                if ($user->can('intern.logbooks.index')) {
                    $internItems[] = [
                        'name' => 'Logbook Saya',
                        'url' => 'intern/logbooks',
                        'icon' => 'menu-icon tf-icons ri ri-book-open-line',
                        'slug' => 'intern.logbooks',
                    ];
                }

                if ($user->can('intern.attendances.index')) {
                    $internItems[] = [
                        'name' => 'Absensi Saya',
                        'url' => 'intern/attendances',
                        'icon' => 'menu-icon tf-icons ri ri-calendar-check-line',
                        'slug' => 'intern.attendances',
                    ];
                }

                $addSection($menu, 'Menu Intern', $internItems);
            }

            $view->with('menuData', [(object) ['menu' => $menu]]);
        });
    }
}
