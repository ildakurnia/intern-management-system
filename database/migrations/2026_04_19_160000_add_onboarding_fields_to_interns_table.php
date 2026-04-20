<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('interns', function (Blueprint $table) {
            $table->string('registration_status')->default('pending')->after('status');
            $table->timestamp('registered_at')->nullable()->after('registration_status');
            $table->timestamp('profile_completed_at')->nullable()->after('registered_at');
            $table->timestamp('documents_completed_at')->nullable()->after('profile_completed_at');
        });

        Schema::table('interns', function (Blueprint $table) {
            $table->string('institution')->nullable()->change();
            $table->string('major')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('interns', function (Blueprint $table) {
            $table->string('institution')->nullable(false)->change();
            $table->string('major')->nullable(false)->change();
            $table->dropColumn([
                'registration_status',
                'registered_at',
                'profile_completed_at',
                'documents_completed_at',
            ]);
        });
    }
};
