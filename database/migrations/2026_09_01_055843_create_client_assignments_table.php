<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('staff_id');
            $table->unsignedBigInteger('client_id');
            $table->string('role_in_project')->default('Assigned Engineer'); // Lead Engineer, Project Manager, Support Tech
            $table->unsignedBigInteger('assigned_by_admin_id')->nullable();
            $table->timestamps();

            $table->index('staff_id');
            $table->index('client_id');
            $table->unique(['staff_id', 'client_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_assignments');
    }
};
