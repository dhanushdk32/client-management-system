<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('system_settings')) {
            DB::table('system_settings')
                ->where('key', 'brand_name')
                ->where('value', 'like', '%RORIRI%')
                ->update(['value' => 'Client Management System']);

            DB::table('system_settings')
                ->where('key', 'brand_tagline')
                ->where('value', 'like', '%Software Solution%')
                ->update(['value' => '']);

            DB::table('system_settings')
                ->where('key', 'company_name')
                ->where('value', 'like', '%RORIRI%')
                ->update(['value' => 'Client Management System']);

            DB::table('system_settings')
                ->where('key', 'company_email')
                ->where('value', 'like', '%roriri%')
                ->update(['value' => 'contact@clientmanagementsystem.com']);

            DB::table('system_settings')
                ->where('key', 'company_website')
                ->where('value', 'like', '%roriri%')
                ->update(['value' => '']);

            DB::table('system_settings')
                ->where('key', 'brand_logo_path')
                ->where('value', 'like', '%roriri%')
                ->delete();
        }
    }

    public function down(): void
    {
    }
};
