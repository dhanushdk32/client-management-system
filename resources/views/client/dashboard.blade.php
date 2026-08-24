@extends('layouts.client')

@section('title', 'Dashboard - Client Portal')
@section('page_title', 'Welcome, ' . ($client->client_company ?? 'Client'))

@section('content')
<div class="row">
    <!-- Stat Cards -->
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-title text-muted text-uppercase mb-1" style="font-size: 12px; letter-spacing: 0.5px;">My Services</div>
                    <div class="stat-value text-dark mb-0" style="font-size: 24px;">{{ $servicesCount }}</div>
                </div>
                <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="fa-solid fa-briefcase fs-5"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-title text-muted text-uppercase mb-1" style="font-size: 12px; letter-spacing: 0.5px;">Documents</div>
                    <div class="stat-value text-dark mb-0" style="font-size: 24px;">{{ $documentsVerified }} / {{ $documentsTotal }}</div>
                </div>
                <div class="bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="fa-solid fa-file-shield fs-5"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-title text-muted text-uppercase mb-1" style="font-size: 12px; letter-spacing: 0.5px;">Open Requests</div>
                    <div class="stat-value text-dark mb-0" style="font-size: 24px;">{{ $openRequests }}</div>
                </div>
                <div class="bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="fa-solid fa-ticket fs-5"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-title text-muted text-uppercase mb-1" style="font-size: 12px; letter-spacing: 0.5px;">Notifications</div>
                    <div class="stat-value text-dark mb-0" style="font-size: 24px;">{{ $unreadNotifications }}</div>
                </div>
                <div class="bg-danger-subtle text-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="fa-regular fa-bell fs-5"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-2">
    <!-- My Services List -->
    <div class="col-md-6 mb-4">
        <div class="card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h6 class="fw-bold mb-0">My Services</h6>
                <a href="#" class="text-decoration-none small fw-semibold">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-borderless align-middle">
                    <tbody>
                        @forelse($recentServices as $service)
                            <tr class="border-bottom">
                                <td class="fw-medium ps-0">{{ $service->service_name }}</td>
                                <td class="text-end pe-0">
                                    <span class="badge {{ $service->status == 'Active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }} rounded-pill px-3 py-1">
                                        {{ $service->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="text-muted text-center py-3">No active services</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Recent Notifications -->
    <div class="col-md-6 mb-4">
        <div class="card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h6 class="fw-bold mb-0">Recent Notifications</h6>
                <a href="#" class="text-decoration-none small fw-semibold">View All</a>
            </div>
            <div class="notification-list">
                @forelse($recentNotifications as $notification)
                    <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                        <div class="mt-1">
                            <i class="fa-solid fa-circle text-{{ $notification->is_read ? 'muted' : 'primary' }}" style="font-size: 8px;"></i>
                        </div>
                        <div class="w-100">
                            <div class="d-flex justify-content-between">
                                <span class="text-sm fw-medium {{ $notification->is_read ? 'text-muted' : '' }}">{{ $notification->title }}</span>
                                <span class="text-muted small">{{ \Carbon\Carbon::parse($notification->created_at)->format('d M Y') }}</span>
                            </div>
                            <div class="text-muted small mt-1">{{ Str::limit($notification->message, 50) }}</div>
                        </div>
                    </div>
                @empty
                    <div class="text-muted text-center py-4">No new notifications.</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="col-md-12">
        <div class="card p-4">
            <h6 class="fw-bold mb-4">Recent Activity</h6>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Action</th>
                            <th>Module</th>
                            <th>Date & Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentActivity as $activity)
                            <tr>
                                <td>{{ $activity->description }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $activity->module }}</span></td>
                                <td>{{ \Carbon\Carbon::parse($activity->created_at)->format('d M Y h:i A') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted py-4">No recent activity.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
