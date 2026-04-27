<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLogbookRequest;
use App\Models\User;
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
        return view('pages.intern.logbooks.index', compact('logbooks'));
    }

    public function create()
    {
        return view('pages.intern.logbooks.create');
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

        return redirect()->route('intern.logbooks.index')->with('status', 'Logbook berhasil disimpan!');
    }

    public function edit($id)
    {
        $logbook = $this->logbookService->getLogbookById($id);
        return view('pages.intern.logbooks.edit', compact('logbook'));
    }

    public function update(StoreLogbookRequest $request, $id)
    {
        $data = $request->validated();
        $this->logbookService->updateLogbook($id, $data);

        return redirect()->route('intern.logbooks.index')->with('status', 'Logbook berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $this->logbookService->deleteLogbook($id);
        return redirect()->route('intern.logbooks.index')->with('status', 'Logbook berhasil dihapus!');
    }
}
