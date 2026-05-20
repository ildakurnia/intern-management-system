<?php

namespace App\Console\Commands;

use App\Models\Intern;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SyncExpiredInternsCommand extends Command
{
    protected $signature = 'interns:sync-expired {--date= : Tanggal referensi untuk pengecekan}';

    protected $description = 'Menandai intern yang sudah lewat end_date menjadi selesai.';

    public function handle(): int
    {
        $date = $this->option('date')
            ? Carbon::parse($this->option('date'))
            : today();

        $updatedCount = Intern::query()
            ->where('status', 'active')
            ->whereDate('end_date', '<', $date->toDateString())
            ->update([
                'status' => 'completed',
            ]);

        $this->info("Berhasil menandai {$updatedCount} intern sebagai selesai.");

        return self::SUCCESS;
    }
}
