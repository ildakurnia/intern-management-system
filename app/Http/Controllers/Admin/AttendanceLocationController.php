<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AttendanceLocation\StoreAttendanceLocationRequest;
use App\Http\Requests\Admin\AttendanceLocation\UpdateAttendanceLocationRequest;
use App\Models\AttendanceLocation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceLocationController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();

        $locations = AttendanceLocation::query()
            ->withCount(['activeInterns as active_interns_count'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($builder) use ($search) {
                    $builder->where('name', 'like', "%{$search}%")
                        ->orWhere('notes', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.admin.attendance-locations.index', compact('locations', 'search'));
    }

    public function create(): View
    {
        return view('pages.admin.attendance-locations.create', [
            'location' => new AttendanceLocation([
                'radius_meters' => 100,
                'is_active' => true,
            ]),
        ]);
    }

    public function store(StoreAttendanceLocationRequest $request): RedirectResponse
    {
        AttendanceLocation::create([
            ...$request->validated(),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('admin.attendance-locations.index')
            ->with('success', 'Lokasi absensi berhasil ditambahkan.');
    }

    public function edit(AttendanceLocation $attendance_location): View
    {
        return view('pages.admin.attendance-locations.edit', [
            'location' => $attendance_location,
        ]);
    }

    public function update(UpdateAttendanceLocationRequest $request, AttendanceLocation $attendance_location): RedirectResponse
    {
        $attendance_location->update([
            ...$request->validated(),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.attendance-locations.index')
            ->with('success', 'Lokasi absensi berhasil diperbarui.');
    }

    public function destroy(AttendanceLocation $attendance_location): RedirectResponse
    {
        if ($attendance_location->interns()->exists()) {
            return back()->with('error', 'Lokasi tidak bisa dihapus karena masih terhubung dengan data intern.');
        }

        $attendance_location->delete();

        return redirect()
            ->route('admin.attendance-locations.index')
            ->with('success', 'Lokasi absensi berhasil dihapus.');
    }
}
