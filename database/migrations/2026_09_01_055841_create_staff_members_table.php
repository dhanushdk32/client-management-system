<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('designation'); // e.g. Project Manager, Lead Developer, Support Engineer, QA
            $table->string('department'); // e.g. Development, Design, QA, Technical Support
            $table->string('password')->nullable(); // Nullable until user completes OTP activation
            $table->enum('status', ['Pending Activation', 'Active', 'Inactive'])->default('Pending Activation');
            $table->string('avatar')->nullable();
            $table->unsignedBigInteger('created_by_admin_id')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->index('status');
            $table->index('department');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_members');
    }
};
