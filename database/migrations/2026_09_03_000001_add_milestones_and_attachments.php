<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add milestones & progress tracking to client_services
        Schema::table('client_services', function (Blueprint $table) {
            if (!Schema::hasColumn('client_services', 'progress_percentage')) {
                $table->integer('progress_percentage')->default(15)->after('status');
            }
            if (!Schema::hasColumn('client_services', 'current_phase')) {
                $table->string('current_phase')->default('Requirements & Scoping')->after('progress_percentage');
            }
            if (!Schema::hasColumn('client_services', 'milestones')) {
                $table->json('milestones')->nullable()->after('current_phase');
            }
        });

        // 2. Add attachments to support_tickets & ticket_replies
        Schema::table('support_tickets', function (Blueprint $table) {
            if (!Schema::hasColumn('support_tickets', 'attachment_path')) {
                $table->string('attachment_path')->nullable()->after('description');
                $table->string('attachment_name')->nullable()->after('attachment_path');
            }
        });

        Schema::table('ticket_replies', function (Blueprint $table) {
            if (!Schema::hasColumn('ticket_replies', 'attachment_path')) {
                $table->string('attachment_path')->nullable()->after('message');
                $table->string('attachment_name')->nullable()->after('attachment_path');
            }
        });

        // 3. Add deliverable approvals & feedback to client_documents
        Schema::table('client_documents', function (Blueprint $table) {
            if (!Schema::hasColumn('client_documents', 'approval_status')) {
                $table->string('approval_status')->default('Pending Approval')->after('status');
            }
            if (!Schema::hasColumn('client_documents', 'client_feedback')) {
                $table->text('client_feedback')->nullable()->after('approval_status');
            }
            if (!Schema::hasColumn('client_documents', 'is_deliverable')) {
                $table->boolean('is_deliverable')->default(false)->after('client_feedback');
            }
        });
    }

    public function down(): void
    {
        Schema::table('client_services', function (Blueprint $table) {
            $table->dropColumn(['progress_percentage', 'current_phase', 'milestones']);
        });

        Schema::table('support_tickets', function (Blueprint $table) {
            $table->dropColumn(['attachment_path', 'attachment_name']);
        });

        Schema::table('ticket_replies', function (Blueprint $table) {
            $table->dropColumn(['attachment_path', 'attachment_name']);
        });

        Schema::table('client_documents', function (Blueprint $table) {
            $table->dropColumn(['approval_status', 'client_feedback', 'is_deliverable']);
        });
    }
};
