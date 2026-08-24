@extends('layouts.client')

@section('title', 'Activity Logs - Client Portal')
@section('page_title', 'Activity History')

@section('content')
<div class="card">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-4">Recent Account Activity</h5>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Date & Time</th>
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
                            <td><span class="badge bg-light text-dark border">{{ $log->module }}</span></td>
                            <td class="fw-medium text-dark">{{ $log->action }}</td>
                            <td class="small text-muted">{{ $log->description }}</td>
                            <td class="small font-monospace">{{ $log->ip_address }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                <i class="fa-solid fa-clock-rotate-left fa-3x mb-3 text-light"></i>
                                <p class="mb-0">No recent activity found.</p>
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
