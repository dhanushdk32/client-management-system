<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ClientService;

class ClientServiceController extends Controller
{
    public function index()
    {
        $client_id = Auth::guard('client')->user()->client_id;
        $services = ClientService::where('client_id', $client_id)->orderBy('created_at', 'desc')->get();
        return view('client.services.index', compact('services'));
    }
}
