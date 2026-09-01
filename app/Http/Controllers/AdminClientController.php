<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\ClientUser;
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
        $query = Client::with('users');

        if ($request->filled('status')) {
            $query->where('client_status', $request->status);
        }

        if ($request->filled('industry')) {
            $query->where('industry', $request->industry);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('client_name', 'like', "%{$search}%")
                  ->orWhere('client_company', 'like', "%{$search}%")
                  ->orWhere('client_email', 'like', "%{$search}%");
            });
        }

        $clients = $query->latest('client_id')->paginate(10);
        $industries = ['IT Services', 'Finance', 'Healthcare', 'Manufacturing', 'Retail', 'Education', 'E-Commerce'];

        return view('admin.clients.index', compact('clients', 'industries'));
    }

    public function create()
    {
        $industries = ['IT Services', 'Finance', 'Healthcare', 'Manufacturing', 'Retail', 'Education', 'E-Commerce'];
        $companySizes = ['1 - 10', '11 - 50', '51 - 100', '101 - 500', '500+'];
        return view('admin.clients.form', compact('industries', 'companySizes'));
    }

    /**
     * AJAX endpoint: Send Welcome OTP to Client Gmail
     */
    public function sendCreationOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:100',
            'name' => 'required|string|max:100',
            'company' => 'required|string|max:100',
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

        // Send Welcome Email with OTP
        try {
            Mail::to($request->email)->send(new WelcomeActivationOtpMail(
                $request->name,
                $request->email,
                'Client Portal',
                $otpCode,
                $request->company
            ));

            return response()->json([
                'success' => true,
                'message' => "Welcome email with OTP sent to {$request->email}!"
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
            'client_company' => 'required|string|max:100',
            'industry' => 'nullable|string|max:255',
            'company_size' => 'nullable|string|max:100',
            'website' => 'nullable|string|max:255',
            'client_gst' => 'nullable|string|max:30',
            'client_name' => 'required|string|max:50',
            'client_email' => 'required|email|max:50|unique:client_users,email',
            'primary_contact' => 'required|string|max:20',
            'secondary_contact' => 'nullable|string|max:255',
            'client_status' => 'required|in:Active,Inactive',
            'otp' => 'required|string|size:6',
            'password' => 'required|string|min:6',
        ]);

        // Verify OTP
        $otpRecord = Otp::where('email', $request->client_email)->first();
        if (!$otpRecord || $otpRecord->otp_code !== $request->otp) {
            return back()->withInput()->withErrors(['otp' => 'The entered OTP code is invalid. Please verify the code sent to client email.']);
        }

        if (Carbon::now()->greaterThan($otpRecord->expires_at)) {
            return back()->withInput()->withErrors(['otp' => 'The OTP code has expired. Please click Send OTP again.']);
        }

        $data = $request->all();
        $optionalFields = ['client_gst', 'industry', 'company_size', 'website', 'secondary_contact', 'client_location'];
        foreach ($optionalFields as $field) {
            if (!isset($data[$field]) || is_null($data[$field])) {
                $data[$field] = '';
            }
        }
        
        $data['entity_id'] = $data['entity_id'] ?? 1;
        $data['joined_date'] = now();

        $client = Client::create($data);

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

        return redirect()->route('admin.clients.index')->with('success', 'Client created successfully! Verification confirmed and login credentials have been sent.');
    }

    public function show(Client $client)
    {
        return view('admin.clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        $industries = ['IT Services', 'Finance', 'Healthcare', 'Manufacturing', 'Retail', 'Education', 'E-Commerce'];
        $companySizes = ['1 - 10', '11 - 50', '51 - 100', '101 - 500', '500+'];
        return view('admin.clients.form', compact('client', 'industries', 'companySizes'));
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
            'client_email' => 'required|email|max:50|unique:client_tbl,client_email,' . $client->client_id . ',client_id',
            'primary_contact' => 'required|string|max:20',
            'secondary_contact' => 'nullable|string|max:255',
            'client_status' => 'required|in:Active,Inactive',
            'password' => 'nullable|string|min:6',
        ]);

        $data = $request->all();
        $optionalFields = ['client_gst', 'industry', 'company_size', 'website', 'secondary_contact', 'client_location'];
        foreach ($optionalFields as $field) {
            if (!isset($data[$field]) || is_null($data[$field])) {
                $data[$field] = '';
            }
        }

        $client->update($data);

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

        return redirect()->route('admin.clients.index')->with('success', 'Client updated successfully.');
    }

    public function destroy(Client $client)
    {
        ClientUser::where('client_id', $client->client_id)->delete();
        $client->delete();

        return redirect()->route('admin.clients.index')->with('success', 'Client deleted successfully.');
    }
}
