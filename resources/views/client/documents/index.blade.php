@extends('layouts.client')

@section('title', 'Documents - Client Portal')
@section('page_title', 'My Documents')

@section('content')
<div class="row">
    <!-- Upload Section -->
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">Upload Document</h5>
                
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

                <form action="{{ route('client.documents.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted">Document Type</label>
                        <select name="document_type" class="form-select bg-light" required>
                            <option value="">Select Type...</option>
                            <option value="Identity Proof">Identity Proof</option>
                            <option value="Address Proof">Address Proof</option>
                            <option value="Company Registration">Company Registration</option>
                            <option value="Tax Certificate">Tax Certificate</option>
                            <option value="Contract/Agreement">Contract / Agreement</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted">Document Name</label>
                        <input type="text" name="document_name" class="form-control bg-light" placeholder="e.g. PAN Card" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold small text-muted">File (PDF, JPG, PNG up to 5MB)</label>
                        <input type="file" name="file" class="form-control bg-light" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 rounded-3 fw-semibold"><i class="fa-solid fa-cloud-arrow-up me-2"></i> Upload Document</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Documents List -->
    <div class="col-md-8 mb-4">
        <div class="card h-100">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">Uploaded Documents</h5>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Name & Type</th>
                                <th>Status</th>
                                <th>Uploaded Date</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($documents as $doc)
                                <tr>
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
                                    <td class="small text-muted">{{ \Carbon\Carbon::parse($doc->created_at)->format('d M Y h:i A') }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('client.documents.download', $doc->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3" title="Download">
                                            <i class="fa-solid fa-download"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-5">
                                        <i class="fa-solid fa-folder-open fa-3x mb-3 text-light"></i>
                                        <p class="mb-0">No documents uploaded yet.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
