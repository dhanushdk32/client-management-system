<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('client_name', 'like', "%{$search}%")
                  ->orWhere('client_company', 'like', "%{$search}%");
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('client_status', $request->status);
        }

        $clients = $query->paginate(10);
        return view('admin.clients.index', compact('clients'));
    }

    public function create()
    {
        return view('admin.clients.form');
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
            'primary_contact' => 'required|string|max:11',
            'secondary_contact' => 'nullable|string|max:255',
            'client_status' => 'required|in:Active,Inactive',
            'password' => 'nullable|string|min:6',
        ]);

        $data = $request->all();
        // Convert nulls to empty strings for specific non-nullable columns
        // Also include client_location as the form doesn't provide it
        $optionalFields = ['client_gst', 'industry', 'company_size', 'website', 'secondary_contact', 'client_location'];
        foreach ($optionalFields as $field) {
            if (!isset($data[$field]) || is_null($data[$field])) {
                $data[$field] = '';
            }
        }
        
        $data['entity_id'] = $data['entity_id'] ?? 1;
        $data['joined_date'] = now();

        $client = Client::create($data);

        // If admin provided a specific password, set it immediately
        $hasPassword = $request->filled('password');
        $plainPassword = $hasPassword ? $request->password : null;
        
        $clientUser = \App\Models\ClientUser::create([
            'client_id' => $client->client_id,
            'name' => $client->client_name,
            'email' => $client->client_email,
            'password' => $hasPassword ? \Illuminate\Support\Facades\Hash::make($plainPassword) : null,
            'role' => 'Admin', // Primary client admin
            'status' => $hasPassword ? 'Active' : 'Pending Activation',
        ]);

        // Generate 6-digit OTP code for welcome activation
        $otpCode = (string) rand(100000, 999999);
        \App\Models\Otp::updateOrCreate(
            ['email' => $client->client_email],
            [
                'otp_code' => $otpCode,
                'expires_at' => \Carbon\Carbon::now()->addHours(24),
            ]
        );

        // Send Welcome Activation Email
        try {
            if ($hasPassword) {
                \Illuminate\Support\Facades\Mail::to($clientUser->email)
                    ->send(new \App\Mail\WelcomeClientMail($client, $plainPassword));
            } else {
                \Illuminate\Support\Facades\Mail::to($clientUser->email)
                    ->send(new \App\Mail\WelcomeActivationOtpMail(
                        $client->client_name,
                        $client->client_email,
                        'Client Portal',
                        $otpCode,
                        $client->client_company
                    ));
            }
            
            return redirect()->route('admin.clients.index')->with('success', 'Client created successfully and welcome activation email sent.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send welcome email: ' . $e->getMessage());
            return redirect()->route('admin.clients.index')->with('success', 'Client created successfully, but email notification failed (SMTP error).');
        }
    }

    public function show(Client $client)
    {
        // Load related services and documents if models exist (we will build them later)
        return view('admin.clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        return view('admin.clients.form', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $request->validate([
            'client_company' => 'required|string|max:100',
            'industry' => 'nullable|string|max:255',
            'company_size' => 'nullable|string|max:100',
            'website' => 'nullable|string|max:255',
            'client_gst' => 'nullable|string|max:30',
            'client_name' => 'required|string|max:50',
            'client_email' => 'required|email|max:50|unique:client_users,email,'.$client->client_id.',client_id',
            'client_status' => 'required|in:Active,Inactive',
            'password' => 'nullable|string|min:6',
        ]);

        $data = $request->all();
        // Convert nulls to empty strings for specific non-nullable columns
        $optionalFields = ['client_gst', 'industry', 'company_size', 'website', 'secondary_contact'];
        foreach ($optionalFields as $field) {
            if (!isset($data[$field]) || is_null($data[$field])) {
                $data[$field] = '';
            }
        }

        $client->update($data);

        // Sync changes to ClientUser if they exist
        $clientUser = \App\Models\ClientUser::where('client_id', $client->client_id)->first();
        if ($clientUser) {
            $userUpdateData = [
                'name' => $data['client_name'],
                'email' => $data['client_email'],
                'status' => $data['client_status'],
            ];

            if ($request->filled('password')) {
                $userUpdateData['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
            }

            $clientUser->update($userUpdateData);
        }

        return redirect()->route('admin.clients.index')->with('success', 'Client updated successfully.');
    }

    public function destroy(Client $client)
    {
        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            // Delete associated records to keep database clean and prevent FK constraint errors
            \App\Models\ClientUser::where('client_id', $client->client_id)->delete();
            
            if (class_exists(\App\Models\ClientService::class)) {
                \App\Models\ClientService::where('client_id', $client->client_id)->delete();
            }
            if (class_exists(\App\Models\ClientDocument::class)) {
                \App\Models\ClientDocument::where('client_id', $client->client_id)->delete();
            }
            if (class_exists(\App\Models\SupportTicket::class)) {
                \App\Models\SupportTicket::where('client_id', $client->client_id)->delete();
            }

            $client->delete();
            
            \Illuminate\Support\Facades\DB::commit();
            
            return redirect()->route('admin.clients.index')->with('success', 'Client and all associated data deleted successfully.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->route('admin.clients.index')->with('error', 'Error deleting client: ' . $e->getMessage());
        }
    }
}
