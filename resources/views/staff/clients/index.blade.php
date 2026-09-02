@extends('layouts.staff')

@section('title', 'Assigned Clients - Staff Portal')
@section('page_title', 'Assigned Clients & Projects')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1">My Managed Clients</h5>
        <p class="text-muted small mb-0">View clients assigned to you and onboard new client accounts</p>
    </div>
    <a href="{{ route('staff.clients.create') }}" class="btn btn-primary rounded-3 px-4 fw-semibold">
        <i class="fa-solid fa-user-plus me-1"></i> Add New Client
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success py-2 px-3 small rounded-3 mb-4">
        <i class="fa-solid fa-circle-check me-1"></i> {{ session('success') }}
    </div>
@endif

<div class="card mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('staff.clients.index') }}" class="row g-2">
            <div class="col-md-10">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-start-0 ps-0" placeholder="Search by client name, project, phone, or email..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100 fw-semibold">Search</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr class="small text-muted">
                    <th class="ps-4">Client ID</th>
                    <th>Client Name / Project</th>
                    <th>Email Address</th>
                    <th>Phone</th>
                    <th>Team Members</th>
                    <th>My Project Role</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $client)
                    <tr>
                        <td class="ps-4 fw-bold text-primary">#CL{{ sprintf('%03d', $client->client_id) }}</td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $client->client_name }}</div>
                            @if($client->client_company && $client->client_company !== $client->client_name)
                                <small class="text-muted"><i class="fa-solid fa-briefcase me-1 text-primary"></i> {{ $client->client_company }}</small>
                            @endif
                        </td>
                        <td>
                            <a href="mailto:{{ $client->client_email }}" class="text-muted text-decoration-none small">
                                <i class="fa-regular fa-envelope me-1"></i> {{ $client->client_email }}
                            </a>
                        </td>
                        <td class="small">{{ $client->primary_contact }}</td>
                        <td>
                            @php
                                $primaryService = $client->services->first();
                                $memberNames = [];
                                
                                if ($primaryService && is_array($primaryService->team_members) && count($primaryService->team_members) > 0) {
                                    foreach ($primaryService->team_members as $mId) {
                                        if (isset($allStaff[$mId])) {
                                            $memberNames[] = $allStaff[$mId];
                                        }
                                    }
                                }
                                
                                if (empty($memberNames) && $client->assignedStaff && $client->assignedStaff->isNotEmpty()) {
                                    $memberNames = $client->assignedStaff->pluck('name')->toArray();
                                }
                            @endphp

                            @if(!empty($memberNames))
                                <div class="d-flex flex-wrap gap-1" style="max-width: 220px;">
                                    @foreach($memberNames as $mName)
                                        <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 11px;">
                                            <i class="fa-solid fa-user-gear text-primary me-1"></i> {{ $mName }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                                {{ $client->pivot->role_in_project ?? 'Lead Engineer' }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('staff.clients.show', $client) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                View Profile & Tickets <i class="fa-solid fa-arrow-right ms-1"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-users-slash fa-3x mb-3 d-block opacity-50"></i>
                            No assigned clients found. Click <strong>"Add New Client"</strong> to register a new client!
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
