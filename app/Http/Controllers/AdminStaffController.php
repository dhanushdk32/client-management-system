<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\StaffMember;
use App\Models\Client;
use App\Models\Otp;
use Carbon\Carbon;
use App\Mail\WelcomeActivationOtpMail;
use App\Mail\WelcomeStaffMail;

class AdminStaffController extends Controller
{
    public function index(Request $request)
    {
        $query = StaffMember::withCount(['assignedClients', 'assignedTickets']);

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('designation', 'like', "%{$search}%");
            });
        }

        $staffMembers = $query->latest()->paginate(10);
        $departments = ['Development', 'Technical Support', 'Project Management', 'Quality Assurance (QA)', 'Design', 'Operations'];

        return view('admin.staff.index', compact('staffMembers', 'departments'));
    }

    public function create()
    {
        $departments = ['Development', 'Technical Support', 'Project Management', 'Quality Assurance (QA)', 'Design', 'Operations'];
        $designations = ['Lead Developer', 'Senior Software Engineer', 'Full Stack Developer', 'Project Manager', 'Technical Support Specialist', 'QA Engineer', 'UI/UX Designer'];
        $clients = Client::where('client_status', 'Active')->get();

        return view('admin.staff.form', compact('departments', 'designations', 'clients'));
    }

    /**
     * AJAX endpoint: Send Welcome OTP to Staff Email
     */
    public function sendCreationOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:100',
            'name' => 'required|string|max:100',
            'designation' => 'required|string|max:100',
        ]);

        if (StaffMember::where('email', $request->email)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'A staff account with this email already exists!'
            ], 422);
        }

        // Generate 6-digit OTP
        $otpCode = (string) rand(100000, 999999);
        Otp::updateOrCreate(
            ['email' => $request->email],
            [
                'otp_code' => $otpCode,
                'expires_at' => Carbon::now()->addMinutes(30),
            ]
        );

        // Send Welcome Email with OTP
        try {
            Mail::to($request->email)->send(new WelcomeActivationOtpMail(
                $request->name,
                $request->email,
                'Staff Member (' . $request->designation . ')',
                $otpCode,
                $request->designation
            ));

            return response()->json([
                'success' => true,
                'message' => "Welcome email with OTP sent to {$request->email}!"
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send staff OTP email: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Could not send email: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:staff_members,email',
            'phone' => 'nullable|string|max:20',
            'department' => 'required|string|max:100',
            'designation' => 'required|string|max:100',
            'status' => 'required|in:Active,Inactive',
            'otp' => 'required|string|size:6',
            'password' => 'required|string|min:6',
            'assigned_clients' => 'nullable|array',
            'assigned_clients.*' => 'exists:client_tbl,client_id',
        ]);

        // Verify OTP
        $otpRecord = Otp::where('email', $request->email)->first();
        if (!$otpRecord || $otpRecord->otp_code !== $request->otp) {
            return back()->withInput()->withErrors(['otp' => 'The entered OTP code is invalid. Please verify the code sent to staff email.']);
        }

        if (Carbon::now()->greaterThan($otpRecord->expires_at)) {
            return back()->withInput()->withErrors(['otp' => 'The OTP code has expired. Please click Send OTP again.']);
        }

        $adminId = Auth::guard('admin')->id();
        $plainPassword = $request->password;

        $staffData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone ?? '',
            'department' => $request->department,
            'designation' => $request->designation,
            'status' => $request->status,
            'created_by_admin_id' => $adminId,
            'password' => Hash::make($plainPassword),
        ];

        $staff = StaffMember::create($staffData);

        // Sync assigned clients
        if ($request->has('assigned_clients')) {
            $staff->assignedClients()->sync($request->assigned_clients);
        }

        // Send Welcome & Credentials Email
        try {
            Mail::to($staff->email)->send(new WelcomeStaffMail($staff, $plainPassword));
        } catch (\Exception $e) {
            Log::error('Failed to send staff credentials email: ' . $e->getMessage());
        }

        // Delete verified OTP
        Otp::where('email', $request->email)->delete();

        return redirect()->route('admin.staff.index')->with('success', 'Staff member created successfully! Verification confirmed and login credentials have been sent.');
    }

    public function edit(StaffMember $staff)
    {
        $departments = ['Development', 'Technical Support', 'Project Management', 'Quality Assurance (QA)', 'Design', 'Operations'];
        $designations = ['Lead Developer', 'Senior Software Engineer', 'Full Stack Developer', 'Project Manager', 'Technical Support Specialist', 'QA Engineer', 'UI/UX Designer'];
        $clients = Client::where('client_status', 'Active')->get();
        $selectedClients = $staff->assignedClients->pluck('client_id')->toArray();

        return view('admin.staff.form', compact('staff', 'departments', 'designations', 'clients', 'selectedClients'));
    }

    public function update(Request $request, StaffMember $staff)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:staff_members,email,' . $staff->id,
            'phone' => 'nullable|string|max:20',
            'department' => 'required|string|max:100',
            'designation' => 'required|string|max:100',
            'status' => 'required|in:Active,Inactive',
            'password' => 'nullable|string|min:6',
            'assigned_clients' => 'nullable|array',
            'assigned_clients.*' => 'exists:client_tbl,client_id',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone ?? '',
            'department' => $request->department,
            'designation' => $request->designation,
            'status' => $request->status,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $staff->update($updateData);

        if ($request->has('assigned_clients')) {
            $staff->assignedClients()->sync($request->assigned_clients);
        } else {
            $staff->assignedClients()->detach();
        }

        return redirect()->route('admin.staff.index')->with('success', 'Staff member updated successfully.');
    }

    public function destroy(StaffMember $staff)
    {
        \App\Models\SupportTicket::where('assigned_staff_id', $staff->id)->update(['assigned_staff_id' => null]);
        $staff->assignedClients()->detach();
        $staff->delete();

        return redirect()->route('admin.staff.index')->with('success', 'Staff member deleted successfully.');
    }
}
