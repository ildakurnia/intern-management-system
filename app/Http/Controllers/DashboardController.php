<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboardService,
    ) {
    }

    public function index(Request $request): RedirectResponse
    {
        return redirect()->route($this->dashboardService->resolveDashboardRouteName($request->user()));
    }

    public function admin(Request $request): View
    {
        if ($request->user()->hasRole('superadmin')) {
            return view('pages.dashboard', $this->dashboardService->buildPageData($request->user(), 'superadmin'));
        }

        return view('pages.admin.dashboard', $this->dashboardService->buildPageData($request->user(), 'admin'));
    }

    public function mentor(Request $request): View
    {
        return view('pages.mentor.dashboard', $this->dashboardService->buildPageData($request->user(), 'mentor'));
    }

    public function intern(Request $request): View
    {
        return view('pages.intern.dashboard', $this->dashboardService->buildPageData($request->user(), 'intern'));
    }
}
