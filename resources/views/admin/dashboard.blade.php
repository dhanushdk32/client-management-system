@extends('layouts.admin')

@section('title', 'RORIRI Dashboard - Admin Console')
@section('page_title', 'Dashboard')

@section('content')
<!-- RORIRI 4x3 Metric Cards Grid (From Screenshot) -->
<div class="row g-4 mb-4">
    <!-- Card 1: Projects -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card-roriri">
            <div>
                <div class="stat-card-label">Projects</div>
                <h3 class="stat-card-value">&#8377; 0.00</h3>
            </div>
            <div class="stat-icon-wrapper bg-icon-yellow">
                <i class="fa-solid fa-briefcase"></i>
            </div>
        </div>
    </div>

    <!-- Card 2: Internship this Month -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card-roriri">
            <div>
                <div class="stat-card-label">Internship this Month</div>
                <h3 class="stat-card-value">&#8377; 0.00</h3>
            </div>
            <div class="stat-icon-wrapper bg-icon-cyan">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
        </div>
    </div>

    <!-- Card 3: Workshop -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card-roriri">
            <div>
                <div class="stat-card-label">Workshop</div>
                <h3 class="stat-card-value">&#8377; 0.00</h3>
            </div>
            <div class="stat-icon-wrapper bg-icon-amber">
                <i class="fa-solid fa-gear"></i>
            </div>
        </div>
    </div>

    <!-- Card 4: Industrial Visit Revenue -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card-roriri">
            <div>
                <div class="stat-card-label">Industrial Visit Revenue</div>
                <h3 class="stat-card-value">&#8377; 0.00</h3>
            </div>
            <div class="stat-icon-wrapper bg-icon-blue">
                <i class="fa-solid fa-building"></i>
            </div>
        </div>
    </div>

    <!-- Card 5: NexGen IT Academy this Month -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card-roriri">
            <div>
                <div class="stat-card-label">NexGen IT Academy this Month</div>
                <h3 class="stat-card-value">&#8377; 0.00</h3>
            </div>
            <div class="stat-icon-wrapper bg-icon-blue">
                <i class="fa-solid fa-building-columns"></i>
            </div>
        </div>
    </div>

    <!-- Card 6: NexGen IT College -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card-roriri">
            <div>
                <div class="stat-card-label">NexGen IT College</div>
                <h3 class="stat-card-value">&#8377; 0.00</h3>
            </div>
            <div class="stat-icon-wrapper bg-icon-teal">
                <i class="fa-solid fa-book-bookmark"></i>
            </div>
        </div>
    </div>

    <!-- Card 7: Nexemy -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card-roriri">
            <div>
                <div class="stat-card-label">Nexemy</div>
                <h3 class="stat-card-value">&#8377; 0.00</h3>
            </div>
            <div class="stat-icon-wrapper bg-icon-green">
                <i class="fa-solid fa-crosshairs"></i>
            </div>
        </div>
    </div>

    <!-- Card 8: Riya IAS Academy -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card-roriri">
            <div>
                <div class="stat-card-label">Riya IAS Academy</div>
                <h3 class="stat-card-value">&#8377; 0.00</h3>
            </div>
            <div class="stat-icon-wrapper bg-icon-cyan">
                <i class="fa-solid fa-book-open"></i>
            </div>
        </div>
    </div>

    <!-- Card 9: Total Income For Rithish Farms -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card-roriri">
            <div>
                <div class="stat-card-label">Total Income For Rithish Farms</div>
                <h3 class="stat-card-value">&#8377; 0.00</h3>
            </div>
            <div class="stat-icon-wrapper bg-icon-amber">
                <i class="fa-solid fa-store"></i>
            </div>
        </div>
    </div>

    <!-- Card 10: Total Income For Roriri Foundation -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card-roriri">
            <div>
                <div class="stat-card-label">Total Income For Roriri Foundation</div>
                <h3 class="stat-card-value">&#8377; 0.00</h3>
            </div>
            <div class="stat-icon-wrapper bg-icon-red">
                <i class="fa-regular fa-heart"></i>
            </div>
        </div>
    </div>

    <!-- Card 11: Total Income For The Month -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card-roriri">
            <div>
                <div class="stat-card-label">Total Income For The Month</div>
                <h3 class="stat-card-value">&#8377; 0.00</h3>
            </div>
            <div class="stat-icon-wrapper bg-icon-green">
                <i class="fa-solid fa-wallet"></i>
            </div>
        </div>
    </div>

    <!-- Card 12: Total Expenses For The Month -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card-roriri">
            <div>
                <div class="stat-card-label">Total Expenses For The Month</div>
                <h3 class="stat-card-value text-danger">&#8377; 0.00</h3>
            </div>
            <div class="stat-icon-wrapper bg-icon-red">
                <i class="fa-solid fa-tags"></i>
            </div>
        </div>
    </div>
</div>

<!-- Operations & Client Activity Section -->
<div class="row g-4 mb-4">
    <!-- Client Growth Chart -->
    <div class="col-lg-8">
        <div class="card p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0 text-dark">Client Growth & Enrollment Analytics</h5>
                <span class="badge bg-primary-subtle text-primary border px-3 py-1">Active Portfolio</span>
            </div>
            <div style="height: 300px;">
                <canvas id="clientAnalyticsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Operations Overview -->
    <div class="col-lg-4">
        <div class="card p-4">
            <h5 class="fw-bold mb-3 text-dark border-bottom pb-2">Portfolio Overview</h5>
            
            <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-primary-subtle text-primary p-2">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <span class="fw-semibold">Total Clients</span>
                </div>
                <span class="badge bg-primary fs-6 px-3 py-1">{{ $totalClients ?? 0 }}</span>
            </div>

            <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-success-subtle text-success p-2">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <span class="fw-semibold">Active Clients</span>
                </div>
                <span class="badge bg-success fs-6 px-3 py-1">{{ $activeClients ?? 0 }}</span>
            </div>

            <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-info-subtle text-info p-2">
                        <i class="fa-solid fa-user-gear"></i>
                    </div>
                    <span class="fw-semibold">Staff Team</span>
                </div>
                <span class="badge bg-info text-dark fs-6 px-3 py-1">{{ \App\Models\StaffMember::count() }}</span>
            </div>

            <div class="d-flex align-items-center justify-content-between py-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-warning-subtle text-warning p-2">
                        <i class="fa-solid fa-ticket"></i>
                    </div>
                    <span class="fw-semibold">Active Tickets</span>
                </div>
                <span class="badge bg-warning text-dark fs-6 px-3 py-1">{{ \App\Models\SupportTicket::whereIn('status', ['Open', 'In Progress'])->count() }}</span>
            </div>

            <div class="mt-4">
                <a href="{{ route('admin.clients.create') }}" class="btn btn-primary w-100 py-2 fw-semibold">
                    <i class="fa-solid fa-plus me-1"></i> Add New Client (with OTP)
                </a>
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
                        grid: { color: '#f1f5f9' },
                        ticks: { precision: 0 }
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
