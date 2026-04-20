<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('route_name')->nullable();
            $table->string('icon')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('menus')->nullOnDelete();
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();

            $table->index(['parent_id', 'order']);
            $table->index('route_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
