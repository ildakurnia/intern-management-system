<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Intern;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AttendanceService
{
    public function getTodayAttendanceForUser(User $user): ?Attendance
    {
        return $user->intern
            ? $this->getTodayAttendanceForIntern($user->intern)
            : null;
    }

    public function getAttendancesForUser(User $user, array $filters = []): LengthAwarePaginator
    {
        if (! $user->intern) {
            return Attendance::query()
                ->whereRaw('1 = 0')
                ->paginate(10);
        }

        return $this->getAttendancesForIntern($user->intern, $filters);
    }

    public function getTodayAttendanceForIntern(Intern $intern, ?CarbonInterface $date = null): ?Attendance
    {
        $date ??= today();

        return Cache::remember(
            $this->todayAttendanceCacheKey($intern->id, $date),
            now()->addSeconds(20),
            function () use ($intern, $date) {
                return Attendance::query()
                    ->with('attendanceLocation')
                    ->where('intern_id', $intern->id)
                    ->whereDate('date', $date->toDateString())
                    ->first();
            }
        );
    }

    public function getAttendancesForIntern(Intern $intern, array $filters = []): LengthAwarePaginator
    {
        $query = Attendance::query()
            ->with(['intern.division', 'attendanceLocation'])
            ->where('intern_id', $intern->id)
            ->latest('date')
            ->latest('check_in_at');

        if (! empty($filters['month'])) {
            [$year, $month] = explode('-', $filters['month']);
            $query->forMonth((int) $year, (int) $month);
        }

        if (! empty($filters['status'])) {
            $query->forStatus($filters['status']);
        }

        return $query->paginate($filters['per_page'] ?? 10)->withQueryString();
    }

    public function getMonitoringAttendances(User $user, array $filters = []): LengthAwarePaginator
    {
        $query = Attendance::query()
            ->with(['intern.user', 'intern.division', 'attendanceLocation'])
            ->latest('date')
            ->latest('check_in_at');

        $this->applyMonitoringFilters($query, $user, $filters);

        return $query->paginate(15)->withQueryString();
    }

    public function getMonitoringSummary(User $user, array $filters = [], ?CarbonInterface $date = null): array
    {
        $date ??= today();

        $query = Attendance::query();

        if ($this->hasMonitoringFilters($filters)) {
            $this->applyMonitoringFilters($query, $user, $filters);
        } else {
            $query->whereDate('date', $date->toDateString());

            if ($user->hasRole('mentor')) {
                $query->whereHas('intern', function (Builder $builder) use ($user) {
                    $builder->where('division_id', $user->division_id);
                });
            }
        }

        $counts = $query
            ->select('status', DB::raw('count(*) as aggregate'))
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        return $this->buildStatusCountPayload($counts);
    }

    public function getInternSummary(User $user, ?CarbonInterface $month = null): array
    {
        if (! $user->intern) {
            return [
                'todayAttendance' => null,
                'attendanceThisMonth' => 0,
                'attendanceStatusCounts' => $this->buildStatusCountPayload(collect()),
                'recentAttendances' => collect(),
            ];
        }

        return $this->getInternSummaryForIntern($user->intern, $month);
    }

    public function getInternSummaryForIntern(Intern $intern, ?CarbonInterface $month = null): array
    {
        $month ??= today();

        return Cache::remember(
            $this->internSummaryCacheKey($intern->id, $month),
            now()->addSeconds(20),
            function () use ($intern, $month) {
                $monthlyQuery = Attendance::query()
                    ->where('intern_id', $intern->id)
                    ->forMonth($month->year, $month->month);

                $counts = (clone $monthlyQuery)
                    ->select('status', DB::raw('count(*) as aggregate'))
                    ->groupBy('status')
                    ->pluck('aggregate', 'status');

                return [
                    'todayAttendance' => $this->getTodayAttendanceForIntern($intern),
                    'attendanceThisMonth' => (clone $monthlyQuery)->count(),
                    'attendanceStatusCounts' => $this->buildStatusCountPayload($counts),
                    'recentAttendances' => Attendance::query()
                        ->with('attendanceLocation')
                        ->where('intern_id', $intern->id)
                        ->latest('date')
                        ->take(5)
                        ->get(),
                ];
            }
        );
    }

    public function getMonitoringInterns(User $user, array $filters = [], ?CarbonInterface $monitorDate = null, ?CarbonInterface $month = null): LengthAwarePaginator
    {
        $monitorDate ??= today();
        $month ??= $monitorDate;

        return Cache::remember(
            $this->monitoringInternsCacheKey($user->id, $filters, $monitorDate, $month),
            now()->addSeconds(20),
            function () use ($user, $filters, $monitorDate, $month) {
                return $this->buildMonitoringInternQuery($user, $filters, $monitorDate, $month)
                    ->paginate($filters['per_page'] ?? 10)
                    ->withQueryString();
            }
        );
    }

    public function getMonitoringInternListSummary(User $user, array $filters = [], ?CarbonInterface $monitorDate = null, ?CarbonInterface $month = null): array
    {
        $monitorDate ??= today();
        $month ??= $monitorDate;

        return Cache::remember(
            $this->monitoringSummaryCacheKey($user->id, $filters, $monitorDate, $month),
            now()->addSeconds(20),
            function () use ($user, $filters, $monitorDate, $month) {
                $interns = $this->buildMonitoringInternQuery($user, $filters, $monitorDate, $month)->get();
                $summary = [
                    'hadir' => 0,
                    'terlambat' => 0,
                    'izin' => 0,
                    'sakit' => 0,
                    'belum_absen' => 0,
                ];

                foreach ($interns as $intern) {
                    $todayAttendance = $intern->attendances->first();

                    if (! $todayAttendance) {
                        $summary['belum_absen']++;
                        continue;
                    }

                    match ($todayAttendance->status) {
                        Attendance::STATUS_PRESENT => $summary['hadir']++,
                        Attendance::STATUS_LATE => $summary['terlambat']++,
                        Attendance::STATUS_PERMISSION => $summary['izin']++,
                        Attendance::STATUS_SICK => $summary['sakit']++,
                        default => $summary['belum_absen']++,
                    };
                }

                return [
                    [
                        'key' => 'hadir',
                        'label' => 'Hadir',
                        'count' => $summary['hadir'],
                        'badge' => 'success',
                    ],
                    [
                        'key' => 'terlambat',
                        'label' => 'Terlambat',
                        'count' => $summary['terlambat'],
                        'badge' => 'warning',
                    ],
                    [
                        'key' => 'izin',
                        'label' => 'Izin',
                        'count' => $summary['izin'],
                        'badge' => 'info',
                    ],
                    [
                        'key' => 'sakit',
                        'label' => 'Sakit',
                        'count' => $summary['sakit'],
                        'badge' => 'danger',
                    ],
                    [
                        'key' => 'belum_absen',
                        'label' => 'Belum Absen',
                        'count' => $summary['belum_absen'],
                        'badge' => 'secondary',
                    ],
                ];
            }
        );
    }

    public function checkIn(User $user, array $locationPayload): Attendance
    {
        $intern = $this->resolveIntern($user);
        $today = today();

        $this->ensureInternCanSubmitForDate($intern, $today);

        $attendance = Attendance::query()
            ->where('intern_id', $intern->id)
            ->whereDate('date', $today->toDateString())
            ->first() ?? new Attendance([
                'intern_id' => $intern->id,
                'date' => $today->toDateString(),
            ]);

        if ($attendance->exists && in_array($attendance->status, Attendance::submissionStatuses(), true)) {
            throw ValidationException::withMessages([
                'attendance' => 'Anda sudah mengajukan status absensi untuk hari ini.',
            ]);
        }

        if ($attendance->check_in_at) {
            throw ValidationException::withMessages([
                'attendance' => 'Anda sudah melakukan check in hari ini.',
            ]);
        }

        $now = now();
        $lateAfter = Carbon::parse($today->toDateString().' '.config('attendance.late_after'));
        $lateMinutes = $now->greaterThan($lateAfter) ? $lateAfter->diffInMinutes($now) : 0;
        $locationMatch = $this->resolveLocationMatch($intern, $locationPayload);

        $attendance->fill([
            'status' => $lateMinutes > 0 ? Attendance::STATUS_LATE : Attendance::STATUS_PRESENT,
            'check_in_at' => $now,
            'check_in_latitude' => $locationPayload['latitude'],
            'check_in_longitude' => $locationPayload['longitude'],
            'check_in_accuracy' => $locationPayload['accuracy'] ?? null,
            'check_in_distance_meters' => $locationMatch['distance_meters'],
            'attendance_location_id' => $locationMatch['location']?->id,
            'late_minutes' => $lateMinutes,
        ])->save();

        $this->notifyStakeholders(
            $intern,
            'Intern Check In',
            $intern->name.' melakukan check in pada '.$now->translatedFormat('d M Y H:i'),
            $lateMinutes > 0 ? 'warning' : 'success',
            icon: 'ri-login-box-line'
        );

        $this->invalidateAttendanceCaches($intern, $user);

        return $attendance->refresh();
    }

    public function checkOut(User $user, array $locationPayload): Attendance
    {
        $intern = $this->resolveIntern($user);
        $attendance = Attendance::query()
            ->where('intern_id', $intern->id)
            ->whereDate('date', today())
            ->first();

        if (! $attendance || ! $attendance->check_in_at) {
            throw ValidationException::withMessages([
                'attendance' => 'Anda belum melakukan check in hari ini.',
            ]);
        }

        if (in_array($attendance->status, Attendance::submissionStatuses(), true)) {
            throw ValidationException::withMessages([
                'attendance' => 'Status izin atau sakit tidak dapat melakukan check out.',
            ]);
        }

        if ($attendance->check_out_at) {
            throw ValidationException::withMessages([
                'attendance' => 'Anda sudah melakukan check out hari ini.',
            ]);
        }

        $now = now();
        $locationMatch = $this->resolveLocationMatch($intern, $locationPayload);

        $attendance->forceFill([
            'check_out_at' => $now,
            'check_out_latitude' => $locationPayload['latitude'],
            'check_out_longitude' => $locationPayload['longitude'],
            'check_out_accuracy' => $locationPayload['accuracy'] ?? null,
            'check_out_distance_meters' => $locationMatch['distance_meters'],
            'attendance_location_id' => $attendance->attendance_location_id ?: $locationMatch['location']?->id,
            'work_minutes' => $attendance->check_in_at->diffInMinutes($now),
        ])->save();

        $this->notifyStakeholders(
            $intern,
            'Intern Check Out',
            $intern->name.' melakukan check out pada '.$now->translatedFormat('d M Y H:i'),
            'info',
            icon: 'ri-logout-box-r-line'
        );

        $this->invalidateAttendanceCaches($intern, $user);

        return $attendance->refresh();
    }

    public function submit(User $user, array $data): Attendance
    {
        $intern = $this->resolveIntern($user);
        $date = Carbon::parse($data['date']);

        $this->ensureInternCanSubmitForDate($intern, $date);

        $existingAttendance = Attendance::query()
            ->where('intern_id', $intern->id)
            ->whereDate('date', $date)
            ->first();

        if ($existingAttendance) {
            throw ValidationException::withMessages([
                'date' => 'Absensi untuk tanggal tersebut sudah tercatat.',
            ]);
        }

        $attendance = Attendance::create([
            'intern_id' => $intern->id,
            'date' => $date->toDateString(),
            'status' => $data['type'],
            'reason' => $data['reason'],
            'attachment_path' => $data['attachment_path'] ?? null,
        ]);

        $this->notifyStakeholders(
            $intern,
            'Pengajuan Absensi Baru',
            $intern->name.' mengajukan '.$attendance->status_label.' untuk tanggal '.$date->translatedFormat('d M Y'),
            $attendance->status === Attendance::STATUS_SICK ? 'danger' : 'info',
            icon: $attendance->status === Attendance::STATUS_SICK ? 'ri-heart-pulse-line' : 'ri-file-list-3-line'
        );

        $this->invalidateAttendanceCaches($intern, $user);

        return $attendance;
    }

    public function markAbsentForDate(CarbonInterface $date): int
    {
        if (! in_array($date->dayOfWeekIso, config('attendance.working_days', [1, 2, 3, 4, 5]), true)) {
            return 0;
        }

        $createdCount = 0;

        Intern::query()
            ->where('status', 'active')
            ->whereDate('start_date', '<=', $date->toDateString())
            ->whereDate('end_date', '>=', $date->toDateString())
            ->whereDoesntHave('attendances', function (Builder $builder) use ($date) {
                $builder->whereDate('date', $date->toDateString());
            })
            ->chunkById(100, function ($interns) use (&$createdCount, $date) {
                foreach ($interns as $intern) {
                    Attendance::create([
                        'intern_id' => $intern->id,
                        'date' => $date->toDateString(),
                        'status' => Attendance::STATUS_ABSENT,
                    ]);

                    $createdCount++;
                }
            });

        return $createdCount;
    }

    private function resolveIntern(User $user): Intern
    {
        $intern = $user->intern;

        if (! $intern) {
            throw ValidationException::withMessages([
                'attendance' => 'Akun Anda belum terhubung dengan data intern.',
            ]);
        }

        return $intern;
    }

    private function ensureInternCanSubmitForDate(Intern $intern, CarbonInterface $date): void
    {
        if ($intern->status !== 'active') {
            throw ValidationException::withMessages([
                'attendance' => 'Hanya intern aktif yang dapat mengisi absensi.',
            ]);
        }

        if ($date->isFuture()) {
            throw ValidationException::withMessages([
                'date' => 'Absensi tidak dapat diajukan untuk tanggal di masa depan.',
            ]);
        }

        if ($date->lt($intern->start_date) || $date->gt($intern->end_date)) {
            throw ValidationException::withMessages([
                'date' => 'Tanggal absensi berada di luar periode magang Anda.',
            ]);
        }
    }

    private function notifyStakeholders(
        Intern $intern,
        string $title,
        string $body,
        string $type = 'info',
        ?string $url = null,
        ?string $icon = null
    ): void {
        $adminIds = User::query()
            ->whereHas('roles', function (Builder $builder) {
                $builder->whereIn('name', ['superadmin', 'admin']);
            })
            ->pluck('id');

        $mentorIds = $intern->division_id
            ? User::query()
                ->where('division_id', $intern->division_id)
                ->whereHas('roles', function (Builder $builder) {
                    $builder->where('name', 'mentor');
                })
                ->pluck('id')
            : collect();

        $recipientIds = $adminIds->merge($mentorIds)->unique();

        foreach ($recipientIds as $recipientId) {
            NotificationService::send(
                userId: (int) $recipientId,
                title: $title,
                body: $body,
                type: $type,
                url: $url,
                icon: $icon
            );
        }
    }

    private function buildStatusCountPayload(Collection $counts): array
    {
        $payload = [];

        foreach (Attendance::labels() as $status => $label) {
            $payload[$status] = [
                'label' => $label,
                'count' => (int) ($counts[$status] ?? 0),
                'badge' => Attendance::badgeClasses()[$status] ?? 'secondary',
            ];
        }

        return $payload;
    }

    private function buildMonitoringInternQuery(
        User $user,
        array $filters,
        CarbonInterface $monitorDate,
        CarbonInterface $month
    ): Builder {
        $presentStatuses = [
            Attendance::STATUS_PRESENT,
            Attendance::STATUS_LATE,
            Attendance::STATUS_PERMISSION,
            Attendance::STATUS_SICK,
        ];

        return Intern::query()
            ->where('status', 'active')
            ->with([
                'user',
                'division',
                'attendances' => function ($builder) use ($monitorDate) {
                    $builder
                        ->with('attendanceLocation')
                        ->whereDate('date', $monitorDate->toDateString())
                        ->latest('check_in_at');
                },
            ])
            ->withCount([
                'attendances as attendance_records_count' => function ($builder) use ($month, $presentStatuses) {
                    $builder
                        ->forMonth($month->year, $month->month)
                        ->whereIn('status', $presentStatuses);
                },
            ])
            ->when($user->hasRole('mentor'), function (Builder $builder) use ($user) {
                $builder->where('division_id', $user->division_id);
            })
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
            ->when(! empty($filters['category']), function (Builder $builder) use ($filters) {
                $builder->where('type', $filters['category']);
            })
            ->orderBy('name');
    }

    private function resolveLocationMatch(Intern $intern, array $locationPayload): array
    {
        $accuracyTolerance = $this->resolveAccuracyTolerance($locationPayload);
        $locations = $intern->activeAttendanceLocations()
            ->where('attendance_locations.is_active', true)
            ->orderByDesc('attendance_location_intern.is_primary')
            ->get([
                'attendance_locations.id',
                'attendance_locations.name',
                'attendance_locations.latitude',
                'attendance_locations.longitude',
                'attendance_locations.radius_meters',
            ]);

        if ($locations->isEmpty()) {
            return [
                'location' => null,
                'distance_meters' => null,
            ];
        }

        $matchedLocation = null;
        $matchedDistance = null;
        $nearestLocation = null;
        $nearestDistance = null;

        foreach ($locations as $location) {
            $distance = (int) round($this->calculateDistanceMeters(
                (float) $locationPayload['latitude'],
                (float) $locationPayload['longitude'],
                (float) $location->latitude,
                (float) $location->longitude
            ));

            if ($nearestDistance === null || $distance < $nearestDistance) {
                $nearestDistance = $distance;
                $nearestLocation = $location;
            }

            $allowedDistance = (int) $location->radius_meters + $accuracyTolerance;

            if ($distance <= $allowedDistance && ($matchedDistance === null || $distance < $matchedDistance)) {
                $matchedLocation = $location;
                $matchedDistance = $distance;
            }
        }

        if ($matchedLocation) {
            return [
                'location' => $matchedLocation,
                'distance_meters' => $matchedDistance,
            ];
        }

        $message = 'Lokasi Anda berada di luar area absensi yang diizinkan.';

        if ($nearestLocation && $nearestDistance !== null) {
            $message .= ' Titik terdekat: '.$nearestLocation->name.' ('.$nearestDistance.' m, radius '.$nearestLocation->radius_meters.' m';

            if ($accuracyTolerance > 0) {
                $message .= ', toleransi akurasi '.$accuracyTolerance.' m';
            }

            $message .= ').';
        }

        throw ValidationException::withMessages([
            'attendance' => $message,
        ]);
    }

    private function resolveAccuracyTolerance(array $locationPayload): int
    {
        $accuracy = isset($locationPayload['accuracy']) ? (float) $locationPayload['accuracy'] : 0;
        $cap = (int) config('attendance.location_accuracy_tolerance_cap', 30);

        if ($accuracy <= 0 || $cap <= 0) {
            return 0;
        }

        return (int) floor(min($accuracy, $cap));
    }

    private function calculateDistanceMeters(
        float $originLatitude,
        float $originLongitude,
        float $targetLatitude,
        float $targetLongitude
    ): float {
        $earthRadius = 6371000;
        $latitudeDelta = deg2rad($targetLatitude - $originLatitude);
        $longitudeDelta = deg2rad($targetLongitude - $originLongitude);

        $a = sin($latitudeDelta / 2) ** 2
            + cos(deg2rad($originLatitude))
            * cos(deg2rad($targetLatitude))
            * sin($longitudeDelta / 2) ** 2;

        return 2 * $earthRadius * asin(min(1, sqrt($a)));
    }

    private function applyMonitoringFilters(Builder $query, User $user, array $filters): void
    {
        if ($user->hasRole('mentor')) {
            $query->whereHas('intern', function (Builder $builder) use ($user) {
                $builder->where('division_id', $user->division_id);
            });
        }

        if (! empty($filters['status'])) {
            $query->forStatus($filters['status']);
        }

        if (! empty($filters['division_id'])) {
            $query->whereHas('intern', function (Builder $builder) use ($filters) {
                $builder->where('division_id', $filters['division_id']);
            });
        }

        if (! empty($filters['date_from'])) {
            $query->whereDate('date', '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $query->whereDate('date', '<=', $filters['date_to']);
        }
    }

    private function hasMonitoringFilters(array $filters): bool
    {
        foreach (['status', 'division_id', 'date_from', 'date_to'] as $key) {
            if (! empty($filters[$key])) {
                return true;
            }
        }

        return false;
    }

    private function invalidateAttendanceCaches(Intern $intern, User $user): void
    {
        $today = today();
        Cache::forget($this->todayAttendanceCacheKey($intern->id, $today));
        Cache::forget($this->internSummaryCacheKey($intern->id, $today));
        Cache::forget($this->monitoringSummaryCacheKey($user->id, [], $today, $today));
        Cache::forget($this->monitoringInternsCacheKey($user->id, [], $today, $today));
    }

    private function todayAttendanceCacheKey(int $internId, CarbonInterface $date): string
    {
        return sprintf('ims.attendance.today.%d.%s', $internId, $date->toDateString());
    }

    private function internSummaryCacheKey(int $internId, CarbonInterface $month): string
    {
        return sprintf('ims.attendance.summary.%d.%s', $internId, $month->format('Y-m'));
    }

    private function monitoringSummaryCacheKey(int $userId, array $filters, CarbonInterface $monitorDate, CarbonInterface $month): string
    {
        return sprintf(
            'ims.attendance.monitor.summary.%d.%s.%s',
            $userId,
            md5(json_encode($filters)),
            $monitorDate->toDateString().'|'.$month->format('Y-m')
        );
    }

    private function monitoringInternsCacheKey(int $userId, array $filters, CarbonInterface $monitorDate, CarbonInterface $month): string
    {
        return sprintf(
            'ims.attendance.monitor.list.%d.%s.%s',
            $userId,
            md5(json_encode($filters)),
            $monitorDate->toDateString().'|'.$month->format('Y-m')
        );
    }
}
