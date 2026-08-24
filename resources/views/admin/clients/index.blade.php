@extends('layouts.admin')

@section('title', 'Clients - Admin Portal')
@section('page_title', 'Clients')

@section('content')
<div class="card">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0">Clients List</h5>
            <a href="{{ route('admin.clients.create') }}" class="btn btn-primary px-4 rounded-3"><i class="fa-solid fa-plus me-2"></i> Add Client</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success py-2">{{ session('success') }}</div>
        @endif

        <div class="d-flex justify-content-between mb-4">
            <form action="{{ route('admin.clients.index') }}" method="GET" class="d-flex gap-2 w-50">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-search text-muted"></i></span>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control border-start-0 ps-0" placeholder="Search clients...">
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
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Client ID</th>
                        <th>Company Name</th>
                        <th>Contact Person</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $client)
                        <tr>
                            <td class="fw-medium text-primary">#CL{{ sprintf('%03d', $client->client_id) }}</td>
                            <td>{{ $client->client_company }}</td>
                            <td>{{ $client->client_name }}</td>
                            <td>
                                @if($client->client_status == 'Active')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2">Active</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-2">Inactive</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end align-items-center gap-2">
                                    <a href="{{ route('admin.clients.show', $client) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3"><i class="fa-solid fa-eye"></i> View</a>
                                    <a href="{{ route('admin.clients.edit', $client) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3"><i class="fa-solid fa-pen"></i></a>
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
                            <td colspan="5" class="text-center text-muted py-4">No clients found.</td>
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
