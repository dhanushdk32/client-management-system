@extends('layouts.admin')

@section('title', 'Request Details - Admin Portal')
@section('page_title', 'Support Request')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.tickets.index') }}" class="text-decoration-none fw-semibold"><i class="fa-solid fa-arrow-left me-2"></i> Back to Requests</a>
</div>

<div class="row">
    <div class="col-md-8 mb-4">
        <div class="card h-100">
            <div class="card-body p-4">
                
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

                <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                    <h5 class="fw-bold mb-0">{{ $ticket->subject }}</h5>
                    <div>
                        @if($ticket->status == 'Open')
                            <span class="badge bg-primary px-3 py-1">Open</span>
                        @elseif($ticket->status == 'In Progress')
                            <span class="badge bg-warning px-3 py-1">In Progress</span>
                        @elseif($ticket->status == 'Resolved')
                            <span class="badge bg-success px-3 py-1">Resolved</span>
                        @else
                            <span class="badge bg-secondary px-3 py-1">Closed</span>
                        @endif
                    </div>
                </div>

                <!-- Original Description -->
                <div class="d-flex gap-3 mb-4">
                    <div class="flex-shrink-0">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="fa-solid fa-user"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="bg-light p-3 rounded-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-bold text-dark">{{ $ticket->client->client_company ?? 'Client' }}</span>
                                <span class="small text-muted">{{ $ticket->created_at->format('d M Y, h:i A') }}</span>
                            </div>
                            <div class="text-dark" style="white-space: pre-wrap;">{{ $ticket->description }}</div>
                        </div>
                    </div>
                </div>

                <!-- Replies -->
                @foreach($ticket->replies as $reply)
                    <div class="d-flex gap-3 mb-4 {{ $reply->sender_type == 'Admin' ? 'flex-row-reverse' : '' }}">
                        <div class="flex-shrink-0">
                            @if($reply->sender_type == 'Admin')
                                <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fa-solid fa-headset"></i>
                                </div>
                            @else
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1 {{ $reply->sender_type == 'Admin' ? 'text-end' : '' }}">
                            <div class="p-3 rounded-3 d-inline-block text-start w-100 {{ $reply->sender_type == 'Admin' ? 'bg-primary text-white' : 'bg-light text-dark' }}">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="fw-bold {{ $reply->sender_type == 'Admin' ? 'text-white' : 'text-dark' }}">
                                        {{ $reply->sender_type == 'Admin' ? 'You (Support)' : ($ticket->client->client_company ?? 'Client') }}
                                    </span>
                                    <span class="small {{ $reply->sender_type == 'Admin' ? 'text-light' : 'text-muted' }} opacity-75">
                                        {{ $reply->created_at->format('d M Y, h:i A') }}
                                    </span>
                                </div>
                                <div style="white-space: pre-wrap;">{{ $reply->message }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
                
                <div class="mt-4 border-top pt-4">
                    <form action="{{ route('admin.tickets.reply', $ticket->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Add a Reply</label>
                            <textarea name="message" class="form-control bg-light" rows="3" required placeholder="Type your response here..."></textarea>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary px-4 rounded-3"><i class="fa-solid fa-reply me-2"></i> Send Reply</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    
    <!-- Sidebar info -->
    <div class="col-md-4">
        <!-- Update Status Card -->
        <div class="card mb-4">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3">Update Status</h6>
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
                    <button type="submit" class="btn btn-outline-primary w-100 rounded-3">Update Status</button>
                </form>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-4">Request Details</h6>
                <table class="table table-borderless mb-0 small">
                    <tr>
                        <td class="text-muted px-0">Ticket ID</td>
                        <td class="fw-medium text-end px-0">#TKT-{{ sprintf('%04d', $ticket->id) }}</td>
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
                        <td class="fw-medium text-end px-0">{{ $ticket->created_at->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted px-0">Last Updated</td>
                        <td class="fw-medium text-end px-0">{{ $ticket->updated_at->diffForHumans() }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
