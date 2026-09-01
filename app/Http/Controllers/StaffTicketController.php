<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SupportTicket;
use App\Models\TicketReply;

class StaffTicketController extends Controller
{
    public function index(Request $request)
    {
        $staff = Auth::guard('staff')->user();
        $assignedClientIds = $staff->assignedClients->pluck('client_id')->toArray();

        $query = SupportTicket::where(function($q) use ($staff, $assignedClientIds) {
            $q->where('assigned_staff_id', $staff->id)
              ->orWhereIn('client_id', $assignedClientIds);
        })->with('client');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $tickets = $query->latest()->paginate(10);

        return view('staff.tickets.index', compact('tickets'));
    }

    public function show(SupportTicket $ticket)
    {
        $ticket->load(['client', 'replies']);
        return view('staff.tickets.show', compact('ticket'));
    }

    public function reply(Request $request, SupportTicket $ticket)
    {
        $request->validate([
            'message' => 'required|string',
            'status' => 'nullable|in:Open,In Progress,Resolved,Closed',
        ]);

        $staff = Auth::guard('staff')->user();

        // Create reply
        TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => $staff->id,
            'message' => "[Staff Response - {$staff->name}]:\n" . $request->message,
        ]);

        // Update status if provided
        if ($request->filled('status')) {
            $ticket->update(['status' => $request->status]);
        }

        // Auto-assign to this staff if not already assigned
        if (!$ticket->assigned_staff_id) {
            $ticket->update(['assigned_staff_id' => $staff->id]);
        }

        return back()->with('success', 'Reply posted and ticket status updated successfully.');
    }
}
