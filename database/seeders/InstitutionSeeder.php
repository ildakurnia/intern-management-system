<?php

namespace Database\Seeders;

use App\Models\Institution;
use App\Models\Intern;
use Illuminate\Database\Seeder;

class InstitutionSeeder extends Seeder
{
    public function run(): void
    {
        $institution = Institution::updateOrCreate(
            ['name' => 'Politeknik Negeri Batam'],
            [
                'is_active' => true,
                'is_allowance_eligible' => true,
            ],
        );

        Intern::query()
            ->whereNull('institution_id')
            ->where(function ($query) {
                $query
                    ->whereRaw('LOWER(TRIM(COALESCE(institution_manual_name, ""))) = ?', ['politeknik negeri batam'])
                    ->orWhereRaw('LOWER(TRIM(COALESCE(institution, ""))) = ?', ['politeknik negeri batam']);
            })
            ->update([
                'institution_id' => $institution->id,
            ]);
    }
}
