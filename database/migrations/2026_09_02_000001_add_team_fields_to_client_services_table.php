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
                if (!Schema::hasColumn('client_services', 'team_name')) {
                    $table->string('team_name')->nullable()->after('assigned_team');
                }
                if (!Schema::hasColumn('client_services', 'team_leader_id')) {
                    $table->unsignedBigInteger('team_leader_id')->nullable()->after('team_name');
                }
                if (!Schema::hasColumn('client_services', 'team_members')) {
                    $table->text('team_members')->nullable()->after('team_leader_id');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('client_services')) {
            Schema::table('client_services', function (Blueprint $table) {
                if (Schema::hasColumn('client_services', 'team_members')) {
                    $table->dropColumn('team_members');
                }
                if (Schema::hasColumn('client_services', 'team_leader_id')) {
                    $table->dropColumn('team_leader_id');
                }
                if (Schema::hasColumn('client_services', 'team_name')) {
                    $table->dropColumn('team_name');
                }
            });
        }
    }
};
