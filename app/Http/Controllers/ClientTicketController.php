<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SupportTicket;
use App\Models\TicketReply;
use App\Models\Client;

class ClientTicketController extends Controller
{
    public function index()
    {
        $client_id = Auth::guard('client')->user()->client_id;
        $tickets = SupportTicket::where('client_id', $client_id)->orderBy('created_at', 'desc')->get();
        return view('client.tickets.index', compact('tickets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:Low,Medium,High',
        ]);

        $user = Auth::guard('client')->user();
        $client = Client::with(['assignedStaff', 'services.teamLeader'])->find($user->client_id);

        // Find primary assigned staff member for this client
        $assignedStaffId = null;
        if ($client) {
            $assignedStaffId = $client->assignedStaff->first()?->id 
                ?? $client->services->first()?->team_leader_id;
        }

        SupportTicket::create([
            'client_id' => $user->client_id,
            'created_by' => $user->id,
            'assigned_staff_id' => $assignedStaffId,
            'subject' => $request->subject,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => 'Open'
        ]);

        return redirect()->route('client.tickets.index')->with('success', 'Your request has been submitted to your assigned engineering team.');
    }

    public function show($id)
    {
        $client_id = Auth::guard('client')->user()->client_id;
        $ticket = SupportTicket::with('replies')->where('client_id', $client_id)->where('id', $id)->firstOrFail();
        
        return view('client.tickets.show', compact('ticket'));
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        $user = Auth::guard('client')->user();
        $ticket = SupportTicket::where('client_id', $user->client_id)->where('id', $id)->firstOrFail();

        TicketReply::create([
            'ticket_id' => $ticket->id,
            'sender_type' => 'Client',
            'sender_id' => $user->id,
            'message' => $request->message
        ]);

        if ($ticket->status != 'Open' && $ticket->status != 'In Progress') {
            $ticket->update(['status' => 'Open']);
        }

        return back()->with('success', 'Reply sent successfully.');
    }
}
