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

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:staff_members,email',
            'phone' => 'nullable|string|max:20',
            'department' => 'required|string|max:100',
            'designation' => 'required|string|max:100',
            'status' => 'required|in:Pending Activation,Active,Inactive',
            'password' => 'nullable|string|min:8',
            'assigned_clients' => 'nullable|array',
            'assigned_clients.*' => 'exists:client_tbl,client_id',
        ]);

        $adminId = Auth::guard('admin')->id();

        $staffData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone ?? '',
            'department' => $request->department,
            'designation' => $request->designation,
            'status' => $request->status,
            'created_by_admin_id' => $adminId,
        ];

        // If admin typed an immediate password, set it, otherwise keep null for OTP activation
        if ($request->filled('password')) {
            $staffData['password'] = Hash::make($request->password);
        }

        $staff = StaffMember::create($staffData);

        // Sync assigned clients
        if ($request->has('assigned_clients')) {
            $staff->assignedClients()->sync($request->assigned_clients);
        }

        // Generate 6-digit OTP code for welcome email
        $otpCode = (string) rand(100000, 999999);
        Otp::updateOrCreate(
            ['email' => $staff->email],
            [
                'otp_code' => $otpCode,
                'expires_at' => Carbon::now()->addHours(24),
            ]
        );

        // Dispatch Welcome OTP Email
        try {
            Mail::to($staff->email)->send(new \App\Mail\WelcomeActivationOtpMail(
                $staff->name,
                $staff->email,
                'Staff Member (' . $staff->designation . ')',
                $otpCode,
                $staff->department
            ));
        } catch (\Exception $e) {
            Log::error('Failed to send staff welcome OTP email: ' . $e->getMessage());
        }

        return redirect()->route('admin.staff.index')->with('success', 'Staff member created successfully and welcome activation OTP email has been sent.');
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
            'status' => 'required|in:Pending Activation,Active,Inactive',
            'password' => 'nullable|string|min:8',
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
        // Unassign tickets
        \App\Models\SupportTicket::where('assigned_staff_id', $staff->id)->update(['assigned_staff_id' => null]);
        $staff->assignedClients()->detach();
        $staff->delete();

        return redirect()->route('admin.staff.index')->with('success', 'Staff member deleted successfully.');
    }
}
