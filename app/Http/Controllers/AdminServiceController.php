<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClientService;
use App\Models\Client;
use App\Models\StaffMember;
use App\Models\Notification;

class AdminServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = ClientService::with(['client', 'teamLeader']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('service_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('team_name', 'like', "%{$search}%")
                  ->orWhere('assigned_team', 'like', "%{$search}%");
            });
        }

        $services = $query->orderBy('created_at', 'desc')->paginate(10);
        $clients = Client::where('client_status', 'Active')->get();

        return view('admin.services.index', compact('services', 'clients'));
    }

    public function create()
    {
        $clients = Client::where('client_status', 'Active')->get();
        $staffMembers = StaffMember::where('status', 'Active')->get();
        return view('admin.services.form', compact('clients', 'staffMembers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:client_tbl,client_id',
            'service_name' => 'required|string|max:255',
            'status' => 'required|in:Active,In Progress,Planning,Completed,On Hold,Under Maintenance',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'description' => 'nullable|string',
            'team_name' => 'nullable|string|max:150',
            'team_leader_id' => 'nullable|exists:staff_members,id',
            'team_members' => 'nullable|array',
            'team_members.*' => 'exists:staff_members,id',
        ]);

        $data = $request->all();

        // Compile a human-readable assigned_team summary
        $teamSummaryParts = [];
        if ($request->filled('team_name')) {
            $teamSummaryParts[] = $request->team_name;
        }

        $leaderName = null;
        if ($request->filled('team_leader_id')) {
            $leader = StaffMember::find($request->team_leader_id);
            if ($leader) {
                $leaderName = $leader->name;
                $teamSummaryParts[] = "Lead: {$leader->name}";
            }
        }

        $memberNames = [];
        if ($request->filled('team_members') && is_array($request->team_members)) {
            $members = StaffMember::whereIn('id', $request->team_members)->pluck('name')->toArray();
            if (!empty($members)) {
                $memberNames = $members;
                $teamSummaryParts[] = "Members: " . implode(', ', $members);
            }
        }

        $data['assigned_team'] = !empty($teamSummaryParts) ? implode(' • ', $teamSummaryParts) : 'Engineering Team';

        $service = ClientService::create($data);

        // Auto-assign staff members to this client in client_assignments table
        $allStaffIds = [];
        if ($request->filled('team_leader_id')) {
            $allStaffIds[] = $request->team_leader_id;
        }
        if ($request->filled('team_members') && is_array($request->team_members)) {
            $allStaffIds = array_unique(array_merge($allStaffIds, $request->team_members));
        }

        if (!empty($allStaffIds)) {
            $client = Client::find($request->client_id);
            if ($client) {
                foreach ($allStaffIds as $staffId) {
                    $isLeader = ($staffId == $request->team_leader_id);
                    $role = $isLeader ? 'Team Leader / Project Lead' : 'Project Engineer / Team Member';
                    $client->assignedStaff()->syncWithoutDetaching([
                        $staffId => ['role_in_project' => $role]
                    ]);
                }
            }
        }

        // Create notification for client
        Notification::create([
            'client_id' => $request->client_id,
            'title' => 'New Project Assigned: ' . $request->service_name,
            'message' => "Your project '{$request->service_name}' has been assigned and is now active in your Client Portal.",
            'is_read' => 0,
        ]);

        return redirect()->route('admin.services.index')->with('success', "Project '{$service->service_name}' assigned to client successfully! Technical team linked.");
    }

    public function edit(ClientService $service)
    {
        $clients = Client::where('client_status', 'Active')->get();
        $staffMembers = StaffMember::where('status', 'Active')->get();
        return view('admin.services.form', compact('service', 'clients', 'staffMembers'));
    }

    public function update(Request $request, ClientService $service)
    {
        $request->validate([
            'client_id' => 'required|exists:client_tbl,client_id',
            'service_name' => 'required|string|max:255',
            'status' => 'required|in:Active,In Progress,Planning,Completed,On Hold,Under Maintenance',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'description' => 'nullable|string',
            'team_name' => 'nullable|string|max:150',
            'team_leader_id' => 'nullable|exists:staff_members,id',
            'team_members' => 'nullable|array',
            'team_members.*' => 'exists:staff_members,id',
        ]);

        $data = $request->all();

        // Compile a human-readable assigned_team summary
        $teamSummaryParts = [];
        if ($request->filled('team_name')) {
            $teamSummaryParts[] = $request->team_name;
        }

        if ($request->filled('team_leader_id')) {
            $leader = StaffMember::find($request->team_leader_id);
            if ($leader) {
                $teamSummaryParts[] = "Lead: {$leader->name}";
            }
        }

        if ($request->filled('team_members') && is_array($request->team_members)) {
            $members = StaffMember::whereIn('id', $request->team_members)->pluck('name')->toArray();
            if (!empty($members)) {
                $teamSummaryParts[] = "Members: " . implode(', ', $members);
            }
        }

        $data['assigned_team'] = !empty($teamSummaryParts) ? implode(' • ', $teamSummaryParts) : 'Engineering Team';

        $service->update($data);

        // Auto-assign staff members to this client in client_assignments table
        $allStaffIds = [];
        if ($request->filled('team_leader_id')) {
            $allStaffIds[] = $request->team_leader_id;
        }
        if ($request->filled('team_members') && is_array($request->team_members)) {
            $allStaffIds = array_unique(array_merge($allStaffIds, $request->team_members));
        }

        if (!empty($allStaffIds)) {
            $client = Client::find($service->client_id);
            if ($client) {
                foreach ($allStaffIds as $staffId) {
                    $isLeader = ($staffId == $request->team_leader_id);
                    $role = $isLeader ? 'Team Leader / Project Lead' : 'Project Engineer / Team Member';
                    $client->assignedStaff()->syncWithoutDetaching([
                        $staffId => ['role_in_project' => $role]
                    ]);
                }
            }
        }

        return redirect()->route('admin.services.index')->with('success', "Project '{$service->service_name}' updated successfully.");
    }

    public function destroy(ClientService $service)
    {
        $name = $service->service_name;
        $service->delete();
        return redirect()->route('admin.services.index')->with('success', "Project '{$name}' deleted successfully.");
    }
}
