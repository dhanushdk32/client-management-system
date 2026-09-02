<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('staff_members') && !Schema::hasColumn('staff_members', 'secondary_phone')) {
            Schema::table('staff_members', function (Blueprint $table) {
                $table->string('secondary_phone')->nullable()->after('phone');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('staff_members') && Schema::hasColumn('staff_members', 'secondary_phone')) {
            Schema::table('staff_members', function (Blueprint $table) {
                $table->dropColumn('secondary_phone');
            });
        }
    }
};
