<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Intern;
use App\Services\AllowanceService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AllowanceController extends Controller
{
    public function __construct(
        private readonly AllowanceService $allowanceService,
    ) {
    }

    public function index(Request $request): View
    {
        $month = $this->allowanceService->parseMonth($request->string('month')->toString() ?: null);
        $filters = $request->only(['search']);

        return view('pages.admin.allowances.index', [
            'selectedMonth' => $month->format('Y-m'),
            'allowances' => $this->allowanceService->getMonthlyAllowances($filters, $month),
            'summary' => $this->allowanceService->getMonthlyAllowanceSummary($filters, $month),
            'selectedSearch' => $request->string('search')->toString(),
        ]);
    }

    public function show(Request $request, Intern $intern): View
    {
        abort_unless($this->allowanceService->isEligibleIntern($intern), 404);

        $month = $this->allowanceService->parseMonth($request->string('month')->toString() ?: null);

        return view('pages.admin.allowances.show', [
            'selectedMonth' => $month->format('Y-m'),
            'allowance' => $this->allowanceService->getInternMonthlyAllowance($intern, $month),
        ]);
    }

    public function print(Request $request): View
    {
        $month = $this->allowanceService->parseMonth($request->string('month')->toString() ?: null);
        $filters = $request->only(['search']);

        return view('pages.admin.allowances.print-index', [
            'selectedMonth' => $month->format('Y-m'),
            'selectedSearch' => $request->string('search')->toString(),
            'rows' => $this->allowanceService->getMonthlyAllowanceCollection($filters, $month),
            'summary' => $this->allowanceService->getMonthlyAllowanceSummary($filters, $month),
        ]);
    }

    public function printShow(Request $request, Intern $intern): View
    {
        abort_unless($this->allowanceService->isEligibleIntern($intern), 404);

        $month = $this->allowanceService->parseMonth($request->string('month')->toString() ?: null);

        return view('pages.admin.allowances.print-show', [
            'selectedMonth' => $month->format('Y-m'),
            'allowance' => $this->allowanceService->getInternMonthlyAllowance($intern, $month),
        ]);
    }
}
