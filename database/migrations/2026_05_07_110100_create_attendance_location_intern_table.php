<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_location_intern', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_location_id')->constrained()->cascadeOnDelete();
            $table->foreignId('intern_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_primary')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamp('assigned_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['attendance_location_id', 'intern_id'], 'attendance_location_intern_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_location_intern');
    }
};
