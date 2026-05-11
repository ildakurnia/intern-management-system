<?php

namespace App\Http\Controllers\Mentor;

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
            ->whereHas('intern', function ($query) use ($request) {
                $query->where('division_id', $request->user()->division_id);
            })
            ->whereBetween('tanggal', [
                $calendarMonth->copy()->startOfMonth()->toDateString(),
                $calendarMonth->copy()->endOfMonth()->toDateString(),
            ])
            ->orderBy('tanggal')
            ->orderByDesc('created_at')
            ->get();

        return view('pages.mentor.logbooks.index', compact('logbooks', 'calendarMonth'));
    }

    public function show($id)
    {
        $logbook = $this->logbookService->getLogbookById($id);
        
        // Opsional: Cek apakah intern ini di bawah divisi mentor tersebut
        abort_if($logbook->intern->division_id !== auth()->user()->division_id, 403);

        // Notifikasi ke Intern: "Mentor sudah melihat logbook kamu"
        NotificationService::send(
            userId: $logbook->intern->user_id,
            title: 'Logbook Dilihat Mentor',
            body: 'Mentor ' . auth()->user()->name . ' telah melihat logbook Anda tanggal ' . \Carbon\Carbon::parse($logbook->tanggal)->translatedFormat('d M Y'),
            type: 'success',
            icon: 'ri-eye-line'
        );

        return view('pages.mentor.logbooks.show', compact('logbook'));
    }
}
