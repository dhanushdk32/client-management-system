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
        Schema::table('client_tbl', function (Blueprint $table) {
            $table->dateTime('joined_date')->nullable()->useCurrent()->after('client_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_tbl', function (Blueprint $table) {
            $table->dropColumn('joined_date');
        });
    }
};
