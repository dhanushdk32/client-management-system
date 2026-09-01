@extends('layouts.staff')

@section('title', 'My Documents - Staff Portal')
@section('page_title', 'Staff Documents & Credentials')

@section('content')
<div class="row g-4">
    <!-- Upload Document Form -->
    <div class="col-lg-5">
        <div class="card p-4">
            <h5 class="fw-bold mb-3 text-primary border-bottom border-2 border-primary pb-2 d-inline-block">
                <i class="fa-solid fa-cloud-arrow-up me-1"></i> Upload Employee Document
            </h5>
            <p class="text-muted small mb-4">Upload your Resume, Experience Certificates, Relieving Letters, Degree Marksheets, and Government ID Proofs for company HR verification.</p>

            @if(session('success'))
                <div class="alert alert-success py-2 px-3 small rounded-3 mb-3">
                    <i class="fa-solid fa-circle-check me-1"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger py-2 px-3 small rounded-3 mb-3">
                    <i class="fa-solid fa-circle-exclamation me-1"></i> {{ session('error') }}
                </div>
            @endif

            @if(isset($errors) && $errors->any())
                <div class="alert alert-danger py-2 px-3 small rounded-3 mb-3">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('staff.documents.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                @csrf

                <!-- Document Name -->
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-muted">Document Name / Title <span class="text-danger">*</span></label>
                    <input type="text" name="document_name" class="form-control bg-light" placeholder="e.g. Resume 2026 / Senior Dev Experience Letter" value="{{ old('document_name') }}" required>
                </div>

                <!-- Document Type Classification -->
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-muted">Document Category <span class="text-danger">*</span></label>
                    <select name="document_type" class="form-select bg-light" required>
                        <option value="">-- Select Category --</option>
                        @foreach($documentTypes as $type)
                            <option value="{{ $type }}" {{ old('document_type') == $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- File Input -->
                <div class="mb-4">
                    <label class="form-label fw-semibold small text-muted">Choose File Attachment <span class="text-danger">*</span></label>
                    <input type="file" name="file" class="form-control bg-light" required>
                    <div class="form-text small text-muted">Supported formats: PDF, PNG, JPG, DOCX, ZIP (Max: 10MB)</div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                    <i class="fa-solid fa-cloud-arrow-up me-1"></i> Upload & Submit for HR Verification
                </button>
            </form>
        </div>
    </div>

    <!-- Uploaded Documents List -->
    <div class="col-lg-7">
        <div class="card p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0 text-dark">My Uploaded Documents</h5>
                <span class="badge bg-light text-dark border px-3 py-1">{{ $myDocuments->total() }} Uploaded</span>
            </div>
            <p class="text-muted small mb-3">All your submitted documents are securely archived. Administrators review and approve your submissions.</p>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="small text-muted">
                            <th>Document Title</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($myDocuments as $doc)
                            <tr>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $doc->document_name }}</div>
                                    <span class="text-muted small" style="font-size: 11px;">
                                        <i class="fa-solid fa-file-pdf me-1 text-danger"></i> {{ basename($doc->file_path) }}
                                    </span>
                                </td>
                                <td><span class="badge bg-light text-dark border">{{ $doc->document_type }}</span></td>
                                <td>
                                    @if($doc->status === 'Verified')
                                        <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1"><i class="fa-solid fa-check-circle me-1"></i> Verified</span>
                                    @elseif($doc->status === 'Rejected')
                                        <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-1"><i class="fa-solid fa-circle-xmark me-1"></i> Rejected</span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning rounded-pill px-2 py-1"><i class="fa-solid fa-clock me-1"></i> Pending Review</span>
                                    @endif
                                </td>
                                <td class="text-muted small">{{ $doc->created_at ? $doc->created_at->format('d M Y') : 'N/A' }}</td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('staff.documents.download', $doc->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-2" title="Download">
                                            <i class="fa-solid fa-download"></i>
                                        </a>
                                        @if($doc->status !== 'Verified')
                                            <form action="{{ route('staff.documents.destroy', $doc->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this document?');" class="m-0 p-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-2" title="Delete">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="fa-solid fa-folder-open fa-2x mb-2 text-muted opacity-50"></i>
                                    <p class="mb-0">No documents uploaded yet. Upload your Resume, Experience, or ID Proofs on the left.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $myDocuments->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
