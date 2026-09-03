@extends('layouts.staff')

@section('title', 'Client Documents Vault - Staff Portal')
@section('page_title', 'Client Documents Vault')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h5 class="fw-bold mb-1 text-primary border-bottom border-2 border-primary pb-2 d-inline-block">Client Documents Vault</h5>
                <p class="text-muted small mb-0">Review, verify, and download compliance documents, GST files, identity proofs, and specifications submitted by clients.</p>
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

        <!-- Scope Tabs -->
        <div class="d-flex gap-2 mb-4 border-bottom pb-3">
            <a href="{{ route('staff.documents.index', ['scope' => 'all'] + request()->except('page', 'scope')) }}" class="btn btn-sm rounded-pill px-3 fw-semibold {{ $scope === 'all' ? 'btn-primary' : 'btn-light border text-muted' }}">
                <i class="fa-solid fa-globe me-1"></i> All Client Documents
            </a>
            <a href="{{ route('staff.documents.index', ['scope' => 'assigned'] + request()->except('page', 'scope')) }}" class="btn btn-sm rounded-pill px-3 fw-semibold {{ $scope === 'assigned' ? 'btn-primary' : 'btn-light border text-muted' }}">
                <i class="fa-solid fa-user-check me-1"></i> My Clients' Documents
            </a>
        </div>

        <!-- Filter Bar -->
        <div class="d-flex justify-content-between mb-4">
            <form action="{{ route('staff.documents.index') }}" method="GET" class="d-flex gap-2 w-100 flex-wrap">
                <input type="hidden" name="scope" value="{{ $scope }}">
                <div class="input-group" style="max-width: 380px;">
                    <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-search text-muted"></i></span>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control border-start-0 ps-0" placeholder="Search by document name, type, or client...">
                </div>
                <select name="status" class="form-select w-auto">
                    <option value="">All Verification Statuses</option>
                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Verified" {{ request('status') == 'Verified' ? 'selected' : '' }}>Verified</option>
                    <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                <button type="submit" class="btn btn-outline-primary fw-semibold">Filter</button>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('staff.documents.index', ['scope' => $scope]) }}" class="btn btn-light border text-muted">Reset</a>
                @endif
            </form>
        </div>

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
                                <div class="fw-semibold text-dark">{{ $doc->client->client_company ?? ($doc->client->client_name ?? 'N/A') }}</div>
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
                                    <a href="{{ route('staff.documents.download', $doc->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3" title="Download">
                                        <i class="fa-solid fa-download me-1"></i> Download
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#staffStatusModal{{ $doc->id }}">
                                        <i class="fa-solid fa-pen me-1"></i> Review
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
            {{ $documents->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection

@push('modals')
@foreach($documents as $doc)
    <div class="modal fade" id="staffStatusModal{{ $doc->id }}" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('staff.documents.update', $doc->id) }}" method="POST" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Review Client Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Updating verification status for <strong>{{ $doc->document_name }}</strong> uploaded by <strong>{{ $doc->client->client_company ?? 'Client' }}</strong>.</p>
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
                    <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Save Decision</button>
                </div>
            </form>
        </div>
    </div>
@endforeach
@endpush
