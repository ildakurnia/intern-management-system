<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('divisions', function (Blueprint $table) {
            $table->id();
            $table->string('name');             // e.g. IT, Marketing, HR
            $table->string('code')->unique();   // e.g. IT, MKT, HR
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Add division_id to users so managers can be assigned to a division
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('division_id')->nullable()->constrained('divisions')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\Division::class);
            $table->dropColumn('division_id');
        });

        Schema::dropIfExists('divisions');
    }
};
