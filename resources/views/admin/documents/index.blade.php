@extends('layouts.admin')

@section('title', 'Client Documents - Admin Portal')
@section('page_title', 'Client Documents Vault')

@section('content')
<div class="card">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="fw-bold mb-1 text-primary border-bottom border-2 border-primary pb-2 d-inline-block">Client Documents Vault</h5>
                <p class="text-muted small mb-0">View, download, and review compliance, GST, identity proofs, and project specification documents uploaded by clients.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success py-2 px-3 small rounded-3 mb-4">
                <i class="fa-solid fa-circle-check me-1"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger py-2 px-3 small rounded-3 mb-4">
                <i class="fa-solid fa-circle-exclamation me-1"></i> {{ session('error') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr class="small text-muted">
                        <th>Client / Organization</th>
                        <th>Document Title</th>
                        <th>Classification</th>
                        <th>Status</th>
                        <th>Uploaded Date</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $doc)
                        <tr>
                            <td>
                                <div class="fw-semibold text-dark">{{ $doc->client->client_company ?? 'N/A' }}</div>
                                <span class="text-muted small">ID: #CL{{ sprintf('%03d', $doc->client_id) }} ({{ $doc->client->client_name ?? '' }})</span>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $doc->document_name }}</div>
                                <span class="text-muted small" style="font-size: 11px;">
                                    <i class="fa-solid fa-file me-1 text-primary"></i> {{ basename($doc->file_path) }}
                                </span>
                            </td>
                            <td><span class="badge bg-light text-dark border">{{ $doc->document_type }}</span></td>
                            <td>
                                @if($doc->status == 'Verified')
                                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1 border border-success-subtle"><i class="fa-solid fa-check-circle me-1"></i> Verified</span>
                                @elseif($doc->status == 'Rejected')
                                    <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-1 border border-danger-subtle"><i class="fa-solid fa-circle-xmark me-1"></i> Rejected</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-1 border border-warning-subtle"><i class="fa-solid fa-clock me-1"></i> Pending</span>
                                @endif
                            </td>
                            <td class="small text-muted">{{ $doc->created_at ? $doc->created_at->format('d M Y, h:i A') : 'N/A' }}</td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.documents.download', $doc->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3" title="Download">
                                        <i class="fa-solid fa-download"></i> Download
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#clientStatusModal{{ $doc->id }}">
                                        <i class="fa-solid fa-pen"></i> Review
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="fa-solid fa-folder-open fa-3x mb-2 text-muted opacity-50"></i>
                                <p class="mb-0">No client documents uploaded yet.</p>
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
    <div class="modal fade" id="clientStatusModal{{ $doc->id }}" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('admin.documents.update', $doc->id) }}" method="POST" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Review Client Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Updating status for <strong>{{ $doc->document_name }}</strong> uploaded by <strong>{{ $doc->client->client_company ?? 'Client' }}</strong>.</p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted">Verification Decision</label>
                        <select name="status" class="form-select" required>
                            <option value="Pending" {{ $doc->status == 'Pending' ? 'selected' : '' }}>Pending (Under Review)</option>
                            <option value="Verified" {{ $doc->status == 'Verified' ? 'selected' : '' }}>Verified (Approved)</option>
                            <option value="Rejected" {{ $doc->status == 'Rejected' ? 'selected' : '' }}>Rejected (Action Required)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Save Decision</button>
                </div>
            </form>
        </div>
    </div>
@endforeach
@endpush
