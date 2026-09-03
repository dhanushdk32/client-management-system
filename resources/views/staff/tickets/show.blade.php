@extends('layouts.staff')

@section('title', 'Ticket #' . $ticket->id . ' - ' . $ticket->subject)
@section('page_title', 'Ticket #' . $ticket->id)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-1">#{{ $ticket->id }} - {{ $ticket->subject }}</h4>
        <div class="d-flex align-items-center gap-2 mt-1">
            <span class="badge {{ $ticket->status == 'Open' ? 'bg-danger-subtle text-danger' : ($ticket->status == 'In Progress' ? 'bg-primary-subtle text-primary' : 'bg-success-subtle text-success') }} px-3 py-1">
                {{ $ticket->status }}
            </span>
            <span class="badge bg-secondary-subtle text-secondary px-3 py-1">
                Priority: {{ $ticket->priority }}
            </span>
            <span class="text-muted small">&bull; Submitted {{ $ticket->created_at->format('M d, Y h:i A') }}</span>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('staff.tickets.index') }}" class="btn btn-light border text-muted">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Tickets
        </a>
        <form action="{{ route('staff.tickets.destroy', $ticket) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this ticket and all conversation messages?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger">
                <i class="fa-solid fa-trash-can me-1"></i> Delete Ticket
            </button>
        </form>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success py-2 mb-4">{{ session('success') }}</div>
@endif

<div class="row g-4">
    <!-- Left Column: Discussion & Replies Thread -->
    <div class="col-lg-8">
        <!-- Initial Ticket Description Card -->
        <div class="card p-4 border-0 shadow-sm mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                        {{ strtoupper(substr($ticket->client->client_name ?? 'CL', 0, 2)) }}
                    </div>
                    <div>
                        <div class="fw-bold text-dark">{{ $ticket->client->client_name ?? 'Client' }}</div>
                        <div class="small text-muted">{{ $ticket->client->client_company ?? '' }}</div>
                    </div>
                </div>
                <span class="badge bg-light text-muted border">Original Request</span>
            </div>
            <div class="text-dark" style="white-space: pre-line; line-height: 1.6;">
                {{ $ticket->description }}
            </div>

            <!-- Original Request Attachment -->
            @if($ticket->attachment_path)
                <div class="mt-3 pt-3 border-top">
                    <div class="small fw-semibold text-muted mb-1"><i class="fa-solid fa-paperclip me-1 text-primary"></i> Client Attachment:</div>
                    @php
                        $ext = strtolower(pathinfo($ticket->attachment_path, PATHINFO_EXTENSION));
                    @endphp
                    @if(in_array($ext, ['png', 'jpg', 'jpeg']))
                        <a href="{{ asset('storage/' . $ticket->attachment_path) }}" target="_blank">
                            <img src="{{ asset('storage/' . $ticket->attachment_path) }}" alt="Attachment" class="img-fluid rounded border mt-1 shadow-sm" style="max-height: 200px;">
                        </a>
                    @else
                        <a href="{{ asset('storage/' . $ticket->attachment_path) }}" target="_blank" download class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 mt-1">
                            <i class="fa-solid fa-file-arrow-down me-1 text-primary"></i> {{ $ticket->attachment_name ?: 'Download Attached File' }}
                        </a>
                    @endif
                </div>
            @endif
        </div>

        <!-- Threaded Replies -->
        @if($ticket->replies->count() > 0)
            <h6 class="fw-bold mb-3 text-muted">Conversation Thread ({{ $ticket->replies->count() }})</h6>
            @foreach($ticket->replies as $reply)
                <div class="card p-4 border-0 shadow-sm mb-3 {{ str_contains($reply->message, '[Staff Response') ? 'border-start border-4 border-primary' : 'bg-light' }}">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="fw-bold small text-primary">
                            <i class="fa-solid fa-comment-dots me-1"></i> {{ str_contains($reply->message, '[Staff Response') ? 'Support Team Reply' : 'Client Update' }}
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <small class="text-muted">{{ $reply->created_at->format('M d, Y h:i A') }}</small>
                            @if($reply->sender_type === 'Staff' && $reply->sender_id == Auth::guard('staff')->user()->id)
                                <form action="{{ route('staff.tickets.replies.destroy', $reply->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this response?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link text-danger p-0 text-decoration-none" title="Delete Reply" style="font-size: 11px;">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                    <div style="white-space: pre-line; line-height: 1.6;">
                        {{ $reply->message }}
                    </div>

                    <!-- Reply Attachment -->
                    @if($reply->attachment_path)
                        <div class="mt-3 pt-2 border-top">
                            <div class="small fw-semibold text-muted mb-1"><i class="fa-solid fa-paperclip me-1 text-primary"></i> Attached File:</div>
                            @php
                                $ext = strtolower(pathinfo($reply->attachment_path, PATHINFO_EXTENSION));
                            @endphp
                            @if(in_array($ext, ['png', 'jpg', 'jpeg']))
                                <a href="{{ asset('storage/' . $reply->attachment_path) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $reply->attachment_path) }}" alt="Attachment" class="img-fluid rounded border mt-1 shadow-sm" style="max-height: 200px;">
                                </a>
                            @else
                                <a href="{{ asset('storage/' . $reply->attachment_path) }}" target="_blank" download class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 mt-1">
                                    <i class="fa-solid fa-file-arrow-down me-1 text-primary"></i> {{ $reply->attachment_name ?: 'Download Attached File' }}
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        @endif

        <!-- Reply Form Card -->
        <div class="card p-4 border-0 shadow-sm mt-4">
            <h5 class="fw-bold mb-3 text-primary"><i class="fa-solid fa-reply me-2"></i> Post Reply & Update Status</h5>
            
            <form action="{{ route('staff.tickets.reply', $ticket) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-muted">Your Response to Client</label>
                    <textarea name="message" class="form-control bg-light" rows="4" placeholder="Type your technical update, solution, or inquiry here..." required></textarea>
                </div>

                <div class="row align-items-center mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small text-muted">Update Ticket Status</label>
                        <select name="status" class="form-select bg-light">
                            <option value="In Progress" {{ $ticket->status == 'In Progress' ? 'selected' : '' }}>In Progress (Working on it)</option>
                            <option value="Resolved" {{ $ticket->status == 'Resolved' ? 'selected' : '' }}>Resolved (Fix implemented)</option>
                            <option value="Closed" {{ $ticket->status == 'Closed' ? 'selected' : '' }}>Closed</option>
                            <option value="Open" {{ $ticket->status == 'Open' ? 'selected' : '' }}>Open</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small text-muted">Attach Technical File / Screenshot</label>
                        <input type="file" name="attachment" class="form-control bg-light" accept=".png,.jpg,.jpeg,.pdf,.zip,.txt,.doc,.docx">
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold shadow-sm">
                        <i class="fa-solid fa-paper-plane me-1"></i> Send Reply to Client
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Column: Ticket Meta & Client Info -->
    <div class="col-lg-4">
        <div class="card p-4 border-0 shadow-sm mb-4">
            <h6 class="fw-bold mb-3 text-primary border-bottom pb-2">Client Information</h6>
            <div class="mb-3">
                <div class="text-muted small">Company:</div>
                <div class="fw-bold">{{ $ticket->client->client_company ?? 'N/A' }}</div>
            </div>
            <div class="mb-3">
                <div class="text-muted small">Contact Person:</div>
                <div class="fw-semibold">{{ $ticket->client->client_name ?? 'N/A' }}</div>
            </div>
            <div class="mb-3">
                <div class="text-muted small">Email:</div>
                <div><a href="mailto:{{ $ticket->client->client_email }}" class="text-decoration-none text-dark">{{ $ticket->client->client_email ?? 'N/A' }}</a></div>
            </div>
            <div>
                <div class="text-muted small">Phone:</div>
                <div class="fw-semibold">{{ $ticket->client->primary_contact ?? 'N/A' }}</div>
            </div>
        </div>

        <div class="card p-4 border-0 shadow-sm">
            <h6 class="fw-bold mb-3 text-primary border-bottom pb-2">Assignment Info</h6>
            <div class="mb-2">
                <span class="text-muted small">Assigned Staff:</span>
                <div class="fw-semibold text-dark">{{ Auth::guard('staff')->user()->name }} (You)</div>
            </div>
            <div>
                <span class="text-muted small">Department:</span>
                <div class="fw-semibold text-dark">{{ Auth::guard('staff')->user()->department }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
