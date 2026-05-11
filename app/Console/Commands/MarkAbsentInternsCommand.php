<?php

namespace App\Console\Commands;

use App\Services\AttendanceService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MarkAbsentInternsCommand extends Command
{
    protected $signature = 'attendance:mark-absent {--date=}';

    protected $description = 'Menandai intern aktif yang belum memiliki absensi sebagai Tidak Hadir.';

    public function handle(AttendanceService $attendanceService): int
    {
        $date = $this->option('date')
            ? Carbon::parse($this->option('date'))
            : today();

        $createdCount = $attendanceService->markAbsentForDate($date);

        $this->info("Berhasil menandai {$createdCount} data absensi sebagai Tidak Hadir.");

        return self::SUCCESS;
    }
}
