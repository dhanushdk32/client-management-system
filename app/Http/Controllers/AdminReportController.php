<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\ClientService;
use App\Models\SupportTicket;

class AdminReportController extends Controller
{
    public function index()
    {
        $stats = [
            'total_clients' => Client::count(),
            'active_clients' => Client::where('client_status', 'Active')->count(),
            'total_services' => ClientService::count(),
            'completed_services' => ClientService::where('status', 'Completed')->count(),
            'open_tickets' => SupportTicket::where('status', 'Open')->count(),
            'resolved_tickets' => SupportTicket::where('status', 'Resolved')->count(),
        ];

        return view('admin.reports.index', compact('stats'));
    }

    public function exportClients()
    {
        $clients = Client::all();
        $csvFileName = 'clients_report_' . date('Y_m_d_H_i_s') . '.csv';
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];
        
        $callback = function() use($clients) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Client ID', 'Company Name', 'Email', 'Phone', 'Industry', 'Status', 'Registered At']);

            foreach ($clients as $client) {
                fputcsv($file, [
                    'CL' . sprintf('%03d', $client->client_id),
                    $client->client_company,
                    $client->client_email,
                    $client->primary_contact,
                    $client->client_industry,
                    $client->client_status,
                    $client->created_at ? $client->created_at->format('Y-m-d') : 'N/A'
                ]);
            }
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    public function exportServices()
    {
        $services = ClientService::with('client')->get();
        $csvFileName = 'services_report_' . date('Y_m_d_H_i_s') . '.csv';
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];
        
        $callback = function() use($services) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Service ID', 'Client Company', 'Service Name', 'Status', 'Start Date', 'End Date', 'Assigned Team']);

            foreach ($services as $service) {
                fputcsv($file, [
                    'SRV' . sprintf('%03d', $service->id),
                    $service->client->client_company ?? 'N/A',
                    $service->service_name,
                    $service->status,
                    $service->start_date ? \Carbon\Carbon::parse($service->start_date)->format('Y-m-d') : 'N/A',
                    $service->end_date ? \Carbon\Carbon::parse($service->end_date)->format('Y-m-d') : 'N/A',
                    $service->assigned_team ?? 'N/A'
                ]);
            }
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
