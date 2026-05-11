<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $institutions = DB::table('institutions')
            ->select('id', 'name')
            ->get();

        foreach ($institutions as $institution) {
            $normalizedName = mb_strtolower(trim((string) $institution->name));

            DB::table('interns')
                ->whereNull('institution_id')
                ->where(function ($query) use ($normalizedName) {
                    $query
                        ->whereRaw('LOWER(TRIM(COALESCE(institution_manual_name, ""))) = ?', [$normalizedName])
                        ->orWhereRaw('LOWER(TRIM(COALESCE(institution, ""))) = ?', [$normalizedName]);
                })
                ->update([
                    'institution_id' => $institution->id,
                ]);
        }
    }

    public function down(): void
    {
        //
    }
};
