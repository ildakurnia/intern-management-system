<?php

use App\Http\Controllers\Admin\InternController as AdminInternController;
use App\Http\Controllers\Admin\InternDocumentController as AdminInternDocumentController;
use App\Http\Controllers\Admin\LogbookController as AdminLogbookController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredInternController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Intern\DocumentController as InternDocumentController;
use App\Http\Controllers\Intern\LogbookController as InternLogbookController;
use App\Http\Controllers\Intern\ProfileController as InternProfileController;
use App\Http\Controllers\InstitutionSearchController;
use App\Http\Controllers\Mentor\LogbookController as MentorLogbookController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DivisionController;
use App\Http\Controllers\Admin\AttendanceLocationController as AdminAttendanceLocationController;
use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\Admin\AllowanceController as AdminAllowanceController;
use App\Http\Controllers\Intern\AttendanceController as InternAttendanceController;
use App\Http\Controllers\Mentor\AttendanceController as MentorAttendanceController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
})->name('home');

// Locale/Language Switcher (digunakan oleh Template Customizer)
Route::get('lang/{locale}', function ($locale) {
    app()->setLocale($locale);
    session()->put('locale', $locale);
    return redirect()->back();
})->name('lang-swap');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.attempt');
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');
    Route::get('/intern/register', [RegisteredInternController::class, 'create'])->name('intern.register');
    Route::post('/intern/register', [RegisteredInternController::class, 'store'])->name('intern.register.store');
});

Route::middleware(['auth', 'sync.expired.interns'])->group(function (): void {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::view('/settings', 'pages.settings.index')->middleware('role:superadmin')->name('settings.index');
    Route::get('/account/profile', [ProfileController::class, 'edit'])
        ->middleware('role:superadmin|admin|mentor')
        ->name('profile.edit');
    Route::put('/account/profile', [ProfileController::class, 'update'])
        ->middleware('role:superadmin|admin|mentor')
        ->name('profile.update');

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware(['hasAnyRoleOrPermission', 'intern.onboarding'])
        ->name('dashboard');

    // NOTIFICATIONS
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read-all');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
    });
    Route::get('/institutions/search', InstitutionSearchController::class)->name('institutions.search');
    Route::middleware(['role:superadmin|admin', 'hasAnyRoleOrPermission'])->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('dashboard.admin');

        // USER MANAGEMENT
        Route::prefix('admin/users')->name('admin.users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        });

        // DIVISION MANAGEMENT
        Route::prefix('admin/divisions')->name('admin.divisions.')->group(function () {
            Route::get('/', [DivisionController::class, 'index'])->name('index');
            Route::get('/create', [DivisionController::class, 'create'])->name('create');
            Route::post('/', [DivisionController::class, 'store'])->name('store');
            Route::get('/{division}/edit', [DivisionController::class, 'edit'])->name('edit');
            Route::put('/{division}', [DivisionController::class, 'update'])->name('update');
            Route::delete('/{division}', [DivisionController::class, 'destroy'])->name('destroy');
        });
    });

    Route::prefix('admin')->middleware(['role:superadmin|admin', 'hasAnyRoleOrPermission'])->name('admin.')->group(function () {
        Route::prefix('interns')->name('interns.')->group(function () {
            Route::get('/', [AdminInternController::class, 'index'])->name('index');
            Route::get('/import', [AdminInternController::class, 'import'])->name('import');
            Route::get('/template', [AdminInternController::class, 'template'])->name('template');
            Route::post('/import', [AdminInternController::class, 'storeImport'])->name('import.store');
            Route::get('/{intern}', [AdminInternController::class, 'show'])->name('show');
            Route::put('/{intern}/approve', [AdminInternController::class, 'approve'])->name('approve');
            Route::put('/{intern}/attendance-locations', [AdminInternController::class, 'updateAttendanceLocations'])->name('attendance-locations.update');
        });

        Route::prefix('attendance-locations')->name('attendance-locations.')->group(function () {
            Route::get('/', [AdminAttendanceLocationController::class, 'index'])->name('index');
            Route::get('/create', [AdminAttendanceLocationController::class, 'create'])->name('create');
            Route::post('/', [AdminAttendanceLocationController::class, 'store'])->name('store');
            Route::get('/{attendance_location}/edit', [AdminAttendanceLocationController::class, 'edit'])->name('edit');
            Route::put('/{attendance_location}', [AdminAttendanceLocationController::class, 'update'])->name('update');
            Route::delete('/{attendance_location}', [AdminAttendanceLocationController::class, 'destroy'])->name('destroy');
        });

        // Route::get('/intern-documents', [AdminInternDocumentController::class, 'index'])
        //    ->name('intern-documents.index');

        Route::get('/logbooks', [AdminLogbookController::class, 'index'])->name('logbooks.index')->middleware('can:admin.logbooks.index');
        Route::get('/logbooks/{logbook}', [AdminLogbookController::class, 'show'])->name('logbooks.show')->middleware('can:admin.logbooks.show');
        Route::get('/attendances', [AdminAttendanceController::class, 'index'])
            ->name('attendances.index')
            ->middleware('can:admin.attendances.index');
        Route::get('/attendances/{intern}', [AdminAttendanceController::class, 'show'])
            ->name('attendances.show')
            ->middleware('can:admin.attendances.show');
        Route::get('/allowances', [AdminAllowanceController::class, 'index'])
            ->name('allowances.index')
            ->middleware('can:admin.allowances.index');
        Route::get('/allowances/print', [AdminAllowanceController::class, 'print'])
            ->name('allowances.print')
            ->middleware('can:admin.allowances.print');
        Route::get('/allowances/{intern}', [AdminAllowanceController::class, 'show'])
            ->name('allowances.show')
            ->middleware('can:admin.allowances.show');
        Route::get('/allowances/{intern}/print', [AdminAllowanceController::class, 'printShow'])
            ->name('allowances.show.print')
            ->middleware('can:admin.allowances.show.print');
    });

    Route::middleware(['role:superadmin', 'hasAnyRoleOrPermission'])->group(function () {
        // ROLES MANAGEMENT
        Route::prefix('roles')->name('roles.')->group(function () {
            Route::get('/', [\App\Http\Controllers\RoleController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\RoleController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\RoleController::class, 'store'])->name('store');
            Route::get('/{role}/edit', [\App\Http\Controllers\RoleController::class, 'edit'])->name('edit');
            Route::put('/{role}', [\App\Http\Controllers\RoleController::class, 'update'])->name('update');
            Route::delete('/{role}', [\App\Http\Controllers\RoleController::class, 'destroy'])->name('destroy');
        });

        // PERMISSIONS MANAGEMENT
        Route::prefix('permissions')->name('permissions.')->group(function () {
            Route::get('/', [\App\Http\Controllers\PermissionController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\PermissionController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\PermissionController::class, 'store'])->name('store');
            Route::get('/{permission}/edit', [\App\Http\Controllers\PermissionController::class, 'edit'])->name('edit');
            Route::put('/{permission}', [\App\Http\Controllers\PermissionController::class, 'update'])->name('update');
            Route::delete('/{permission}', [\App\Http\Controllers\PermissionController::class, 'destroy'])->name('destroy');
        });
    });

    Route::middleware(['role:mentor', 'hasAnyRoleOrPermission'])->group(function () {
        Route::get('/mentor/dashboard', [DashboardController::class, 'mentor'])->name('dashboard.mentor');

        Route::prefix('mentor')->name('mentor.')->group(function () {
            Route::get('/logbooks', [MentorLogbookController::class, 'index'])
                ->name('logbooks.index')
                ->middleware('can:mentor.logbooks.index');
            Route::get('/logbooks/{logbook}', [MentorLogbookController::class, 'show'])
                ->name('logbooks.show')
                ->middleware('can:mentor.logbooks.show');
            Route::get('/attendances', [MentorAttendanceController::class, 'index'])
                ->name('attendances.index')
                ->middleware('can:mentor.attendances.index');
            Route::get('/attendances/{intern}', [MentorAttendanceController::class, 'show'])
                ->name('attendances.show')
                ->middleware('can:mentor.attendances.show');
        });
    });

    Route::prefix('intern')
        ->middleware(['role:intern', 'hasAnyRoleOrPermission'])
        ->name('intern.')
        ->group(function () {
            Route::get('/approval-pending', function () {
                $intern = request()->user()->intern;

                if ($intern && $intern->isPeriodExpired()) {
                    return redirect()->route('intern.period-ended');
                }

                if ($intern && $intern->registration_status === 'approved') {
                    return redirect()->route('dashboard.intern');
                }

                return view('pages.intern.approval-pending', compact('intern'));
            })->name('approval.pending');

            Route::get('/period-ended', function () {
                $intern = request()->user()->intern;

                if ($intern && ! $intern->isPeriodExpired()) {
                    return redirect()->route('dashboard.intern');
                }

                return view('pages.intern.period-ended', compact('intern'));
            })->name('period-ended');
        });

    Route::prefix('intern')
        ->middleware(['role:intern', 'intern.onboarding', 'hasAnyRoleOrPermission'])
        ->name('intern.')
        ->group(function () {
            Route::get('/profile', [InternProfileController::class, 'edit'])->name('profile.edit');
            Route::put('/profile', [InternProfileController::class, 'update'])->name('profile.update');
            Route::get('/documents', [InternDocumentController::class, 'edit'])->name('documents.edit');
            Route::get('/documents/{field}/preview', [InternDocumentController::class, 'preview'])->name('documents.preview');
            Route::put('/documents', [InternDocumentController::class, 'update'])->name('documents.update');
            
            // INTERN LOGBOOKS
            Route::prefix('logbooks')->name('logbooks.')->group(function () {
                Route::get('/', [InternLogbookController::class, 'index'])
                    ->name('index')
                    ->middleware('can:intern.logbooks.index');
                Route::get('/create', [InternLogbookController::class, 'create'])
                    ->name('create')
                    ->middleware('can:intern.logbooks.create');
                Route::post('/', [InternLogbookController::class, 'store'])
                    ->name('store')
                    ->middleware('can:intern.logbooks.store');
                Route::get('/{logbook}', [InternLogbookController::class, 'show'])
                    ->name('show')
                    ->middleware('can:intern.logbooks.show');
                Route::get('/{logbook}/edit', [InternLogbookController::class, 'edit'])
                    ->name('edit')
                    ->middleware('can:intern.logbooks.edit');
                Route::put('/{logbook}', [InternLogbookController::class, 'update'])
                    ->name('update')
                    ->middleware('can:intern.logbooks.update');
                Route::delete('/{logbook}', [InternLogbookController::class, 'destroy'])
                    ->name('destroy')
                    ->middleware('can:intern.logbooks.destroy');
            });

            Route::prefix('attendances')->name('attendances.')->group(function () {
                Route::get('/', [InternAttendanceController::class, 'index'])
                    ->name('index')
                    ->middleware('can:intern.attendances.index');
                Route::post('/check-in', [InternAttendanceController::class, 'checkIn'])
                    ->name('check-in')
                    ->middleware('can:intern.attendances.check-in');
                Route::post('/check-out', [InternAttendanceController::class, 'checkOut'])
                    ->name('check-out')
                    ->middleware('can:intern.attendances.check-out');
                Route::get('/submissions/{type}/create', [InternAttendanceController::class, 'createSubmission'])
                    ->name('submissions.create')
                    ->middleware('can:intern.attendances.submissions.create');
                Route::post('/submissions', [InternAttendanceController::class, 'storeSubmission'])
                    ->name('submissions.store')
                    ->middleware('can:intern.attendances.submissions.store');
            });
        });

    Route::middleware(['role:intern', 'hasAnyRoleOrPermission', 'intern.onboarding'])->group(function () {
        Route::get('/intern/dashboard', [DashboardController::class, 'intern'])->name('dashboard.intern');
    });
});
