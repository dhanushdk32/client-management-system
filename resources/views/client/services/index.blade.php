@extends('layouts.client')

@section('title', 'My Projects & Services - Client Portal')
@section('page_title', 'My Projects & Engineering Squad')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stat-card-roriri">
            <div>
                <div class="stat-card-label">Contracted Projects</div>
                <h3 class="stat-card-value text-primary">{{ $services->count() }}</h3>
            </div>
            <div class="stat-icon-wrapper bg-icon-blue">
                <i class="fa-solid fa-briefcase"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card-roriri">
            <div>
                <div class="stat-card-label">Active / Live Projects</div>
                <h3 class="stat-card-value text-success">{{ $services->whereIn('status', ['Active', 'In Progress'])->count() }}</h3>
            </div>
            <div class="stat-icon-wrapper bg-icon-green">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card-roriri">
            <div>
                <div class="stat-card-label">Completed Deliverables</div>
                <h3 class="stat-card-value text-info">{{ $services->where('status', 'Completed')->count() }}</h3>
            </div>
            <div class="stat-icon-wrapper bg-icon-cyan">
                <i class="fa-solid fa-check-double"></i>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h5 class="fw-bold mb-1 text-primary border-bottom border-2 border-primary pb-2 d-inline-block">My Subscribed Projects & Dedicated Team Leads</h5>
                <p class="text-muted small mb-0">Track live project delivery status, milestones, and connect directly with your assigned Project Team Leader.</p>
            </div>
            <a href="{{ route('client.tickets.index') }}" class="btn btn-primary rounded-pill px-4 fw-semibold">
                <i class="fa-solid fa-comments me-1"></i> Open Support Desk
            </a>
        </div>

        <div class="row g-4">
            @forelse($services as $service)
                <div class="col-lg-6">
                    <div class="card h-100 border p-4 shadow-sm rounded-4">
                        <!-- Project Header -->
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="fw-bold text-dark mb-1">{{ $service->service_name }}</h5>
                                <span class="badge bg-light text-secondary border font-monospace">#PRJ{{ sprintf('%03d', $service->id) }}</span>
                            </div>
                            <div>
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
                            </div>
                        </div>

                        <!-- 🌟 Highlighted Team Leader Card for Direct Conversation -->
                        <div class="card bg-primary-subtle border border-primary-subtle p-3 rounded-3 mb-3">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 44px; height: 44px; font-size: 16px;">
                                        @if($service->teamLeader)
                                            {{ strtoupper(substr($service->teamLeader->name, 0, 2)) }}
                                        @else
                                            TL
                                        @endif
                                    </div>
                                    <div>
                                        <div class="small text-muted fw-semibold text-uppercase" style="font-size: 10.5px;">Assigned Project Team Leader</div>
                                        <div class="fw-bold text-dark fs-6">
                                            {{ $service->teamLeader->name ?? ($service->client->assignedStaff->first()?->name ?? 'Engineering Lead') }}
                                        </div>
                                        <small class="text-muted d-block">
                                            {{ $service->teamLeader->designation ?? 'Lead Technical Manager' }} &bull; {{ $service->team_name ?: ($service->assigned_team ?? 'Engineering Squad') }}
                                        </small>
                                    </div>
                                </div>
                                <div>
                                    <a href="{{ route('client.tickets.index') }}" class="btn btn-sm btn-primary rounded-pill px-3 fw-semibold shadow-sm">
                                        <i class="fa-solid fa-comment-dots me-1"></i> Message Lead
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Scope & Deliverables -->
                        <div class="bg-light rounded-3 p-3 mb-3 small text-secondary">
                            <strong class="d-block text-dark mb-1"><i class="fa-solid fa-file-lines me-1 text-primary"></i> Scope & Deliverables:</strong>
                            {{ $service->description ?: 'Ongoing development, deployment, and technical infrastructure support as configured by your dedicated engineering lead.' }}
                        </div>

                        <!-- Timeline & Full Details -->
                        <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-auto">
                            <div class="small">
                                <span class="text-muted">Target Delivery: </span>
                                <span class="fw-semibold text-dark">{{ $service->end_date ? $service->end_date->format('d M Y') : 'Active Lifecycle' }}</span>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#serviceModal{{ $service->id }}">
                                <i class="fa-solid fa-circle-info me-1"></i> Full Details
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted py-5">
                    <i class="fa-solid fa-folder-open fa-3x mb-3 text-muted opacity-50"></i>
                    <p class="mb-2 fs-5 fw-semibold text-dark">No projects assigned yet.</p>
                    <p class="text-muted small mb-0">Your administrator will assign your projects and technical team leader shortly.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('modals')
    @foreach($services as $service)
        <!-- Modal for Service Details -->
        <div class="modal fade" id="serviceModal{{ $service->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title fw-bold text-primary">{{ $service->service_name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <!-- Team Leader Section in Modal -->
                        <div class="p-3 bg-light border rounded-3 mb-3">
                            <div class="small text-muted fw-semibold">Team Leader Responsible</div>
                            <div class="fw-bold text-dark fs-6">{{ $service->teamLeader->name ?? ($service->client->assignedStaff->first()?->name ?? 'Lead Engineer') }}</div>
                            <small class="text-muted">{{ $service->teamLeader->designation ?? 'Technical Lead' }} &bull; {{ $service->team_name ?: ($service->assigned_team ?? 'Engineering Squad') }}</small>
                        </div>

                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="text-muted w-40">Status</td>
                                <td class="fw-semibold">: 
                                    <span class="badge bg-primary-subtle text-primary px-3 py-1">{{ $service->status }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Start Date</td>
                                <td class="fw-semibold">: {{ $service->start_date ? $service->start_date->format('d M Y') : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Target Delivery</td>
                                <td class="fw-semibold">: {{ $service->end_date ? $service->end_date->format('d M Y') : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted" colspan="2">Detailed Scope Description:</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="border rounded bg-light p-3 small text-secondary">
                                    {{ $service->description ?? 'No detailed description available.' }}
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer border-top-0 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                        <a href="{{ route('client.tickets.index') }}" class="btn btn-primary rounded-pill px-4">
                            <i class="fa-solid fa-comment-dots me-1"></i> Message Team Leader
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endpush
