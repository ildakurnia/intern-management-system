<?php 

namespace App\Services;

use App\Models\Logbook;
use App\Models\User;

class LogbookService
{
    public function getLogbooksForUser(User $user)
    {
        if ($user->hasRole('intern')) {
            // Cek dulu apakah data intern-nya ada
            $internId = $user->intern?->id;

            return Logbook::query()
                ->with(['intern.user', 'intern.division'])
                ->where('intern_id', $internId)
                ->latest()
                ->paginate(10);
        }

        if ($user->hasRole('mentor')) {
            return Logbook::query()
                ->with(['intern.user', 'intern.division'])
                ->whereHas('intern', function ($q) use ($user) {
                    $q->where('division_id', $user->division_id);
                })
                ->latest()
                ->paginate(10);
        }

        return Logbook::query()
            ->with(['intern.user', 'intern.division'])
            ->latest()
            ->paginate(10); // Admin lihat semua
    }

    public function updateLogbook($id, array $data)
    {
        $logbook = $this->getLogbookById($id);
        return $logbook->update($data);
    }

    public function deleteLogbook($id)
    {
        $logbook = $this->getLogbookById($id);
        return $logbook->delete();
    }

    public function createLogbook(array $data)
    {
        return Logbook::create($data);
    }

    public function getLogbookById($id)
    {
        return Logbook::with(['intern.user', 'intern.division'])->findOrFail($id);
    }
}
