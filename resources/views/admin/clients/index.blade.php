@extends('layouts.admin')

@section('title', 'Clients - Admin Portal')
@section('page_title', 'Client Directory')

@section('content')
<div class="card">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="fw-bold mb-1 text-primary border-bottom border-2 border-primary pb-2 d-inline-block">Client Directory</h5>
                <p class="text-muted small mb-0">Manage registered clients, project subscriptions, contact details, and account access.</p>
            </div>
            <a href="{{ route('admin.clients.create') }}" class="btn btn-primary px-4 rounded-3 fw-semibold">
                <i class="fa-solid fa-plus me-1"></i> Add New Client
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success py-2 px-3 small rounded-3 mb-4">
                <i class="fa-solid fa-circle-check me-1"></i> {{ session('success') }}
            </div>
        @endif

        <div class="d-flex justify-content-between mb-4">
            <form action="{{ route('admin.clients.index') }}" method="GET" class="d-flex gap-2 w-50">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-search text-muted"></i></span>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control border-start-0 ps-0" placeholder="Search by name, phone, email, project, or city...">
                </div>
                <select name="status" class="form-select w-auto">
                    <option value="">All Status</option>
                    <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                <button type="submit" class="btn btn-outline-secondary">Filter</button>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr class="small text-muted">
                        <th>Client ID</th>
                        <th>Client Details</th>
                        <th>Contact Info</th>
                        <th>Location</th>
                        <th>Joined Date</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $client)
                        <tr>
                            <td class="fw-bold text-primary">#CL{{ sprintf('%03d', $client->client_id) }}</td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $client->client_name }}</div>
                                @if($client->services->isNotEmpty())
                                    <span class="text-muted small" style="font-size: 11px;">
                                        <i class="fa-solid fa-briefcase me-1 text-primary"></i> {{ $client->services->first()->service_name }}
                                    </span>
                                @elseif($client->client_company && $client->client_company !== $client->client_name)
                                    <span class="text-muted small" style="font-size: 11px;">
                                        <i class="fa-solid fa-briefcase me-1 text-primary"></i> {{ $client->client_company }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="small fw-semibold text-dark"><i class="fa-solid fa-phone me-1 text-muted"></i> {{ $client->primary_contact }}</div>
                                <div class="small text-muted"><i class="fa-solid fa-envelope me-1 text-muted"></i> {{ $client->client_email }}</div>
                            </td>
                            <td>
                                <div class="small text-dark">{{ $client->city ?: ($client->client_location ?: 'N/A') }}</div>
                                @if($client->state)
                                    <span class="text-muted small" style="font-size: 11px;">{{ $client->state }}, {{ $client->country ?? 'India' }}</span>
                                @endif
                            </td>
                            <td class="small text-muted">
                                {{ $client->joined_date ? $client->joined_date->format('d M Y') : 'N/A' }}
                            </td>
                            <td>
                                @if($client->client_status == 'Active')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1"><i class="fa-solid fa-circle-check me-1"></i> Active</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1"><i class="fa-solid fa-circle-xmark me-1"></i> Inactive</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end align-items-center gap-2">
                                    <a href="{{ route('admin.clients.show', $client) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3"><i class="fa-solid fa-eye"></i> View</a>
                                    <a href="{{ route('admin.clients.edit', $client) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3"><i class="fa-solid fa-pen"></i> Edit</a>
                                    <form action="{{ route('admin.clients.destroy', $client) }}" method="POST" class="m-0 p-0" onsubmit="return confirm('Are you sure you want to delete this client?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="fa-solid fa-users-slash fa-3x mb-2 text-muted opacity-50"></i>
                                <p class="mb-0">No clients registered yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 d-flex justify-content-between align-items-center">
            <div class="text-muted small">
                Showing {{ $clients->firstItem() ?? 0 }} to {{ $clients->lastItem() ?? 0 }} of {{ $clients->total() }} entries
            </div>
            <div>
                {{ $clients->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
