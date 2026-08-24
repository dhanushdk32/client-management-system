@extends('layouts.admin')

@section('title', 'Support Requests - Admin Portal')
@section('page_title', 'Support Requests')

@section('content')
<div class="card">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-4">Manage Requests</h5>

        @if(session('success'))
            <div class="alert alert-success py-2">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger py-2">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger py-2">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Ticket ID</th>
                        <th>Client</th>
                        <th>Subject</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Last Updated</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                        <tr>
                            <td class="fw-medium text-muted">#TKT-{{ sprintf('%04d', $ticket->id) }}</td>
                            <td>
                                <div class="fw-medium">{{ $ticket->client->client_company ?? 'N/A' }}</div>
                            </td>
                            <td>
                                <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="text-decoration-none fw-medium text-dark">{{ Str::limit($ticket->subject, 30) }}</a>
                            </td>
                            <td>
                                @if($ticket->priority == 'High')
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1">High</span>
                                @elseif($ticket->priority == 'Medium')
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1">Medium</span>
                                @else
                                    <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-3 py-1">Low</span>
                                @endif
                            </td>
                            <td>
                                @if($ticket->status == 'Open')
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1">Open</span>
                                @elseif($ticket->status == 'In Progress')
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1">In Progress</span>
                                @elseif($ticket->status == 'Resolved')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">Resolved</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 py-1">Closed</span>
                                @endif
                            </td>
                            <td class="small text-muted">{{ $ticket->updated_at->diffForHumans() }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="fa-solid fa-reply"></i> Reply
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="fa-solid fa-ticket fa-3x mb-3 text-light"></i>
                                <p class="mb-0">No support requests found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $tickets->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
