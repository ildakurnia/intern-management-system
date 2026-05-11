<?php

namespace App\Http\Controllers\Admin;

use App\Exports\InternTemplateExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Intern\UpdateInternAttendanceLocationsRequest;
use App\Imports\InternsImport;
use App\Models\AttendanceLocation;
use App\Models\Intern;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Services\NotificationService;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class InternController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');

        // For admin, view all interns. For mentor, view only their division.
        // We'll use the scopeForMentor from the model.
        
        $interns = Intern::with(['user', 'division', 'institutionReference'])
            ->forMentor($request->user())
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                           ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('institution', 'like', "%{$search}%")
                    ->orWhere('major', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.interns.index', compact('interns', 'search'));
    }

    public function show(Intern $intern): View
    {
        // Eager load relations
        $intern->load(['user', 'division', 'logbooks', 'attendanceLocations', 'institutionReference']);

        $attendanceLocations = AttendanceLocation::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.interns.show', compact('intern', 'attendanceLocations'));
    }

    public function updateAttendanceLocations(UpdateInternAttendanceLocationsRequest $request, Intern $intern): RedirectResponse
    {
        $intern->loadMissing('attendanceLocations');

        $locationIds = collect($request->input('location_ids', []))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        if ($locationIds->isEmpty()) {
            $intern->attendanceLocations()->detach();

            return back()->with('status', 'Semua lokasi absensi intern berhasil dilepas.');
        }

        $primaryLocationId = $request->filled('primary_location_id')
            ? (int) $request->input('primary_location_id')
            : $locationIds->first();

        $existingLocations = $intern->attendanceLocations->keyBy('id');
        $syncPayload = [];

        foreach ($locationIds as $locationId) {
            $syncPayload[$locationId] = [
                'is_primary' => $locationId === $primaryLocationId,
                'is_active' => true,
                'assigned_at' => optional($existingLocations->get($locationId)?->pivot)->assigned_at ?? now(),
            ];
        }

        $intern->attendanceLocations()->sync($syncPayload);

        return back()->with('status', 'Lokasi absensi intern berhasil diperbarui.');
    }

    /**
     * Approve intern registration
     */
    public function approve(Intern $intern): RedirectResponse
    {
        if (! $intern->user_id) {
            return back()->with('status', 'Intern belum registrasi akun, jadi belum bisa di-approve.');
        }

        $intern->update([
            'registration_status' => 'approved',
        ]);

        $intern->refreshOperationalStatus();

        if ($intern->user_id) {
            NotificationService::send(
                userId: $intern->user_id,
                title: 'Pendaftaran Disetujui',
                body: 'Akun magang Anda telah disetujui oleh Admin. Silakan lanjutkan dengan melengkapi profil dan berkas wajib.',
                type: 'success',
                icon: 'ri-check-double-line'
            );
        }

        return back()->with('status', 'Intern berhasil disetujui. Tahap berikutnya adalah melengkapi data onboarding.');
    }

    // Stubs for the import functionalities
    public function import(): View
    {
        return view('pages.admin.interns.import');
    }

    public function template(): BinaryFileResponse
    {
        return Excel::download(new InternTemplateExport(), 'template-import-intern.xlsx');
    }

    public function storeImport(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv,txt'],
        ]);

        $import = new InternsImport();

        try {
            Excel::import($import, $validated['file']);
        } catch (\Throwable $exception) {
            return back()
                ->withInput()
                ->withErrors([
                    'file' => 'File gagal diproses. Pastikan format file sesuai template dan tidak rusak.',
                ]);
        }

        $result = $import->result();
        $statusMessage = "Import selesai. {$result['imported']} data berhasil diproses";

        if ($result['skipped'] > 0) {
            $statusMessage .= " dan {$result['skipped']} baris dilewati.";
        } else {
            $statusMessage .= '.';
        }

        return redirect()
            ->route('admin.interns.index')
            ->with('status', $statusMessage)
            ->with('import_errors', $result['errors']);
    }
}
