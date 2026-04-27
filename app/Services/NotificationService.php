<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    /**
     * Kirim notifikasi ke satu user.
     *
     * @param  int    $userId   ID user penerima
     * @param  string $title    Judul notifikasi
     * @param  string|null $body   Isi singkat notifikasi
     * @param  string $type     success | warning | info | danger
     * @param  string|null $url URL tujuan saat diklik
     * @param  string|null $icon Remix Icon class
     */
    public static function send(
        int    $userId,
        string $title,
        string $body = null,
        string $type = 'info',
        string $url  = null,
        string $icon = null
    ): Notification {
        $icons = [
            'success' => 'ri-checkbox-circle-line',
            'danger'  => 'ri-error-warning-line',
            'warning' => 'ri-alert-line',
            'info'    => 'ri-information-line',
        ];

        return Notification::create([
            'user_id' => $userId,
            'type'    => $type,
            'icon'    => $icon ?? ($icons[$type] ?? 'ri-notification-2-line'),
            'title'   => $title,
            'body'    => $body,
            'url'     => $url,
            'read_at' => null,
        ]);
    }
}
