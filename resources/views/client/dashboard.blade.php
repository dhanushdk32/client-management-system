@extends('layouts.client')

@section('title', 'Dashboard - Client Portal')
@section('page_title', 'Welcome, ' . ($client->client_company ?? 'Client'))

@section('content')
<!-- Top Metric Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
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
    <div class="col-md-3 col-6">
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
    <div class="col-md-3 col-6">
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
    <div class="col-md-3 col-6">
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

<!-- 🌟 Dedicated Assigned Project Team Leader Banner -->
@if(isset($primaryTeamLeader) && $primaryTeamLeader)
    <div class="card border-0 shadow-sm mb-4 bg-primary text-white p-4 rounded-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center fw-bold shadow" style="width: 52px; height: 52px; font-size: 20px;">
                    {{ strtoupper(substr($primaryTeamLeader->name, 0, 2)) }}
                </div>
                <div>
                    <span class="badge bg-white bg-opacity-25 text-white rounded-pill px-3 py-1 mb-1 font-monospace" style="font-size: 11px;">
                        YOUR DEDICATED TEAM LEADER
                    </span>
                    <h5 class="fw-bold mb-0 text-white">{{ $primaryTeamLeader->name }}</h5>
                    <small class="text-white-50">{{ $primaryTeamLeader->designation ?? 'Lead Technical Project Manager' }} &bull; {{ $primaryTeamLeader->department ?? 'Engineering' }}</small>
                </div>
            </div>
            <div>
                <a href="{{ route('client.tickets.index') }}" class="btn btn-light text-primary fw-bold rounded-pill px-4 shadow-sm">
                    <i class="fa-solid fa-comment-dots me-1"></i> Start Conversation with Team Lead
                </a>
            </div>
        </div>
    </div>
@endif

<div class="row g-4 mb-4">
    <!-- My Services List with Team Lead Indicators -->
    <div class="col-md-6">
        <div class="card p-4 h-100 shadow-sm border-0">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-briefcase me-1 text-primary"></i> My Active Projects & Services</h6>
                <a href="{{ route('client.services.index') }}" class="text-decoration-none small fw-semibold">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-borderless align-middle mb-0">
                    <tbody>
                        @forelse($recentServices as $service)
                            <tr class="border-bottom">
                                <td class="fw-semibold text-dark ps-0">
                                    {{ $service->service_name }}
                                    <small class="text-muted d-block" style="font-size: 11px;">
                                        <i class="fa-solid fa-user-tie text-primary me-1"></i> Lead: {{ $service->teamLeader->name ?? 'Assigned Engineer' }}
                                    </small>
                                </td>
                                <td class="text-end pe-0">
                                    <span class="badge {{ $service->status == 'Active' ? 'bg-success-subtle text-success' : 'bg-primary-subtle text-primary' }} rounded-pill px-3 py-1">
                                        {{ $service->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="text-muted text-center py-4">No active contracted services</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Recent Notifications -->
    <div class="col-md-6">
        <div class="card p-4 h-100 shadow-sm border-0">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0 text-dark"><i class="fa-regular fa-bell me-1 text-primary"></i> Recent Notifications</h6>
                <a href="{{ route('client.notifications.index') }}" class="text-decoration-none small fw-semibold">View All</a>
            </div>
            <div class="notification-list">
                @forelse($recentNotifications as $notification)
                    <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                        <div class="mt-1">
                            <i class="fa-solid fa-circle text-{{ $notification->is_read ? 'muted' : 'primary' }}" style="font-size: 8px;"></i>
                        </div>
                        <div class="w-100">
                            <div class="d-flex justify-content-between">
                                <span class="text-sm fw-medium {{ $notification->is_read ? 'text-muted' : 'text-dark fw-bold' }}">{{ $notification->title }}</span>
                                <span class="text-muted small" style="font-size: 11px;">{{ \Carbon\Carbon::parse($notification->created_at)->format('d M Y') }}</span>
                            </div>
                            <div class="text-muted small mt-1">{{ Str::limit($notification->message, 60) }}</div>
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
        <div class="card p-4 shadow-sm border-0">
            <h6 class="fw-bold mb-4 text-dark"><i class="fa-solid fa-clock-rotate-left me-1 text-primary"></i> Recent Platform Activity</h6>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="small text-muted">
                            <th>Activity Description</th>
                            <th>Category</th>
                            <th>Date & Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentActivity as $activity)
                            <tr>
                                <td class="fw-semibold text-dark">{{ $activity->description }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $activity->module }}</span></td>
                                <td class="small text-muted">{{ \Carbon\Carbon::parse($activity->created_at)->format('d M Y, h:i A') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted py-4">No recent activity logs recorded.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
