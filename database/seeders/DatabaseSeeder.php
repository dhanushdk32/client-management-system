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

        // 3. Client & Client User Account
        $client = Client::updateOrCreate(
            ['client_email' => 'client@company.com'],
            [
                'entity_id' => 1,
                'client_name' => 'Demo Client',
                'client_company' => 'Acme Technologies Inc.',
                'client_gst' => 'GSTIN33AAACD1234F1Z5',
                'industry' => 'IT Services',
                'company_size' => '11 - 50',
                'website' => 'https://acme-tech.example.com',
                'primary_contact' => '+1 555-0142',
                'secondary_contact' => '+1 555-0143',
                'client_location' => 'San Francisco, CA',
                'client_status' => 'Active',
                'joined_date' => now(),
            ]
        );

        ClientUser::updateOrCreate(
            ['email' => 'client@company.com'],
            [
                'client_id' => $client->client_id,
                'name' => 'Demo Client Admin',
                'password' => Hash::make('client123'),
                'role' => 'Admin',
                'status' => 'Active',
            ]
        );

        // 4. Assign Staff to Client
        $staff->assignedClients()->syncWithoutDetaching([$client->client_id]);

        // 5. Sample Subscribed Service
        ClientService::updateOrCreate(
            ['client_id' => $client->client_id, 'service_name' => 'Custom Web App Development & Cloud Hosting'],
            [
                'description' => 'Dedicated Laravel web application with CI/CD and Alwaysdata deployment.',
                'cost' => 1500.00,
                'start_date' => now()->subMonths(1),
                'end_date' => now()->addMonths(11),
                'status' => 'Active',
            ]
        );

        // 6. Sample Support Ticket
        SupportTicket::updateOrCreate(
            ['client_id' => $client->client_id, 'subject' => 'SSL Certificate & Domain Setup Assistance'],
            [
                'description' => 'We need assistance configuring our custom DNS records for the production portal.',
                'status' => 'Open',
                'priority' => 'High',
                'assigned_staff_id' => $staff->id,
            ]
        );
    }
}
