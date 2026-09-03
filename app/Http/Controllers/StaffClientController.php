<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;

class StaffClientController extends Controller
{
    public function index(Request $request)
    {
        $staff = Auth::guard('staff')->user();

        // Staff only sees clients assigned to them
        $query = $staff->assignedClients()->with(['services.teamLeader', 'assignedStaff'])->withCount('users');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('client_name', 'like', "%{$search}%")
                  ->orWhere('client_company', 'like', "%{$search}%")
                  ->orWhere('client_email', 'like', "%{$search}%")
                  ->orWhere('primary_contact', 'like', "%{$search}%");
            });
        }

        $clients = $query->orderBy('client_tbl.client_id', 'asc')->paginate(10);
        $allStaff = \App\Models\StaffMember::pluck('name', 'id')->toArray();
        $assignedCount = $clients->total();

        return view('staff.clients.index', compact('clients', 'allStaff', 'assignedCount'));
    }

    public function show(Client $client)
    {
        $staff = Auth::guard('staff')->user();

        // Security check: staff can only view clients assigned to them
        if (!$staff->assignedClients()->where('client_tbl.client_id', $client->client_id)->exists()) {
            return redirect()->route('staff.clients.index')->with('error', 'Unauthorized access: You can only view clients assigned to you.');
        }

        $client->load(['services.teamLeader', 'users', 'assignedStaff']);
        $tickets = \App\Models\SupportTicket::where('client_id', $client->client_id)->latest()->get();
        $documents = \App\Models\ClientDocument::where('client_id', $client->client_id)->latest()->get();
        $allStaff = \App\Models\StaffMember::pluck('name', 'id')->toArray();

        return view('staff.clients.show', compact('client', 'tickets', 'documents', 'allStaff'));
    }
}
