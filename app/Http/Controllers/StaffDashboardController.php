<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SupportTicket;
use App\Models\ClientService;
use App\Models\ClientDocument;

class StaffDashboardController extends Controller
{
    public function index()
    {
        $staff = Auth::guard('staff')->user();

        // Get only clients assigned to this staff member
        $assignedClients = $staff->assignedClients()->withCount('users')->get();
        $assignedClientIds = $assignedClients->pluck('client_id')->toArray();

        // Projects for assigned clients or where staff is leader/member
        $projectsQuery = ClientService::with(['client', 'teamLeader'])
            ->where(function($q) use ($staff, $assignedClientIds) {
                $q->whereIn('client_id', $assignedClientIds)
                  ->orWhere('team_leader_id', $staff->id)
                  ->orWhereJsonContains('team_members', (string) $staff->id)
                  ->orWhereJsonContains('team_members', (int) $staff->id);
            });

        $totalProjectsCount = (clone $projectsQuery)->count();
        $activeProjectsCount = (clone $projectsQuery)->whereIn('status', ['Active', 'In Progress'])->count();
        $activeProjects = (clone $projectsQuery)->whereIn('status', ['Active', 'In Progress'])->latest()->take(5)->get();

        // Tickets for assigned clients
        $ticketsQuery = SupportTicket::where(function($q) use ($staff, $assignedClientIds) {
            $q->where('assigned_staff_id', $staff->id)
              ->orWhereIn('client_id', $assignedClientIds);
        })->with('client');

        $openTicketsCount = (clone $ticketsQuery)->whereIn('status', ['Open', 'In Progress'])->count();
        $resolvedTicketsCount = (clone $ticketsQuery)->where('status', 'Resolved')->count();
        $recentTickets = (clone $ticketsQuery)->latest()->take(6)->get();

        // Documents for assigned clients
        $documentsQuery = ClientDocument::whereIn('client_id', $assignedClientIds);
        $totalDocumentsCount = (clone $documentsQuery)->count();
        $pendingDocumentsCount = (clone $documentsQuery)->where('status', 'Pending')->count();

        $stats = [
            'assigned_clients' => $assignedClients->count(),
            'total_projects' => $totalProjectsCount,
            'active_projects' => $activeProjectsCount,
            'open_tickets' => $openTicketsCount,
            'resolved_tickets' => $resolvedTicketsCount,
            'total_documents' => $totalDocumentsCount,
            'pending_documents' => $pendingDocumentsCount,
        ];

        return view('staff.dashboard', compact('staff', 'stats', 'assignedClients', 'recentTickets', 'activeProjects'));
    }
}
