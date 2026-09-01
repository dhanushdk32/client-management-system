@extends('layouts.admin')

@section('title', 'Client Projects - Admin Portal')
@section('page_title', 'Client Projects & Services')

@section('content')
<div class="card">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="fw-bold mb-1 text-primary border-bottom border-2 border-primary pb-2 d-inline-block">Client Projects & Contracted Services</h5>
                <p class="text-muted small mb-0">Manage and assign contracted projects, delivery timelines, and engineering teams to clients.</p>
            </div>
            <a href="{{ route('admin.services.create') }}" class="btn btn-primary px-4 rounded-3 fw-semibold">
                <i class="fa-solid fa-plus me-1"></i> Assign New Project
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success py-2 px-3 small rounded-3 mb-4">
                <i class="fa-solid fa-circle-check me-1"></i> {{ session('success') }}
            </div>
        @endif

        <!-- Filter Bar -->
        <div class="d-flex justify-content-between mb-4">
            <form action="{{ route('admin.services.index') }}" method="GET" class="d-flex gap-2 w-75">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-search text-muted"></i></span>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control border-start-0 ps-0" placeholder="Search by project name or description...">
                </div>
                <select name="client_id" class="form-select w-auto">
                    <option value="">All Clients</option>
                    @foreach($clients as $c)
                        <option value="{{ $c->client_id }}" {{ request('client_id') == $c->client_id ? 'selected' : '' }}>
                            {{ $c->client_company }}
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
                <button type="submit" class="btn btn-outline-secondary">Filter</button>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr class="small text-muted">
                        <th>Client / Organization</th>
                        <th>Project / Service</th>
                        <th>Assigned Team / Lead</th>
                        <th>Status</th>
                        <th>Delivery Timeline</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $service)
                        <tr>
                            <td>
                                <div class="fw-semibold text-dark">{{ $service->client->client_company ?? 'N/A' }}</div>
                                <span class="text-muted small">ID: #CL{{ sprintf('%03d', $service->client_id) }} ({{ $service->client->client_name ?? '' }})</span>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $service->service_name }}</div>
                                @if($service->description)
                                    <span class="text-muted small text-truncate d-inline-block" style="max-width: 260px;">
                                        {{ $service->description }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    <i class="fa-solid fa-users-gear me-1 text-primary"></i> {{ $service->assigned_team ?? 'Engineering Team' }}
                                </span>
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
                                <div><span class="text-muted">Start:</span> {{ $service->start_date ? $service->start_date->format('d M Y') : 'N/A' }}</div>
                                <div><span class="text-muted">Target:</span> <strong>{{ $service->end_date ? $service->end_date->format('d M Y') : 'N/A' }}</strong></div>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3" title="Edit">
                                        <i class="fa-solid fa-pen"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.services.destroy', $service) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this project?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3" title="Delete">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="fa-solid fa-briefcase fa-3x mb-2 text-muted opacity-50"></i>
                                <p class="mb-0">No client projects configured yet. Click "Assign New Project" above to create one.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $services->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
