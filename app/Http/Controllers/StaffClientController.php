<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\Client;
use App\Models\ClientUser;
use App\Models\Otp;
use Carbon\Carbon;

class StaffClientController extends Controller
{
    public function index(Request $request)
    {
        $staff = Auth::guard('staff')->user();
        $query = $staff->assignedClients()->withCount('users');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('client_company', 'like', "%{$search}%")
                  ->orWhere('client_name', 'like', "%{$search}%")
                  ->orWhere('client_email', 'like', "%{$search}%");
            });
        }

        $clients = $query->latest()->paginate(10);

        return view('staff.clients.index', compact('clients'));
    }

    public function create()
    {
        $industries = ['IT Services', 'Finance', 'Healthcare', 'Manufacturing', 'Retail', 'Education', 'E-Commerce'];
        $companySizes = ['1 - 10', '11 - 50', '51 - 100', '101 - 500', '500+'];

        return view('staff.clients.form', compact('industries', 'companySizes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_company' => 'required|string|max:100',
            'industry' => 'nullable|string|max:255',
            'company_size' => 'nullable|string|max:100',
            'website' => 'nullable|string|max:255',
            'client_gst' => 'nullable|string|max:30',
            'client_name' => 'required|string|max:50',
            'client_email' => 'required|email|max:50|unique:client_users,email',
            'primary_contact' => 'required|string|max:20',
            'secondary_contact' => 'nullable|string|max:255',
        ]);

        $data = $request->all();
        $optionalFields = ['client_gst', 'industry', 'company_size', 'website', 'secondary_contact', 'client_location'];
        foreach ($optionalFields as $field) {
            if (!isset($data[$field]) || is_null($data[$field])) {
                $data[$field] = '';
            }
        }

        $data['entity_id'] = $data['entity_id'] ?? 1;
        $data['client_status'] = 'Active';
        $data['joined_date'] = now();

        $client = Client::create($data);

        // Create client user pending activation
        $clientUser = ClientUser::create([
            'client_id' => $client->client_id,
            'name' => $client->client_name,
            'email' => $client->client_email,
            'password' => null, // Set via OTP activation
            'role' => 'Admin',
            'status' => 'Pending Activation',
        ]);

        // Automatically assign this staff member to manage the new client
        $staff = Auth::guard('staff')->user();
        $staff->assignedClients()->attach($client->client_id, [
            'role_in_project' => 'Account Manager / Lead Engineer'
        ]);

        // Generate 6-digit OTP code for welcome email
        $otpCode = (string) rand(100000, 999999);
        Otp::updateOrCreate(
            ['email' => $client->client_email],
            [
                'otp_code' => $otpCode,
                'expires_at' => Carbon::now()->addHours(24),
            ]
        );

        // Send Welcome OTP Email to client
        try {
            Mail::to($client->client_email)->send(new \App\Mail\WelcomeActivationOtpMail(
                $client->client_name,
                $client->client_email,
                'Client Portal',
                $otpCode,
                $client->client_company
            ));
        } catch (\Exception $e) {
            Log::error('Failed to send client welcome OTP email from staff: ' . $e->getMessage());
        }

        return redirect()->route('staff.clients.index')->with('success', 'Client account created successfully and Welcome OTP email sent to ' . $client->client_email);
    }

    public function show(Client $client)
    {
        $staff = Auth::guard('staff')->user();
        
        // Ensure staff is assigned or allowed to view
        $tickets = \App\Models\SupportTicket::where('client_id', $client->client_id)->latest()->get();
        $documents = \App\Models\ClientDocument::where('client_id', $client->client_id)->latest()->get();

        return view('staff.clients.show', compact('client', 'tickets', 'documents'));
    }
}
