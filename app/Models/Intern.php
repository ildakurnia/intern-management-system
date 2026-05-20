<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Intern extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'division_id',
        'name',
        'email',
        'phone',
        'address',
        'birth_date',
        'gender',
        'photo',
        'type',
        'institution',
        'institution_id',
        'institution_manual_name',
        'bank_account_number',
        'major',
        'faculty',
        // Siswa
        'nis',
        'school_grade',
        // Mahasiswa
        'nim',
        'semester',
        'gpa',
        // Period
        'start_date',
        'end_date',
        'status',
        'registration_status',
        'registered_at',
        'profile_completed_at',
        'documents_completed_at',
        // Documents
        'ktp_path',
        'student_card_path',
        'bpjs_path',
        'recommendation_letter_path',
        'notes',
    ];

    protected $casts = [
        'birth_date'  => 'date',
        'start_date'  => 'date',
        'end_date'    => 'date',
        'gpa'         => 'decimal:2',
        'registered_at' => 'datetime',
        'profile_completed_at' => 'datetime',
        'documents_completed_at' => 'datetime',
    ];

    /**
     * The division this intern belongs to
     */
    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function institutionReference(): BelongsTo
    {
        return $this->belongsTo(Institution::class, 'institution_id');
    }

    /**
     * The user account linked to this intern (if any)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function logbooks(): HasMany
    {
        return $this->hasMany(Logbook::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function attendanceLocations(): BelongsToMany
    {
        return $this->belongsToMany(AttendanceLocation::class)
            ->withPivot(['is_primary', 'is_active', 'assigned_at', 'notes'])
            ->withTimestamps();
    }

    public function activeAttendanceLocations(): BelongsToMany
    {
        return $this->attendanceLocations()->wherePivot('is_active', true);
    }

    /**
     * Scope: only active interns
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope: filter by type (siswa/mahasiswa)
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope: filter by division (for mentor-level access)
     */
    public function scopeForMentor($query, User $mentor)
    {
        if ($mentor->hasAnyRole(['superadmin', 'admin'])) {
            return $query;
        }

        return $query->where('division_id', $mentor->division_id);
    }

    public function hasCompletedProfile(): bool
    {
        return $this->profile_completed_at !== null;
    }

    public function hasCompletedDocuments(): bool
    {
        return $this->documents_completed_at !== null;
    }

    public function refreshDocumentCompletion(): void
    {
        $this->forceFill([
            'documents_completed_at' => $this->ktp_path && $this->student_card_path && $this->bpjs_path && $this->recommendation_letter_path
                ? ($this->documents_completed_at ?? now())
                : null,
        ])->save();
    }

    public function refreshOperationalStatus(): void
    {
        $this->syncOperationalStatus();
    }

    public function syncOperationalStatus(?CarbonInterface $date = null): bool
    {
        $date ??= today();

        if ($this->status === 'terminated') {
            return false;
        }

        if ($this->isPeriodExpired($date)) {
            return $this->markPeriodCompleted();
        }

        $shouldBeActive = $this->registration_status === 'approved'
            && $this->hasCompletedProfile()
            && $this->hasCompletedDocuments();

        if ($shouldBeActive && $this->status !== 'active') {
            $this->forceFill(['status' => 'active'])->save();
            return true;
        }

        return false;
    }

    public function isPeriodExpired(?CarbonInterface $date = null): bool
    {
        $date ??= today();

        return $this->end_date !== null && $date->gt($this->end_date);
    }

    public function markPeriodCompleted(): bool
    {
        if (in_array($this->status, ['completed', 'terminated'], true)) {
            return false;
        }

        return (bool) $this->forceFill(['status' => 'completed'])->save();
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'active' => 'Aktif',
            'completed' => 'Selesai',
            'terminated' => 'Dihentikan',
            default => ucfirst((string) $this->status),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'active' => 'success',
            'completed' => 'secondary',
            'terminated' => 'danger',
            default => 'info',
        };
    }

    public function getInstitutionLabelAttribute(): string
    {
        return $this->institutionReference?->name
            ?? $this->institution_manual_name
            ?? $this->institution
            ?? '-';
    }
}
