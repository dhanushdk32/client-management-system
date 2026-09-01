<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staff_members')->onDelete('cascade');
            $table->string('document_name');
            $table->string('document_type'); // Resume, Experience Letter, Relieving Letter, Degree Certificate, ID Proof, etc.
            $table->string('file_path');
            $table->string('file_type')->nullable();
            $table->string('status')->default('Pending'); // Pending, Verified, Rejected
            $table->text('remarks')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->unsignedBigInteger('verified_by_admin_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_documents');
    }
};
