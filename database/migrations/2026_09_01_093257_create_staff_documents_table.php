<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('staff_documents')) {
            Schema::create('staff_documents', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('staff_id');
                $table->string('document_name');
                $table->string('document_type');
                $table->string('file_path');
                $table->string('file_type')->nullable();
                $table->string('status')->default('Pending');
                $table->text('remarks')->nullable();
                $table->timestamp('verified_at')->nullable();
                $table->unsignedBigInteger('verified_by_admin_id')->nullable();
                $table->timestamps();

                $table->index('staff_id');
                $table->index('status');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_documents');
    }
};
