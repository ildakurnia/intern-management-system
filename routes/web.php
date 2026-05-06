<?php

use App\Http\Controllers\Admin\InternController as AdminInternController;
use App\Http\Controllers\Admin\InternDocumentController as AdminInternDocumentController;
use App\Http\Controllers\Admin\LogbookController as AdminLogbookController;
use App\Http\Controllers\Admin\TaskController as AdminTaskController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredInternController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Intern\LogbookController as InternLogbookController;
use App\Http\Controllers\Intern\ProfileController as InternProfileController;
use App\Http\Controllers\Intern\TaskController as InternTaskController;
use App\Http\Controllers\Mentor\LogbookController as MentorLogbookController;
use App\Http\Controllers\Mentor\TaskController as MentorTaskController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DivisionController;

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
    Route::get('/intern/register', [RegisteredInternController::class, 'create'])->name('intern.register');
    Route::post('/intern/register', [RegisteredInternController::class, 'store'])->name('intern.register.store');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware(['hasAnyRoleOrPermission', 'intern.onboarding'])
        ->name('dashboard');

    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index'])
        ->name('profile.index');

    // NOTIFICATIONS
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read-all');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
    });
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
            Route::get('/{intern}/edit', [AdminInternController::class, 'edit'])->name('edit');
            Route::put('/{intern}', [AdminInternController::class, 'update'])->name('update');
            Route::put('/{intern}/approve', [AdminInternController::class, 'approve'])->name('approve');
        });

        // Route::get('/intern-documents', [AdminInternDocumentController::class, 'index'])
        //    ->name('intern-documents.index');

        Route::get('/logbooks', [AdminLogbookController::class, 'index'])->name('logbooks.index')->middleware('can:admin.logbooks.index');
        Route::get('/logbooks/{logbook}', [AdminLogbookController::class, 'show'])->name('logbooks.show')->middleware('can:admin.logbooks.show');

        // ADMIN TASKS
        Route::prefix('tasks')->name('tasks.')->group(function () {
            Route::get('/', [AdminTaskController::class, 'index'])->name('index');
            Route::get('/create', [AdminTaskController::class, 'create'])->name('create');
            Route::post('/', [AdminTaskController::class, 'store'])->name('store');
            Route::get('/{task}', [AdminTaskController::class, 'show'])->name('show');
            Route::get('/{task}/edit', [AdminTaskController::class, 'edit'])->name('edit');
            Route::put('/{task}', [AdminTaskController::class, 'update'])->name('update');
            Route::delete('/{task}', [AdminTaskController::class, 'destroy'])->name('destroy');
        });
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
            Route::get('/logbooks', [MentorLogbookController::class, 'index'])->name('logbooks.index');
            Route::get('/logbooks/{logbook}', [MentorLogbookController::class, 'show'])->name('logbooks.show');
            
            // MENTOR TASKS
            Route::prefix('tasks')->name('tasks.')->group(function () {
                Route::get('/', [MentorTaskController::class, 'index'])->name('index');
                Route::get('/create', [MentorTaskController::class, 'create'])->name('create');
                Route::post('/', [MentorTaskController::class, 'store'])->name('store');
                Route::get('/{task}', [MentorTaskController::class, 'show'])->name('show');
                Route::get('/{task}/edit', [MentorTaskController::class, 'edit'])->name('edit');
                Route::put('/{task}', [MentorTaskController::class, 'update'])->name('update');
                Route::delete('/{task}', [MentorTaskController::class, 'destroy'])->name('destroy');
            });
        });
    });

    Route::prefix('intern')
        ->middleware(['role:intern', 'intern.onboarding', 'hasAnyRoleOrPermission'])
        ->name('intern.')
        ->group(function () {
            Route::get('/profile', [InternProfileController::class, 'edit'])->name('profile.edit');
            Route::put('/profile', [InternProfileController::class, 'update'])->name('profile.update');
            
            
            // INTERN LOGBOOKS
            Route::prefix('logbooks')->name('logbooks.')->group(function () {
                Route::get('/', [InternLogbookController::class, 'index'])->name('index');
                Route::get('/create', [InternLogbookController::class, 'create'])->name('create');
                Route::post('/', [InternLogbookController::class, 'store'])->name('store');
                Route::get('/{logbook}', [InternLogbookController::class, 'show'])->name('show');
                Route::get('/{logbook}/edit', [InternLogbookController::class, 'edit'])->name('edit');
                Route::put('/{logbook}', [InternLogbookController::class, 'update'])->name('update');
                Route::delete('/{logbook}', [InternLogbookController::class, 'destroy'])->name('destroy');
            });

            // INTERN TASKS
            Route::prefix('tasks')->name('tasks.')->group(function () {
                Route::get('/', [InternTaskController::class, 'index'])->name('index');
                Route::get('/{task}', [InternTaskController::class, 'show'])->name('show');
                Route::put('/{task}/update-status', [InternTaskController::class, 'updateStatus'])->name('update-status');
            });
        });

    Route::middleware(['role:intern', 'hasAnyRoleOrPermission', 'intern.onboarding'])->group(function () {
        Route::get('/intern/dashboard', [DashboardController::class, 'intern'])->name('dashboard.intern');
    });
});
