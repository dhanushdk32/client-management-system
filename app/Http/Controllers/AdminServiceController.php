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
        $query = ClientService::with('client');

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
            'assigned_team' => 'nullable|string|max:255',
        ]);

        $service = ClientService::create($request->all());

        // Create notification for client
        Notification::create([
            'client_id' => $request->client_id,
            'title' => 'New Project Assigned: ' . $request->service_name,
            'message' => "Your project '{$request->service_name}' has been configured and is now active in your Client Portal.",
            'is_read' => 0,
        ]);

        return redirect()->route('admin.services.index')->with('success', "Project '{$service->service_name}' assigned to client successfully! It is now visible in their portal.");
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
            'assigned_team' => 'nullable|string|max:255',
        ]);

        $service->update($request->all());

        return redirect()->route('admin.services.index')->with('success', "Project '{$service->service_name}' updated successfully.");
    }

    public function destroy(ClientService $service)
    {
        $name = $service->service_name;
        $service->delete();
        return redirect()->route('admin.services.index')->with('success', "Project '{$name}' deleted successfully.");
    }
}
