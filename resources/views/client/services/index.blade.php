@extends('layouts.client')

@section('title', 'My Projects & Services - Client Portal')
@section('page_title', 'My Projects & Services')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stat-card-roriri">
            <div>
                <div class="stat-card-label">Total Contracted Projects</div>
                <h3 class="stat-card-value">{{ $services->count() }}</h3>
            </div>
            <div class="stat-icon-wrapper bg-icon-blue">
                <i class="fa-solid fa-briefcase"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card-roriri">
            <div>
                <div class="stat-card-label">Active / In-Progress Projects</div>
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
                <h3 class="stat-card-value text-primary">{{ $services->where('status', 'Completed')->count() }}</h3>
            </div>
            <div class="stat-icon-wrapper bg-icon-cyan">
                <i class="fa-solid fa-check-double"></i>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="fw-bold mb-1 text-primary border-bottom border-2 border-primary pb-2 d-inline-block">My Subscribed Projects & Services</h5>
                <p class="text-muted small mb-0">Track live project delivery status, engineering milestones, and dedicated development teams.</p>
            </div>
            <a href="{{ route('client.tickets.index') }}" class="btn btn-outline-primary rounded-pill px-4 fw-semibold">
                <i class="fa-solid fa-ticket me-1"></i> Request Support / Changes
            </a>
        </div>

        <div class="row g-4">
            @forelse($services as $service)
                <div class="col-lg-6">
                    <div class="card h-100 border p-4 shadow-sm">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="fw-bold text-dark mb-1">{{ $service->service_name }}</h5>
                                <span class="text-muted small">
                                    <i class="fa-solid fa-users-gear me-1 text-primary"></i> Team: <strong>{{ $service->assigned_team ?? 'Dedicated IT Team' }}</strong>
                                </span>
                            </div>
                            <div>
                                @if($service->status == 'Active')
                                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2 border border-success-subtle"><i class="fa-solid fa-circle-check me-1"></i> Active</span>
                                @elseif($service->status == 'In Progress')
                                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 border border-primary-subtle"><i class="fa-solid fa-spinner fa-spin me-1"></i> In Progress</span>
                                @elseif($service->status == 'Planning')
                                    <span class="badge bg-info-subtle text-info rounded-pill px-3 py-2 border border-info-subtle"><i class="fa-solid fa-compass-drafting me-1"></i> Planning</span>
                                @elseif($service->status == 'Completed')
                                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2 border border-success-subtle"><i class="fa-solid fa-check-double me-1"></i> Completed</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2 border">{{ $service->status }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- Scope / Description -->
                        <div class="bg-light rounded-3 p-3 mb-3 small text-secondary">
                            <strong class="d-block text-dark mb-1"><i class="fa-solid fa-file-lines me-1 text-muted"></i> Scope & Deliverables:</strong>
                            {{ $service->description ?: 'Ongoing development, deployment, and technical infrastructure support as configured by your account team.' }}
                        </div>

                        <!-- Timeline & Actions -->
                        <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-auto">
                            <div class="small">
                                <span class="text-muted">Delivery: </span>
                                <span class="fw-semibold text-dark">{{ $service->end_date ? $service->end_date->format('d M Y') : 'Active Lifecycle' }}</span>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#serviceModal{{ $service->id }}">
                                <i class="fa-solid fa-circle-info me-1"></i> Full Details
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted py-5">
                    <i class="fa-solid fa-folder-open fa-3x mb-3 text-muted opacity-50"></i>
                    <p class="mb-2 fs-5 fw-semibold text-dark">No projects assigned yet.</p>
                    <p class="text-muted small mb-0">Your administrator or account manager will configure your projects here shortly.</p>
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
                                <td class="text-muted">Assigned Team</td>
                                <td class="fw-semibold">: {{ $service->assigned_team ?? 'Dedicated IT Team' }}</td>
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
                            <i class="fa-solid fa-ticket me-1"></i> Raise Ticket for this Project
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endpush
