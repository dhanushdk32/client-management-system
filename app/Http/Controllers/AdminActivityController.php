<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;

class AdminActivityController extends Controller
{
    public function index()
    {
        $activities = ActivityLog::with(['client', 'admin', 'user'])->orderBy('created_at', 'desc')->paginate(30);
        return view('admin.activity.index', compact('activities'));
    }
}
