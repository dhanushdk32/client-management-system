@extends('layouts.staff')

@section('title', $client->client_company . ' - Client Details')
@section('page_title', $client->client_company)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1">{{ $client->client_company }}</h5>
        <span class="badge bg-success-subtle text-success border px-3 py-1">Active Client</span>
    </div>
    <a href="{{ route('staff.clients.index') }}" class="btn btn-light border text-muted">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Clients
    </a>
</div>

<div class="row g-4">
    <!-- Left Column: Company & Contact Info -->
    <div class="col-md-5">
        <div class="card p-4 border-0 shadow-sm mb-4">
            <h6 class="fw-bold mb-3 text-primary border-bottom pb-2">Company Overview</h6>
            <table class="table table-sm table-borderless mb-0">
                <tr>
                    <td class="text-muted" style="width: 140px;">Industry:</td>
                    <td class="fw-semibold">{{ $client->industry ?: 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Company Size:</td>
                    <td>{{ $client->company_size ?: 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Website:</td>
                    <td>
                        @if($client->website)
                            <a href="{{ $client->website }}" target="_blank" class="text-primary text-decoration-none">{{ $client->website }}</a>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="text-muted">GST / Tax ID:</td>
                    <td>{{ $client->client_gst ?: 'N/A' }}</td>
                </tr>
            </table>

            <h6 class="fw-bold mt-4 mb-3 text-primary border-bottom pb-2">Primary Contact</h6>
            <table class="table table-sm table-borderless mb-0">
                <tr>
                    <td class="text-muted" style="width: 140px;">Name:</td>
                    <td class="fw-semibold">{{ $client->client_name }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Email:</td>
                    <td><a href="mailto:{{ $client->client_email }}" class="text-decoration-none text-dark">{{ $client->client_email }}</a></td>
                </tr>
                <tr>
                    <td class="text-muted">Phone:</td>
                    <td>{{ $client->primary_contact }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Right Column: Support Tickets & Documents -->
    <div class="col-md-7">
        <!-- Tickets Card -->
        <div class="card p-4 border-0 shadow-sm mb-4">
            <h6 class="fw-bold mb-3 text-primary border-bottom pb-2">Client Support Tickets ({{ $tickets->count() }})</h6>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Ticket</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $ticket)
                            <tr>
                                <td>
                                    <div class="fw-semibold">#{{ $ticket->id }} - {{ Str::limit($ticket->subject, 30) }}</div>
                                    <small class="text-muted">{{ $ticket->created_at->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <span class="badge {{ $ticket->priority == 'High' || $ticket->priority == 'Urgent' ? 'bg-danger' : 'bg-secondary' }}">
                                        {{ $ticket->priority }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $ticket->status == 'Open' ? 'bg-danger-subtle text-danger' : ($ticket->status == 'In Progress' ? 'bg-primary-subtle text-primary' : 'bg-success-subtle text-success') }}">
                                        {{ $ticket->status }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('staff.tickets.show', $ticket) }}" class="btn btn-sm btn-outline-primary">Open</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-3 text-muted">No tickets found for this client.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Uploaded Documents Card -->
        <div class="card p-4 border-0 shadow-sm">
            <h6 class="fw-bold mb-3 text-primary border-bottom pb-2">Uploaded Client Documents ({{ $documents->count() }})</h6>
            <ul class="list-group list-group-flush">
                @forelse($documents as $doc)
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div>
                            <div class="fw-semibold text-dark"><i class="fa-regular fa-file-lines me-2 text-primary"></i> {{ $doc->file_name }}</div>
                            <small class="text-muted">{{ $doc->document_type ?? 'Document' }} &bull; {{ $doc->created_at->format('M d, Y') }}</small>
                        </div>
                        <span class="badge bg-light text-dark border">{{ $doc->verification_status ?? 'Uploaded' }}</span>
                    </li>
                @empty
                    <li class="list-group-item px-0 text-muted small">No documents uploaded by client yet.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
