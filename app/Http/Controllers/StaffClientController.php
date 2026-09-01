<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\Client;
use App\Models\ClientUser;
use App\Models\Otp;
use Carbon\Carbon;
use App\Mail\WelcomeActivationOtpMail;
use App\Mail\WelcomeClientMail;

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

        $clients = $query->latest('client_tbl.client_id')->paginate(10);

        return view('staff.clients.index', compact('clients'));
    }

    public function create()
    {
        $industries = ['IT Services', 'Finance', 'Healthcare', 'Manufacturing', 'Retail', 'Education', 'E-Commerce'];
        $companySizes = ['1 - 10', '11 - 50', '51 - 100', '101 - 500', '500+'];

        return view('staff.clients.form', compact('industries', 'companySizes'));
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
            Log::error('Failed to send OTP email from staff: ' . $e->getMessage());
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
            'joined_date' => 'nullable|date',
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
        $data['client_status'] = 'Active';
        $data['joined_date'] = $request->filled('joined_date') ? Carbon::parse($request->joined_date) : now();

        $client = Client::create($data);
        $plainPassword = $request->password;

        // Create client user
        $clientUser = ClientUser::create([
            'client_id' => $client->client_id,
            'name' => $client->client_name,
            'email' => $client->client_email,
            'password' => Hash::make($plainPassword),
            'role' => 'Admin',
            'status' => 'Active',
        ]);

        // Automatically assign this staff member to manage the new client
        $staff = Auth::guard('staff')->user();
        $staff->assignedClients()->attach($client->client_id, [
            'role_in_project' => 'Account Manager / Lead Engineer'
        ]);

        // Send Final Welcome & Credentials Email
        try {
            Mail::to($clientUser->email)->send(new WelcomeClientMail($client, $plainPassword));
        } catch (\Exception $e) {
            Log::error('Failed to send welcome credentials email from staff: ' . $e->getMessage());
        }

        // Delete verified OTP
        Otp::where('email', $request->client_email)->delete();

        return redirect()->route('staff.clients.index')->with('success', 'Client created successfully! Verification confirmed and login credentials have been sent.');
    }

    public function show(Client $client)
    {
        $tickets = \App\Models\SupportTicket::where('client_id', $client->client_id)->latest()->get();
        $documents = \App\Models\ClientDocument::where('client_id', $client->client_id)->latest()->get();

        return view('staff.clients.show', compact('client', 'tickets', 'documents'));
    }
}
