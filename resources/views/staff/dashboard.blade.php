@extends('layouts.staff')

@section('title', 'Staff Dashboard - My Operations')
@section('page_title', 'My Operations Dashboard')

@section('content')
<!-- High-Level Metric Cards -->
<div class="row g-4 mb-4">
    <!-- Stat 1: Assigned Clients -->
    <div class="col-md-3 col-sm-6">
        <div class="stat-card-roriri">
            <div>
                <div class="stat-card-label">My Assigned Clients</div>
                <h3 class="stat-card-value text-primary">{{ $stats['assigned_clients'] }}</h3>
                <span class="badge bg-primary-subtle text-primary border rounded-pill px-2 py-0 mt-1" style="font-size: 11px;">
                    Active Accounts
                </span>
            </div>
            <div class="stat-icon-wrapper bg-icon-cyan">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>
    </div>

    <!-- Stat 2: Active Projects -->
    <div class="col-md-3 col-sm-6">
        <div class="stat-card-roriri">
            <div>
                <div class="stat-card-label">My Active Projects</div>
                <h3 class="stat-card-value text-info">{{ $stats['active_projects'] }}</h3>
                <span class="badge bg-info-subtle text-info border rounded-pill px-2 py-0 mt-1" style="font-size: 11px;">
                    {{ $stats['total_projects'] }} Total Assigned
                </span>
            </div>
            <div class="stat-icon-wrapper bg-icon-blue">
                <i class="fa-solid fa-briefcase"></i>
            </div>
        </div>
    </div>

    <!-- Stat 3: Open Requests -->
    <div class="col-md-3 col-sm-6">
        <div class="stat-card-roriri">
            <div>
                <div class="stat-card-label">My Open Requests</div>
                <h3 class="stat-card-value text-warning">{{ $stats['open_tickets'] }}</h3>
                <span class="badge bg-warning-subtle text-warning border rounded-pill px-2 py-0 mt-1" style="font-size: 11px;">
                    {{ $stats['resolved_tickets'] }} Resolved
                </span>
            </div>
            <div class="stat-icon-wrapper bg-icon-yellow">
                <i class="fa-solid fa-ticket"></i>
            </div>
        </div>
    </div>

    <!-- Stat 4: Document Vault -->
    <div class="col-md-3 col-sm-6">
        <div class="stat-card-roriri">
            <div>
                <div class="stat-card-label">Client Documents</div>
                <h3 class="stat-card-value text-success">{{ $stats['total_documents'] }}</h3>
                <span class="badge bg-success-subtle text-success border rounded-pill px-2 py-0 mt-1" style="font-size: 11px;">
                    {{ $stats['pending_documents'] }} Pending Review
                </span>
            </div>
            <div class="stat-icon-wrapper bg-icon-green">
                <i class="fa-solid fa-file-shield"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Left Column: Support Inquiries & Active Projects -->
    <div class="col-lg-8">
        <!-- Support Tickets Live Desk -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-comments me-2 text-primary"></i> My Clients' Support Inquiries</h5>
                    <p class="text-muted small mb-0">Direct requests and conversations from your assigned clients.</p>
                </div>
                <a href="{{ route('staff.tickets.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold">Open Ticket Desk</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr class="small text-muted">
                                <th class="ps-4">Ticket</th>
                                <th>Client Company</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTickets as $ticket)
                                <tr>
                                    <td class="ps-4 fw-semibold">
                                        <a href="{{ route('staff.tickets.show', $ticket) }}" class="text-decoration-none text-dark">
                                            #{{ $ticket->id }} - {{ Str::limit($ticket->subject, 32) }}
                                        </a>
                                        <small class="text-muted d-block" style="font-size: 11px;">{{ $ticket->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-dark">{{ $ticket->client->client_company ?? ($ticket->client->client_name ?? 'N/A') }}</span>
                                    </td>
                                    <td>
                                        @if($ticket->priority == 'High' || $ticket->priority == 'Urgent')
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-pill">High</span>
                                        @elseif($ticket->priority == 'Medium')
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 rounded-pill">Medium</span>
                                        @else
                                            <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1 rounded-pill">Low</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($ticket->status == 'Open')
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 rounded-pill">Open</span>
                                        @elseif($ticket->status == 'In Progress')
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 rounded-pill">In Progress</span>
                                        @elseif($ticket->status == 'Resolved')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill">Resolved</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary border px-2 py-1 rounded-pill">{{ $ticket->status }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('staff.tickets.show', $ticket) }}" class="btn btn-sm btn-primary rounded-pill px-3">
                                            <i class="fa-solid fa-reply me-1"></i> Reply
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No open requests for your assigned clients.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Active Projects & Squads Feed -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-briefcase me-2 text-info"></i> My Assigned Projects</h5>
                    <p class="text-muted small mb-0">Projects where you are the Lead or Team Member.</p>
                </div>
                <a href="{{ route('staff.services.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-semibold">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr class="small text-muted">
                                <th class="ps-4">Project</th>
                                <th>Client</th>
                                <th>Team Lead</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Target Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activeProjects as $proj)
                                <tr>
                                    <td class="ps-4 fw-bold text-dark">
                                        {{ $proj->service_name }}
                                        <span class="badge bg-light text-muted border font-monospace ms-1" style="font-size: 10px;">#PRJ{{ sprintf('%03d', $proj->id) }}</span>
                                    </td>
                                    <td>{{ $proj->client->client_company ?? ($proj->client->client_name ?? 'N/A') }}</td>
                                    <td>
                                        @if($proj->teamLeader)
                                            <span class="small fw-semibold text-dark">{{ $proj->teamLeader->name }}</span>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-1">{{ $proj->status }}</span>
                                    </td>
                                    <td class="text-end pe-4 small text-muted">
                                        {{ $proj->end_date ? \Carbon\Carbon::parse($proj->end_date)->format('M d, Y') : 'N/A' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No active projects assigned yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Assigned Clients List -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-users me-1 text-primary"></i> My Assigned Clients</h6>
                <span class="badge bg-primary-subtle text-primary">{{ $assignedClients->count() }}</span>
            </div>
            <p class="text-muted small mb-3">You can view complete 360 project details, documents, and contacts for these clients.</p>

            <div class="list-group list-group-flush">
                @forelse($assignedClients as $ac)
                    <a href="{{ route('staff.clients.show', $ac) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0 py-2 border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 34px; height: 34px; font-size: 11px;">
                                {{ strtoupper(substr($ac->client_company ?: $ac->client_name, 0, 2)) }}
                            </div>
                            <div>
                                <div class="fw-semibold text-dark small">{{ $ac->client_company ?: $ac->client_name }}</div>
                                <small class="text-muted" style="font-size: 11px;"><i class="fa-regular fa-envelope me-1"></i> {{ $ac->client_email }}</small>
                            </div>
                        </div>
                        <i class="fa-solid fa-chevron-right text-muted" style="font-size: 11px;"></i>
                    </a>
                @empty
                    <div class="text-muted small py-4 text-center">
                        <i class="fa-solid fa-user-clock fa-2x mb-2 text-light d-block"></i>
                        No clients assigned to you yet.<br>Admin will assign clients to your account.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
