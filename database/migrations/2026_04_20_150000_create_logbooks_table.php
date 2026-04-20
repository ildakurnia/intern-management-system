<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logbooks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intern_id')->constrained('interns')->cascadeOnDelete();
            $table->date('tanggal');
            $table->text('uraian_aktivitas');
            $table->text('pembelajaran_diperoleh');
            $table->text('kendala_dialami')->nullable();
            $table->timestamps();

            $table->unique(['intern_id', 'tanggal']);
            $table->index('tanggal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logbooks');
    }
};
