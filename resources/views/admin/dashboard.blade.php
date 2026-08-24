@extends('layouts.admin')

@section('title', 'Dashboard - Admin Portal')
@section('page_title', 'Dashboard')

@section('content')
<div class="row">
    <!-- Stat Cards -->
    <div class="col-md-3">
        <div class="card stat-card blue">
            <div class="stat-title">Total Clients</div>
            <div class="stat-value">{{ $totalClients }}</div>
            <div class="stat-trend {{ $totalThisMonth > 0 ? 'positive' : 'text-muted' }}"><i class="fa-solid fa-arrow-{{ $totalThisMonth > 0 ? 'up' : 'right' }}"></i> {{ $totalThisMonth }} this month</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card green">
            <div class="stat-title">Active Clients</div>
            <div class="stat-value">{{ $activeClients }}</div>
            <div class="stat-trend {{ $activeThisMonth > 0 ? 'positive' : 'text-muted' }}"><i class="fa-solid fa-arrow-{{ $activeThisMonth > 0 ? 'up' : 'right' }}"></i> {{ $activeThisMonth }} this month</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card orange">
            <div class="stat-title">Pending Clients</div>
            <div class="stat-value">{{ $pendingClients }}</div>
            <div class="stat-trend {{ $pendingThisMonth > 0 ? 'orange' : 'text-muted' }}"><i class="fa-solid fa-arrow-{{ $pendingThisMonth > 0 ? 'up text-warning' : 'right' }}"></i> {{ $pendingThisMonth }} this month</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card red">
            <div class="stat-title">Inactive Clients</div>
            <div class="stat-value">{{ $inactiveClients }}</div>
            <div class="stat-trend {{ $inactiveThisMonth > 0 ? 'negative' : 'text-muted' }}"><i class="fa-solid fa-arrow-{{ $inactiveThisMonth > 0 ? 'up' : 'right' }}"></i> {{ $inactiveThisMonth }} this month</div>
        </div>
    </div>
</div>

<div class="row mt-2">
    <!-- Chart -->
    <div class="col-md-6">
        <div class="card p-4 h-100">
            <h6 class="fw-bold mb-4">{{ $chartTitle ?? 'New Clients' }}</h6>
            <div style="position: relative; height: 300px; width: 100%;">
                <canvas id="clientsChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Recent Activities -->
    <div class="col-md-6">
        <div class="card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h6 class="fw-bold mb-0">Recent Activities</h6>
                <a href="#" class="text-decoration-none small fw-semibold">View All</a>
            </div>
            <div class="activity-list">
                @forelse($recentActivities as $activity)
                    <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                        <div class="text-sm">
                            <span class="fw-medium">{{ $activity->action ?? 'Action performed' }}</span> 
                            <span class="text-muted">{{ Str::limit($activity->description ?? '', 40) }}</span>
                        </div>
                        <div class="text-muted small">
                            {{ \Carbon\Carbon::parse($activity->created_at)->format('d M Y') }}
                        </div>
                    </div>
                @empty
                    <div class="text-muted text-center py-4">No recent activities found.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('clientsChart').getContext('2d');
        var clientsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'New Clients',
                    data: {!! json_encode($chartData) !!},
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true, max: 30, ticks: { stepSize: 10 } },
                    x: { grid: { display: false } }
                }
            }
        });
    });
</script>
@endsection
