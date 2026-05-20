<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Tandai satu notifikasi sebagai sudah dibaca.
     */
    public function markAsRead(Request $request, $id)
    {
        $notif = Notification::where('user_id', Auth::id())->findOrFail($id);
        $notif->update(['read_at' => now()]);
        Cache::forget("ims.topbar.notifications." . Auth::id());

        return response()->json(['success' => true]);
    }

    /**
     * Tandai semua notifikasi sebagai sudah dibaca.
     */
    public function markAllAsRead(Request $request)
    {
        Notification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        Cache::forget("ims.topbar.notifications." . Auth::id());

        return response()->json(['success' => true]);
    }

    /**
     * Hapus (archive) satu notifikasi.
     */
    public function destroy($id)
    {
        $notif = Notification::where('user_id', Auth::id())->findOrFail($id);
        $notif->delete();
        Cache::forget("ims.topbar.notifications." . Auth::id());

        return response()->json(['success' => true]);
    }
}
