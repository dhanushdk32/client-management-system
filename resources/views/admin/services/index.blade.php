@extends('layouts.admin')

@section('title', 'Services - Admin Portal')
@section('page_title', 'Services Management')

@section('content')
<div class="card">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0">Assigned Services</h5>
            <a href="{{ route('admin.services.create') }}" class="btn btn-primary px-4 rounded-3"><i class="fa-solid fa-plus me-2"></i> Assign Service</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success py-2">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Client</th>
                        <th>Service Name</th>
                        <th>Status</th>
                        <th>Timeline</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $service)
                        <tr>
                            <td>
                                <div class="fw-medium">{{ $service->client->client_company ?? 'N/A' }}</div>
                                <div class="small text-muted">ID: CL{{ sprintf('%03d', $service->client_id) }}</div>
                            </td>
                            <td class="fw-medium">{{ $service->service_name }}</td>
                            <td>
                                <span class="badge bg-{{ $service->status == 'Active' ? 'success' : ($service->status == 'Pending' ? 'warning' : 'secondary') }}-subtle text-{{ $service->status == 'Active' ? 'success' : ($service->status == 'Pending' ? 'warning' : 'secondary') }} rounded-pill px-3 py-2 border border-{{ $service->status == 'Active' ? 'success' : ($service->status == 'Pending' ? 'warning' : 'secondary') }}-subtle">
                                    {{ $service->status }}
                                </span>
                            </td>
                            <td class="small">
                                <div><span class="text-muted">Start:</span> {{ $service->start_date ? $service->start_date->format('d M Y') : 'N/A' }}</div>
                                <div><span class="text-muted">End:</span> {{ $service->end_date ? $service->end_date->format('d M Y') : 'N/A' }}</div>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 me-2"><i class="fa-solid fa-pen"></i></a>
                                <form action="{{ route('admin.services.destroy', $service) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this service?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No services assigned yet.</td>
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
