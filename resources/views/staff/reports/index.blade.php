@extends('layouts.staff')

@section('title', 'Reports & Project Portfolio - Staff Portal')
@section('page_title', 'Reports & Project Portfolio')

@section('content')
<!-- High-Level Metric Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card h-100 border-0 shadow-sm" style="border-left: 4px solid #0284c7 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">Clients Portfolio</div>
                        <h3 class="fw-bold my-1 text-dark">{{ $stats['total_clients'] }}</h3>
                        <span class="badge bg-primary-subtle text-primary small">{{ $stats['my_clients'] }} Assigned to Me</span>
                    </div>
                    <div class="stat-icon-wrapper bg-icon-cyan">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card h-100 border-0 shadow-sm" style="border-left: 4px solid #3b82f6 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">Total Projects</div>
                        <h3 class="fw-bold my-1 text-dark">{{ $stats['total_projects'] }}</h3>
                        <span class="badge bg-primary-subtle text-primary small">{{ $stats['active_projects'] }} In Progress</span>
                    </div>
                    <div class="stat-icon-wrapper bg-icon-blue">
                        <i class="fa-solid fa-briefcase"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card h-100 border-0 shadow-sm" style="border-left: 4px solid #10b981 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">Completed Projects</div>
                        <h3 class="fw-bold my-1 text-dark">{{ $stats['completed_projects'] }}</h3>
                        <span class="badge bg-success-subtle text-success small">Delivered</span>
                    </div>
                    <div class="stat-icon-wrapper bg-icon-green">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card h-100 border-0 shadow-sm" style="border-left: 4px solid #f59e0b !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">Support Requests</div>
                        <h3 class="fw-bold my-1 text-dark">{{ $stats['open_tickets'] }}</h3>
                        <span class="badge bg-warning-subtle text-warning small">{{ $stats['resolved_tickets'] }} Resolved</span>
                    </div>
                    <div class="stat-icon-wrapper bg-icon-amber">
                        <i class="fa-solid fa-ticket"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Project Portfolio & Live Status Report Table -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-transparent p-4 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h5 class="fw-bold mb-1 text-primary">
                <i class="fa-solid fa-chart-pie me-1"></i> Project Portfolio & Operations Report
            </h5>
            <p class="text-muted small mb-0">Overview of client projects, assigned technical squads, delivery timelines, and statuses.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('staff.reports.export.services') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold">
                <i class="fa-solid fa-file-arrow-down me-1"></i> Export Projects (CSV)
            </a>
            <a href="{{ route('staff.reports.export.clients') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-semibold">
                <i class="fa-solid fa-file-arrow-down me-1"></i> Export Clients (CSV)
            </a>
        </div>
    </div>

    <div class="card-body p-4">
        <!-- Scope Tabs -->
        <div class="d-flex gap-2 mb-4 border-bottom pb-3">
            <a href="{{ route('staff.reports.index', ['scope' => 'all'] + request()->except('page', 'scope')) }}" class="btn btn-sm rounded-pill px-3 fw-semibold {{ $scope === 'all' ? 'btn-primary' : 'btn-light border text-muted' }}">
                <i class="fa-solid fa-globe me-1"></i> All Projects ({{ $stats['total_projects'] }})
            </a>
            <a href="{{ route('staff.reports.index', ['scope' => 'assigned'] + request()->except('page', 'scope')) }}" class="btn btn-sm rounded-pill px-3 fw-semibold {{ $scope === 'assigned' ? 'btn-primary' : 'btn-light border text-muted' }}">
                <i class="fa-solid fa-user-check me-1"></i> My Clients' Projects
            </a>
        </div>

        <!-- Filter Form -->
        <form action="{{ route('staff.reports.index') }}" method="GET" class="row g-2 mb-4 align-items-center">
            <input type="hidden" name="scope" value="{{ $scope }}">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-search text-muted"></i></span>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control bg-light border-start-0 ps-0" placeholder="Search by project name, client, team, or lead...">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select bg-light">
                    <option value="">All Project Statuses</option>
                    <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active (Live)</option>
                    <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="Planning" {{ request('status') == 'Planning' ? 'selected' : '' }}>Planning</option>
                    <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                    <option value="On Hold" {{ request('status') == 'On Hold' ? 'selected' : '' }}>On Hold</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100 fw-semibold">Filter</button>
            </div>
            <div class="col-md-2">
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('staff.reports.index', ['scope' => $scope]) }}" class="btn btn-light border w-100 text-muted">Reset</a>
                @endif
            </div>
        </form>

        <!-- Projects Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr class="small text-muted">
                        <th>Client</th>
                        <th>Project / Service</th>
                        <th>Team Squad</th>
                        <th>Team Leader</th>
                        <th>Team Members</th>
                        <th>Status</th>
                        <th>Timeline</th>
                        <th class="text-end">Client 360</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($projects as $project)
                        <tr>
                            <td>
                                <div class="fw-semibold text-dark">{{ $project->client->client_company ?? ($project->client->client_name ?? 'N/A') }}</div>
                                <small class="text-muted">{{ $project->client->client_name ?? '' }}</small>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $project->service_name }}</div>
                                @if($project->description)
                                    <span class="text-muted small text-truncate d-inline-block" style="max-width: 180px;">
                                        {{ $project->description }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border fw-medium px-2 py-1">
                                    <i class="fa-solid fa-users-gear me-1 text-primary"></i>
                                    {{ $project->team_name ?: ($project->assigned_team ?? 'Engineering Team') }}
                                </span>
                            </td>
                            <td>
                                @if($project->teamLeader)
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 28px; height: 28px; font-size: 11px;">
                                            {{ strtoupper(substr($project->teamLeader->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <span class="fw-semibold text-dark small">{{ $project->teamLeader->name }}</span>
                                            <span class="text-muted d-block" style="font-size: 10px;">Lead</span>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted small">Not Assigned</span>
                                @endif
                            </td>
                            <td>
                                @if(!empty($project->team_members) && is_array($project->team_members))
                                    <div class="d-flex flex-wrap gap-1" style="max-width: 200px;">
                                        @foreach($project->team_members as $memberId)
                                            @if(isset($allStaff[$memberId]))
                                                <span class="badge bg-light text-secondary border font-monospace" style="font-size: 10.5px;">
                                                    {{ $allStaff[$memberId] }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted small">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($project->status === 'Active')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill">Active</span>
                                @elseif($project->status === 'In Progress')
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 rounded-pill">In Progress</span>
                                @elseif($project->status === 'Completed')
                                    <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1 rounded-pill">Completed</span>
                                @elseif($project->status === 'Planning')
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 rounded-pill">Planning</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border px-2 py-1 rounded-pill">{{ $project->status }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="small text-dark">
                                    <i class="fa-regular fa-calendar text-muted me-1"></i>
                                    {{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('M d, Y') : 'N/A' }}
                                </div>
                                @if($project->end_date)
                                    <div class="small text-muted" style="font-size: 11px;">
                                        Due: {{ \Carbon\Carbon::parse($project->end_date)->format('M d, Y') }}
                                    </div>
                                @endif
                            </td>
                            <td class="text-end">
                                @if($project->client)
                                    <a href="{{ route('staff.clients.show', $project->client->client_id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        <i class="fa-solid fa-eye me-1"></i> View Client
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-folder-open fa-3x mb-3 text-light"></i>
                                <p class="mb-0">No project portfolio records match the selected criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $projects->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
