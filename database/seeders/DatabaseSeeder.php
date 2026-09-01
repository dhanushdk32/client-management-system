<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\PortalAdmin;
use App\Models\StaffMember;
use App\Models\Client;
use App\Models\ClientUser;
use App\Models\ClientService;
use App\Models\SupportTicket;
use App\Models\ClientDocument;
use App\Models\Notification;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin Account
        PortalAdmin::updateOrCreate(
            ['email' => 'admindk@gmail.com'],
            [
                'name' => 'Admin DK',
                'password' => Hash::make('admindk123'),
                'role' => 'Super Admin',
                'status' => 'active',
            ]
        );

        // 2. Staff Member Account
        $staff = StaffMember::updateOrCreate(
            ['email' => 'developer@itcompany.com'],
            [
                'name' => 'Alex Developer',
                'phone' => '+1 555-0199',
                'designation' => 'Lead Full Stack Developer',
                'department' => 'Development',
                'password' => Hash::make('staff123'),
                'status' => 'Active',
            ]
        );

        // 3. Complete Original Clients Data
        $clientsData = [
            [
                'client_id' => 6,
                'entity_id' => 1,
                'client_name' => 'Joshua',
                'client_company' => 'Best Matrimonial',
                'industry' => 'Retail',
                'company_size' => '1 - 10',
                'website' => '',
                'client_location' => '--',
                'client_email' => 'joesva@gmail.com',
                'primary_contact' => '7338934701',
                'secondary_contact' => '',
                'client_gst' => '',
                'client_status' => 'Active',
                'joined_date' => '2026-08-24 16:20:25',
                'client_created_date' => '2024-11-16 10:35:29',
            ],
            [
                'client_id' => 7,
                'entity_id' => 1,
                'client_name' => 'Senthil Murugan',
                'client_company' => 'SD Tiles',
                'industry' => 'Manufacturing',
                'company_size' => '11 - 50',
                'website' => '',
                'client_location' => '',
                'client_email' => 'senthil@sdtiles.com',
                'primary_contact' => '9597174280',
                'secondary_contact' => '',
                'client_gst' => '',
                'client_status' => 'Active',
                'joined_date' => '2026-08-24 16:20:25',
                'client_created_date' => '2024-11-29 09:15:02',
            ],
            [
                'client_id' => 8,
                'entity_id' => 1,
                'client_name' => 'Manikandan BNI',
                'client_company' => 'Gold Plan Mobile App',
                'industry' => 'IT Services',
                'company_size' => '1 - 10',
                'website' => '',
                'client_location' => 'Tirunelveli',
                'client_email' => 'manikandan@gmail.com',
                'primary_contact' => '9094447770',
                'secondary_contact' => '',
                'client_gst' => '',
                'client_status' => 'Active',
                'joined_date' => '2026-08-24 16:20:25',
                'client_created_date' => '2024-12-05 02:06:50',
            ],
            [
                'client_id' => 13,
                'entity_id' => 1,
                'client_name' => 'dhanush',
                'client_company' => 'dhanush it park',
                'industry' => 'IT Services',
                'company_size' => '11 - 50',
                'website' => '',
                'client_location' => 'pavoorchatram',
                'client_email' => 'dhanush420490@gmail.com',
                'primary_contact' => '9876543210',
                'secondary_contact' => '',
                'client_gst' => '',
                'client_status' => 'Active',
                'joined_date' => '2026-08-24 11:02:58',
                'client_created_date' => '2026-08-24 11:02:58',
            ],
            [
                'client_id' => 14,
                'entity_id' => 1,
                'client_name' => 'Acme Demo Admin',
                'client_company' => 'Acme Technologies Inc.',
                'industry' => 'IT Services',
                'company_size' => '11 - 50',
                'website' => 'https://acme-tech.example.com',
                'client_location' => 'San Francisco, CA',
                'client_email' => 'client@company.com',
                'primary_contact' => '+1 555-0142',
                'secondary_contact' => '+1 555-0143',
                'client_gst' => 'GSTIN33AAACD1234F1Z5',
                'client_status' => 'Active',
                'joined_date' => now(),
                'client_created_date' => now(),
            ]
        ];

        foreach ($clientsData as $c) {
            $createdClient = Client::updateOrCreate(['client_id' => $c['client_id']], $c);
            
            // Client User Account for Login
            ClientUser::updateOrCreate(
                ['email' => $c['client_email']],
                [
                    'client_id' => $createdClient->client_id,
                    'name' => $c['client_name'],
                    'password' => Hash::make('password123'),
                    'role' => 'Admin',
                    'status' => 'Active',
                ]
            );

            // Assign to staff
            $staff->assignedClients()->syncWithoutDetaching([$createdClient->client_id]);
        }

        // 4. Client Services
        ClientService::updateOrCreate(
            ['client_id' => 13, 'service_name' => 'Cloud Infrastructure & API Development'],
            [
                'description' => 'Dedicated cloud architecture and microservices deployment.',
                'status' => 'Active',
                'start_date' => now()->subMonths(2),
                'end_date' => now()->addMonths(10),
                'assigned_team' => 'DevOps & Backend',
            ]
        );

        ClientService::updateOrCreate(
            ['client_id' => 6, 'service_name' => 'Matrimonial Mobile Application'],
            [
                'description' => 'Cross-platform Flutter app development and backend setup.',
                'status' => 'Active',
                'start_date' => now()->subMonths(1),
                'end_date' => now()->addMonths(5),
                'assigned_team' => 'Mobile Dev Team',
            ]
        );

        // 5. Original Support Tickets
        SupportTicket::updateOrCreate(
            ['client_id' => 13, 'subject' => 'for update my ui'],
            [
                'description' => 'i want improve my projects ui , add more styles',
                'status' => 'In Progress',
                'priority' => 'Medium',
                'assigned_staff_id' => $staff->id,
            ]
        );

        // 6. Original Client Documents
        ClientDocument::updateOrCreate(
            ['client_id' => 13, 'file_name' => 'pan_card.png'],
            [
                'file_type' => 'image/png',
                'document_type' => 'Identity Proof',
                'file_path' => 'documents/clients/13/pan_card.png',
                'verification_status' => 'Pending',
            ]
        );

        // 7. Notifications
        Notification::updateOrCreate(
            ['client_id' => 13, 'title' => 'Document Uploaded'],
            [
                'message' => 'Your document "pan card" has been successfully uploaded and is pending verification.',
                'is_read' => false,
            ]
        );
    }
}
