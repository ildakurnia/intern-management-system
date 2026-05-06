<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'intern_id',
        'mentor_id',
        'title',
        'description',
        'status',
        'due_date',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function intern()
    {
        return $this->belongsTo(Intern::class)->withTrashed();
    }

    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }
}
