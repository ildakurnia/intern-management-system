<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('interns', function (Blueprint $table) {
            $table->foreignId('institution_id')
                ->nullable()
                ->after('institution')
                ->constrained('institutions')
                ->nullOnDelete();
            $table->string('institution_manual_name')->nullable()->after('institution_id');
        });

        DB::table('interns')
            ->whereNotNull('institution')
            ->update([
                'institution_manual_name' => DB::raw('institution'),
            ]);
    }

    public function down(): void
    {
        Schema::table('interns', function (Blueprint $table) {
            $table->dropConstrainedForeignId('institution_id');
            $table->dropColumn('institution_manual_name');
        });
    }
};
