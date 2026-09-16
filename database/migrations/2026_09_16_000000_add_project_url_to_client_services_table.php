<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('client_services')) {
            Schema::table('client_services', function (Blueprint $table) {
                if (!Schema::hasColumn('client_services', 'project_url')) {
                    $table->string('project_url', 500)->nullable()->after('status');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('client_services')) {
            Schema::table('client_services', function (Blueprint $table) {
                if (Schema::hasColumn('client_services', 'project_url')) {
                    $table->dropColumn('project_url');
                }
            });
        }
    }
};
