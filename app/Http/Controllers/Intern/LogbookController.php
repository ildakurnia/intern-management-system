<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLogbookRequest;
use App\Models\Logbook;
use App\Models\User;
use App\Services\LogbookService;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LogbookController extends Controller
{
    public function __construct(
        protected LogbookService $logbookService
    ) {}

    public function index(Request $request)
    {
        $intern = $request->user()->intern;
        $monthParam = $request->string('month')->toString();

        try {
            $calendarMonth = $monthParam !== ''
                ? Carbon::createFromFormat('Y-m', $monthParam)->startOfMonth()
                : now()->startOfMonth();
        } catch (\Throwable $exception) {
            $calendarMonth = now()->startOfMonth();
        }

        $logbooks = Logbook::query()
            ->where('intern_id', $intern->id)
            ->whereBetween('tanggal', [
                $calendarMonth->copy()->startOfMonth()->toDateString(),
                $calendarMonth->copy()->endOfMonth()->toDateString(),
            ])
            ->orderBy('tanggal')
            ->get();

        return view('pages.intern.logbooks.index', compact('logbooks', 'calendarMonth', 'intern'));
    }

    public function create()
    {
        return redirect()
            ->route('intern.logbooks.index', ['month' => now()->format('Y-m')])
            ->with('status', 'Gunakan kalender logbook untuk memilih tanggal laporan.');
    }

    public function show($id)
    {
        $logbook = $this->logbookService->getLogbookById($id);
        return view('pages.intern.logbooks.show', compact('logbook'));
    }

    public function store(StoreLogbookRequest $request)
    {
        $data = $request->validated();
        $data['intern_id'] = $request->user()->intern->id;
        
        $logbook = $this->logbookService->createLogbook($data);

        // Kirim notifikasi ke semua admin & superadmin
        $admins = User::role(['admin', 'superadmin'])->get();
        foreach ($admins as $admin) {
            NotificationService::send(
                userId: $admin->id,
                title: 'Logbook Baru Diterima',
                body: $request->user()->name . ' mengupload logbook untuk tanggal ' . \Carbon\Carbon::parse($data['tanggal'])->translatedFormat('d M Y'),
                type: 'info',
                icon: 'ri-file-text-line'
            );
        }

        if ($request->filled('return_month')) {
            return redirect()
                ->route('intern.logbooks.index', ['month' => $request->input('return_month')])
                ->with('status', 'Logbook berhasil disimpan!');
        }

        return redirect()->route('intern.logbooks.index')->with('status', 'Logbook berhasil disimpan!');
    }

    public function edit($id)
    {
        return redirect()
            ->route('intern.logbooks.show', $id)
            ->with('status', 'Logbook yang sudah dikirim tidak dapat diedit lagi.');
    }

    public function update(StoreLogbookRequest $request, $id)
    {
        return redirect()
            ->route('intern.logbooks.show', $id)
            ->with('status', 'Logbook yang sudah dikirim tidak dapat diedit lagi.');
    }

    public function destroy($id)
    {
        $this->logbookService->deleteLogbook($id);
        return redirect()->route('intern.logbooks.index')->with('status', 'Logbook berhasil dihapus!');
    }
}
