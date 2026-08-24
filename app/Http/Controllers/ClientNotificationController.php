<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class ClientNotificationController extends Controller
{
    public function index()
    {
        $client_id = Auth::guard('client')->user()->client_id;
        $notifications = Notification::where('client_id', $client_id)->orderBy('created_at', 'desc')->paginate(15);
        return view('client.notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $client_id = Auth::guard('client')->user()->client_id;
        $notification = Notification::where('client_id', $client_id)->where('id', $id)->firstOrFail();
        
        $notification->update(['is_read' => 1]);
        
        return back()->with('success', 'Notification marked as read.');
    }
}
