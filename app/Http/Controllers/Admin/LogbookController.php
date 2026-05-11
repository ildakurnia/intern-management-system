<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Logbook;
use App\Services\LogbookService;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LogbookController extends Controller
{
    public function __construct(
        protected LogbookService $logbookService
    ) {}

    public function index(Request $request): View
    {
        $calendarMonth = $request->filled('month')
            ? Carbon::createFromFormat('Y-m', (string) $request->input('month'))->startOfMonth()
            : now()->startOfMonth();

        $logbooks = Logbook::query()
            ->with(['intern.user', 'intern.division'])
            ->whereBetween('tanggal', [
                $calendarMonth->copy()->startOfMonth()->toDateString(),
                $calendarMonth->copy()->endOfMonth()->toDateString(),
            ])
            ->orderBy('tanggal')
            ->orderByDesc('created_at')
            ->get();

        return view('pages.admin.logbooks.index', compact('logbooks', 'calendarMonth'));
    }

    public function show($id)
    {
        $logbook = $this->logbookService->getLogbookById($id);

        // Notifikasi ke Intern: "Admin sudah melihat logbook kamu"
        NotificationService::send(
            userId: $logbook->intern->user_id,
            title: 'Logbook Dilihat Admin',
            body: 'Admin ' . auth()->user()->name . ' telah melihat logbook Anda tanggal ' . \Carbon\Carbon::parse($logbook->tanggal)->translatedFormat('d M Y'),
            type: 'success',
            icon: 'ri-eye-line'
        );

        return view('pages.admin.logbooks.show', compact('logbook'));
    }
}
