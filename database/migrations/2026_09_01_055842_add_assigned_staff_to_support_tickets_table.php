<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('support_tickets') && !Schema::hasColumn('support_tickets', 'assigned_staff_id')) {
            Schema::table('support_tickets', function (Blueprint $table) {
                $table->unsignedBigInteger('assigned_staff_id')->nullable();
                $table->index('assigned_staff_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('support_tickets') && Schema::hasColumn('support_tickets', 'assigned_staff_id')) {
            Schema::table('support_tickets', function (Blueprint $table) {
                $table->dropColumn('assigned_staff_id');
            });
        }
    }
};
