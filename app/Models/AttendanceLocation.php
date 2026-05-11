<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttendanceLocation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'radius_meters',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'radius_meters' => 'integer',
        'is_active' => 'boolean',
    ];

    public function interns(): BelongsToMany
    {
        return $this->belongsToMany(Intern::class)
            ->withPivot(['is_primary', 'is_active', 'assigned_at', 'notes'])
            ->withTimestamps();
    }

    public function activeInterns(): BelongsToMany
    {
        return $this->interns()->wherePivot('is_active', true);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}
