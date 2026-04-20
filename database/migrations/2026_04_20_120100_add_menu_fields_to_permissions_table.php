<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tableNames = config('permission.table_names');
        $permissionsTable = $tableNames['permissions'] ?? 'permissions';

        Schema::table($permissionsTable, function (Blueprint $table) {
            $table->foreignId('menu_id')
                ->nullable()
                ->after('guard_name')
                ->constrained('menus')
                ->nullOnDelete();
            $table->string('label')->nullable()->after('menu_id');
            $table->unsignedInteger('sort_order')->default(0)->after('label');

            $table->index(['menu_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        $tableNames = config('permission.table_names');
        $permissionsTable = $tableNames['permissions'] ?? 'permissions';

        Schema::table($permissionsTable, function (Blueprint $table) {
            $table->dropIndex(['menu_id', 'sort_order']);
            $table->dropConstrainedForeignId('menu_id');
            $table->dropColumn(['label', 'sort_order']);
        });
    }
};
