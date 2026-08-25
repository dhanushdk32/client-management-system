@extends('layouts.admin')

@section('title', 'Documents - Admin Portal')
@section('page_title', 'Client Documents Management')

@section('content')
<div class="card">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-4">Uploaded Documents</h5>

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
                        <th>Client</th>
                        <th>Document Details</th>
                        <th>Status</th>
                        <th>Uploaded Date</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $doc)
                        <tr>
                            <td>
                                <div class="fw-medium">{{ $doc->client->client_company ?? 'N/A' }}</div>
                                <div class="small text-muted">ID: CL{{ sprintf('%03d', $doc->client_id) }}</div>
                            </td>
                            <td>
                                <div class="fw-medium">{{ $doc->document_name }}</div>
                                <div class="small text-muted">{{ $doc->document_type }}</div>
                            </td>
                            <td>
                                @if($doc->status == 'Verified')
                                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1 border border-success-subtle"><i class="fa-solid fa-check-circle me-1"></i> Verified</span>
                                @elseif($doc->status == 'Rejected')
                                    <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-1 border border-danger-subtle"><i class="fa-solid fa-circle-xmark me-1"></i> Rejected</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-1 border border-warning-subtle"><i class="fa-solid fa-clock me-1"></i> Pending</span>
                                @endif
                            </td>
                            <td class="small">{{ \Carbon\Carbon::parse($doc->created_at)->format('d M Y h:i A') }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.documents.download', $doc->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 me-2" title="Download">
                                    <i class="fa-solid fa-download"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#statusModal{{ $doc->id }}">
                                    <i class="fa-solid fa-pen"></i> Update
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                <i class="fa-solid fa-folder-open fa-3x mb-3 text-light"></i>
                                <p class="mb-0">No documents uploaded yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $documents->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection

@push('modals')
@foreach($documents as $doc)
    <!-- Modal for Status Update -->
    <div class="modal fade" id="statusModal{{ $doc->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('admin.documents.update', $doc->id) }}" method="POST" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Update Document Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Update status for <strong>{{ $doc->document_name }}</strong> belonging to <strong>{{ $doc->client->client_company ?? 'N/A' }}</strong>.</p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="Pending" {{ $doc->status == 'Pending' ? 'selected' : '' }}>Pending (Awaiting Verification)</option>
                            <option value="Verified" {{ $doc->status == 'Verified' ? 'selected' : '' }}>Verified (Approved)</option>
                            <option value="Rejected" {{ $doc->status == 'Rejected' ? 'selected' : '' }}>Rejected (Action Required)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Update Status</button>
                </div>
            </form>
        </div>
    </div>
@endforeach
@endpush
