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
        $assignedClientIds = $staff->assignedClients()->pluck('client_tbl.client_id')->toArray();

        // Requests from assigned clients or directly assigned to this staff
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

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('client', function($cq) use ($search) {
                      $cq->where('client_name', 'like', "%{$search}%")
                        ->orWhere('client_company', 'like', "%{$search}%");
                  });
            });
        }

        $tickets = $query->latest()->paginate(10);

        return view('staff.tickets.index', compact('tickets'));
    }

    public function show(SupportTicket $ticket)
    {
        $staff = Auth::guard('staff')->user();
        $assignedClientIds = $staff->assignedClients()->pluck('client_tbl.client_id')->toArray();

        // Security check
        if ($ticket->assigned_staff_id != $staff->id && !in_array($ticket->client_id, $assignedClientIds)) {
            return redirect()->route('staff.tickets.index')->with('error', 'Unauthorized access: This request is from a client not assigned to you.');
        }

        $ticket->load(['client', 'replies']);
        return view('staff.tickets.show', compact('ticket'));
    }

    public function reply(Request $request, SupportTicket $ticket)
    {
        $staff = Auth::guard('staff')->user();
        $assignedClientIds = $staff->assignedClients()->pluck('client_tbl.client_id')->toArray();

        if ($ticket->assigned_staff_id != $staff->id && !in_array($ticket->client_id, $assignedClientIds)) {
            return redirect()->route('staff.tickets.index')->with('error', 'Unauthorized access to this ticket.');
        }

        $request->validate([
            'message' => 'required|string',
            'status' => 'nullable|in:Open,In Progress,Resolved,Closed',
        ]);

        // Create reply
        TicketReply::create([
            'ticket_id' => $ticket->id,
            'sender_type' => 'Staff',
            'sender_id' => $staff->id,
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
