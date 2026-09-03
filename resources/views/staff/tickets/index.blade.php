@extends('layouts.staff')

@section('title', 'Support Tickets Desk - Staff Portal')
@section('page_title', 'Support Tickets Desk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1">Assigned Support Requests</h5>
        <p class="text-muted small mb-0">Respond to client issues, provide technical updates, and resolve requests</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success py-2 mb-4">{{ session('success') }}</div>
@endif

<!-- Filter Bar -->
<div class="card mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('staff.tickets.index') }}" class="row g-2 align-items-center">
            <div class="col-md-5">
                <select name="status" class="form-select bg-light">
                    <option value="">All Statuses</option>
                    <option value="Open" {{ request('status') == 'Open' ? 'selected' : '' }}>Open</option>
                    <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="Resolved" {{ request('status') == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>
            <div class="col-md-5">
                <select name="priority" class="form-select bg-light">
                    <option value="">All Priorities</option>
                    <option value="Low" {{ request('priority') == 'Low' ? 'selected' : '' }}>Low</option>
                    <option value="Medium" {{ request('priority') == 'Medium' ? 'selected' : '' }}>Medium</option>
                    <option value="High" {{ request('priority') == 'High' ? 'selected' : '' }}>High</option>
                    <option value="Urgent" {{ request('priority') == 'Urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
                @if(request()->hasAny(['status', 'priority']))
                    <a href="{{ route('staff.tickets.index') }}" class="btn btn-light border" title="Reset"><i class="fa-solid fa-rotate-left"></i></a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Tickets Table -->
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Ticket ID & Subject</th>
                    <th>Client Company</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Created Date</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $ticket)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark">#{{ $ticket->id }} - {{ $ticket->subject }}</div>
                            <small class="text-muted">{{ Str::limit($ticket->description, 50) }}</small>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $ticket->client->client_company ?? 'N/A' }}</div>
                            <small class="text-muted">{{ $ticket->client->client_name ?? '' }}</small>
                        </td>
                        <td>
                            @if($ticket->priority == 'Urgent')
                                <span class="badge bg-danger text-white px-2 py-1">Urgent</span>
                            @elseif($ticket->priority == 'High')
                                <span class="badge bg-danger-subtle text-danger border border-danger px-2 py-1">High</span>
                            @elseif($ticket->priority == 'Medium')
                                <span class="badge bg-warning-subtle text-warning border border-warning px-2 py-1">Medium</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary px-2 py-1">Low</span>
                            @endif
                        </td>
                        <td>
                            @if($ticket->status == 'Open')
                                <span class="badge bg-danger-subtle text-danger border border-danger px-3 py-1">Open</span>
                            @elseif($ticket->status == 'In Progress')
                                <span class="badge bg-primary-subtle text-primary border border-primary px-3 py-1">In Progress</span>
                            @elseif($ticket->status == 'Resolved')
                                <span class="badge bg-success-subtle text-success border border-success px-3 py-1">Resolved</span>
                            @else
                                <span class="badge bg-dark-subtle text-dark border px-3 py-1">Closed</span>
                            @endif
                        </td>
                        <td>{{ $ticket->created_at->format('M d, Y h:i A') }}</td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('staff.tickets.show', $ticket) }}" class="btn btn-sm btn-primary px-3">
                                    Reply & Manage <i class="fa-solid fa-arrow-right ms-1"></i>
                                </a>
                                <form action="{{ route('staff.tickets.destroy', $ticket) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this ticket and all conversation messages?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" title="Delete Ticket" style="width: 31px; height: 31px; padding: 0;">
                                        <i class="fa-solid fa-trash-can" style="font-size: 11px;"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-ticket-simple fa-3x mb-3 d-block opacity-50"></i>
                            No support tickets assigned to you at the moment.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($tickets->hasPages())
        <div class="card-footer bg-white border-top py-3">
            {{ $tickets->links() }}
        </div>
    @endif
</div>
@endsection
