<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SupportTicket;
use App\Models\Client;

class StaffDashboardController extends Controller
{
    public function index()
    {
        $staff = Auth::guard('staff')->user();

        // Get assigned clients
        $assignedClients = $staff->assignedClients()->withCount('users')->get();
        $assignedClientIds = $assignedClients->pluck('client_id')->toArray();

        // Get assigned tickets and tickets from assigned clients
        $assignedTicketsCount = SupportTicket::where('assigned_staff_id', $staff->id)
            ->whereIn('status', ['Open', 'In Progress'])
            ->count();

        $resolvedTicketsCount = SupportTicket::where('assigned_staff_id', $staff->id)
            ->where('status', 'Resolved')
            ->count();

        $recentTickets = SupportTicket::where(function($q) use ($staff, $assignedClientIds) {
                $q->where('assigned_staff_id', $staff->id)
                  ->orWhereIn('client_id', $assignedClientIds);
            })
            ->with('client')
            ->latest()
            ->take(5)
            ->get();

        return view('staff.dashboard', compact('staff', 'assignedClients', 'assignedTicketsCount', 'resolvedTicketsCount', 'recentTickets'));
    }
}
