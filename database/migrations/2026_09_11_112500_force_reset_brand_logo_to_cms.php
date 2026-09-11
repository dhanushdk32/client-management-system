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
                ->update(['value' => 'Client Management System']);

            DB::table('system_settings')
                ->where('key', 'brand_tagline')
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

            // Clear any old uploaded logo so the new official CMS logo takes over
            DB::table('system_settings')
                ->whereIn('key', ['brand_logo_path', 'brand_logo_type'])
                ->delete();
        }
    }

    public function down(): void
    {
    }
};
