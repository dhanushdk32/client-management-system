<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
            'message' => 'required|string',
            'attachment' => 'nullable|file|mimes:png,jpg,jpeg,pdf,zip,txt,doc,docx|max:10240',
        ]);

        $admin = Auth::guard('admin')->user();
        $ticket = SupportTicket::findOrFail($id);

        $attachmentPath = null;
        $attachmentName = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentName = $file->getClientOriginalName();
            $attachmentPath = $file->storeAs('attachments/replies/' . $ticket->client_id, time() . '_' . $attachmentName, 'public');
        }

        TicketReply::create([
            'ticket_id' => $ticket->id,
            'sender_type' => 'Admin',
            'sender_id' => $admin->id,
            'message' => $request->message,
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
        ]);

        return back()->with('success', 'Reply sent successfully.');
    }

    public function destroy($id)
    {
        $ticket = SupportTicket::with('replies')->findOrFail($id);

        // Delete ticket attachment
        if ($ticket->attachment_path && Storage::disk('public')->exists($ticket->attachment_path)) {
            Storage::disk('public')->delete($ticket->attachment_path);
        }

        // Delete replies attachments
        foreach ($ticket->replies as $rep) {
            if ($rep->attachment_path && Storage::disk('public')->exists($rep->attachment_path)) {
                Storage::disk('public')->delete($rep->attachment_path);
            }
        }

        $ticket->replies()->delete();
        $ticket->delete();

        return redirect()->route('admin.tickets.index')->with('success', 'Support ticket and all responses deleted successfully.');
    }

    public function destroyReply($id)
    {
        $reply = TicketReply::findOrFail($id);

        if ($reply->attachment_path && Storage::disk('public')->exists($reply->attachment_path)) {
            Storage::disk('public')->delete($reply->attachment_path);
        }

        $reply->delete();

        return back()->with('success', 'Reply deleted successfully.');
    }
}
