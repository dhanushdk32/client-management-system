<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalClients = Client::count();
        $activeClients = Client::where('client_status', 'Active')->count();
        $inactiveClients = Client::where('client_status', 'Inactive')->count();
        $pendingClients = Client::where('client_status', 'Pending')->count();

        $currentMonth = \Carbon\Carbon::now()->month;
        $currentYear = \Carbon\Carbon::now()->year;

        $totalThisMonth = Client::whereMonth('client_created_date', $currentMonth)->whereYear('client_created_date', $currentYear)->count();
        $activeThisMonth = Client::where('client_status', 'Active')->whereMonth('client_created_date', $currentMonth)->whereYear('client_created_date', $currentYear)->count();
        $inactiveThisMonth = Client::where('client_status', 'Inactive')->whereMonth('client_created_date', $currentMonth)->whereYear('client_created_date', $currentYear)->count();
        $pendingThisMonth = Client::where('client_status', 'Pending')->whereMonth('client_created_date', $currentMonth)->whereYear('client_created_date', $currentYear)->count();

        // Chart Data (New Clients grouped by year)
        $chartRawData = DB::table('client_tbl')
            ->select(DB::raw('YEAR(client_created_date) as year'), DB::raw('count(*) as count'))
            ->groupBy('year')
            ->orderBy('year', 'asc')
            ->get();

        $chartLabels = [];
        $chartData = [];
        
        $currentYearValue = \Carbon\Carbon::now()->year;
        $minYearDB = DB::table('client_tbl')->min(DB::raw('YEAR(client_created_date)')) ?? $currentYearValue;
        $startYear = min($minYearDB, $currentYearValue - 4); // show at least last 5 years
        
        for ($y = $startYear; $y <= $currentYearValue; $y++) {
            $chartLabels[] = (string)$y;
            $count = $chartRawData->where('year', $y)->first()->count ?? 0;
            $chartData[] = $count;
        }
        
        $chartTitle = "New Clients (Yearly Overview)";

        // Fetch recent activities (from activity_logs, assuming we will create the model soon, for now use DB facade)
        $recentActivities = DB::table('activity_logs')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalClients',
            'activeClients',
            'inactiveClients',
            'pendingClients',
            'totalThisMonth',
            'activeThisMonth',
            'inactiveThisMonth',
            'pendingThisMonth',
            'recentActivities',
            'chartLabels',
            'chartData',
            'chartTitle'
        ));
    }
}
