<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Notification extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'type',
        'icon',
        'title',
        'body',
        'url',
        'read_at',
        'notifiable_type',
        'notifiable_id',
        'data',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    /**
     * Relasi ke User penerima notifikasi.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Cek apakah notifikasi sudah dibaca.
     */
    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    /**
     * Scope: hanya yang belum dibaca.
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope: milik user tertentu, terbaru dulu.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId)->latest();
    }
}
