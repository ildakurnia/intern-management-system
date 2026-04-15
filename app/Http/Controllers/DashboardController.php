<?php

namespace App\Http\Controllers;

use App\Enums\UserRoleEnum;
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
        return view('pages.dashboard', $this->dashboardService->buildPageData($request->user(), UserRoleEnum::ADMIN));
    }

    public function mentor(Request $request): View
    {
        return view('pages.dashboard', $this->dashboardService->buildPageData($request->user(), UserRoleEnum::MENTOR));
    }

    public function intern(Request $request): View
    {
        return view('pages.dashboard', $this->dashboardService->buildPageData($request->user(), UserRoleEnum::INTERN));
    }
}
