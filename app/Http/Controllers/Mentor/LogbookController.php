<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Services\LogbookService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LogbookController extends Controller
{
    public function __construct(
        protected LogbookService $logbookService
    ) {}

    public function index(Request $request)
    {
        $logbooks = $this->logbookService->getLogbooksForUser($request->user());
        return view('pages.mentor.logbooks.index', compact('logbooks'));
    }

    public function show($id)
    {
        $logbook = $this->logbookService->getLogbookById($id);
        
        // Cek apakah intern ini adalah anak bimbingan mentor tersebut
        abort_if(optional($logbook->intern)->mentor_id !== auth()->id(), 403, 'Anda tidak memiliki akses ke logbook ini.');

        // Notifikasi ke Intern: "Mentor sudah melihat logbook kamu"
        if ($logbook->intern && $logbook->intern->user_id) {
            NotificationService::send(
                userId: $logbook->intern->user_id,
                title: 'Logbook Dilihat Mentor',
                body: 'Mentor ' . auth()->user()->name . ' telah melihat logbook Anda tanggal ' . \Carbon\Carbon::parse($logbook->tanggal)->translatedFormat('d M Y'),
                type: 'success',
                icon: 'ri-eye-line'
            );
        }

        return view('pages.mentor.logbooks.show', compact('logbook'));
    }
}
