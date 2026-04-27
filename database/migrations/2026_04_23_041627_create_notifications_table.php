<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('type')->default('info');
                $table->string('icon')->default('ri-information-line');
                $table->string('title');
                $table->text('body')->nullable();
                $table->string('url')->nullable();
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        } else {
            // Table exists - add missing columns if needed
            Schema::table('notifications', function (Blueprint $table) {
                if (!Schema::hasColumn('notifications', 'user_id')) {
                    $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
                }
                if (!Schema::hasColumn('notifications', 'type')) {
                    $table->string('type')->default('info')->after('user_id');
                }
                if (!Schema::hasColumn('notifications', 'icon')) {
                    $table->string('icon')->default('ri-information-line')->after('type');
                }
                if (!Schema::hasColumn('notifications', 'title')) {
                    $table->string('title')->after('icon');
                }
                if (!Schema::hasColumn('notifications', 'body')) {
                    $table->text('body')->nullable()->after('title');
                }
                if (!Schema::hasColumn('notifications', 'url')) {
                    $table->string('url')->nullable()->after('body');
                }
                if (!Schema::hasColumn('notifications', 'read_at')) {
                    $table->timestamp('read_at')->nullable()->after('url');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
