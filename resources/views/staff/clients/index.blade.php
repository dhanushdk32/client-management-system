@extends('layouts.staff')

@section('title', 'Assigned Clients - Staff Portal')
@section('page_title', 'Assigned Clients & Projects')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1">My Managed Clients</h5>
        <p class="text-muted small mb-0">View clients assigned to you and onboard new client accounts</p>
    </div>
    <a href="{{ route('staff.clients.create') }}" class="btn btn-primary rounded-3 px-4">
        <i class="fa-solid fa-user-plus me-2"></i> Add New Client
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success py-2 mb-4">{{ session('success') }}</div>
@endif

<div class="card mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('staff.clients.index') }}" class="row g-2">
            <div class="col-md-10">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-start-0 ps-0" placeholder="Search by company name, contact person, or email..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Search</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Company Name</th>
                    <th>Contact Person</th>
                    <th>Email Address</th>
                    <th>Phone</th>
                    <th>My Project Role</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $client)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-primary">{{ $client->client_company }}</div>
                            <small class="text-muted">{{ $client->industry ?: 'IT Services' }}</small>
                        </td>
                        <td>{{ $client->client_name }}</td>
                        <td>
                            <a href="mailto:{{ $client->client_email }}" class="text-muted text-decoration-none">
                                <i class="fa-regular fa-envelope me-1"></i> {{ $client->client_email }}
                            </a>
                        </td>
                        <td>{{ $client->primary_contact }}</td>
                        <td>
                            <span class="badge bg-indigo-subtle text-primary border px-2 py-1">
                                {{ $client->pivot->role_in_project ?? 'Lead Engineer' }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('staff.clients.show', $client) }}" class="btn btn-sm btn-outline-primary">
                                View Profile & Tickets <i class="fa-solid fa-arrow-right ms-1"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-building-circle-xmark fa-3x mb-3 d-block opacity-50"></i>
                            No assigned clients found. You can click <strong>"Add New Client"</strong> to register a new client!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($clients->hasPages())
        <div class="card-footer bg-white border-top py-3">
            {{ $clients->links() }}
        </div>
    @endif
</div>
@endsection
