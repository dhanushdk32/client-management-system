@extends('layouts.staff')

@section('title', ($client->client_company ?: $client->client_name) . ' - Client 360 Dashboard')
@section('page_title', 'Client 360 Dashboard')

@section('content')
<!-- Top Back & Action Navigation -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('staff.clients.index') }}" class="btn btn-light border rounded-pill px-3 py-1 text-muted small fw-semibold">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Clients
        </a>
        <span class="text-muted small">/</span>
        <span class="fw-semibold text-dark small">#CL{{ sprintf('%03d', $client->client_id) }}</span>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('staff.tickets.index') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold">
            <i class="fa-solid fa-ticket me-1"></i> Open Ticket Desk
        </a>
    </div>
</div>

<!-- Client Profile Banner Card -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-4">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center shadow-sm" style="width: 64px; height: 64px; font-size: 26px; font-weight: 800;">
                    {{ strtoupper(substr($client->client_company ?: $client->client_name, 0, 2)) }}
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <h4 class="fw-bold mb-0 text-dark">{{ $client->client_company ?: $client->client_name }}</h4>
                        @if($client->client_status === 'Active')
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">Active Client</span>
                        @else
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1">Inactive</span>
                        @endif
                        <span class="badge bg-light text-secondary border font-monospace">ID: #CL{{ sprintf('%03d', $client->client_id) }}</span>
                    </div>
                    <div class="text-muted small mt-1">
                        <i class="fa-regular fa-user me-1 text-primary"></i> <strong>Contact Person:</strong> {{ $client->client_name }}
                    </div>
                </div>
            </div>

            <div class="d-flex flex-column text-md-end text-start">
                <span class="text-muted small">Joined Platform</span>
                <span class="fw-semibold text-dark">
                    <i class="fa-regular fa-calendar text-muted me-1"></i>
                    {{ $client->joined_date ? $client->joined_date->format('M d, Y') : ($client->client_created_date ? \Carbon\Carbon::parse($client->client_created_date)->format('M d, Y') : 'N/A') }}
                </span>
            </div>
        </div>

        <hr class="my-3 text-muted opacity-25">

        <!-- Contact & Location Metadata Pills -->
        <div class="row g-3 text-muted small">
            <div class="col-md-3 col-sm-6">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-regular fa-envelope text-primary fs-6"></i>
                    <div>
                        <span class="d-block" style="font-size: 11px;">Email Address</span>
                        <a href="mailto:{{ $client->client_email }}" class="fw-semibold text-dark text-decoration-none text-truncate d-inline-block" style="max-width: 180px;">{{ $client->client_email }}</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-phone text-success fs-6"></i>
                    <div>
                        <span class="d-block" style="font-size: 11px;">Primary Phone</span>
                        <span class="fw-semibold text-dark">{{ $client->primary_contact }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-location-dot text-danger fs-6"></i>
                    <div>
                        <span class="d-block" style="font-size: 11px;">Location / City</span>
                        <span class="fw-semibold text-dark">{{ $client->city ? ($client->city . ', ' . ($client->state ?? '')) : ($client->client_location ?: 'Not specified') }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-layer-group text-info fs-6"></i>
                    <div>
                        <span class="d-block" style="font-size: 11px;">Industry / Sector</span>
                        <span class="fw-semibold text-dark">{{ $client->industry ?: 'Custom Software & Services' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 4 Client 360 Metric Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="stat-card-roriri">
            <div>
                <div class="stat-card-label text-uppercase">Projects / Services</div>
                <h3 class="stat-card-value text-primary mb-0">{{ $client->services->count() }}</h3>
            </div>
            <div class="stat-icon-wrapper bg-icon-cyan">
                <i class="fa-solid fa-briefcase"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card-roriri">
            <div>
                <div class="stat-card-label text-uppercase">Support Requests</div>
                <h3 class="stat-card-value text-warning mb-0">{{ $tickets->count() }}</h3>
            </div>
            <div class="stat-icon-wrapper bg-icon-yellow">
                <i class="fa-solid fa-ticket"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card-roriri">
            <div>
                <div class="stat-card-label text-uppercase">Uploaded Files</div>
                <h3 class="stat-card-value text-success mb-0">{{ $documents->count() }}</h3>
            </div>
            <div class="stat-icon-wrapper bg-icon-green">
                <i class="fa-solid fa-file-shield"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card-roriri">
            <div>
                <div class="stat-card-label text-uppercase">Assigned Staff</div>
                <h3 class="stat-card-value text-purple mb-0">{{ $client->assignedStaff->count() }}</h3>
            </div>
            <div class="stat-icon-wrapper bg-icon-purple">
                <i class="fa-solid fa-user-group"></i>
            </div>
        </div>
    </div>
</div>

<!-- Client Detailed Tabs -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white p-3 border-bottom">
        <ul class="nav nav-pills gap-2" id="client360Tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active rounded-pill px-4 fw-semibold small" id="services-tab" data-bs-toggle="tab" data-bs-target="#tab-services" type="button" role="tab">
                    <i class="fa-solid fa-briefcase me-1"></i> Projects & Services ({{ $client->services->count() }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-pill px-4 fw-semibold small text-muted" id="tickets-tab" data-bs-toggle="tab" data-bs-target="#tab-tickets" type="button" role="tab">
                    <i class="fa-solid fa-comments me-1"></i> Support Conversations ({{ $tickets->count() }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-pill px-4 fw-semibold small text-muted" id="docs-tab" data-bs-toggle="tab" data-bs-target="#tab-docs" type="button" role="tab">
                    <i class="fa-solid fa-file-lines me-1"></i> Documents Vault ({{ $documents->count() }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-pill px-4 fw-semibold small text-muted" id="team-tab" data-bs-toggle="tab" data-bs-target="#tab-team" type="button" role="tab">
                    <i class="fa-solid fa-users me-1"></i> Assigned Team & Users ({{ $client->assignedStaff->count() + $client->users->count() }})
                </button>
            </li>
        </ul>
    </div>

    <div class="card-body p-4">
        <div class="tab-content" id="client360TabsContent">
            
            <!-- 1. PROJECTS & SERVICES TAB -->
            <div class="tab-pane fade show active" id="tab-services" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0 text-primary">Subscribed Projects & Software Contracts</h6>
                    <span class="badge bg-light text-dark border">{{ $client->services->count() }} Record(s)</span>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr class="small text-muted">
                                <th>Project Name</th>
                                <th>Assigned Squad</th>
                                <th>Team Lead</th>
                                <th>Team Members</th>
                                <th>Status</th>
                                <th>Timeline</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($client->services as $service)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $service->service_name }}</div>
                                        @if($service->description)
                                            <small class="text-muted d-block text-truncate" style="max-width: 250px;">{{ $service->description }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border px-2 py-1">
                                            <i class="fa-solid fa-users-gear me-1 text-primary"></i>
                                            {{ $service->team_name ?: ($service->assigned_team ?? 'Engineering Squad') }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($service->teamLeader)
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 28px; height: 28px; font-size: 11px;">
                                                    {{ strtoupper(substr($service->teamLeader->name, 0, 2)) }}
                                                </div>
                                                <div>
                                                    <span class="fw-semibold text-dark small">{{ $service->teamLeader->name }}</span>
                                                    <span class="text-muted d-block" style="font-size: 10px;">Lead</span>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted small">Not Assigned</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!empty($service->team_members) && is_array($service->team_members))
                                            <div class="d-flex flex-wrap gap-1" style="max-width: 180px;">
                                                @foreach($service->team_members as $memberId)
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
                                        @if($service->status === 'Active')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill">Active</span>
                                        @elseif($service->status === 'In Progress')
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 rounded-pill">In Progress</span>
                                        @elseif($service->status === 'Completed')
                                            <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1 rounded-pill">Completed</span>
                                        @elseif($service->status === 'Planning')
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 rounded-pill">Planning</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary border px-2 py-1 rounded-pill">{{ $service->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="small text-dark">
                                            <i class="fa-regular fa-calendar text-muted me-1"></i>
                                            {{ $service->start_date ? \Carbon\Carbon::parse($service->start_date)->format('M d, Y') : 'N/A' }}
                                        </div>
                                        @if($service->end_date)
                                            <div class="small text-muted" style="font-size: 11px;">
                                                Due: {{ \Carbon\Carbon::parse($service->end_date)->format('M d, Y') }}
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="fa-solid fa-briefcase fa-2x mb-2 text-light"></i>
                                        <p class="mb-0">No active projects or services contracted yet.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 2. SUPPORT TICKETS TAB -->
            <div class="tab-pane fade" id="tab-tickets" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0 text-primary">Support Requests & Client Communications</h6>
                    <span class="badge bg-light text-dark border">{{ $tickets->count() }} Ticket(s)</span>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr class="small text-muted">
                                <th>Ticket ID</th>
                                <th>Subject & Request Summary</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Submitted Date</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tickets as $ticket)
                                <tr>
                                    <td>
                                        <span class="badge bg-light text-dark border font-monospace">#{{ $ticket->id }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $ticket->subject }}</div>
                                        @if($ticket->description)
                                            <small class="text-muted d-block text-truncate" style="max-width: 320px;">{{ $ticket->description }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($ticket->priority === 'High' || $ticket->priority === 'Urgent')
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-pill">High</span>
                                        @elseif($ticket->priority === 'Medium')
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 rounded-pill">Medium</span>
                                        @else
                                            <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1 rounded-pill">Low</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($ticket->status === 'Open')
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 rounded-pill">Open</span>
                                        @elseif($ticket->status === 'In Progress')
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 rounded-pill">In Progress</span>
                                        @elseif($ticket->status === 'Resolved')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill">Resolved</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary border px-2 py-1 rounded-pill">{{ $ticket->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="small text-dark">{{ $ticket->created_at->format('M d, Y') }}</div>
                                        <small class="text-muted" style="font-size: 11px;">{{ $ticket->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('staff.tickets.show', $ticket) }}" class="btn btn-sm btn-primary rounded-pill px-3">
                                            <i class="fa-solid fa-reply me-1"></i> Open Chat
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="fa-regular fa-comments fa-2x mb-2 text-light"></i>
                                        <p class="mb-0">No support requests submitted by this client.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 3. DOCUMENTS VAULT TAB -->
            <div class="tab-pane fade" id="tab-docs" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0 text-primary">Uploaded Client Documents & Compliance Files</h6>
                    <span class="badge bg-light text-dark border">{{ $documents->count() }} File(s)</span>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr class="small text-muted">
                                <th>Document Name</th>
                                <th>Type</th>
                                <th>Uploaded Date</th>
                                <th>Verification Status</th>
                                <th class="text-end">Download</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($documents as $doc)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="fa-regular fa-file-pdf text-danger fs-5"></i>
                                            <div>
                                                <span class="fw-semibold text-dark">{{ $doc->file_name }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-secondary border">{{ $doc->document_type ?: 'General Document' }}</span>
                                    </td>
                                    <td>
                                        <span class="small text-dark">{{ $doc->created_at->format('M d, Y') }}</span>
                                    </td>
                                    <td>
                                        @if($doc->verification_status === 'Verified')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill">Verified</span>
                                        @elseif($doc->verification_status === 'Rejected')
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-pill">Rejected</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 rounded-pill">Pending Review</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if($doc->file_path)
                                            <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                <i class="fa-solid fa-download me-1"></i> Download
                                            </a>
                                        @else
                                            <span class="text-muted small">File N/A</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="fa-regular fa-folder-open fa-2x mb-2 text-light"></i>
                                        <p class="mb-0">No documents uploaded for this client yet.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 4. ASSIGNED TEAM & USERS TAB -->
            <div class="tab-pane fade" id="tab-team" role="tabpanel">
                <div class="row g-4">
                    <!-- Assigned Staff Team -->
                    <div class="col-md-6">
                        <div class="card bg-light border p-3 h-100">
                            <h6 class="fw-bold mb-3 text-primary">
                                <i class="fa-solid fa-user-tie me-1"></i> Assigned Staff Squad
                            </h6>
                            <div class="list-group list-group-flush">
                                @forelse($client->assignedStaff as $member)
                                    <div class="list-group-item bg-white rounded-3 mb-2 border d-flex justify-content-between align-items-center p-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 36px; height: 36px;">
                                                {{ strtoupper(substr($member->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $member->name }}</div>
                                                <small class="text-muted">{{ $member->designation ?? 'Team Member' }} &bull; {{ $member->department ?? 'Engineering' }}</small>
                                            </div>
                                        </div>
                                        <span class="badge bg-primary-subtle text-primary border">{{ $member->pivot->role_in_project ?? 'Squad Member' }}</span>
                                    </div>
                                @empty
                                    <div class="text-muted text-center py-3">No staff members explicitly assigned.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Client Portal User Accounts -->
                    <div class="col-md-6">
                        <div class="card bg-light border p-3 h-100">
                            <h6 class="fw-bold mb-3 text-primary">
                                <i class="fa-solid fa-users me-1"></i> Client Portal Authorized Users
                            </h6>
                            <div class="list-group list-group-flush">
                                @forelse($client->users as $u)
                                    <div class="list-group-item bg-white rounded-3 mb-2 border d-flex justify-content-between align-items-center p-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center fw-bold" style="width: 36px; height: 36px;">
                                                {{ strtoupper(substr($u->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $u->name }}</div>
                                                <small class="text-muted">{{ $u->email }}</small>
                                            </div>
                                        </div>
                                        <span class="badge {{ $u->status === 'Active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }} rounded-pill px-3 py-1">
                                            {{ $u->status ?? 'Active' }}
                                        </span>
                                    </div>
                                @empty
                                    <div class="text-muted text-center py-3">No client user accounts created.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
