<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Logbook extends Model
{
    protected $fillable = [
        'intern_id',
        'tanggal',
        'uraian_aktivitas',
        'pembelajaran_diperoleh',
        'kendala_dialami',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function intern(): BelongsTo
    {
        return $this->belongsTo(Intern::class);
    }
}
