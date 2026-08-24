<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClientService;
use App\Models\Client;

class AdminServiceController extends Controller
{
    public function index(Request $request)
    {
        $services = ClientService::with('client')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        $clients = Client::where('client_status', 'Active')->get();
        return view('admin.services.form', compact('clients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:client_tbl,client_id',
            'service_name' => 'required|string|max:255',
            'status' => 'required|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'description' => 'nullable|string',
            'assigned_team' => 'nullable|string|max:255',
        ]);

        ClientService::create($request->all());

        return redirect()->route('admin.services.index')->with('success', 'Service assigned successfully.');
    }

    public function edit(ClientService $service)
    {
        $clients = Client::where('client_status', 'Active')->get();
        return view('admin.services.form', compact('service', 'clients'));
    }

    public function update(Request $request, ClientService $service)
    {
        $request->validate([
            'client_id' => 'required|exists:client_tbl,client_id',
            'service_name' => 'required|string|max:255',
            'status' => 'required|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'description' => 'nullable|string',
            'assigned_team' => 'nullable|string|max:255',
        ]);

        $service->update($request->all());

        return redirect()->route('admin.services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(ClientService $service)
    {
        $service->delete();
        return redirect()->route('admin.services.index')->with('success', 'Service deleted successfully.');
    }
}
