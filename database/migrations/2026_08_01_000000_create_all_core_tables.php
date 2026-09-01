<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. client_tbl
        if (!Schema::hasTable('client_tbl')) {
            Schema::create('client_tbl', function (Blueprint $table) {
                $table->id('client_id');
                $table->unsignedBigInteger('entity_id')->default(1);
                $table->string('client_name');
                $table->string('client_company');
                $table->string('client_gst')->default('');
                $table->string('industry')->default('');
                $table->string('company_size')->default('');
                $table->string('website')->default('');
                $table->string('primary_contact');
                $table->string('secondary_contact')->default('');
                $table->string('client_email');
                $table->string('client_location')->default('');
                $table->enum('client_status', ['Active', 'Inactive'])->default('Active');
                $table->dateTime('joined_date')->nullable();
                $table->timestamp('client_created_date')->nullable();
                $table->timestamp('client_updated_date')->nullable();
            });
        }

        // 2. portal_admins
        if (!Schema::hasTable('portal_admins')) {
            Schema::create('portal_admins', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->string('role')->default('Super Admin');
                $table->string('status')->default('active');
                $table->timestamp('last_login_at')->nullable();
                $table->rememberToken();
                $table->timestamps();
            });
        }

        // 3. client_users
        if (!Schema::hasTable('client_users')) {
            Schema::create('client_users', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('client_id');
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password')->nullable();
                $table->string('role')->default('Admin');
                $table->string('status')->default('Active');
                $table->timestamp('last_login_at')->nullable();
                $table->rememberToken();
                $table->timestamps();
            });
        }

        // 4. client_services
        if (!Schema::hasTable('client_services')) {
            Schema::create('client_services', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('client_id');
                $table->string('service_name');
                $table->text('description')->nullable();
                $table->decimal('cost', 10, 2)->default(0.00);
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->string('assigned_team')->nullable();
                $table->string('status')->default('Active');
                $table->timestamps();
            });
        }

        // 5. support_tickets
        if (!Schema::hasTable('support_tickets')) {
            Schema::create('support_tickets', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('client_id');
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('assigned_staff_id')->nullable();
                $table->string('subject');
                $table->text('description');
                $table->string('status')->default('Open');
                $table->string('priority')->default('Medium');
                $table->timestamps();
            });
        }

        // 6. ticket_replies
        if (!Schema::hasTable('ticket_replies')) {
            Schema::create('ticket_replies', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('ticket_id');
                $table->string('sender_type')->default('User');
                $table->unsignedBigInteger('user_id')->default(1);
                $table->unsignedBigInteger('sender_id')->default(1);
                $table->text('message');
                $table->timestamps();
            });
        }

        // 7. client_documents
        if (!Schema::hasTable('client_documents')) {
            Schema::create('client_documents', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('client_id');
                $table->string('file_name');
                $table->string('file_path');
                $table->string('file_type')->nullable();
                $table->string('document_type')->nullable();
                $table->string('verification_status')->default('Pending Verification');
                $table->timestamps();
            });
        }

        // 8. activity_logs
        if (!Schema::hasTable('activity_logs')) {
            Schema::create('activity_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('client_id')->nullable();
                $table->string('user_type')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('activity');
                $table->text('details')->nullable();
                $table->string('ip_address')->nullable();
                $table->timestamps();
            });
        }

        // 9. notifications
        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('client_id')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('title');
                $table->text('message');
                $table->boolean('is_read')->default(false);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('client_documents');
        Schema::dropIfExists('ticket_replies');
        Schema::dropIfExists('support_tickets');
        Schema::dropIfExists('services');
        Schema::dropIfExists('client_users');
        Schema::dropIfExists('portal_admins');
        Schema::dropIfExists('client_tbl');
    }
};
