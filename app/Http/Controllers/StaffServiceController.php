<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ClientService;
use App\Models\Client;
use App\Models\StaffMember;
use App\Models\Notification;

class StaffServiceController extends Controller
{
    public function index(Request $request)
    {
        $staff = Auth::guard('staff')->user();
        $assignedClientIds = $staff->assignedClients()->pluck('client_tbl.client_id')->toArray();
        $scope = $request->get('scope', 'all');

        $query = ClientService::with(['client', 'teamLeader']);

        if ($scope === 'assigned') {
            $query->whereIn('client_id', $assignedClientIds);
        }

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
                  ->orWhere('assigned_team', 'like', "%{$search}%")
                  ->orWhereHas('client', function($cq) use ($search) {
                      $cq->where('client_name', 'like', "%{$search}%")
                        ->orWhere('client_company', 'like', "%{$search}%");
                  });
            });
        }

        $services = $query->orderBy('created_at', 'desc')->paginate(10);
        $clients = Client::where('client_status', 'Active')->get();
        $allStaff = StaffMember::pluck('name', 'id')->toArray();

        return view('staff.services.index', compact('services', 'clients', 'allStaff', 'scope'));
    }

    public function create()
    {
        $clients = Client::where('client_status', 'Active')->get();
        $staffMembers = StaffMember::where('status', 'Active')->get();
        return view('staff.services.form', compact('clients', 'staffMembers'));
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

        $service = ClientService::create($data);

        // Auto-assign staff members to client
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
            'title' => 'Project Assigned: ' . $request->service_name,
            'message' => "Your project '{$request->service_name}' has been assigned and is tracked in your portal.",
            'is_read' => 0,
        ]);

        return redirect()->route('staff.services.index')->with('success', "Project '{$service->service_name}' created and team assigned successfully!");
    }

    public function edit(ClientService $service)
    {
        $clients = Client::where('client_status', 'Active')->get();
        $staffMembers = StaffMember::where('status', 'Active')->get();
        return view('staff.services.form', compact('service', 'clients', 'staffMembers'));
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

        return redirect()->route('staff.services.index')->with('success', "Project '{$service->service_name}' updated successfully.");
    }
}
