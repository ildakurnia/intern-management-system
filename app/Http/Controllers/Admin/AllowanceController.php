<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\Institution;
use App\Models\Intern;
use App\Services\AllowanceService;
use Carbon\CarbonInterface;
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
        $filterContext = $this->buildFilterContext($request, $month);
        $allowances = $this->allowanceService->getMonthlyAllowances($filterContext['filters'], $month);

        return view('pages.admin.allowances.index', [
            'selectedMonth' => $month->format('Y-m'),
            'allowances' => $allowances,
            'summary' => $this->allowanceService->getMonthlyAllowanceSummaryFromRows($allowances->getCollection()),
            'filterOptions' => $filterContext['options'],
            'selectedFilters' => $filterContext['selected'],
            'printQuery' => $filterContext['printQuery'],
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
        $filterContext = $this->buildFilterContext($request, $month);
        $rows = $this->allowanceService->getMonthlyAllowanceCollection($filterContext['filters'], $month);

        return view('pages.admin.allowances.print-index', [
            'selectedMonth' => $month->format('Y-m'),
            'rows' => $rows,
            'summary' => $this->allowanceService->getMonthlyAllowanceSummaryFromRows($rows),
            'filterOptions' => $filterContext['options'],
            'selectedFilters' => $filterContext['selected'],
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

    private function buildFilterContext(Request $request, CarbonInterface $month): array
    {
        $selected = [
            'search' => $request->string('search')->toString(),
            'type' => $request->string('type')->toString(),
            'institution_id' => $request->string('institution_id')->toString(),
            'division_id' => $request->string('division_id')->toString(),
        ];

        $filters = array_filter($selected, fn (string $value) => filled($value));

        return [
            'selected' => $selected,
            'filters' => $filters,
            'printQuery' => array_merge(['month' => $month->format('Y-m')], $filters),
            'options' => [
                'types' => [
                    '' => 'Semua tipe',
                    'siswa' => 'Siswa',
                    'mahasiswa' => 'Mahasiswa',
                ],
                'institutions' => Institution::query()
                    ->active()
                    ->orderBy('name')
                    ->get(['id', 'name']),
                'divisions' => Division::query()
                    ->active()
                    ->orderBy('name')
                    ->get(['id', 'name', 'code']),
            ],
        ];
    }
}
