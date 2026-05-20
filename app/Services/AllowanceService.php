<?php

namespace App\Services;

use App\Models\Attendance;
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

        $paginator = $this->buildAllowanceQuery($filters, $month)
            ->paginate(max((int) ($filters['per_page'] ?? 10), 1))
            ->withQueryString();

        $paginator->setCollection(
            $paginator->getCollection()->map(fn (Intern $intern) => $this->buildAllowanceRow($intern, $month))
        );

        return $paginator;
    }

    public function getMonthlyAllowanceCollection(array $filters = [], ?CarbonInterface $month = null): Collection
    {
        $month ??= today();

        return $this->buildAllowanceQuery($filters, $month)
            ->get()
            ->map(fn (Intern $intern) => $this->buildAllowanceRow($intern, $month));
    }

    public function getMonthlyAllowanceSummary(array $filters = [], ?CarbonInterface $month = null): array
    {
        $rows = $this->getMonthlyAllowanceCollection($filters, $month);

        return $this->getMonthlyAllowanceSummaryFromRows($rows);
    }

    public function getMonthlyAllowanceSummaryFromRows(Collection $rows): array
    {
        return [
            [
                'label' => 'Intern Tersaring',
                'count' => $rows->count(),
                'badge' => 'primary',
                'meta' => 'Intern siswa atau mahasiswa yang cocok dengan filter aktif.',
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
                'meta' => 'Akumulasi nominal dari data yang tampil.',
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
        ];
    }

    public function isEligibleIntern(Intern $intern): bool
    {
        return $this->resolveAllowanceEligibility($intern)['is_eligible'];
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

    private function buildAllowanceQuery(array $filters, CarbonInterface $month): Builder
    {
        $query = Intern::query()
            ->with(['division', 'institutionReference', 'user'])
            ->whereIn('type', $this->eligibleTypes())
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
            ->when(! empty($filters['type']) && in_array($filters['type'], $this->eligibleTypes(), true), function (Builder $builder) use ($filters) {
                $builder->where('type', $filters['type']);
            })
            ->when(! empty($filters['institution_id']), function (Builder $builder) use ($filters) {
                $builder->where('institution_id', $filters['institution_id']);
            })
            ->when(! empty($filters['division_id']), function (Builder $builder) use ($filters) {
                $builder->where('division_id', $filters['division_id']);
            })
            ->when(! empty($filters['search']), function (Builder $builder) use ($filters) {
                $search = trim((string) $filters['search']);

                $builder->where(function (Builder $nested) use ($search) {
                    $nested
                        ->where('name', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%')
                        ->orWhere('nis', 'like', '%'.$search.'%')
                        ->orWhere('nim', 'like', '%'.$search.'%')
                        ->orWhere('institution', 'like', '%'.$search.'%')
                        ->orWhere('institution_manual_name', 'like', '%'.$search.'%')
                        ->orWhereHas('division', function (Builder $divisionQuery) use ($search) {
                            $divisionQuery
                                ->where('name', 'like', '%'.$search.'%')
                                ->orWhere('code', 'like', '%'.$search.'%');
                        })
                        ->orWhereHas('institutionReference', function (Builder $institutionQuery) use ($search) {
                            $institutionQuery->where('name', 'like', '%'.$search.'%');
                        })
                        ->orWhereHas('user', function (Builder $userQuery) use ($search) {
                            $userQuery
                                ->where('name', 'like', '%'.$search.'%')
                                ->orWhere('email', 'like', '%'.$search.'%');
                        });
                });
            });

        return $query->orderBy('name')->orderBy('id');
    }

    private function buildAllowanceRow(Intern $intern, CarbonInterface $month, ?Collection $attendances = null): array
    {
        $intern->loadMissing('institutionReference');
        $identifierLabel = $intern->type === 'siswa' ? 'NIS' : 'NIM';
        $identifierValue = trim((string) ($intern->type === 'siswa' ? $intern->nis : $intern->nim));
        $eligibility = $this->resolveAllowanceEligibility($intern);
        $isEligible = $eligibility['is_eligible'];

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
        $allowanceAmount = $isEligible
            ? ($countedDays >= $maxWorkdays
                ? $maxAmount
                : (int) round($dailyRate * $countedDays))
            : 0;

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
            'identifier_label' => $identifierLabel,
            'identifier_value' => $identifierValue !== '' ? $identifierValue : '-',
            'participant_type_label' => $intern->type === 'siswa' ? 'Siswa' : 'Mahasiswa',
            'is_eligible' => $isEligible,
        ];
    }

    private function resolveAllowanceEligibility(Intern $intern): array
    {
        $intern->loadMissing('institutionReference');

        if (! in_array($intern->type, $this->eligibleTypes(), true)) {
            return [
                'is_eligible' => false,
                'reason' => 'Tipe intern tidak masuk kategori allowance.',
            ];
        }

        if (! $intern->institutionReference) {
            return [
                'is_eligible' => false,
                'reason' => 'Asal sekolah/kampus belum dipilih.',
            ];
        }

        if (! $intern->institutionReference->is_active) {
            return [
                'is_eligible' => false,
                'reason' => 'Asal sekolah/kampus belum aktif untuk allowance.',
            ];
        }

        return [
            'is_eligible' => true,
            'reason' => 'Memenuhi syarat allowance.',
        ];
    }

    private function eligibleTypes(): array
    {
        $eligibleTypes = config('allowance.eligible_types', [config('allowance.eligible_type', 'mahasiswa')]);

        if (! is_array($eligibleTypes)) {
            $eligibleTypes = [$eligibleTypes];
        }

        return array_values(array_filter($eligibleTypes, fn ($type) => is_string($type) && trim($type) !== ''));
    }
}
