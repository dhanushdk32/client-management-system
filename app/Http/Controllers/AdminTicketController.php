<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SupportTicket;
use App\Models\TicketReply;

class AdminTicketController extends Controller
{
    public function index(Request $request)
    {
        $tickets = SupportTicket::with('client')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.tickets.index', compact('tickets'));
    }

    public function show($id)
    {
        $ticket = SupportTicket::with(['client', 'replies'])->findOrFail($id);
        return view('admin.tickets.show', compact('ticket'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Open,In Progress,Resolved,Closed'
        ]);

        $ticket = SupportTicket::findOrFail($id);
        $ticket->update(['status' => $request->status]);

        return back()->with('success', 'Ticket status updated successfully.');
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        $admin = Auth::guard('admin')->user();
        $ticket = SupportTicket::findOrFail($id);

        TicketReply::create([
            'ticket_id' => $ticket->id,
            'sender_type' => 'Admin',
            'sender_id' => $admin->id,
            'message' => $request->message
        ]);

        return back()->with('success', 'Reply sent successfully.');
    }
}
