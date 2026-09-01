@extends('layouts.staff')

@section('title', 'Staff Dashboard - IT Operations')
@section('page_title', 'Engineering & Operations Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <!-- Stat 1: Assigned Clients -->
    <div class="col-md-4">
        <div class="card p-4 border-0 shadow-sm bg-white rounded-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-semibold text-uppercase tracking-wider">My Assigned Clients</div>
                    <h2 class="fw-bold mb-0 text-primary mt-2">{{ $assignedClients->count() }}</h2>
                </div>
                <div class="p-3 bg-primary-subtle text-primary rounded-circle">
                    <i class="fa-solid fa-building fa-2x"></i>
                </div>
            </div>
            <div class="mt-3">
                <a href="{{ route('staff.clients.index') }}" class="text-decoration-none small fw-semibold text-primary">
                    View Assigned Clients <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Stat 2: Active / Open Tickets -->
    <div class="col-md-4">
        <div class="card p-4 border-0 shadow-sm bg-white rounded-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-semibold text-uppercase tracking-wider">Active Support Tickets</div>
                    <h2 class="fw-bold mb-0 text-warning mt-2">{{ $assignedTicketsCount }}</h2>
                </div>
                <div class="p-3 bg-warning-subtle text-warning rounded-circle">
                    <i class="fa-solid fa-ticket fa-2x"></i>
                </div>
            </div>
            <div class="mt-3">
                <a href="{{ route('staff.tickets.index', ['status' => 'Open']) }}" class="text-decoration-none small fw-semibold text-warning">
                    Open Ticket Desk <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Stat 3: Resolved Tickets -->
    <div class="col-md-4">
        <div class="card p-4 border-0 shadow-sm bg-white rounded-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-semibold text-uppercase tracking-wider">Resolved Tickets</div>
                    <h2 class="fw-bold mb-0 text-success mt-2">{{ $resolvedTicketsCount }}</h2>
                </div>
                <div class="p-3 bg-success-subtle text-success rounded-circle">
                    <i class="fa-solid fa-circle-check fa-2x"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="small text-muted">All-time resolution record</span>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Recent Support Tickets -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Recent Client Inquiries & Tickets</h5>
                <a href="{{ route('staff.tickets.index') }}" class="btn btn-sm btn-outline-primary">View All Tickets</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Ticket Subject</th>
                                <th>Client Company</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTickets as $ticket)
                                <tr>
                                    <td class="ps-4 fw-semibold">
                                        <a href="{{ route('staff.tickets.show', $ticket) }}" class="text-decoration-none text-dark">
                                            #{{ $ticket->id }} - {{ Str::limit($ticket->subject, 35) }}
                                        </a>
                                    </td>
                                    <td>{{ $ticket->client->client_company ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge {{ $ticket->priority == 'High' || $ticket->priority == 'Urgent' ? 'bg-danger' : ($ticket->priority == 'Medium' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                            {{ $ticket->priority }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $ticket->status == 'Open' ? 'bg-danger-subtle text-danger' : ($ticket->status == 'In Progress' ? 'bg-primary-subtle text-primary' : 'bg-success-subtle text-success') }}">
                                            {{ $ticket->status }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('staff.tickets.show', $ticket) }}" class="btn btn-sm btn-outline-primary">
                                            Reply <i class="fa-solid fa-reply ms-1"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No open tickets at this time.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Quick Client Creation & Assigned Clients -->
    <div class="col-lg-4">
        <!-- Quick Action Card -->
        <div class="card border-0 shadow-sm mb-4 bg-primary text-white p-4">
            <h5 class="fw-bold mb-2 text-white"><i class="fa-solid fa-user-plus me-2"></i> Onboard New Client</h5>
            <p class="small text-white-50 mb-3">Staff members can onboard new clients directly. The system will automatically dispatch an activation OTP email to the client.</p>
            <a href="{{ route('staff.clients.create') }}" class="btn btn-light text-primary fw-bold w-100 py-2">
                + Create Client Account
            </a>
        </div>

        <!-- Assigned Clients List -->
        <div class="card border-0 shadow-sm p-4">
            <h6 class="fw-bold mb-3">My Assigned Clients</h6>
            <div class="list-group list-group-flush">
                @forelse($assignedClients->take(5) as $ac)
                    <a href="{{ route('staff.clients.show', $ac) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0">
                        <div>
                            <div class="fw-semibold text-dark">{{ $ac->client_company }}</div>
                            <small class="text-muted">{{ $ac->client_name }}</small>
                        </div>
                        <i class="fa-solid fa-chevron-right text-muted small"></i>
                    </a>
                @empty
                    <div class="text-muted small py-2">No clients assigned to you yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
