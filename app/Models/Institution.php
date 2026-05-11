<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Institution extends Model
{
    protected $fillable = [
        'name',
        'is_active',
        'is_allowance_eligible',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_allowance_eligible' => 'boolean',
    ];

    public function interns(): HasMany
    {
        return $this->hasMany(Intern::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
