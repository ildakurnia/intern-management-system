<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\LogbookService;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class LogbookController extends Controller
{
    public function __construct(
        protected LogbookService $logbookService
    ) {}

    public function index(Request $request)
    {
        $logbooks = $this->logbookService->getLogbooksForUser($request->user());
        return view('pages.admin.logbooks.index', compact('logbooks'));
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
