@extends('layouts.client')

@section('title', 'Dashboard - Client Portal')
@section('page_title', 'Welcome, ' . ($client->client_company ?? 'Client'))

@section('content')
<div class="row g-3 mb-4">
    <!-- Stat Cards -->
    <div class="col-md-3">
        <div class="stat-card-roriri">
            <div>
                <div class="stat-card-label text-uppercase">My Services</div>
                <h3 class="stat-card-value text-primary mb-0">{{ $servicesCount }}</h3>
            </div>
            <div class="stat-icon-wrapper bg-icon-cyan">
                <i class="fa-solid fa-briefcase"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card-roriri">
            <div>
                <div class="stat-card-label text-uppercase">Documents</div>
                <h3 class="stat-card-value text-success mb-0">{{ $documentsVerified }} / {{ $documentsTotal }}</h3>
            </div>
            <div class="stat-icon-wrapper bg-icon-green">
                <i class="fa-solid fa-file-shield"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card-roriri">
            <div>
                <div class="stat-card-label text-uppercase">Open Requests</div>
                <h3 class="stat-card-value text-warning mb-0">{{ $openRequests }}</h3>
            </div>
            <div class="stat-icon-wrapper bg-icon-yellow">
                <i class="fa-solid fa-ticket"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card-roriri">
            <div>
                <div class="stat-card-label text-uppercase">Notifications</div>
                <h3 class="stat-card-value text-danger mb-0">{{ $unreadNotifications }}</h3>
            </div>
            <div class="stat-icon-wrapper bg-icon-red">
                <i class="fa-regular fa-bell"></i>
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
