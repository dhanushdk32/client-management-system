@extends('layouts.client')

@section('title', 'Document Vault & Deliverables - Client Portal')
@section('page_title', 'Document Vault & Project Deliverables')

@section('content')
<div class="row g-4">
    <!-- Upload Compliance File Section -->
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 h-100 p-4 rounded-4">
            <h5 class="fw-bold mb-1 text-primary"><i class="fa-solid fa-cloud-arrow-up me-2"></i> Upload File</h5>
            <p class="text-muted small mb-4">Securely submit GST certificates, PAN cards, specifications, or agreements.</p>
            
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
            @if($errors->any())
                <div class="alert alert-danger py-2 px-3 small rounded-3 mb-4">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('client.documents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-muted">Document Classification</label>
                    <select name="document_type" class="form-select bg-light" required>
                        <option value="">Select Type...</option>
                        <option value="GST Certificate">GST Certificate</option>
                        <option value="Company Registration">Company Registration / Incorporation</option>
                        <option value="Identity / Address Proof">Identity / Address Proof</option>
                        <option value="Contract / SLA Agreement">Contract / SLA Agreement</option>
                        <option value="Project Scope / Specification">Project Scope / Specification</option>
                        <option value="Other Compliance File">Other Compliance File</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-muted">Document Name / Title</label>
                    <input type="text" name="document_name" class="form-control bg-light" placeholder="e.g. GST Registration Certificate 2026" required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold small text-muted">File (PDF, JPG, PNG, DOC, ZIP up to 10MB)</label>
                    <input type="file" name="file" class="form-control bg-light" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.zip" required>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill fw-semibold shadow-sm">
                    <i class="fa-solid fa-upload me-2"></i> Upload to Vault
                </button>
            </form>
        </div>
    </div>

    <!-- Documents Vault List & Deliverables Review -->
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 h-100 p-4 rounded-4">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <div>
                    <h5 class="fw-bold mb-1 text-dark"><i class="fa-solid fa-shield-halved text-primary me-2"></i> Vault Files & Handover Deliverables</h5>
                    <p class="text-muted small mb-0">Review compliance status and provide one-click sign-offs for technical deliverables.</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="small text-muted">
                            <th>Document Title</th>
                            <th>Verification</th>
                            <th>Sign-off Status</th>
                            <th>Date</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($documents as $doc)
                            <tr>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $doc->document_name }}</div>
                                    <span class="badge bg-light text-secondary border font-monospace" style="font-size: 10px;">{{ $doc->document_type }}</span>
                                </td>
                                <td>
                                    @if($doc->status == 'Verified')
                                        <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1 border border-success-subtle"><i class="fa-solid fa-check-circle me-1"></i> Verified</span>
                                    @elseif($doc->status == 'Rejected')
                                        <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-1 border border-danger-subtle"><i class="fa-solid fa-circle-xmark me-1"></i> Rejected</span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-1 border border-warning-subtle"><i class="fa-solid fa-clock me-1"></i> Pending Review</span>
                                    @endif
                                </td>
                                <td>
                                    @if($doc->approval_status == 'Approved')
                                        <span class="badge bg-success text-white rounded-pill px-3 py-1 shadow-sm"><i class="fa-solid fa-check-double me-1"></i> Approved</span>
                                    @elseif($doc->approval_status == 'Revision Requested')
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1"><i class="fa-solid fa-rotate-left me-1"></i> Revision Needed</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary border rounded-pill px-2 py-1">Awaiting Sign-off</span>
                                    @endif
                                </td>
                                <td class="small text-muted">{{ \Carbon\Carbon::parse($doc->created_at)->format('d M Y') }}</td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('client.documents.download', $doc->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3" title="Download">
                                            <i class="fa-solid fa-download me-1"></i> Download
                                        </a>

                                        @if($doc->approval_status != 'Approved')
                                            <button type="button" class="btn btn-sm btn-success rounded-pill px-3 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#approveModal{{ $doc->id }}">
                                                <i class="fa-solid fa-check me-1"></i> Sign-off
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="fa-solid fa-folder-open fa-3x mb-3 text-light"></i>
                                    <p class="mb-0">No documents in your vault yet. Upload compliance files using the form on the left.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('modals')
@foreach($documents as $doc)
    <!-- Sign-off / Review Modal -->
    <div class="modal fade" id="approveModal{{ $doc->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold text-dark">Deliverable Sign-off</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="mb-3">Please select your decision for <strong>{{ $doc->document_name }}</strong>:</p>
                    
                    <!-- Approve Option -->
                    <form action="{{ route('client.documents.approve', $doc->id) }}" method="POST" class="mb-3">
                        @csrf
                        <button type="submit" class="btn btn-success w-100 py-2 rounded-pill fw-bold shadow-sm">
                            <i class="fa-solid fa-circle-check me-2"></i> Formally Approve Deliverable
                        </button>
                    </form>

                    <div class="text-center text-muted small my-3">&bull; OR &bull;</div>

                    <!-- Request Revision Option -->
                    <form action="{{ route('client.documents.revision', $doc->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Feedback / Revision Notes</label>
                            <textarea name="client_feedback" class="form-control bg-light" rows="3" placeholder="Explain what changes are needed before approval..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-outline-danger w-100 rounded-pill fw-semibold">
                            <i class="fa-solid fa-rotate-left me-2"></i> Request Revision from Team Lead
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endpush
