<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
     * Scope: filter by division (for manager-level access)
     */
    public function scopeForManager($query, User $manager)
    {
        if ($manager->hasRole('admin')) {
            return $query; // admin sees all
        }

        return $query->where('division_id', $manager->division_id);
    }
}
