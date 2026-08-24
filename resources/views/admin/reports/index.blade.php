@extends('layouts.admin')

@section('title', 'Reports & Analytics - Admin Portal')
@section('page_title', 'Reports & Analytics')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card h-100 bg-primary text-white border-0 shadow-sm">
            <div class="card-body p-4 text-center">
                <i class="fa-solid fa-users fa-3x mb-3 opacity-75"></i>
                <h2 class="fw-bold mb-1">{{ $stats['total_clients'] }}</h2>
                <p class="mb-0 fw-medium">Total Clients</p>
                <div class="small mt-2 opacity-75">{{ $stats['active_clients'] }} Active Clients</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 bg-success text-white border-0 shadow-sm">
            <div class="card-body p-4 text-center">
                <i class="fa-solid fa-briefcase fa-3x mb-3 opacity-75"></i>
                <h2 class="fw-bold mb-1">{{ $stats['total_services'] }}</h2>
                <p class="mb-0 fw-medium">Total Services</p>
                <div class="small mt-2 opacity-75">{{ $stats['completed_services'] }} Completed Services</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 bg-warning text-dark border-0 shadow-sm">
            <div class="card-body p-4 text-center">
                <i class="fa-solid fa-ticket fa-3x mb-3 opacity-75"></i>
                <h2 class="fw-bold mb-1">{{ $stats['open_tickets'] }}</h2>
                <p class="mb-0 fw-medium">Open Support Requests</p>
                <div class="small mt-2 opacity-75">{{ $stats['resolved_tickets'] }} Resolved Requests</div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white p-4 border-bottom">
        <h5 class="fw-bold mb-0">Data Exports</h5>
    </div>
    <div class="card-body p-4">
        <div class="row g-4">
            <!-- Client Export -->
            <div class="col-md-6">
                <div class="border rounded-3 p-4 d-flex align-items-center justify-content-between h-100">
                    <div>
                        <h6 class="fw-bold mb-1">Clients Export (CSV)</h6>
                        <p class="text-muted small mb-0">Download a complete list of all clients including their contact details and statuses.</p>
                    </div>
                    <a href="{{ route('admin.reports.export.clients') }}" class="btn btn-primary rounded-circle" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa-solid fa-download"></i>
                    </a>
                </div>
            </div>

            <!-- Services Export -->
            <div class="col-md-6">
                <div class="border rounded-3 p-4 d-flex align-items-center justify-content-between h-100">
                    <div>
                        <h6 class="fw-bold mb-1">Services Export (CSV)</h6>
                        <p class="text-muted small mb-0">Download a complete list of all assigned services, timelines, and current statuses.</p>
                    </div>
                    <a href="{{ route('admin.reports.export.services') }}" class="btn btn-success rounded-circle" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa-solid fa-download"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
