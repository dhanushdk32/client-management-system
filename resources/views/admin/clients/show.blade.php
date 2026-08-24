@extends('layouts.admin')

@section('title', 'Client Details - Admin Portal')
@section('page_title', 'Client Details')

@section('content')
<div class="card mb-4">
    <div class="card-body p-4 position-relative">
        <a href="{{ route('admin.clients.edit', $client) }}" class="btn btn-primary rounded-3 px-4 position-absolute top-0 end-0 mt-4 me-4"><i class="fa-solid fa-pen me-2"></i> Edit Client</a>
        
        <div class="d-flex align-items-center mb-4">
            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-4" style="width: 70px; height: 70px;">
                <i class="fa-solid fa-building fa-2x"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-1 d-flex align-items-center gap-3">
                    {{ $client->client_company }}
                    @if($client->client_status == 'Active')
                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1 fs-6">Active</span>
                    @else
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1 fs-6">Inactive</span>
                    @endif
                </h4>
                <div class="text-muted small">Client ID: CL{{ sprintf('%03d', $client->client_id) }}</div>
            </div>
        </div>

        <ul class="nav nav-tabs mt-4" id="clientTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-semibold text-dark" data-bs-toggle="tab" data-bs-target="#overview" type="button">Overview</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-semibold text-muted" data-bs-toggle="tab" data-bs-target="#contacts" type="button">Contacts</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-semibold text-muted" data-bs-toggle="tab" data-bs-target="#services" type="button">Services</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-semibold text-muted" data-bs-toggle="tab" data-bs-target="#documents" type="button">Documents</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-semibold text-muted" data-bs-toggle="tab" data-bs-target="#activity" type="button">Activity</button>
            </li>
        </ul>
    </div>
</div>

<div class="tab-content" id="clientTabsContent">
    <!-- OVERVIEW TAB -->
    <div class="tab-pane fade show active" id="overview" role="tabpanel">
        <div class="row">
            <div class="col-md-12">
                <div class="card h-100">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-4 text-primary">Company Information</h6>
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td class="text-muted w-50">Company Name</td>
                                    <td class="fw-medium">: {{ $client->client_company }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Industry</td>
                                    <td class="fw-medium">: {{ $client->industry ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Company Size</td>
                                    <td class="fw-medium">: {{ $client->company_size ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Website</td>
                                    <td class="fw-medium">: {{ $client->website ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">GST Number</td>
                                    <td class="fw-medium">: {{ $client->client_gst ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Registration Date</td>
                                    <td class="fw-medium">: {{ \Carbon\Carbon::parse($client->client_created_date)->format('d M Y') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- CONTACTS TAB -->
    <div class="tab-pane fade" id="contacts" role="tabpanel">
        <div class="card">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-4 text-primary">Primary Contact</h6>
                <div class="row mb-5">
                    <div class="col-md-4">
                        <div class="text-muted small mb-1">Name</div>
                        <div class="fw-medium">{{ $client->client_name }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small mb-1">Email</div>
                        <div class="fw-medium">{{ $client->client_email }}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="small text-muted fw-semibold">Primary Contact / Phone</div>
                        <div class="fw-medium">{{ $client->primary_contact }}</div>
                    </div>
                </div>

                @if($client->secondary_contact)
                <h6 class="fw-bold mb-4 text-primary">Secondary Contact</h6>
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-muted small mb-1">Name</div>
                        <div class="fw-medium">{{ $client->secondary_contact }}</div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- SERVICES TAB -->
    <div class="tab-pane fade" id="services" role="tabpanel">
        <div class="card">
            <div class="card-body p-4 text-center text-muted">
                <i class="fa-solid fa-briefcase fa-3x mb-3 text-light"></i>
                <p>Services logic will be implemented in Phase 9.</p>
            </div>
        </div>
    </div>

    <!-- DOCUMENTS TAB -->
    <div class="tab-pane fade" id="documents" role="tabpanel">
        <div class="card">
            <div class="card-body p-4 text-center text-muted">
                <i class="fa-solid fa-file-lines fa-3x mb-3 text-light"></i>
                <p>Documents logic will be implemented in Phase 10.</p>
            </div>
        </div>
    </div>

    <!-- ACTIVITY TAB -->
    <div class="tab-pane fade" id="activity" role="tabpanel">
        <div class="card">
            <div class="card-body p-4 text-center text-muted">
                <i class="fa-solid fa-clock-rotate-left fa-3x mb-3 text-light"></i>
                <p>Activity logic will be implemented in Phase 13.</p>
            </div>
        </div>
    </div>
</div>
@endsection
