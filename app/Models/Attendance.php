<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    public const STATUS_PRESENT = 'hadir';
    public const STATUS_LATE = 'terlambat';
    public const STATUS_PERMISSION = 'izin';
    public const STATUS_SICK = 'sakit';
    public const STATUS_ABSENT = 'tidak_hadir';

    protected $fillable = [
        'intern_id',
        'attendance_location_id',
        'date',
        'status',
        'check_in_at',
        'check_in_latitude',
        'check_in_longitude',
        'check_in_accuracy',
        'check_in_distance_meters',
        'check_out_at',
        'check_out_latitude',
        'check_out_longitude',
        'check_out_accuracy',
        'check_out_distance_meters',
        'late_minutes',
        'work_minutes',
        'reason',
        'attachment_path',
    ];

    protected $casts = [
        'date' => 'date',
        'check_in_at' => 'datetime',
        'check_out_at' => 'datetime',
        'check_in_latitude' => 'decimal:7',
        'check_in_longitude' => 'decimal:7',
        'check_in_accuracy' => 'decimal:2',
        'check_in_distance_meters' => 'integer',
        'check_out_latitude' => 'decimal:7',
        'check_out_longitude' => 'decimal:7',
        'check_out_accuracy' => 'decimal:2',
        'check_out_distance_meters' => 'integer',
        'late_minutes' => 'integer',
        'work_minutes' => 'integer',
    ];

    public static function statuses(): array
    {
        return [
            self::STATUS_PRESENT,
            self::STATUS_LATE,
            self::STATUS_PERMISSION,
            self::STATUS_SICK,
            self::STATUS_ABSENT,
        ];
    }

    public static function labels(): array
    {
        return [
            self::STATUS_PRESENT => 'Hadir',
            self::STATUS_LATE => 'Terlambat',
            self::STATUS_PERMISSION => 'Izin',
            self::STATUS_SICK => 'Sakit',
            self::STATUS_ABSENT => 'Tidak Hadir',
        ];
    }

    public static function badgeClasses(): array
    {
        return [
            self::STATUS_PRESENT => 'success',
            self::STATUS_LATE => 'warning',
            self::STATUS_PERMISSION => 'info',
            self::STATUS_SICK => 'danger',
            self::STATUS_ABSENT => 'secondary',
        ];
    }

    public static function submissionStatuses(): array
    {
        return [
            self::STATUS_PERMISSION,
            self::STATUS_SICK,
        ];
    }

    public function intern(): BelongsTo
    {
        return $this->belongsTo(Intern::class);
    }

    public function attendanceLocation(): BelongsTo
    {
        return $this->belongsTo(AttendanceLocation::class);
    }

    public function scopeForDate(Builder $query, string $date): Builder
    {
        return $query->whereDate('date', $date);
    }

    public function scopeForMonth(Builder $query, int $year, int $month): Builder
    {
        return $query->whereYear('date', $year)->whereMonth('date', $month);
    }

    public function scopeForStatus(Builder $query, ?string $status): Builder
    {
        return $status ? $query->where('status', $status) : $query;
    }

    public function getStatusLabelAttribute(): string
    {
        return self::labels()[$this->status] ?? ucfirst((string) $this->status);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return self::badgeClasses()[$this->status] ?? 'secondary';
    }

    public function getWorkDurationLabelAttribute(): string
    {
        if (! $this->work_minutes) {
            return '-';
        }

        $hours = intdiv($this->work_minutes, 60);
        $minutes = $this->work_minutes % 60;

        return $this->formatDuration($hours, $minutes);
    }

    public function getLateDurationLabelAttribute(): string
    {
        if (! $this->late_minutes) {
            return '-';
        }

        $hours = intdiv($this->late_minutes, 60);
        $minutes = $this->late_minutes % 60;

        return $this->formatDuration($hours, $minutes);
    }

    private function formatDuration(int $hours, int $minutes): string
    {
        $parts = [];

        if ($hours > 0) {
            $parts[] = $hours.' jam';
        }

        if ($minutes > 0) {
            $parts[] = $minutes.' menit';
        }

        return $parts === [] ? '0 menit' : implode(' ', $parts);
    }
}
