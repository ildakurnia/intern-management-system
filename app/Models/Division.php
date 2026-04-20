<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Division extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Interns assigned to this division
     */
    public function interns(): HasMany
    {
        return $this->hasMany(Intern::class);
    }

    /**
     * Mentors (users) assigned to this division
     */
    public function mentors(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
