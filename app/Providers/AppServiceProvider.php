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
            
            // Dashboard (All)
            $menu[] = (object)[
                'name' => 'Dashboard',
                'url' => 'dashboard',
                'icon' => 'menu-icon tf-icons ri ri-pie-chart-2-line',
                'slug' => 'dashboard'
            ];
            
            // Admin Data Management
            if (auth()->check() && auth()->user()->hasAnyRole(['admin', 'superadmin'])) {
                $menu[] = (object)[ 'menuHeader' => 'Manajemen Intern' ];
                $menu[] = (object)[
                    'name' => 'Data Intern',
                    'url' => 'admin/interns',
                    'icon' => 'menu-icon tf-icons ri ri-group-line',
                    'slug' => 'admin.interns'
                ];
                $menu[] = (object)[
                    'name' => 'Logbook Intern',
                    'url' => 'admin/logbooks',
                    'icon' => 'menu-icon tf-icons ri ri-graduation-cap-line',
                    'slug' => 'admin.logbooks'
                ];
                $menu[] = (object)[
                    'name' => 'Task / Jobdesk',
                    'url' => 'admin/tasks',
                    'icon' => 'menu-icon tf-icons ri ri-task-line',
                    'slug' => 'admin.tasks'
                ];
            }
            
            // Superadmin Menu
            if (auth()->check() && auth()->user()->hasRole('superadmin')) {
                $menu[] = (object)[ 'menuHeader' => 'System' ];
                $menu[] = (object)[
                    'name' => 'Roles & Permissions',
                    'icon' => 'menu-icon tf-icons ri ri-lock-2-line',
                    'slug' => ['roles', 'permissions'],
                    'submenu' => [
                        (object)[
                            'name' => 'Kelola Role',
                            'url' => 'roles',
                            'slug' => 'roles.index',
                        ],
                        (object)[
                            'name' => 'Kelola Permission',
                            'url' => 'permissions',
                            'slug' => 'permissions.index',
                        ],
                    ]
                ];
                $menu[] = (object)[
                    'name' => 'Manajemen Pengguna',
                    'url' => 'admin/users',
                    'icon' => 'menu-icon tf-icons ri ri-group-line',
                    'slug' => 'admin.users',
                ];
                $menu[] = (object)[
                    'name' => 'Divisi / Departemen',
                    'url' => 'admin/divisions',
                    'icon' => 'menu-icon tf-icons ri ri-community-line',
                    'slug' => 'admin.divisions',
                ];
            }
            
            // Mentor Menu
            if (auth()->check() && auth()->user()->hasRole('mentor')) {
                $menu[] = (object)[ 'menuHeader' => 'Monitoring Mentor' ];
                $menu[] = (object)[
                    'name' => 'Logbook Mentee',
                    'url' => 'mentor/logbooks',
                    'icon' => 'menu-icon tf-icons ri ri-book-read-line',
                    'slug' => 'mentor.logbooks'
                ];
                $menu[] = (object)[
                    'name' => 'Task / Jobdesk',
                    'url' => 'mentor/tasks',
                    'icon' => 'menu-icon tf-icons ri ri-task-line',
                    'slug' => 'mentor.tasks'
                ];
            }

            // Intern Menu
            if (auth()->check() && auth()->user()->hasRole('intern')) {
                $menu[] = (object)[ 'menuHeader' => 'Area Anak Magang' ];
                $menu[] = (object)[
                    'name' => 'Profil Saya',
                    'url' => 'intern/profile',
                    'icon' => 'menu-icon tf-icons ri ri-user-3-line',
                    'slug' => 'intern.profile'
                ];
                $menu[] = (object)[
                    'name' => 'Logbook Saya',
                    'url' => 'intern/logbooks',
                    'icon' => 'menu-icon tf-icons ri ri-draft-line',
                    'slug' => 'intern.logbooks'
                ];
                $menu[] = (object)[
                    'name' => 'Tugas Saya',
                    'url' => 'intern/tasks',
                    'icon' => 'menu-icon tf-icons ri ri-task-line',
                    'slug' => 'intern.tasks'
                ];
            }

            $view->with('menuData', [ (object)[ 'menu' => $menu ] ]);
        });
    }
}
