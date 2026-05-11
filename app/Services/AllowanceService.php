<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Institution;
use App\Models\Intern;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class AllowanceService
{
    public function getMonthlyAllowances(array $filters = [], ?CarbonInterface $month = null): LengthAwarePaginator
    {
        $month ??= today();

        $paginator = $this->buildEligibleInternQuery($filters, $month)
            ->paginate($filters['per_page'] ?? 10)
            ->withQueryString();

        $paginator->setCollection(
            $paginator->getCollection()->map(fn (Intern $intern) => $this->buildAllowanceRow($intern, $month))
        );

        return $paginator;
    }

    public function getMonthlyAllowanceCollection(array $filters = [], ?CarbonInterface $month = null): Collection
    {
        $month ??= today();

        return $this->buildEligibleInternQuery($filters, $month)
            ->get()
            ->map(fn (Intern $intern) => $this->buildAllowanceRow($intern, $month));
    }

    public function getMonthlyAllowanceSummary(array $filters = [], ?CarbonInterface $month = null): array
    {
        $rows = $this->getMonthlyAllowanceCollection($filters, $month);

        return [
            [
                'label' => 'Mahasiswa Eligible',
                'count' => $rows->count(),
                'badge' => 'primary',
                'meta' => 'Mahasiswa Polibatam yang masuk perhitungan bulan ini.',
            ],
            [
                'label' => 'Hari Hadir',
                'count' => $rows->sum('attendance_days'),
                'badge' => 'success',
                'meta' => 'Akumulasi hadir dan terlambat yang tercatat.',
            ],
            [
                'label' => 'Total Uang Saku',
                'count' => $this->formatCurrency($rows->sum('allowance_amount')),
                'badge' => 'info',
                'meta' => 'Total nominal yang siap dicetak pada bulan terpilih.',
            ],
        ];
    }

    public function getInternMonthlyAllowance(Intern $intern, ?CarbonInterface $month = null): array
    {
        $month ??= today();
        $intern->loadMissing(['division', 'institutionReference', 'user']);

        $attendances = Attendance::query()
            ->with('attendanceLocation')
            ->where('intern_id', $intern->id)
            ->forMonth($month->year, $month->month)
            ->latest('date')
            ->get();

        $row = $this->buildAllowanceRow($intern, $month, $attendances);
        $statusCounts = [
            Attendance::STATUS_PRESENT => $attendances->where('status', Attendance::STATUS_PRESENT)->count(),
            Attendance::STATUS_LATE => $attendances->where('status', Attendance::STATUS_LATE)->count(),
            Attendance::STATUS_PERMISSION => $attendances->where('status', Attendance::STATUS_PERMISSION)->count(),
            Attendance::STATUS_SICK => $attendances->where('status', Attendance::STATUS_SICK)->count(),
            Attendance::STATUS_ABSENT => $attendances->where('status', Attendance::STATUS_ABSENT)->count(),
        ];

        return [
            ...$row,
            'attendances' => $attendances,
            'status_counts' => $statusCounts,
            'eligible_institution' => $this->getEligibleInstitution(),
        ];
    }

    public function isEligibleIntern(Intern $intern): bool
    {
        $intern->loadMissing('institutionReference');

        return $intern->type === config('allowance.eligible_type', 'mahasiswa')
            && (bool) $intern->institutionReference?->is_allowance_eligible;
    }

    public function parseMonth(?string $month): CarbonInterface
    {
        return filled($month)
            ? Carbon::createFromFormat('Y-m', $month)->startOfMonth()
            : today()->startOfMonth();
    }

    public function formatCurrency(int|float $amount): string
    {
        return 'Rp '.number_format((float) $amount, 0, ',', '.');
    }

    private function buildEligibleInternQuery(array $filters, CarbonInterface $month): Builder
    {
        return Intern::query()
            ->with(['division', 'institutionReference', 'user'])
            ->where('type', config('allowance.eligible_type', 'mahasiswa'))
            ->whereHas('institutionReference', function (Builder $builder) {
                $builder
                    ->where('is_active', true)
                    ->where('is_allowance_eligible', true);
            })
            ->withCount([
                'attendances as attendance_days_count' => function (Builder $builder) use ($month) {
                    $builder
                        ->forMonth($month->year, $month->month)
                        ->whereIn('status', [
                            Attendance::STATUS_PRESENT,
                            Attendance::STATUS_LATE,
                        ]);
                },
                'attendances as present_days_count' => function (Builder $builder) use ($month) {
                    $builder
                        ->forMonth($month->year, $month->month)
                        ->where('status', Attendance::STATUS_PRESENT);
                },
                'attendances as late_days_count' => function (Builder $builder) use ($month) {
                    $builder
                        ->forMonth($month->year, $month->month)
                        ->where('status', Attendance::STATUS_LATE);
                },
            ])
            ->when(! empty($filters['search']), function (Builder $builder) use ($filters) {
                $search = trim((string) $filters['search']);

                $builder->where(function (Builder $nested) use ($search) {
                    $nested
                        ->where('name', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%')
                        ->orWhereHas('user', function (Builder $userQuery) use ($search) {
                            $userQuery
                                ->where('name', 'like', '%'.$search.'%')
                                ->orWhere('email', 'like', '%'.$search.'%');
                        });
                });
            })
            ->orderBy('name');
    }

    private function buildAllowanceRow(Intern $intern, CarbonInterface $month, ?Collection $attendances = null): array
    {
        $attendanceDays = $attendances
            ? $attendances->whereIn('status', [Attendance::STATUS_PRESENT, Attendance::STATUS_LATE])->count()
            : (int) ($intern->attendance_days_count ?? 0);

        $presentDays = $attendances
            ? $attendances->where('status', Attendance::STATUS_PRESENT)->count()
            : (int) ($intern->present_days_count ?? 0);

        $lateDays = $attendances
            ? $attendances->where('status', Attendance::STATUS_LATE)->count()
            : (int) ($intern->late_days_count ?? 0);

        $maxWorkdays = (int) config('allowance.max_workdays', 22);
        $maxAmount = (int) config('allowance.max_amount', 500000);
        $dailyRate = $maxAmount / max($maxWorkdays, 1);
        $countedDays = min($attendanceDays, $maxWorkdays);
        $allowanceAmount = $countedDays >= $maxWorkdays
            ? $maxAmount
            : (int) round($dailyRate * $countedDays);

        return [
            'intern' => $intern,
            'month' => $month->format('Y-m'),
            'attendance_days' => $attendanceDays,
            'present_days' => $presentDays,
            'late_days' => $lateDays,
            'counted_days' => $countedDays,
            'daily_rate' => $dailyRate,
            'daily_rate_label' => $this->formatCurrency((int) round($dailyRate)),
            'allowance_amount' => $allowanceAmount,
            'allowance_amount_label' => $this->formatCurrency($allowanceAmount),
            'max_amount_label' => $this->formatCurrency($maxAmount),
            'max_workdays' => $maxWorkdays,
            'is_capped' => $attendanceDays > $maxWorkdays,
            'institution_label' => $intern->institution_label,
        ];
    }

    private function getEligibleInstitution(): ?Institution
    {
        return Institution::query()
            ->where('is_active', true)
            ->where('is_allowance_eligible', true)
            ->first();
    }
}
