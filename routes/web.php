<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
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
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('dashboard.admin');
        Route::resource('roles', \App\Http\Controllers\RoleController::class);
        Route::resource('permissions', \App\Http\Controllers\PermissionController::class);
    });

    Route::middleware('role:manager')->group(function () {
        Route::get('/manager/dashboard', [DashboardController::class, 'manager'])->name('dashboard.manager');
    });

    Route::middleware('role:intern')->group(function () {
        Route::get('/intern/dashboard', [DashboardController::class, 'intern'])->name('dashboard.intern');
    });

    // --- MANAGER PERMISSIONS ---
    Route::middleware('permission:manage_interns')->get('/manager/interns', function () {
        return view('pages.feature', ['title' => 'Data Intern']);
    })->name('managers.interns');

    Route::middleware('permission:review_daily_log')->get('/manager/reports', function () {
        return view('pages.feature', ['title' => 'Review Laporan']);
    })->name('managers.reports');

    Route::middleware('permission:manage_attendance')->get('/manager/attendance', function () {
        return view('pages.feature', ['title' => 'Monitoring Absensi']);
    })->name('managers.attendance');

    // --- INTERN PERMISSIONS (Daily Log, Checkin, Allowance) ---
    Route::middleware('permission:submit_daily_log')->get('/intern/daily-log', function () {
        return view('pages.feature', ['title' => 'Isi Daily Log']);
    })->name('interns.daily_log');

    Route::middleware('permission:submit_attendance')->get('/intern/checkin', function () {
        return view('pages.feature', ['title' => 'Check In / Out']);
    })->name('interns.checkin');

    Route::middleware('permission:view_allowance')->get('/intern/allowance', function () {
        return view('pages.feature', ['title' => 'Status Allowance']);
    })->name('interns.allowance');
});
