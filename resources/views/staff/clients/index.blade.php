@extends('layouts.staff')

@section('title', 'My Assigned Clients - Staff Portal')
@section('page_title', 'My Assigned Clients')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h5 class="fw-bold mb-1 text-primary border-bottom border-2 border-primary pb-2 d-inline-block">My Assigned Clients Directory</h5>
        <p class="text-muted small mb-0">Clients and projects assigned directly to you by the Administrator.</p>
    </div>
    <div>
        <span class="badge bg-primary-subtle text-primary border rounded-pill px-3 py-2 fw-semibold">
            <i class="fa-solid fa-user-check me-1"></i> {{ $assignedCount }} Assigned Client(s)
        </span>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success py-2 px-3 small rounded-3 mb-4">
        <i class="fa-solid fa-circle-check me-1"></i> {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger py-2 px-3 small rounded-3 mb-4">
        <i class="fa-solid fa-circle-exclamation me-1"></i> {{ session('error') }}
    </div>
@endif

<!-- Search Bar -->
<div class="card mb-4 shadow-sm border-0">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('staff.clients.index') }}" class="row g-2">
            <div class="col-md-10">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-start-0 ps-0" placeholder="Search by client name, company, phone, or email..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100 fw-semibold">Search</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr class="small text-muted">
                    <th class="ps-4">Client ID</th>
                    <th>Client Organization / Name</th>
                    <th>Email Address</th>
                    <th>Phone</th>
                    <th>My Project Role</th>
                    <th class="text-end pe-4">Client 360</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $client)
                    <tr>
                        <td class="ps-4 fw-bold text-primary">#CL{{ sprintf('%03d', $client->client_id) }}</td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $client->client_company ?: $client->client_name }}</div>
                            @if($client->client_company && $client->client_company !== $client->client_name)
                                <small class="text-muted"><i class="fa-regular fa-user me-1 text-primary"></i> {{ $client->client_name }}</small>
                            @endif
                        </td>
                        <td>
                            <a href="mailto:{{ $client->client_email }}" class="text-muted text-decoration-none small">
                                <i class="fa-regular fa-envelope me-1"></i> {{ $client->client_email }}
                            </a>
                        </td>
                        <td class="small">{{ $client->primary_contact }}</td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                                {{ $client->pivot->role_in_project ?? 'Assigned Engineer / Squad Member' }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('staff.clients.show', $client) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                View Dashboard <i class="fa-solid fa-arrow-right ms-1"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-user-shield fa-3x mb-3 d-block text-light"></i>
                            No assigned clients found. The Administrator will allocate clients to you.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($clients->hasPages())
        <div class="card-footer bg-white border-top py-3">
            {{ $clients->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
