<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;
use App\Models\ClientService;
use App\Models\SupportTicket;
use App\Models\StaffMember;

class StaffReportController extends Controller
{
    public function index(Request $request)
    {
        $staff = Auth::guard('staff')->user();
        
        $assignedClientIds = $staff->assignedClients()->pluck('client_tbl.client_id')->toArray();

        $stats = [
            'total_clients' => Client::count(),
            'my_clients' => count($assignedClientIds),
            'total_projects' => ClientService::count(),
            'active_projects' => ClientService::whereIn('status', ['Active', 'In Progress'])->count(),
            'completed_projects' => ClientService::where('status', 'Completed')->count(),
            'open_tickets' => SupportTicket::where('status', 'Open')->count(),
            'resolved_tickets' => SupportTicket::where('status', 'Resolved')->count(),
            'my_tickets' => SupportTicket::whereIn('client_id', $assignedClientIds)->count(),
        ];

        $scope = $request->get('scope', 'all');

        $query = ClientService::with(['client', 'teamLeader'])->latest();

        if ($scope === 'assigned') {
            $query->whereIn('client_id', $assignedClientIds);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('service_name', 'like', "%{$search}%")
                  ->orWhere('team_name', 'like', "%{$search}%")
                  ->orWhere('assigned_team', 'like', "%{$search}%")
                  ->orWhereHas('client', function($cq) use ($search) {
                      $cq->where('client_name', 'like', "%{$search}%")
                        ->orWhere('client_company', 'like', "%{$search}%");
                  });
            });
        }

        $projects = $query->paginate(10);
        $allStaff = StaffMember::pluck('name', 'id')->toArray();

        return view('staff.reports.index', compact('stats', 'projects', 'allStaff', 'scope'));
    }

    public function exportClients()
    {
        $clients = Client::all();
        $csvFileName = 'staff_clients_report_' . date('Y_m_d_H_i_s') . '.csv';
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];
        
        $callback = function() use($clients) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Client ID', 'Client Name', 'Company / Project', 'Email', 'Primary Contact', 'Secondary Contact', 'Location', 'Status', 'Joined Date']);

            foreach ($clients as $client) {
                fputcsv($file, [
                    '#CL' . sprintf('%03d', $client->client_id),
                    $client->client_name,
                    $client->client_company,
                    $client->client_email,
                    $client->primary_contact,
                    $client->secondary_contact ?? '',
                    $client->city ? ($client->city . ', ' . ($client->state ?? '')) : ($client->client_location ?? ''),
                    $client->client_status,
                    $client->joined_date ? $client->joined_date->format('Y-m-d') : 'N/A'
                ]);
            }
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    public function exportServices()
    {
        $services = ClientService::with(['client', 'teamLeader'])->get();
        $allStaff = StaffMember::pluck('name', 'id')->toArray();
        $csvFileName = 'staff_projects_report_' . date('Y_m_d_H_i_s') . '.csv';
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];
        
        $callback = function() use($services, $allStaff) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Project ID', 'Client Name', 'Project Title', 'Team Name', 'Team Leader', 'Team Members', 'Status', 'Start Date', 'Target Delivery Date', 'Description']);

            foreach ($services as $service) {
                $leader = $service->teamLeader ? $service->teamLeader->name : 'N/A';
                $members = [];
                if (is_array($service->team_members)) {
                    foreach ($service->team_members as $mId) {
                        if (isset($allStaff[$mId])) $members[] = $allStaff[$mId];
                    }
                }
                $membersStr = !empty($members) ? implode(', ', $members) : 'N/A';

                fputcsv($file, [
                    '#PRJ' . sprintf('%03d', $service->id),
                    $service->client->client_name ?? 'N/A',
                    $service->service_name,
                    $service->team_name ?: ($service->assigned_team ?? 'N/A'),
                    $leader,
                    $membersStr,
                    $service->status,
                    $service->start_date ? \Carbon\Carbon::parse($service->start_date)->format('Y-m-d') : 'N/A',
                    $service->end_date ? \Carbon\Carbon::parse($service->end_date)->format('Y-m-d') : 'N/A',
                    $service->description ?? ''
                ]);
            }
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
