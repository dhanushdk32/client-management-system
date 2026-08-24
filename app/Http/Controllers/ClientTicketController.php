<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SupportTicket;
use App\Models\TicketReply;

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

        SupportTicket::create([
            'client_id' => $user->client_id,
            'created_by' => $user->id,
            'subject' => $request->subject,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => 'Open'
        ]);

        return redirect()->route('client.tickets.index')->with('success', 'Ticket submitted successfully.');
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

        // If ticket was answered/resolved, perhaps client reply opens it back? We can just keep it as is.
        if ($ticket->status != 'Open' && $ticket->status != 'In Progress') {
            $ticket->update(['status' => 'Open']);
        }

        return back()->with('success', 'Reply sent successfully.');
    }
}
