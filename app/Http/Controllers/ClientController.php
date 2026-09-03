<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ClientService;

class ClientController extends Controller
{
    public function dashboard()
    {
        $user = Auth::guard('client')->user();
        $client = $user->client;

        if (!$client) {
            abort(404, 'Client not found.');
        }

        // Fetch counts scoped to this client
        $servicesCount = DB::table('client_services')->where('client_id', $client->client_id)->count();
        
        $documentsVerified = DB::table('client_documents')
            ->where('client_id', $client->client_id)
            ->where('status', 'Verified')
            ->count();
            
        $documentsTotal = DB::table('client_documents')->where('client_id', $client->client_id)->count();
        
        $openRequests = DB::table('support_tickets')
            ->where('client_id', $client->client_id)
            ->whereIn('status', ['Open', 'In Progress'])
            ->count();
            
        $unreadNotifications = DB::table('notifications')
            ->where('user_id', $user->id)
            ->where('is_read', 0)
            ->count();

        // Recent items with team leader
        $recentServices = ClientService::with('teamLeader')
            ->where('client_id', $client->client_id)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        $primaryTeamLeader = $recentServices->first()?->teamLeader ?? $client->assignedStaff->first();

        $recentNotifications = DB::table('notifications')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        $recentActivity = DB::table('activity_logs')
            ->where('client_id', $client->client_id)
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        return view('client.dashboard', compact(
            'client',
            'servicesCount',
            'documentsVerified',
            'documentsTotal',
            'openRequests',
            'unreadNotifications',
            'recentServices',
            'primaryTeamLeader',
            'recentNotifications',
            'recentActivity'
        ));
    }
}
