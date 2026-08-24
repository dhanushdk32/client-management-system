@extends('layouts.admin')

@section('title', 'Activity Logs - Admin Portal')
@section('page_title', 'System Activity Logs')

@section('content')
<div class="card">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-4">All System Activity</h5>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Date & Time</th>
                        <th>User / Source</th>
                        <th>Module</th>
                        <th>Action</th>
                        <th>Description</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $log)
                        <tr>
                            <td class="small text-muted">{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y, h:i A') }}</td>
                            <td>
                                @if($log->admin_id)
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1"><i class="fa-solid fa-user-shield me-1"></i> Admin ({{ $log->admin->username ?? 'Admin' }})</span>
                                @elseif($log->user_id)
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1"><i class="fa-solid fa-user me-1"></i> Client User ({{ $log->user->username ?? 'User' }})</span>
                                @elseif($log->client_id)
                                    <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1"><i class="fa-solid fa-building me-1"></i> {{ $log->client->client_company ?? 'Client' }}</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">System</span>
                                @endif
                            </td>
                            <td><span class="badge bg-light text-dark border">{{ $log->module }}</span></td>
                            <td class="fw-medium text-dark">{{ $log->action }}</td>
                            <td class="small text-muted">{{ $log->description }}</td>
                            <td class="small font-monospace">{{ $log->ip_address }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="fa-solid fa-clock-rotate-left fa-3x mb-3 text-light"></i>
                                <p class="mb-0">No system activity found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $activities->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
