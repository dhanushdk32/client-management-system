@extends('layouts.admin')

@section('title', 'Request Details - Admin Portal')
@section('page_title', 'Support Request')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <a href="{{ route('admin.tickets.index') }}" class="btn btn-light border rounded-pill px-3 py-1 text-muted small fw-semibold">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Requests
    </a>
    <form action="{{ route('admin.tickets.destroy', $ticket->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this support ticket and all conversation messages?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-semibold">
            <i class="fa-solid fa-trash-can me-1"></i> Delete Ticket
        </button>
    </form>
</div>

<div class="row">
    <div class="col-md-8 mb-4">
        <div class="card h-100 shadow-sm border-0 rounded-4">
            <div class="card-body p-4">
                
                @if(session('success'))
                    <div class="alert alert-success py-2 px-3 small rounded-3 mb-4">{{ session('success') }}</div>
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

                <!-- Original Description -->
                <div class="d-flex gap-3 mb-4">
                    <div class="flex-shrink-0">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 42px; height: 42px;">
                            <i class="fa-solid fa-user"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="bg-light p-3 rounded-3 border">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-bold text-dark">{{ $ticket->client->client_company ?? 'Client' }}</span>
                                <span class="small text-muted">{{ $ticket->created_at->format('d M Y, h:i A') }}</span>
                            </div>
                            <div class="text-dark" style="white-space: pre-wrap;">{{ $ticket->description }}</div>

                            <!-- Attachment in Ticket -->
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

                <!-- Replies -->
                @foreach($ticket->replies as $reply)
                    <div class="d-flex gap-3 mb-4 {{ $reply->sender_type == 'Admin' ? 'flex-row-reverse' : '' }}">
                        <div class="flex-shrink-0">
                            @if($reply->sender_type == 'Admin')
                                <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 42px; height: 42px;">
                                    <i class="fa-solid fa-headset"></i>
                                </div>
                            @else
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 42px; height: 42px;">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1 {{ $reply->sender_type == 'Admin' ? 'text-end' : '' }}">
                            <div class="p-3 rounded-3 d-inline-block text-start w-100 {{ $reply->sender_type == 'Admin' ? 'bg-primary text-white' : 'bg-light border text-dark' }}">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="fw-bold {{ $reply->sender_type == 'Admin' ? 'text-white' : 'text-dark' }}">
                                        @if(str_contains($reply->message, '[Staff Response'))
                                            <span class="badge bg-info-subtle text-info border border-info-subtle me-1"><i class="fa-solid fa-user-gear me-1"></i> Staff Reply</span>
                                        @elseif($reply->sender_type == 'Admin')
                                            <span class="badge bg-light text-dark me-1"><i class="fa-solid fa-shield-halved me-1 text-primary"></i> Admin (You)</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary border me-1"><i class="fa-solid fa-user me-1"></i> Client</span>
                                            {{ $ticket->client->client_company ?? 'Client' }}
                                        @endif
                                    </span>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="small {{ $reply->sender_type == 'Admin' ? 'text-light' : 'text-muted' }} opacity-75">
                                            {{ $reply->created_at->format('d M Y, h:i A') }}
                                        </span>
                                        <form action="{{ route('admin.tickets.replies.destroy', $reply->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this message?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link {{ $reply->sender_type == 'Admin' ? 'text-white' : 'text-danger' }} p-0 text-decoration-none" title="Delete Reply" style="font-size: 11px;">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <div style="white-space: pre-wrap;">{{ $reply->message }}</div>

                                <!-- Attachment in Reply -->
                                @if($reply->attachment_path)
                                    <div class="mt-3 pt-2 border-top">
                                        <div class="small fw-semibold {{ $reply->sender_type == 'Admin' ? 'text-light' : 'text-muted' }} mb-1"><i class="fa-solid fa-paperclip me-1"></i> Attachment:</div>
                                        @php
                                            $ext = strtolower(pathinfo($reply->attachment_path, PATHINFO_EXTENSION));
                                        @endphp
                                        @if(in_array($ext, ['png', 'jpg', 'jpeg']))
                                            <a href="{{ asset('storage/' . $reply->attachment_path) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $reply->attachment_path) }}" alt="Attachment" class="img-fluid rounded border mt-1 shadow-sm" style="max-height: 200px;">
                                            </a>
                                        @else
                                            <a href="{{ asset('storage/' . $reply->attachment_path) }}" target="_blank" download class="btn btn-sm btn-outline-light rounded-pill px-3 py-1 mt-1">
                                                <i class="fa-solid fa-file-arrow-down me-1"></i> {{ $reply->attachment_name ?: 'Download Attached File' }}
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
                
                <div class="mt-4 border-top pt-4">
                    <form action="{{ route('admin.tickets.reply', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Add a Response</label>
                            <textarea name="message" class="form-control bg-light" rows="3" required placeholder="Type your response here..."></textarea>
                        </div>
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <label class="btn btn-sm btn-light border rounded-pill px-3 mb-0 text-muted" style="cursor: pointer;">
                                    <i class="fa-solid fa-paperclip me-1 text-primary"></i> Attach File
                                    <input type="file" name="attachment" class="d-none" accept=".png,.jpg,.jpeg,.pdf,.zip,.txt,.doc,.docx" onchange="document.getElementById('file-chosen-admin').textContent = this.files[0].name;">
                                </label>
                                <span id="file-chosen-admin" class="small text-muted ms-2"></span>
                            </div>
                            <button type="submit" class="btn btn-primary px-4 rounded-pill fw-semibold shadow-sm">
                                <i class="fa-solid fa-reply me-2"></i> Send Reply
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    
    <!-- Sidebar info -->
    <div class="col-md-4">
        <!-- Update Status Card -->
        <div class="card mb-4 border-0 shadow-sm p-4 rounded-4">
            <h6 class="fw-bold mb-3 text-dark border-bottom pb-2">Update Status</h6>
            <form action="{{ route('admin.tickets.update', $ticket->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <select name="status" class="form-select bg-light">
                        <option value="Open" {{ $ticket->status == 'Open' ? 'selected' : '' }}>Open</option>
                        <option value="In Progress" {{ $ticket->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="Resolved" {{ $ticket->status == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="Closed" {{ $ticket->status == 'Closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-outline-primary w-100 rounded-pill fw-semibold">Update Status</button>
            </form>
        </div>

        <div class="card mb-4 border-0 shadow-sm p-4 rounded-4">
            <h6 class="fw-bold mb-3 text-dark border-bottom pb-2">Request Meta</h6>
            <table class="table table-borderless mb-0 small">
                <tr>
                    <td class="text-muted px-0">Ticket ID</td>
                    <td class="fw-bold text-end px-0 text-primary">#TKT-{{ sprintf('%04d', $ticket->id) }}</td>
                </tr>
                <tr>
                    <td class="text-muted px-0">Client</td>
                    <td class="fw-medium text-end px-0">{{ $ticket->client->client_company ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="text-muted px-0">Priority</td>
                    <td class="fw-medium text-end px-0">{{ $ticket->priority }}</td>
                </tr>
                <tr>
                    <td class="text-muted px-0">Created</td>
                    <td class="fw-medium text-end px-0">{{ $ticket->created_at->format('d M Y, h:i A') }}</td>
                </tr>
                <tr>
                    <td class="text-muted px-0">Last Updated</td>
                    <td class="fw-medium text-end px-0">{{ $ticket->updated_at->diffForHumans() }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection
