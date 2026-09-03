<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SupportTicket;
use App\Models\Client;
use App\Models\ClientService;
use App\Models\ClientDocument;
use App\Models\ActivityLog;

class StaffDashboardController extends Controller
{
    public function index()
    {
        $staff = Auth::guard('staff')->user();

        // Get assigned clients
        $assignedClients = $staff->assignedClients()->withCount('users')->get();
        $assignedClientIds = $assignedClients->pluck('client_id')->toArray();

        $stats = [
            'total_clients' => Client::count(),
            'assigned_clients' => $assignedClients->count(),
            'total_projects' => ClientService::count(),
            'active_projects' => ClientService::whereIn('status', ['Active', 'In Progress'])->count(),
            'open_tickets' => SupportTicket::whereIn('status', ['Open', 'In Progress'])->count(),
            'resolved_tickets' => SupportTicket::where('status', 'Resolved')->count(),
            'my_tickets' => SupportTicket::where(function($q) use ($staff, $assignedClientIds) {
                $q->where('assigned_staff_id', $staff->id)
                  ->orWhereIn('client_id', $assignedClientIds);
            })->whereIn('status', ['Open', 'In Progress'])->count(),
            'total_documents' => ClientDocument::count(),
            'pending_documents' => ClientDocument::where('status', 'Pending')->count(),
        ];

        // Recent Support Tickets
        $recentTickets = SupportTicket::with('client')
            ->latest()
            ->take(6)
            ->get();

        // Active Projects
        $activeProjects = ClientService::with(['client', 'teamLeader'])
            ->whereIn('status', ['Active', 'In Progress'])
            ->latest()
            ->take(5)
            ->get();

        // Recent Activities
        $recentActivities = ActivityLog::latest()->take(6)->get();

        return view('staff.dashboard', compact('staff', 'stats', 'assignedClients', 'recentTickets', 'activeProjects', 'recentActivities'));
    }
}
