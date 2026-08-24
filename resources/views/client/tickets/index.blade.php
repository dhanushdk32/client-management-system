@extends('layouts.client')

@section('title', 'My Requests - Client Portal')
@section('page_title', 'Support Requests')

@section('content')
<div class="card mb-4">
    <div class="card-body p-4 d-flex justify-content-between align-items-center">
        <div>
            <h5 class="fw-bold mb-1">Need help?</h5>
            <p class="text-muted mb-0 small">Create a new support ticket and our team will get back to you.</p>
        </div>
        <button type="button" class="btn btn-primary px-4 rounded-3" data-bs-toggle="modal" data-bs-target="#createTicketModal">
            <i class="fa-solid fa-plus me-2"></i> New Request
        </button>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success py-2 mb-4">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger py-2 mb-4">
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-4">My Requests</h5>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
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
                            <td class="fw-medium">
                                <a href="{{ route('client.tickets.show', $ticket->id) }}" class="text-decoration-none text-dark">{{ $ticket->subject }}</a>
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
                                <a href="{{ route('client.tickets.show', $ticket->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="fa-solid fa-eye me-1"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                <i class="fa-solid fa-ticket fa-3x mb-3 text-light"></i>
                                <p class="mb-0">You have no support requests.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Ticket Modal -->
<div class="modal fade" id="createTicketModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form action="{{ route('client.tickets.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header pb-0 border-0">
                <h5 class="modal-title fw-bold">Create Support Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-4">
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-muted">Subject</label>
                    <input type="text" name="subject" class="form-control bg-light" required placeholder="Brief description of the issue">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-muted">Priority</label>
                    <select name="priority" class="form-select bg-light" required>
                        <option value="Low">Low</option>
                        <option value="Medium" selected>Medium</option>
                        <option value="High">High</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-muted">Detailed Description</label>
                    <textarea name="description" rows="5" class="form-control bg-light" required placeholder="Please describe your issue in detail so our team can assist you better..."></textarea>
                </div>
            </div>
            <div class="modal-footer pt-0 border-0 mb-3 mx-3">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary px-4">Submit Request</button>
            </div>
        </form>
    </div>
</div>
@endsection
