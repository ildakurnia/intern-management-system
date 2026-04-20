<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
            'documents_completed_at' => $this->ktp_path && $this->student_card_path && $this->bpjs_path
                ? ($this->documents_completed_at ?? now())
                : null,
        ])->save();
    }
}
