@extends('layouts.client')

@section('title', 'Request Details - Client Portal')
@section('page_title', 'Support Conversation')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <a href="{{ route('client.tickets.index') }}" class="btn btn-light border rounded-pill px-3 py-1 text-muted small fw-semibold">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Requests Desk
    </a>
    <form action="{{ route('client.tickets.destroy', $ticket->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this support request and all its conversation history? This cannot be undone.');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-semibold">
            <i class="fa-solid fa-trash-can me-1"></i> Delete Request
        </button>
    </form>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card h-100 shadow-sm border-0 rounded-4">
            <div class="card-body p-4">
                
                @if(session('success'))
                    <div class="alert alert-success py-2 px-3 rounded-3 mb-4">
                        <i class="fa-solid fa-circle-check me-1"></i> {{ session('success') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger py-2 px-3 rounded-3 mb-4">
                        <ul class="mb-0 ps-3 small">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom flex-wrap gap-2">
                    <div>
                        <span class="badge bg-light text-secondary border font-monospace mb-1">#TKT-{{ sprintf('%04d', $ticket->id) }}</span>
                        <h5 class="fw-bold mb-0 text-dark">{{ $ticket->subject }}</h5>
                    </div>
                    <div>
                        @if($ticket->status == 'Open')
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1 rounded-pill">Open</span>
                        @elseif($ticket->status == 'In Progress')
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-1 rounded-pill">In Progress</span>
                        @elseif($ticket->status == 'Resolved')
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill">Resolved</span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary border px-3 py-1 rounded-pill">Closed</span>
                        @endif
                    </div>
                </div>

                <!-- Original Request Bubble -->
                <div class="d-flex gap-3 mb-4">
                    <div class="flex-shrink-0">
                        <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 42px; height: 42px;">
                            You
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="bg-light p-3 rounded-3 border">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-bold text-dark">Original Request</span>
                                <span class="small text-muted">{{ $ticket->created_at->format('d M Y, h:i A') }}</span>
                            </div>
                            <div class="text-dark" style="white-space: pre-wrap;">{{ $ticket->description }}</div>

                            <!-- Attached File in Original Request -->
                            @if($ticket->attachment_path)
                                <div class="mt-3 pt-2 border-top">
                                    <div class="small fw-semibold text-muted mb-1"><i class="fa-solid fa-paperclip me-1 text-primary"></i> Attachment:</div>
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
                    </div>
                </div>

                <!-- Conversation Replies -->
                @foreach($ticket->replies as $reply)
                    @php
                        $isStaffOrAdmin = ($reply->sender_type == 'Admin' || $reply->sender_type == 'Staff' || str_contains($reply->message, '[Staff Response'));
                    @endphp
                    <div class="d-flex gap-3 mb-4 {{ $isStaffOrAdmin ? '' : 'flex-row-reverse' }}">
                        <div class="flex-shrink-0">
                            @if($isStaffOrAdmin)
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 42px; height: 42px; font-size: 13px;">
                                    TL
                                </div>
                            @else
                                <div class="bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 42px; height: 42px;">
                                    You
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1 {{ $isStaffOrAdmin ? '' : 'text-end' }}">
                            <div class="p-3 rounded-3 d-inline-block text-start w-100 {{ $isStaffOrAdmin ? 'bg-primary-subtle border border-primary-subtle text-dark' : 'bg-light border text-dark' }}">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="fw-bold text-primary">
                                        @if($isStaffOrAdmin)
                                            <i class="fa-solid fa-user-tie me-1"></i>
                                            {{ $ticket->assignedStaff->name ?? ($ticket->client->assignedStaff->first()?->name ?? 'Assigned Team Lead') }}
                                            <span class="badge bg-primary text-white ms-1 font-monospace" style="font-size: 10px;">TEAM LEADER</span>
                                        @else
                                            <i class="fa-solid fa-user me-1 text-muted"></i> You
                                        @endif
                                    </span>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="small text-muted">
                                            {{ $reply->created_at->format('d M Y, h:i A') }}
                                        </span>
                                        @if(!$isStaffOrAdmin && $reply->sender_id == Auth::guard('client')->user()->id)
                                            <form action="{{ route('client.tickets.replies.destroy', $reply->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this reply?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link text-danger p-0 text-decoration-none" title="Delete Reply" style="font-size: 11px;">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                                <div style="white-space: pre-wrap;">{{ $reply->message }}</div>

                                <!-- Attached File in Reply -->
                                @if($reply->attachment_path)
                                    <div class="mt-3 pt-2 border-top">
                                        <div class="small fw-semibold text-muted mb-1"><i class="fa-solid fa-paperclip me-1 text-primary"></i> Attachment:</div>
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
                        </div>
                    </div>
                @endforeach
                
                @if($ticket->status != 'Closed')
                    <div class="mt-4 border-top pt-4">
                        <form action="{{ route('client.tickets.reply', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold small text-muted">Reply to Team Leader</label>
                                <textarea name="message" class="form-control bg-light" rows="3" required placeholder="Type your reply or question here..."></textarea>
                            </div>
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div>
                                    <label class="btn btn-sm btn-light border rounded-pill px-3 mb-0 text-muted" style="cursor: pointer;">
                                        <i class="fa-solid fa-paperclip me-1 text-primary"></i> Attach Screenshot / File
                                        <input type="file" name="attachment" class="d-none" accept=".png,.jpg,.jpeg,.pdf,.zip,.txt,.doc,.docx" onchange="document.getElementById('file-chosen-client').textContent = this.files[0].name;">
                                    </label>
                                    <span id="file-chosen-client" class="small text-muted ms-2"></span>
                                </div>
                                <button type="submit" class="btn btn-primary px-4 rounded-pill fw-semibold shadow-sm">
                                    <i class="fa-solid fa-paper-plane me-2"></i> Send Reply
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="alert alert-secondary mt-4 mb-0 text-center rounded-3">
                        <i class="fa-solid fa-lock me-2"></i> This ticket is closed. You can submit a new request if needed.
                    </div>
                @endif

            </div>
        </div>
    </div>
    
    <!-- Right Sidebar info -->
    <div class="col-lg-4">
        @php
            $leader = $ticket->assignedStaff ?? $ticket->client->assignedStaff->first();
        @endphp
        <div class="card mb-4 border-0 shadow-sm p-4 bg-light rounded-4">
            <h6 class="fw-bold mb-3 text-primary border-bottom pb-2">
                <i class="fa-solid fa-user-tie me-1"></i> Dedicated Team Leader
            </h6>
            @if($leader)
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 46px; height: 46px; font-size: 16px;">
                        {{ strtoupper(substr($leader->name, 0, 2)) }}
                    </div>
                    <div>
                        <div class="fw-bold text-dark fs-6">{{ $leader->name }}</div>
                        <small class="text-muted d-block">{{ $leader->designation ?? 'Technical Team Lead' }}</small>
                    </div>
                </div>
                <div class="small text-muted">
                    <i class="fa-solid fa-briefcase text-primary me-1"></i> Department: <strong>{{ $leader->department ?? 'Engineering' }}</strong>
                </div>
            @else
                <div class="text-muted small">Assigned Technical Squad</div>
            @endif
        </div>

        <div class="card mb-4 border-0 shadow-sm p-4 rounded-4">
            <h6 class="fw-bold mb-3 text-dark border-bottom pb-2">Request Meta</h6>
            <table class="table table-borderless mb-0 small">
                <tr>
                    <td class="text-muted px-0">Ticket ID:</td>
                    <td class="fw-bold text-end px-0 text-primary">#TKT-{{ sprintf('%04d', $ticket->id) }}</td>
                </tr>
                <tr>
                    <td class="text-muted px-0">Priority:</td>
                    <td class="fw-medium text-end px-0">
                        @if($ticket->priority == 'High')
                            <span class="badge bg-danger-subtle text-danger border">High Priority</span>
                        @elseif($ticket->priority == 'Medium')
                            <span class="badge bg-warning-subtle text-warning border">Medium</span>
                        @else
                            <span class="badge bg-info-subtle text-info border">Low</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="text-muted px-0">Submitted:</td>
                    <td class="fw-medium text-end px-0">{{ $ticket->created_at->format('d M Y, h:i A') }}</td>
                </tr>
                <tr>
                    <td class="text-muted px-0">Last Active:</td>
                    <td class="fw-medium text-end px-0">{{ $ticket->updated_at->diffForHumans() }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection
