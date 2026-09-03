<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KnowledgeArticle;

class KnowledgeArticleSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            [
                'title' => 'Getting Started with Your Client Portal',
                'slug' => 'getting-started-with-client-portal',
                'category' => 'Getting Started',
                'icon' => 'fa-rocket',
                'summary' => 'A comprehensive guide on navigating your client portal, viewing live projects, and tracking milestones.',
                'content' => "Welcome to your dedicated Client Portal! This platform allows you to:\n\n1. **Track Project Milestones:** View real-time progress updates across development, UI/UX, and QA phases.\n2. **Collaborate with Team Leads:** Directly message your assigned Technical Lead on support requests.\n3. **Document Vault:** Securely upload compliance files and approve deliverable handovers.\n4. **Activity Logs:** Audit all platform updates in real-time.",
                'is_published' => true,
            ],
            [
                'title' => 'How to Submit a Project Support Request',
                'slug' => 'how-to-submit-support-request',
                'category' => 'Support & Communication',
                'icon' => 'fa-ticket',
                'summary' => 'Learn how to submit change requests, technical inquiries, and attach screenshots directly to your team lead.',
                'content' => "Submitting a request is fast and streamlined:\n\n1. Navigate to **Support Desk** from the sidebar or dashboard.\n2. Click **'Submit New Request'**.\n3. Enter your subject, choose a priority level (*Low, Medium, High*), and explain the request.\n4. You can also attach screenshots, error logs, or PDF specifications directly.\n5. Your assigned Team Leader will receive the request immediately and reply within the conversation thread.",
                'is_published' => true,
            ],
            [
                'title' => 'Reviewing and Approving Project Deliverables',
                'slug' => 'reviewing-approving-deliverables',
                'category' => 'Project Management',
                'icon' => 'fa-check-circle',
                'summary' => 'Step-by-step instructions on reviewing handover files and providing formal milestone sign-offs.',
                'content' => "When your engineering team finishes a sprint or release milestone:\n\n1. Go to **Documents Vault**.\n2. Look for files labeled with **'Deliverable Sign-Off Required'**.\n3. Download and review the file or build.\n4. Click **'✅ Approve Deliverable'** to confirm acceptance, or **'🔄 Request Revision'** with your feedback notes.\n5. Your engineering lead will be notified automatically to implement revisions.",
                'is_published' => true,
            ],
            [
                'title' => 'Security, GST Compliance & Data Protection',
                'slug' => 'security-gst-compliance-data-protection',
                'category' => 'Security & Compliance',
                'icon' => 'fa-shield-halved',
                'summary' => 'Information about our security protocols, encrypted file storage, and data privacy standards.',
                'content' => "We adhere to enterprise-grade security standards:\n\n- All uploaded files are stored in isolated encrypted storage volumes.\n- Communication threads are strictly access-controlled and visible only to your assigned team and executive administration.\n- Two-Factor Gmail OTP verification protects all sensitive account workflows.",
                'is_published' => true,
            ],
        ];

        foreach ($articles as $art) {
            KnowledgeArticle::updateOrCreate(['slug' => $art['slug']], $art);
        }
    }
}
