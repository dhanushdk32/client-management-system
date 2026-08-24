<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\Client;

class AdminNotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::with('client')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.notifications.index', compact('notifications'));
    }

    public function create()
    {
        $clients = Client::where('client_status', 'Active')->get();
        return view('admin.notifications.form', compact('clients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:client_tbl,client_id',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        Notification::create([
            'client_id' => $request->client_id,
            'title' => $request->title,
            'message' => $request->message,
            'is_read' => 0
        ]);

        return redirect()->route('admin.notifications.index')->with('success', 'Notification dispatched successfully.');
    }
}
