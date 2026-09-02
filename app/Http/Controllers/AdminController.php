<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\StaffMember;
use App\Models\ClientService;
use App\Models\SupportTicket;
use App\Models\ClientDocument;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Real Application Metrics
        $totalClients = Client::count();
        $activeClients = Client::where('client_status', 'Active')->count();
        $inactiveClients = Client::where('client_status', 'Inactive')->count();
        $pendingClients = Client::where('client_status', 'Pending')->count();

        $totalStaff = StaffMember::count();
        $activeStaff = StaffMember::where('status', 'Active')->count();

        $totalServices = ClientService::count();
        $activeServices = ClientService::where('status', 'Active')->count();

        $totalTickets = SupportTicket::count();
        $openTickets = SupportTicket::whereIn('status', ['Open', 'In Progress'])->count();
        $resolvedTickets = SupportTicket::where('status', 'Resolved')->count();

        $totalDocuments = ClientDocument::count();

        $currentMonth = \Carbon\Carbon::now()->month;
        $currentYear = \Carbon\Carbon::now()->year;

        $newClientsThisMonth = Client::whereMonth('client_created_date', $currentMonth)->whereYear('client_created_date', $currentYear)->count();

        // Chart Data (New Clients grouped by year)
        $driver = DB::connection()->getDriverName();
        $yearExpression = $driver === 'sqlite' 
            ? "strftime('%Y', client_created_date)" 
            : "YEAR(client_created_date)";

        $chartRawData = DB::table('client_tbl')
            ->select(DB::raw("{$yearExpression} as year"), DB::raw('count(*) as count'))
            ->groupBy('year')
            ->orderBy('year', 'asc')
            ->get();

        $chartLabels = [];
        $chartData = [];
        
        $currentYearValue = (int) \Carbon\Carbon::now()->year;
        $minYearDB = DB::table('client_tbl')->min(DB::raw($yearExpression)) ?? $currentYearValue;
        $startYear = min((int)$minYearDB, $currentYearValue - 4); // show at least last 5 years
        
        for ($y = $startYear; $y <= $currentYearValue; $y++) {
            $chartLabels[] = (string)$y;
            $count = $chartRawData->where('year', (string)$y)->first()->count 
                ?? $chartRawData->where('year', $y)->first()->count 
                ?? 0;
            $chartData[] = $count;
        }

        // Recent Activity Logs
        $recentActivities = DB::table('activity_logs')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Recent Clients (Added within the last 1 month)
        $oneMonthAgo = \Carbon\Carbon::now()->subMonth();
        $recentClients = Client::where(function($q) use ($oneMonthAgo) {
                $q->where('joined_date', '>=', $oneMonthAgo)
                  ->orWhere(function($subQ) use ($oneMonthAgo) {
                      $subQ->whereNull('joined_date')
                           ->where('client_created_date', '>=', $oneMonthAgo);
                  });
            })
            ->orderBy('client_id', 'desc')
            ->limit(5)
            ->get();

        // Recent Tickets
        $recentTickets = SupportTicket::with('client')->latest('id')->limit(5)->get();

        return view('admin.dashboard', compact(
            'totalClients',
            'activeClients',
            'inactiveClients',
            'pendingClients',
            'totalStaff',
            'activeStaff',
            'totalServices',
            'activeServices',
            'totalTickets',
            'openTickets',
            'resolvedTickets',
            'totalDocuments',
            'newClientsThisMonth',
            'recentActivities',
            'recentClients',
            'recentTickets',
            'chartLabels',
            'chartData'
        ));
    }
}
