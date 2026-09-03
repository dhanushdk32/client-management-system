@extends('layouts.client')

@section('title', 'My Support Requests - Client Portal')
@section('page_title', 'Support Requests & Conversations')

@section('content')
<div class="card mb-4 border-0 shadow-sm rounded-4">
    <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h5 class="fw-bold mb-1 text-primary"><i class="fa-solid fa-headset me-2"></i> Dedicated Support Desk</h5>
            <p class="text-muted mb-0 small">Direct communication channel with your assigned Technical Lead and Engineering Squad.</p>
        </div>
        <button type="button" class="btn btn-primary px-4 rounded-pill fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#createTicketModal">
            <i class="fa-solid fa-plus me-1"></i> New Support Request
        </button>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success py-2 px-3 small rounded-3 mb-4">
        <i class="fa-solid fa-circle-check me-1"></i> {{ session('success') }}
    </div>
@endif
@if($errors->any())
    <div class="alert alert-danger py-2 px-3 small rounded-3 mb-4">
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr class="small text-muted">
                        <th>Ticket ID & Subject</th>
                        <th>Assigned Team Leader</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Last Activity</th>
                        <th class="text-end">Conversation</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                        <tr>
                            <td>
                                <a href="{{ route('client.tickets.show', $ticket->id) }}" class="text-decoration-none text-dark fw-bold">
                                    {{ $ticket->subject }}
                                </a>
                                <small class="text-muted d-block font-monospace" style="font-size: 11px;">#TKT-{{ sprintf('%04d', $ticket->id) }}</small>
                            </td>
                            <td>
                                @if($ticket->assignedStaff)
                                    <div class="fw-semibold text-dark small">
                                        <i class="fa-solid fa-user-tie text-primary me-1"></i> {{ $ticket->assignedStaff->name }}
                                    </div>
                                    <small class="text-muted" style="font-size: 10.5px;">Lead</small>
                                @else
                                    <span class="text-muted small">Assigned Squad</span>
                                @endif
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
                                    <span class="badge bg-secondary-subtle text-secondary border rounded-pill px-3 py-1">Closed</span>
                                @endif
                            </td>
                            <td class="small text-muted">{{ $ticket->updated_at->diffForHumans() }}</td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('client.tickets.show', $ticket->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold">
                                        Open Chat <i class="fa-solid fa-arrow-right ms-1"></i>
                                    </a>
                                    <form action="{{ route('client.tickets.destroy', $ticket->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this request and its conversation history? This cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" title="Delete Request" style="width: 32px; height: 32px; padding: 0;">
                                            <i class="fa-solid fa-trash-can" style="font-size: 12px;"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="fa-solid fa-ticket fa-3x mb-3 text-light"></i>
                                <p class="mb-0">You have no active support requests. Click "New Support Request" above to connect with your team lead.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('modals')
<!-- Create Ticket Modal -->
<div class="modal fade" id="createTicketModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form action="{{ route('client.tickets.store') }}" method="POST" enctype="multipart/form-data" class="modal-content rounded-4 border-0 shadow">
            @csrf
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold text-dark">Submit Support Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-muted">Subject / Topic</label>
                    <input type="text" name="subject" class="form-control bg-light" required placeholder="e.g. Question regarding API integration milestone">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-muted">Priority Level</label>
                    <select name="priority" class="form-select bg-light" required>
                        <option value="Low">Low - General inquiry</option>
                        <option value="Medium" selected>Medium - Milestone or task question</option>
                        <option value="High">High - Urgent blocker</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-muted">Detailed Message</label>
                    <textarea name="description" rows="4" class="form-control bg-light" required placeholder="Describe your request or issue in detail..."></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-muted">Attach Screenshot / Document (Optional)</label>
                    <input type="file" name="attachment" class="form-control bg-light" accept=".png,.jpg,.jpeg,.pdf,.zip,.txt,.doc,.docx">
                    <small class="text-muted" style="font-size: 11px;">PNG, JPG, PDF, ZIP up to 10MB</small>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm">Send to Team Leader</button>
            </div>
        </form>
    </div>
</div>
@endpush
