@extends('layouts.client')

@section('title', 'My Services - Client Portal')
@section('page_title', 'My Services')

@section('content')
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stat-card text-center text-primary h-100">
            <div class="stat-title">Total Services</div>
            <div class="stat-value">{{ $services->count() }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card text-center text-success h-100">
            <div class="stat-title">Active Services</div>
            <div class="stat-value">{{ $services->where('status', 'Active')->count() }}</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-4">Service Details</h5>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Service Name</th>
                        <th>Status</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $service)
                        <tr>
                            <td class="fw-medium">{{ $service->service_name }}</td>
                            <td>
                                @if($service->status == 'Active')
                                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2 border border-success-subtle">Active</span>
                                @elseif($service->status == 'Pending')
                                    <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2 border border-warning-subtle">Pending</span>
                                @elseif($service->status == 'Completed')
                                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 border border-primary-subtle">Completed</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2 border border-secondary-subtle">{{ $service->status }}</span>
                                @endif
                            </td>
                            <td>{{ $service->start_date ? $service->start_date->format('d M Y') : 'N/A' }}</td>
                            <td>{{ $service->end_date ? $service->end_date->format('d M Y') : 'N/A' }}</td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#serviceModal{{ $service->id }}">
                                    <i class="fa-solid fa-eye me-1"></i> View
                                </button>
                            </td>
                        </tr>

                        <!-- Modal for Service Details -->
                        <div class="modal fade" id="serviceModal{{ $service->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header border-bottom-0 pb-0">
                                        <h5 class="modal-title fw-bold">{{ $service->service_name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body pt-4">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td class="text-muted w-50">Status</td>
                                                <td class="fw-medium">: 
                                                    <span class="badge bg-{{ $service->status == 'Active' ? 'success' : ($service->status == 'Pending' ? 'warning' : 'primary') }}-subtle text-{{ $service->status == 'Active' ? 'success' : ($service->status == 'Pending' ? 'warning' : 'primary') }} px-2 py-1">{{ $service->status }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Start Date</td>
                                                <td class="fw-medium">: {{ $service->start_date ? $service->start_date->format('d M Y') : 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">End Date</td>
                                                <td class="fw-medium">: {{ $service->end_date ? $service->end_date->format('d M Y') : 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Assigned Team</td>
                                                <td class="fw-medium">: {{ $service->assigned_team ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted" colspan="2">Description</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" class="fw-medium border rounded bg-light p-3">{{ $service->description ?? 'No description available.' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="modal-footer border-top-0 pt-0">
                                        <button type="button" class="btn btn-secondary rounded-3 px-4" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No services assigned yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
