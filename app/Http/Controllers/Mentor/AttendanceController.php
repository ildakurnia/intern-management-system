<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Intern;
use App\Services\AttendanceService;
use Carbon\Carbon;
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
        $filters = $request->only([
            'search',
            'category',
        ]);

        return view('pages.mentor.attendances.index', [
            'interns' => $this->attendanceService->getMonitoringInterns($request->user(), $filters),
            'summary' => $this->attendanceService->getMonitoringInternListSummary($request->user(), $filters),
            'categoryOptions' => [
                'mahasiswa' => 'Mahasiswa',
                'siswa' => 'Siswa',
            ],
            'selectedMonth' => $month,
        ]);
    }

    public function show(Request $request, Intern $intern): View
    {
        abort_if($intern->division_id !== $request->user()->division_id, 403);

        $month = $request->string('month')->toString() ?: now()->format('Y-m');
        $status = $request->string('status')->toString() ?: null;

        $intern->load(['user', 'division']);

        return view('pages.mentor.attendances.show', [
            'intern' => $intern,
            'todayAttendance' => $this->attendanceService->getTodayAttendanceForIntern($intern),
            'attendances' => $this->attendanceService->getAttendancesForIntern($intern, [
                'month' => $month,
                'status' => $status,
            ]),
            'attendanceSummary' => $this->attendanceService->getInternSummaryForIntern(
                $intern,
                Carbon::createFromFormat('Y-m', $month)
            ),
            'statusOptions' => Attendance::labels(),
            'selectedMonth' => $month,
            'selectedStatus' => $status,
        ]);
    }
}
