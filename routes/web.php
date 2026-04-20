<?php

use App\Http\Controllers\Admin\InternController as AdminInternController;
use App\Http\Controllers\Admin\InternDocumentController as AdminInternDocumentController;
use App\Http\Controllers\Admin\LogbookController as AdminLogbookController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredInternController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Intern\DocumentController as InternDocumentController;
use App\Http\Controllers\Intern\LogbookController as InternLogbookController;
use App\Http\Controllers\Intern\ProfileController as InternProfileController;
use App\Http\Controllers\Mentor\LogbookController as MentorLogbookController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
})->name('home');

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

    Route::middleware(['role:superadmin|admin', 'hasAnyRoleOrPermission'])->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('dashboard.admin');
    });

    Route::prefix('admin')->middleware(['role:superadmin|admin', 'hasAnyRoleOrPermission'])->name('admin.')->group(function () {
        Route::prefix('interns')->name('interns.')->group(function () {
            Route::get('/', [AdminInternController::class, 'index'])->name('index');
            Route::get('/import', [AdminInternController::class, 'import'])->name('import');
            Route::get('/template', [AdminInternController::class, 'template'])->name('template');
            Route::post('/import', [AdminInternController::class, 'storeImport'])->name('import.store');
            Route::get('/{intern}', [AdminInternController::class, 'show'])->name('show');
        });

        Route::get('/intern-documents', [AdminInternDocumentController::class, 'index'])
            ->name('intern-documents.index');

        Route::get('/logbooks', [AdminLogbookController::class, 'index'])->name('logbooks.index');
        Route::get('/logbooks/{logbook}', [AdminLogbookController::class, 'show'])->name('logbooks.show');
    });

    Route::middleware(['role:superadmin', 'hasAnyRoleOrPermission'])->group(function () {
        Route::resource('roles', \App\Http\Controllers\RoleController::class)->except(['show']);
        Route::resource('permissions', \App\Http\Controllers\PermissionController::class)->except(['show']);
    });

    Route::middleware(['role:mentor', 'hasAnyRoleOrPermission'])->group(function () {
        Route::get('/mentor/dashboard', [DashboardController::class, 'mentor'])->name('dashboard.mentor');

        Route::prefix('mentor')->name('mentor.')->group(function () {
            Route::get('/logbooks', [MentorLogbookController::class, 'index'])->name('logbooks.index');
            Route::get('/logbooks/{logbook}', [MentorLogbookController::class, 'show'])->name('logbooks.show');
        });
    });

    Route::prefix('intern')
        ->middleware(['role:intern', 'intern.onboarding', 'hasAnyRoleOrPermission'])
        ->name('intern.')
        ->group(function () {
            Route::get('/profile', [InternProfileController::class, 'edit'])->name('profile.edit');
            Route::put('/profile', [InternProfileController::class, 'update'])->name('profile.update');
            Route::get('/documents', [InternDocumentController::class, 'edit'])->name('documents.edit');
            Route::put('/documents', [InternDocumentController::class, 'update'])->name('documents.update');
            Route::resource('logbooks', InternLogbookController::class);
        });

    Route::middleware(['role:intern', 'hasAnyRoleOrPermission', 'intern.onboarding'])->group(function () {
        Route::get('/intern/dashboard', [DashboardController::class, 'intern'])->name('dashboard.intern');
    });
});
