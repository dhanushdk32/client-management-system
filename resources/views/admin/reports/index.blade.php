@extends('layouts.admin')

@section('title', 'Reports & Project Analytics - Admin Portal')
@section('page_title', 'Reports & Project Portfolio')

@section('content')
<!-- High-Level Metric Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card h-100 border-0 shadow-sm" style="border-left: 4px solid #0284c7 !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold">Total Clients</div>
                        <h3 class="fw-bold my-1 text-dark">{{ $stats['total_clients'] }}</h3>
                        <span class="badge bg-success-subtle text-success small">{{ $stats['active_clients'] }} Active</span>
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
                        <span class="badge bg-primary-subtle text-primary small">{{ $stats['active_projects'] }} Ongoing</span>
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
                <i class="fa-solid fa-chart-pie me-1"></i> Project Portfolio & Team Report
            </h5>
            <p class="text-muted small mb-0">Overview of all client projects, assigned technical squads, team leads, and delivery timelines.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.reports.export.services') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold">
                <i class="fa-solid fa-file-arrow-down me-1"></i> Export Projects (CSV)
            </a>
            <a href="{{ route('admin.reports.export.clients') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-semibold">
                <i class="fa-solid fa-file-arrow-down me-1"></i> Export Clients (CSV)
            </a>
        </div>
    </div>

    <div class="card-body p-4">
        <!-- Filter Form -->
        <form action="{{ route('admin.reports.index') }}" method="GET" class="row g-2 mb-4 align-items-center">
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
                    <a href="{{ route('admin.reports.index') }}" class="btn btn-light border w-100 text-muted">Reset</a>
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
                        <th>Team Name</th>
                        <th>Team Leader</th>
                        <th>Members</th>
                        <th>Status</th>
                        <th>Timeline</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($projects as $project)
                        <tr>
                            <td>
                                <div class="fw-semibold text-dark">{{ $project->client->client_name ?? 'N/A' }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $project->service_name }}</div>
                                @if($project->description)
                                    <span class="text-muted small text-truncate d-inline-block" style="max-width: 200px;">
                                        {{ $project->description }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $teamName = $project->team_name;
                                    if (!$teamName && $project->assigned_team) {
                                        $parts = explode('•', $project->assigned_team);
                                        $teamName = trim($parts[0]);
                                    }
                                @endphp
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                                    <i class="fa-solid fa-users me-1"></i> {{ $teamName ?: 'General Team' }}
                                </span>
                            </td>
                            <td>
                                @if($project->teamLeader)
                                    <div class="fw-semibold text-dark small">
                                        <i class="fa-solid fa-user-tie text-primary me-1"></i> {{ $project->teamLeader->name }}
                                    </div>
                                    <span class="text-muted" style="font-size: 11px;">{{ $project->teamLeader->designation }}</span>
                                @elseif($project->assigned_team && preg_match('/Lead:\s*([^•]+)/', $project->assigned_team, $m))
                                    <div class="fw-semibold text-dark small">
                                        <i class="fa-solid fa-user-tie text-primary me-1"></i> {{ trim($m[1]) }}
                                    </div>
                                    <span class="text-muted" style="font-size: 11px;">Team Lead</span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td>
                                @if(is_array($project->team_members) && count($project->team_members) > 0)
                                    <div class="d-flex flex-wrap gap-1" style="max-width: 180px;">
                                        @foreach($project->team_members as $mId)
                                            @if(isset($allStaff[$mId]))
                                                <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 11px;">
                                                    {{ $allStaff[$mId] }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                @elseif($project->assigned_team && preg_match('/Members:\s*([^•]+)/', $project->assigned_team, $m))
                                    <div class="d-flex flex-wrap gap-1" style="max-width: 180px;">
                                        @foreach(explode(',', $m[1]) as $mName)
                                            <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 11px;">
                                                {{ trim($mName) }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td>
                                @if($project->status == 'Active')
                                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1 border border-success-subtle"><i class="fa-solid fa-circle-check me-1"></i> Active</span>
                                @elseif($project->status == 'In Progress')
                                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1 border border-primary-subtle"><i class="fa-solid fa-spinner fa-spin me-1"></i> In Progress</span>
                                @elseif($project->status == 'Planning')
                                    <span class="badge bg-info-subtle text-info rounded-pill px-3 py-1 border border-info-subtle"><i class="fa-solid fa-compass-drafting me-1"></i> Planning</span>
                                @elseif($project->status == 'Completed')
                                    <span class="badge bg-teal-subtle text-success rounded-pill px-3 py-1 border border-success-subtle"><i class="fa-solid fa-check-double me-1"></i> Completed</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-1 border">{{ $project->status }}</span>
                                @endif
                            </td>
                            <td class="small">
                                <div><span class="text-muted">Start:</span> {{ $project->start_date ? $project->start_date->format('d M Y') : 'N/A' }}</div>
                                <div><span class="text-muted">Target:</span> <strong>{{ $project->end_date ? $project->end_date->format('d M Y') : 'N/A' }}</strong></div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-briefcase fa-3x mb-2 text-muted opacity-50"></i>
                                <p class="mb-0">No projects found matching the criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($projects->hasPages())
            <div class="mt-4 d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Showing {{ $projects->firstItem() ?? 0 }} to {{ $projects->lastItem() ?? 0 }} of {{ $projects->total() }} entries
                </div>
                <div>
                    {{ $projects->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Data Export Cards -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-transparent p-4 border-bottom">
        <h5 class="fw-bold mb-0 text-primary">
            <i class="fa-solid fa-file-export me-1"></i> Export System Reports
        </h5>
    </div>
    <div class="card-body p-4">
        <div class="row g-4">
            <!-- Client Export -->
            <div class="col-md-6">
                <div class="border rounded-3 p-4 d-flex align-items-center justify-content-between h-100 bg-light">
                    <div>
                        <h6 class="fw-bold mb-1 text-dark">Clients Report (CSV)</h6>
                        <p class="text-muted small mb-0">Download complete list of all registered clients with phone numbers, emails, and joined dates.</p>
                    </div>
                    <a href="{{ route('admin.reports.export.clients') }}" class="btn btn-primary rounded-circle shadow-sm" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;" title="Download CSV">
                        <i class="fa-solid fa-download"></i>
                    </a>
                </div>
            </div>

            <!-- Services Export -->
            <div class="col-md-6">
                <div class="border rounded-3 p-4 d-flex align-items-center justify-content-between h-100 bg-light">
                    <div>
                        <h6 class="fw-bold mb-1 text-dark">Projects & Services Report (CSV)</h6>
                        <p class="text-muted small mb-0">Download all projects with client details, team names, team leads, members, and delivery milestones.</p>
                    </div>
                    <a href="{{ route('admin.reports.export.services') }}" class="btn btn-success rounded-circle shadow-sm" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;" title="Download CSV">
                        <i class="fa-solid fa-download"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
