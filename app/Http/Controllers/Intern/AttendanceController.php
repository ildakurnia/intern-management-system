<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Http\Requests\Intern\Attendance\StoreAttendanceCheckpointRequest;
use App\Http\Requests\Intern\Attendance\StoreAttendanceSubmissionRequest;
use App\Models\Attendance;
use App\Services\AttendanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function __construct(
        private readonly AttendanceService $attendanceService,
    ) {
    }

    public function index(Request $request): View
    {
        $month = $request->string('month')->toString() ?: now()->format('Y-m');
        $status = $request->string('status')->toString() ?: null;

        return view('pages.intern.attendances.index', [
            'todayAttendance' => $this->attendanceService->getTodayAttendanceForUser($request->user()),
            'attendances' => $this->attendanceService->getAttendancesForUser($request->user(), [
                'month' => $month,
                'status' => $status,
            ]),
            'attendanceSummary' => $this->attendanceService->getInternSummary($request->user()),
            'statusOptions' => Attendance::labels(),
            'selectedMonth' => $month,
            'selectedStatus' => $status,
        ]);
    }

    public function checkIn(StoreAttendanceCheckpointRequest $request): RedirectResponse
    {
        $this->attendanceService->checkIn($request->user(), $request->validated());

        return redirect()
            ->route('intern.attendances.index')
            ->with('status', 'Check in berhasil disimpan.');
    }

    public function checkOut(StoreAttendanceCheckpointRequest $request): RedirectResponse
    {
        $this->attendanceService->checkOut($request->user(), $request->validated());

        return redirect()
            ->route('intern.attendances.index')
            ->with('status', 'Check out berhasil disimpan.');
    }

    public function createSubmission(Request $request, string $type): View
    {
        abort_unless(in_array($type, Attendance::submissionStatuses(), true), 404);

        return view('pages.intern.attendances.submission', [
            'type' => $type,
            'typeLabel' => Attendance::labels()[$type],
        ]);
    }

    public function storeSubmission(StoreAttendanceSubmissionRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('attachment')) {
            $validated['attachment_path'] = $request->file('attachment')->store(
                config('attendance.attachment_directory'),
                config('attendance.attachment_disk')
            );
        }

        $this->attendanceService->submit($request->user(), $validated);

        return redirect()
            ->route('intern.attendances.index')
            ->with('status', 'Pengajuan absensi berhasil dikirim.');
    }
}
