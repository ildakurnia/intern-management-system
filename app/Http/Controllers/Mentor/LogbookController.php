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
