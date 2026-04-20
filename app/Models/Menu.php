<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Models\Permission;

class Menu extends Model
{
    protected $fillable = [
        'title',
        'route_name',
        'icon',
        'parent_id',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('order');
    }

    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class)->orderBy('sort_order');
    }
}
