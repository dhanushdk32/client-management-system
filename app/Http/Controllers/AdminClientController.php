<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\ClientUser;
use App\Models\ClientService;
use App\Models\Otp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\WelcomeActivationOtpMail;
use App\Mail\WelcomeClientMail;

class AdminClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::with(['users', 'services']);

        if ($request->filled('status')) {
            $query->where('client_status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('client_name', 'like', "%{$search}%")
                  ->orWhere('client_company', 'like', "%{$search}%")
                  ->orWhere('client_email', 'like', "%{$search}%")
                  ->orWhere('primary_contact', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        $clients = $query->orderBy('client_id', 'asc')->paginate(10);

        return view('admin.clients.index', compact('clients'));
    }

    public function create()
    {
        return view('admin.clients.form');
    }

    /**
     * AJAX endpoint: Send Welcome OTP to Client Gmail
     */
    public function sendCreationOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:100',
            'name' => 'required|string|max:100|regex:/^[a-zA-Z\s\.\'-]+$/',
            'project_title' => 'nullable|string|max:255',
        ], [
            'name.regex' => 'The Client Name must only contain letters, dots, and spaces (no numbers or symbols).',
        ]);

        if (ClientUser::where('email', $request->email)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'A client account with this email already exists!'
            ], 422);
        }

        // Generate 6-digit OTP (Valid for 5 minutes)
        $otpCode = (string) rand(100000, 999999);
        Otp::updateOrCreate(
            ['email' => $request->email],
            [
                'otp_code' => $otpCode,
                'expires_at' => Carbon::now()->addMinutes(5),
            ]
        );

        $projectName = $request->project_title ?: 'Client Project';

        // Send Welcome Email with OTP
        try {
            Mail::to($request->email)->send(new WelcomeActivationOtpMail(
                $request->name,
                $request->email,
                'Client Portal',
                $otpCode,
                $projectName
            ));

            return response()->json([
                'success' => true,
                'message' => "Verification OTP code sent to {$request->email}!"
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send OTP email: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Could not send email: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_name' => 'required|string|max:100|regex:/^[a-zA-Z\s\.\'-]+$/',
            'primary_contact' => 'required|string|regex:/^[0-9+\s\-]{7,15}$/',
            'secondary_contact' => 'nullable|string|regex:/^[0-9+\s\-]{7,15}$/',
            'client_email' => 'required|email|max:100|unique:client_tbl,client_email|unique:client_users,email',
            'client_status' => 'required|in:Active,Inactive',
            'joined_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'project_title' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100|regex:/^[a-zA-Z\s\.\'-]*$/',
            'state' => 'nullable|string|max:100|regex:/^[a-zA-Z\s\.\'-]*$/',
            'country' => 'nullable|string|max:100|regex:/^[a-zA-Z\s\.\'-]*$/',
            'otp' => 'required|string|size:6|regex:/^[0-9]{6}$/',
            'password' => 'required|string|min:6',
        ], [
            'client_name.regex' => 'The Client Name must only contain letters, dots, and spaces (no numbers or symbols).',
            'primary_contact.regex' => 'The Primary Contact must only contain numeric digits (e.g. 9876543210).',
            'secondary_contact.regex' => 'The Secondary Contact must only contain numeric digits.',
            'city.regex' => 'City name must only contain letters and spaces.',
            'state.regex' => 'State name must only contain letters and spaces.',
            'country.regex' => 'Country name must only contain letters and spaces.',
            'otp.regex' => 'The OTP code must be a 6-digit number.',
        ]);

        // Verify OTP
        $otpRecord = Otp::where('email', $request->client_email)->first();
        if (!$otpRecord || $otpRecord->otp_code !== $request->otp) {
            return back()->withInput()->withErrors(['otp' => 'The entered OTP code is invalid. Please verify the code sent to client email.']);
        }

        if (Carbon::now()->greaterThan($otpRecord->expires_at)) {
            return back()->withInput()->withErrors(['otp' => 'The OTP code has expired. Please click Resend OTP.']);
        }

        $joinedDate = $request->filled('joined_date') ? Carbon::parse($request->joined_date) : now();
        $location = $request->address ?: trim(($request->city ? $request->city : '') . ($request->state ? ', ' . $request->state : ''));

        $client = Client::create([
            'entity_id' => 1,
            'client_name' => $request->client_name,
            'client_company' => $request->project_title ?: $request->client_name,
            'client_email' => $request->client_email,
            'primary_contact' => $request->primary_contact,
            'secondary_contact' => $request->secondary_contact ?? '',
            'client_location' => $location ?: 'Not Specified',
            'city' => $request->city ?? '',
            'state' => $request->state ?? '',
            'country' => $request->country ?? 'India',
            'client_gst' => '',
            'industry' => 'Custom Projects',
            'company_size' => '1 - 10',
            'website' => '',
            'client_status' => $request->client_status,
            'joined_date' => $joinedDate,
        ]);

        // Create Project / Service if project_title is provided
        if ($request->filled('project_title')) {
            ClientService::create([
                'client_id' => $client->client_id,
                'service_name' => $request->project_title,
                'status' => $request->client_status == 'Active' ? 'Active' : 'Pending',
                'start_date' => $joinedDate,
                'end_date' => $request->filled('end_date') ? Carbon::parse($request->end_date) : null,
                'description' => "Initial project contracted on {$joinedDate->format('d M Y')}.",
                'assigned_team' => 'Engineering & Development',
            ]);
        }

        $plainPassword = $request->password;
        
        $clientUser = ClientUser::create([
            'client_id' => $client->client_id,
            'name' => $client->client_name,
            'email' => $client->client_email,
            'password' => Hash::make($plainPassword),
            'role' => 'Admin',
            'status' => 'Active',
        ]);

        // Send Final Welcome & Credentials Email
        try {
            Mail::to($clientUser->email)->send(new WelcomeClientMail($client, $plainPassword));
        } catch (\Exception $e) {
            Log::error('Failed to send welcome credentials email: ' . $e->getMessage());
        }

        // Delete verified OTP
        Otp::where('email', $request->client_email)->delete();

        return redirect()->route('admin.clients.index')->with('success', "Client #CL{$client->client_id} created successfully! Verified and login credentials sent.");
    }

    public function show(Client $client)
    {
        $client->load(['services', 'users']);
        return view('admin.clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        $client->load('services');
        $primaryService = $client->services->first();
        return view('admin.clients.form', compact('client', 'primaryService'));
    }

    public function update(Request $request, Client $client)
    {
        $request->validate([
            'client_name' => 'required|string|max:100|regex:/^[a-zA-Z\s\.\'-]+$/',
            'primary_contact' => 'required|string|regex:/^[0-9+\s\-]{7,15}$/',
            'secondary_contact' => 'nullable|string|regex:/^[0-9+\s\-]{7,15}$/',
            'client_email' => 'required|email|max:100|unique:client_tbl,client_email,' . $client->client_id . ',client_id',
            'client_status' => 'required|in:Active,Inactive',
            'joined_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'project_title' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100|regex:/^[a-zA-Z\s\.\'-]*$/',
            'state' => 'nullable|string|max:100|regex:/^[a-zA-Z\s\.\'-]*$/',
            'country' => 'nullable|string|max:100|regex:/^[a-zA-Z\s\.\'-]*$/',
            'password' => 'nullable|string|min:6',
        ], [
            'client_name.regex' => 'The Client Name must only contain letters, dots, and spaces.',
            'primary_contact.regex' => 'The Primary Contact must only contain numeric digits.',
            'secondary_contact.regex' => 'The Secondary Contact must only contain numeric digits.',
            'city.regex' => 'City name must only contain letters and spaces.',
            'state.regex' => 'State name must only contain letters and spaces.',
            'country.regex' => 'Country name must only contain letters and spaces.',
        ]);

        $joinedDate = $request->filled('joined_date') ? Carbon::parse($request->joined_date) : $client->joined_date;
        $location = $request->address ?: trim(($request->city ? $request->city : '') . ($request->state ? ', ' . $request->state : ''));

        $client->update([
            'client_name' => $request->client_name,
            'client_company' => $request->project_title ?: $request->client_name,
            'client_email' => $request->client_email,
            'primary_contact' => $request->primary_contact,
            'secondary_contact' => $request->secondary_contact ?? '',
            'client_location' => $location ?: ($client->client_location ?? 'Not Specified'),
            'city' => $request->city ?? $client->city,
            'state' => $request->state ?? $client->state,
            'country' => $request->country ?? $client->country,
            'client_status' => $request->client_status,
            'joined_date' => $joinedDate,
        ]);

        // Update or create primary project
        if ($request->filled('project_title')) {
            $service = ClientService::where('client_id', $client->client_id)->first();
            if ($service) {
                $service->update([
                    'service_name' => $request->project_title,
                    'status' => $request->client_status == 'Active' ? 'Active' : 'Pending',
                    'end_date' => $request->filled('end_date') ? Carbon::parse($request->end_date) : $service->end_date,
                ]);
            } else {
                ClientService::create([
                    'client_id' => $client->client_id,
                    'service_name' => $request->project_title,
                    'status' => $request->client_status == 'Active' ? 'Active' : 'Pending',
                    'start_date' => $joinedDate,
                    'end_date' => $request->filled('end_date') ? Carbon::parse($request->end_date) : null,
                    'description' => "Project contracted on {$joinedDate->format('d M Y')}.",
                    'assigned_team' => 'Engineering & Development',
                ]);
            }
        }

        // Update Client User
        $clientUser = ClientUser::where('client_id', $client->client_id)->first();
        if ($clientUser) {
            $userUpdateData = [
                'name' => $client->client_name,
                'email' => $client->client_email,
                'status' => $client->client_status,
            ];
            if ($request->filled('password')) {
                $userUpdateData['password'] = Hash::make($request->password);
            }
            $clientUser->update($userUpdateData);
        }

        return redirect()->route('admin.clients.index')->with('success', "Client #CL{$client->client_id} updated successfully.");
    }

    public function destroy(Client $client)
    {
        $clientId = $client->client_id;
        
        // 1. Delete associated user logins
        ClientUser::where('client_id', $clientId)->delete();
        
        // 2. Delete projects / services
        ClientService::where('client_id', $clientId)->delete();
        
        // 3. Delete support tickets & ticket replies
        $ticketIds = \App\Models\SupportTicket::where('client_id', $clientId)->pluck('id');
        if ($ticketIds->isNotEmpty()) {
            \App\Models\TicketReply::whereIn('ticket_id', $ticketIds)->delete();
            \App\Models\SupportTicket::whereIn('id', $ticketIds)->delete();
        }
        
        // 4. Delete client documents & disk files
        $docs = \App\Models\ClientDocument::where('client_id', $clientId)->get();
        foreach ($docs as $doc) {
            if ($doc->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($doc->file_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($doc->file_path);
            }
            $doc->delete();
        }
        
        // 5. Delete notifications
        \App\Models\Notification::where('client_id', $clientId)->delete();
        
        // 6. Detach assigned staff
        $client->assignedStaff()->detach();
        
        // 7. Delete client record
        $client->delete();

        return redirect()->route('admin.clients.index')->with('success', "Client #CL{$clientId} and all related data deleted successfully.");
    }
}
