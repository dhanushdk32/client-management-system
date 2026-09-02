@extends('layouts.client')

@section('title', 'My Profile - Client Portal')
@section('page_title', 'Client Profile & Project Info')

@section('content')
<div class="row g-4">
    <!-- Left Column: Client Profile & Contact Information -->
    <div class="col-lg-7">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-white p-4 border-bottom d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-1 text-primary">
                        <i class="fa-solid fa-user-circle me-1"></i> My Profile Information
                    </h5>
                    <p class="text-muted small mb-0">Manage your personal contact info, email address, and billing location.</p>
                </div>
                <button type="button" id="editProfileBtn" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold">
                    <i class="fa-solid fa-pen me-1"></i> Edit Profile
                </button>
            </div>

            <div class="card-body p-4">
                @if(session('success'))
                    <div class="alert alert-success py-2 px-3 small rounded-3 mb-4">
                        <i class="fa-solid fa-circle-check me-1"></i> {{ session('success') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger py-2 px-3 small rounded-3 mb-4">
                        <ul class="mb-0 ps-3 small">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('client.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-muted">Client ID</label>
                            <input type="text" class="form-control bg-light" value="#CL{{ sprintf('%03d', $client->client_id) }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-muted">Account Status</label>
                            <div>
                                <span class="badge {{ $client->client_status === 'Active' ? 'bg-success-subtle text-success border border-success' : 'bg-danger-subtle text-danger' }} px-3 py-2 rounded-pill">
                                    <i class="fa-solid fa-circle-check me-1"></i> {{ $client->client_status }}
                                </span>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold small text-muted">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="client_name" class="form-control editable-field bg-light" value="{{ old('client_name', $client->client_name) }}" readonly required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold small text-muted">Email Address (Login ID) <span class="text-danger">*</span></label>
                            <input type="email" name="client_email" class="form-control editable-field bg-light" value="{{ old('client_email', $client->client_email) }}" readonly required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-muted">Primary Phone Number <span class="text-danger">*</span></label>
                            <input type="text" name="primary_contact" class="form-control editable-field bg-light" value="{{ old('primary_contact', $client->primary_contact) }}" readonly required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-muted">Secondary Phone Number</label>
                            <input type="text" name="secondary_contact" class="form-control editable-field bg-light" value="{{ old('secondary_contact', $client->secondary_contact) }}" placeholder="Optional mobile number" readonly>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold small text-muted">Address / Location</label>
                            <input type="text" name="address" class="form-control editable-field bg-light" value="{{ old('address', $client->client_location) }}" placeholder="Street address, unit, building" readonly>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold small text-muted">City</label>
                            <input type="text" name="city" class="form-control editable-field bg-light" value="{{ old('city', $client->city) }}" placeholder="City" readonly>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold small text-muted">State</label>
                            <input type="text" name="state" class="form-control editable-field bg-light" value="{{ old('state', $client->state) }}" placeholder="State" readonly>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold small text-muted">Country</label>
                            <input type="text" name="country" class="form-control editable-field bg-light" value="{{ old('country', $client->country ?? 'India') }}" placeholder="Country" readonly>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold small text-muted">Joined Date</label>
                            <input type="text" class="form-control bg-light" value="{{ $client->joined_date ? $client->joined_date->format('d M Y') : ($client->client_created_date ? \Carbon\Carbon::parse($client->client_created_date)->format('d M Y') : 'N/A') }}" readonly>
                        </div>
                    </div>

                    <div id="saveActions" class="d-none justify-content-end gap-3 mt-4 border-top pt-3">
                        <button type="button" id="cancelBtn" class="btn btn-light px-4 border rounded-3 fw-semibold text-muted">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4 rounded-3 fw-semibold">
                            <i class="fa-solid fa-check me-1"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Column: Subscribed Project & Dedicated Team Information -->
    <div class="col-lg-5">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-white p-4 border-bottom">
                <h5 class="fw-bold mb-1 text-primary">
                    <i class="fa-solid fa-briefcase me-1"></i> Project & Team Information
                </h5>
                <p class="text-muted small mb-0">Details of your contracted project, assigned team leader, and milestone timeline.</p>
            </div>

            <div class="card-body p-4">
                @if($primaryService)
                    <div class="p-3 rounded-3 bg-light border mb-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h5 class="fw-bold text-dark mb-0">{{ $primaryService->service_name }}</h5>
                                <span class="badge bg-primary-subtle text-primary border mt-1">#PRJ{{ sprintf('%03d', $primaryService->id) }}</span>
                            </div>
                            <span class="badge bg-success-subtle text-success px-3 py-1 rounded-pill border">
                                {{ $primaryService->status }}
                            </span>
                        </div>
                    </div>

                    <!-- Team & Leader Details -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted">Assigned Engineering Team</label>
                        <div class="p-2 rounded-3 bg-white border d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-primary-subtle text-primary p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                <i class="fa-solid fa-users"></i>
                            </div>
                            <div class="fw-bold text-dark small">
                                {{ $primaryService->team_name ?: ($primaryService->assigned_team ?? 'Dedicated Engineering Team') }}
                            </div>
                        </div>
                    </div>

                    @if($primaryService->teamLeader)
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Project Team Leader / Lead Engineer</label>
                            <div class="p-2 rounded-3 bg-white border d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-info-subtle text-info p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                    <i class="fa-solid fa-user-tie"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark small">{{ $primaryService->teamLeader->name }}</div>
                                    <span class="text-muted" style="font-size: 11px;">{{ $primaryService->teamLeader->designation }} ({{ $primaryService->teamLeader->department }})</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Timeline -->
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <div class="p-2 rounded-3 bg-light border">
                                <span class="text-muted small d-block" style="font-size: 11px;">Start Date</span>
                                <strong class="text-dark small">{{ $primaryService->start_date ? $primaryService->start_date->format('d M Y') : 'N/A' }}</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 rounded-3 bg-light border">
                                <span class="text-muted small d-block" style="font-size: 11px;">Target Delivery</span>
                                <strong class="text-dark small">{{ $primaryService->end_date ? $primaryService->end_date->format('d M Y') : 'Active Lifecycle' }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Scope Description -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold small text-muted">Project Deliverables & Scope</label>
                        <div class="p-3 rounded-3 bg-light border small text-secondary">
                            {{ $primaryService->description ?: 'Ongoing development, deployment, and technical infrastructure support as configured by your account team.' }}
                        </div>
                    </div>

                    <a href="{{ route('client.tickets.index') }}" class="btn btn-outline-primary w-100 py-2 rounded-3 fw-semibold">
                        <i class="fa-solid fa-ticket me-1"></i> Request Support / Submit Milestone Ticket
                    </a>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="fa-solid fa-folder-open fa-3x mb-3 opacity-50"></i>
                        <p class="mb-1 fw-semibold text-dark">No Active Project Linked</p>
                        <p class="small text-muted mb-0">Your account administrator will assign your contracted project shortly.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editBtn = document.getElementById('editProfileBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        const saveActions = document.getElementById('saveActions');
        const editableFields = document.querySelectorAll('.editable-field');

        function toggleEdit(isEditing) {
            editableFields.forEach(field => {
                if (isEditing) {
                    field.removeAttribute('readonly');
                    field.classList.remove('bg-light');
                    field.classList.add('bg-white');
                } else {
                    field.setAttribute('readonly', 'readonly');
                    field.classList.add('bg-light');
                    field.classList.remove('bg-white');
                }
            });
            
            if (isEditing) {
                editBtn.classList.add('d-none');
                saveActions.classList.remove('d-none');
                saveActions.classList.add('d-flex');
            } else {
                editBtn.classList.remove('d-none');
                saveActions.classList.add('d-none');
                saveActions.classList.remove('d-flex');
            }
        }

        editBtn.addEventListener('click', () => toggleEdit(true));
        cancelBtn.addEventListener('click', () => toggleEdit(false));
    });
</script>
@endpush
