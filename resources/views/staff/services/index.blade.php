@extends('layouts.staff')

@section('title', 'My Assigned Projects - Staff Portal')
@section('page_title', 'My Projects & Services')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h5 class="fw-bold mb-1 text-primary border-bottom border-2 border-primary pb-2 d-inline-block">My Assigned Projects & Contracted Services</h5>
                <p class="text-muted small mb-0">Track and manage client projects, engineering squads, delivery deadlines, and operational status for your assigned clients.</p>
            </div>
            <a href="{{ route('staff.services.create') }}" class="btn btn-primary px-4 rounded-3 fw-semibold">
                <i class="fa-solid fa-plus me-1"></i> Add / Assign Project
            </a>
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

        <!-- Filter Bar -->
        <div class="d-flex justify-content-between mb-4">
            <form action="{{ route('staff.services.index') }}" method="GET" class="d-flex gap-2 w-100 flex-wrap">
                <div class="input-group" style="max-width: 380px;">
                    <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-search text-muted"></i></span>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control border-start-0 ps-0" placeholder="Search by project name or description...">
                </div>
                <select name="client_id" class="form-select w-auto">
                    <option value="">All My Clients</option>
                    @foreach($clients as $c)
                        <option value="{{ $c->client_id }}" {{ request('client_id') == $c->client_id ? 'selected' : '' }}>
                            {{ $c->client_company ?: $c->client_name }}
                        </option>
                    @endforeach
                </select>
                <select name="status" class="form-select w-auto">
                    <option value="">All Statuses</option>
                    <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="Planning" {{ request('status') == 'Planning' ? 'selected' : '' }}>Planning</option>
                    <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                </select>
                <button type="submit" class="btn btn-outline-primary fw-semibold">Filter</button>
                @if(request()->hasAny(['search', 'client_id', 'status']))
                    <a href="{{ route('staff.services.index') }}" class="btn btn-light border text-muted">Reset</a>
                @endif
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr class="small text-muted">
                        <th>Client</th>
                        <th>Project / Service</th>
                        <th>Team Squad</th>
                        <th>Team Leader</th>
                        <th>Members</th>
                        <th>Status</th>
                        <th>Delivery Timeline</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $service)
                        <tr>
                            <td>
                                <div class="fw-semibold text-dark">{{ $service->client->client_company ?? ($service->client->client_name ?? 'N/A') }}</div>
                                <small class="text-muted">{{ $service->client->client_name ?? '' }}</small>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $service->service_name }}</div>
                                @if($service->description)
                                    <span class="text-muted small text-truncate d-inline-block" style="max-width: 220px;">
                                        {{ $service->description }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $teamName = $service->team_name;
                                    if (!$teamName && $service->assigned_team) {
                                        $parts = explode('•', $service->assigned_team);
                                        $teamName = trim($parts[0]);
                                    }
                                @endphp
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                                    <i class="fa-solid fa-users me-1"></i> {{ $teamName ?: 'General Team' }}
                                </span>
                            </td>
                            <td>
                                @if($service->teamLeader)
                                    <div class="fw-semibold text-dark small">
                                        <i class="fa-solid fa-user-tie text-primary me-1"></i> {{ $service->teamLeader->name }}
                                    </div>
                                    <span class="text-muted" style="font-size: 11px;">{{ $service->teamLeader->designation }}</span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td>
                                @if(is_array($service->team_members) && count($service->team_members) > 0)
                                    <div class="d-flex flex-wrap gap-1" style="max-width: 180px;">
                                        @foreach($service->team_members as $mId)
                                            @if(isset($allStaff[$mId]))
                                                <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 11px;">
                                                    {{ $allStaff[$mId] }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td>
                                @if($service->status == 'Active')
                                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1 border border-success-subtle"><i class="fa-solid fa-circle-check me-1"></i> Active</span>
                                @elseif($service->status == 'In Progress')
                                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1 border border-primary-subtle"><i class="fa-solid fa-spinner fa-spin me-1"></i> In Progress</span>
                                @elseif($service->status == 'Planning')
                                    <span class="badge bg-info-subtle text-info rounded-pill px-3 py-1 border border-info-subtle"><i class="fa-solid fa-compass-drafting me-1"></i> Planning</span>
                                @elseif($service->status == 'Completed')
                                    <span class="badge bg-teal-subtle text-success rounded-pill px-3 py-1 border border-success-subtle"><i class="fa-solid fa-check-double me-1"></i> Completed</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-1 border">{{ $service->status }}</span>
                                @endif
                            </td>
                            <td class="small">
                                <div><span class="text-muted">Start:</span> {{ $service->start_date ? \Carbon\Carbon::parse($service->start_date)->format('d M Y') : 'N/A' }}</div>
                                <div><span class="text-muted">Target:</span> <strong>{{ $service->end_date ? \Carbon\Carbon::parse($service->end_date)->format('d M Y') : 'N/A' }}</strong></div>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('staff.services.edit', $service) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3" title="Edit">
                                    <i class="fa-solid fa-pen"></i> Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="fa-solid fa-briefcase fa-3x mb-2 text-muted opacity-50"></i>
                                <p class="mb-0">No assigned client projects found. Click "Add / Assign Project" above to create one.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $services->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
