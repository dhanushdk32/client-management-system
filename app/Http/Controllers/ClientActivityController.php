<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

class ClientActivityController extends Controller
{
    public function index()
    {
        $client_id = Auth::guard('client')->user()->client_id;
        $activities = ActivityLog::where('client_id', $client_id)->orderBy('created_at', 'desc')->paginate(20);
        return view('client.activity.index', compact('activities'));
    }
}
