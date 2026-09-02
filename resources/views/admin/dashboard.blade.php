@extends('layouts.admin')

@section('title', 'RORIRI Dashboard - Admin Console')
@section('page_title', 'Dashboard')

@section('content')
<!-- Metric Cards Grid (Real System Modules) -->
<div class="row g-4 mb-4">
    <!-- Card 1: Total Clients -->
    <div class="col-xl-3 col-md-6">
        <a href="{{ route('admin.clients.index') }}" class="text-decoration-none">
            <div class="stat-card-roriri">
                <div>
                    <div class="stat-card-label">Total Clients</div>
                    <h3 class="stat-card-value">{{ $totalClients ?? 0 }}</h3>
                </div>
                <div class="stat-icon-wrapper bg-icon-blue">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
        </a>
    </div>

    <!-- Card 2: Active Clients -->
    <div class="col-xl-3 col-md-6">
        <a href="{{ route('admin.clients.index', ['status' => 'Active']) }}" class="text-decoration-none">
            <div class="stat-card-roriri">
                <div>
                    <div class="stat-card-label">Active Clients</div>
                    <h3 class="stat-card-value text-success">{{ $activeClients ?? 0 }}</h3>
                </div>
                <div class="stat-icon-wrapper bg-icon-green">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
        </a>
    </div>

    <!-- Card 3: Staff Team Members -->
    <div class="col-xl-3 col-md-6">
        <a href="{{ route('admin.staff.index') }}" class="text-decoration-none">
            <div class="stat-card-roriri">
                <div>
                    <div class="stat-card-label">Staff Team</div>
                    <h3 class="stat-card-value">{{ $totalStaff ?? 0 }}</h3>
                </div>
                <div class="stat-icon-wrapper bg-icon-cyan">
                    <i class="fa-solid fa-user-gear"></i>
                </div>
            </div>
        </a>
    </div>

    <!-- Card 4: Active Services -->
    <div class="col-xl-3 col-md-6">
        <a href="{{ route('admin.services.index') }}" class="text-decoration-none">
            <div class="stat-card-roriri">
                <div>
                    <div class="stat-card-label">Active Services</div>
                    <h3 class="stat-card-value">{{ $activeServices ?? 0 }}</h3>
                </div>
                <div class="stat-icon-wrapper bg-icon-amber">
                    <i class="fa-solid fa-briefcase"></i>
                </div>
            </div>
        </a>
    </div>

    <!-- Card 5: Total Tickets -->
    <div class="col-xl-3 col-md-6">
        <a href="{{ route('admin.tickets.index') }}" class="text-decoration-none">
            <div class="stat-card-roriri">
                <div>
                    <div class="stat-card-label">Support Tickets</div>
                    <h3 class="stat-card-value">{{ $totalTickets ?? 0 }}</h3>
                </div>
                <div class="stat-icon-wrapper bg-icon-yellow">
                    <i class="fa-solid fa-ticket"></i>
                </div>
            </div>
        </a>
    </div>

    <!-- Card 6: Open / In-Progress Tickets -->
    <div class="col-xl-3 col-md-6">
        <a href="{{ route('admin.tickets.index') }}" class="text-decoration-none">
            <div class="stat-card-roriri">
                <div>
                    <div class="stat-card-label">Pending Requests</div>
                    <h3 class="stat-card-value text-danger">{{ $openTickets ?? 0 }}</h3>
                </div>
                <div class="stat-icon-wrapper bg-icon-red">
                    <i class="fa-solid fa-clock"></i>
                </div>
            </div>
        </a>
    </div>

    <!-- Card 7: Documents Vault -->
    <div class="col-xl-3 col-md-6">
        <a href="{{ route('admin.documents.index') }}" class="text-decoration-none">
            <div class="stat-card-roriri">
                <div>
                    <div class="stat-card-label">Uploaded Documents</div>
                    <h3 class="stat-card-value">{{ $totalDocuments ?? 0 }}</h3>
                </div>
                <div class="stat-icon-wrapper bg-icon-teal">
                    <i class="fa-solid fa-file-lines"></i>
                </div>
            </div>
        </a>
    </div>

    <!-- Card 8: New This Month -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card-roriri">
            <div>
                <div class="stat-card-label">New This Month</div>
                <h3 class="stat-card-value text-primary">+{{ $newClientsThisMonth ?? 0 }}</h3>
            </div>
            <div class="stat-icon-wrapper bg-icon-purple">
                <i class="fa-solid fa-arrow-trend-up"></i>
            </div>
        </div>
    </div>
</div>

<!-- Operations & Analytics Row -->
<div class="row g-4 mb-4">
    <!-- Growth Chart -->
    <div class="col-lg-8">
        <div class="card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0 text-dark">Client Portfolio Growth</h5>
                <span class="badge bg-primary-subtle text-primary border px-3 py-1">Yearly Overview</span>
            </div>
            <div style="height: 280px;">
                <canvas id="clientAnalyticsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Onboarding -->
    <div class="col-lg-4">
        <div class="card p-4 h-100">
            <h5 class="fw-bold mb-3 text-dark border-bottom pb-2">Quick Onboarding</h5>
            <p class="text-muted small mb-4">Fast-track account creation with automatic OTP verification and credentials delivery:</p>

            <div class="d-flex flex-column gap-3">
                <a href="{{ route('admin.clients.create') }}" class="btn btn-primary py-2 fw-semibold d-flex align-items-center justify-content-center gap-2">
                    <i class="fa-solid fa-user-plus"></i> Onboard New Client (with OTP)
                </a>

                <a href="{{ route('admin.staff.create') }}" class="btn btn-outline-primary py-2 fw-semibold d-flex align-items-center justify-content-center gap-2">
                    <i class="fa-solid fa-user-gear"></i> Add Staff Member (with OTP)
                </a>

                <a href="{{ route('admin.tickets.index') }}" class="btn btn-light border py-2 fw-semibold d-flex align-items-center justify-content-center gap-2 text-dark">
                    <i class="fa-solid fa-ticket text-warning"></i> View Support Queue ({{ $openTickets ?? 0 }} Open)
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Recent Clients & Tickets -->
<div class="row g-4">
    <!-- Recent Clients -->
    <div class="col-lg-6">
        <div class="card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="fw-bold mb-0 text-dark">Recently Added Clients</h5>
                    <span class="text-muted" style="font-size: 11px;">Added within the last 30 days</span>
                </div>
                <a href="{{ route('admin.clients.index') }}" class="text-primary small fw-semibold text-decoration-none">View All &rarr;</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="small text-muted">
                            <th>Client Name</th>
                            <th>Contact</th>
                            <th>Joined Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentClients as $client)
                            <tr>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $client->client_name }}</div>
                                    <span class="text-muted small" style="font-size: 11px;">{{ $client->client_email }}</span>
                                </td>
                                <td class="small">{{ $client->primary_contact }}</td>
                                <td class="small text-muted">
                                    {{ $client->joined_date ? $client->joined_date->format('d M Y') : 'N/A' }}
                                </td>
                                <td>
                                    <span class="badge {{ $client->client_status === 'Active' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} rounded-pill px-2 py-1">
                                        {{ $client->client_status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-4 small">No new clients added in the past 30 days.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Support Tickets -->
    <div class="col-lg-6">
        <div class="card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0 text-dark">Recent Support Tickets</h5>
                <a href="{{ route('admin.tickets.index') }}" class="text-primary small fw-semibold text-decoration-none">View All &rarr;</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="small text-muted">
                            <th>Client / Subject</th>
                            <th>Priority</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTickets as $ticket)
                            <tr>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $ticket->subject }}</div>
                                    <span class="text-muted small">{{ $ticket->client->client_company ?? 'Client' }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $ticket->priority }}</span>
                                </td>
                                <td>
                                    @if($ticket->status === 'Resolved')
                                        <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1">Resolved</span>
                                    @elseif($ticket->status === 'In Progress')
                                        <span class="badge bg-warning-subtle text-warning rounded-pill px-2 py-1">In Progress</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-1">Open</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted py-3">No support tickets found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('clientAnalyticsChart').getContext('2d');
        const labels = {!! json_encode($chartLabels) !!};
        const data = {!! json_encode($chartData) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Client Enrolments',
                    data: data,
                    borderColor: '#0284c7',
                    backgroundColor: 'rgba(2, 132, 199, 0.08)',
                    borderWidth: 3,
                    pointBackgroundColor: '#0284c7',
                    pointRadius: 4,
                    tension: 0.35,
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
                    y: {
                        beginAtZero: true,
                        suggestedMin: 0,
                        suggestedMax: 15,
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            stepSize: 5,
                            precision: 0
                        }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    });
</script>
@endsection
