@extends('layouts.admin')

@section('title', isset($client) ? 'Edit Client - Admin Portal' : 'Add New Client - Admin Portal')
@section('page_title', isset($client) ? 'Edit Client Details' : 'Add New Client')

@section('content')
<div class="card">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="fw-bold mb-1 text-primary border-bottom border-2 border-primary pb-2 d-inline-block">
                    {{ isset($client) ? 'Edit Client #' . sprintf('CL%03d', $client->client_id) : 'Add New Client' }}
                </h5>
                <p class="text-muted small mb-0">Fill in the client's information, project details, location, and verify via Gmail OTP.</p>
            </div>
            <a href="{{ route('admin.clients.index') }}" class="btn btn-light border text-muted"><i class="fa-solid fa-arrow-left me-1"></i> Back to Clients</a>
        </div>

        @if($errors->any())
            <div class="alert alert-danger p-3 mb-4 rounded-3">
                <div class="fw-bold mb-1"><i class="fa-solid fa-circle-exclamation me-1"></i> Please resolve the following errors:</div>
                <ul class="mb-0 ps-3 small">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="clientForm" action="{{ isset($client) ? route('admin.clients.update', $client->client_id) : route('admin.clients.store') }}" method="POST" autocomplete="off">
            @csrf
            @if(isset($client))
                @method('PUT')
            @endif

            <!-- 1. Client Personal & Contact Details -->
            <h6 class="fw-bold mb-3 text-secondary border-bottom pb-2">
                <i class="fa-solid fa-user me-1 text-primary"></i> 1. Client Information
            </h6>
            <div class="row g-4 mb-4">
                <!-- Client Name -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Client Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="client_name" id="clientName" class="form-control bg-light" placeholder="e.g. John Doe" value="{{ old('client_name', $client->client_name ?? '') }}" required pattern="[A-Za-z\s\.\'-]+" title="Name should only contain letters and spaces" oninput="this.value = this.value.replace(/[^a-zA-Z\s\.\'-]/g, '')">
                    <div class="form-text small text-muted">Only alphabets and spaces allowed.</div>
                </div>

                <!-- Client Status -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Client Status <span class="text-danger">*</span></label>
                    <select name="client_status" class="form-select bg-light" required>
                        <option value="Active" {{ old('client_status', $client->client_status ?? 'Active') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ old('client_status', $client->client_status ?? '') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <!-- Primary Phone -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Primary Contact Number <span class="text-danger">*</span></label>
                    <input type="tel" name="primary_contact" id="primaryContact" class="form-control bg-light" placeholder="e.g. 9876543210" value="{{ old('primary_contact', $client->primary_contact ?? '') }}" required pattern="[0-9+\s\-]{7,15}" title="Numbers only (7-15 digits)" oninput="this.value = this.value.replace(/[^0-9+\s\-]/g, '')">
                    <div class="form-text small text-muted">Digits only (e.g. 9876543210).</div>
                </div>

                <!-- Secondary Phone -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Secondary Contact Number (Optional)</label>
                    <input type="tel" name="secondary_contact" id="secondaryContact" class="form-control bg-light" placeholder="e.g. 9123456780" value="{{ old('secondary_contact', $client->secondary_contact ?? '') }}" pattern="[0-9+\s\-]{7,15}" title="Numbers only (7-15 digits)" oninput="this.value = this.value.replace(/[^0-9+\s\-]/g, '')">
                </div>
            </div>

            <!-- 2. Project & Timeline Details -->
            <h6 class="fw-bold mb-3 mt-4 text-secondary border-bottom pb-2">
                <i class="fa-solid fa-briefcase me-1 text-primary"></i> 2. Project & Timeline
            </h6>
            <div class="row g-4 mb-4">
                <!-- Project Title -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Project Title / Subscribed Service</label>
                    <input type="text" name="project_title" id="projectTitle" list="projectSuggestions" class="form-control bg-light" placeholder="e.g. Mobile Application Development" value="{{ old('project_title', isset($primaryService) ? $primaryService->service_name : ($client->client_company ?? '')) }}">
                    <datalist id="projectSuggestions">
                        <option value="Mobile Application (Android & iOS)">
                        <option value="Custom ERP & Business Portal">
                        <option value="Corporate Website Redesign">
                        <option value="Cloud Infrastructure & API Setup">
                        <option value="UI/UX Design & Branding">
                    </datalist>
                </div>

                <!-- Joined Date -->
                <div class="col-md-3">
                    <label class="form-label fw-semibold small text-muted">Client Joined Date</label>
                    <input type="date" name="joined_date" class="form-control bg-light" value="{{ old('joined_date', isset($client) && $client->joined_date ? \Carbon\Carbon::parse($client->joined_date)->format('Y-m-d') : date('Y-m-d')) }}">
                </div>

                <!-- End Date -->
                <div class="col-md-3">
                    <label class="form-label fw-semibold small text-muted">Project Target / End Date</label>
                    <input type="date" name="end_date" class="form-control bg-light" value="{{ old('end_date', isset($primaryService) && $primaryService->end_date ? \Carbon\Carbon::parse($primaryService->end_date)->format('Y-m-d') : '') }}">
                </div>
            </div>

            <!-- 3. Location Details -->
            <h6 class="fw-bold mb-3 mt-4 text-secondary border-bottom pb-2">
                <i class="fa-solid fa-location-dot me-1 text-primary"></i> 3. Location Details
            </h6>
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Street Address</label>
                    <input type="text" name="address" class="form-control bg-light" placeholder="e.g. 123 Main Street, Suite 400" value="{{ old('address', $client->client_location ?? '') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold small text-muted">City</label>
                    <input type="text" name="city" class="form-control bg-light" placeholder="e.g. Chennai" value="{{ old('city', $client->city ?? '') }}" pattern="[A-Za-z\s\.\'-]*" oninput="this.value = this.value.replace(/[^a-zA-Z\s\.\'-]/g, '')">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold small text-muted">State</label>
                    <input type="text" name="state" class="form-control bg-light" placeholder="e.g. Tamil Nadu" value="{{ old('state', $client->state ?? '') }}" pattern="[A-Za-z\s\.\'-]*" oninput="this.value = this.value.replace(/[^a-zA-Z\s\.\'-]/g, '')">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold small text-muted">Country</label>
                    <input type="text" name="country" class="form-control bg-light" placeholder="e.g. India" value="{{ old('country', $client->country ?? 'India') }}" pattern="[A-Za-z\s\.\'-]*" oninput="this.value = this.value.replace(/[^a-zA-Z\s\.\'-]/g, '')">
                </div>
            </div>

            <!-- 4. Authentication, OTP & Password Section -->
            <h6 class="fw-bold mb-3 mt-4 text-secondary border-bottom pb-2">
                <i class="fa-solid fa-shield-halved me-1 text-primary"></i> 4. Login Credentials & OTP Verification
            </h6>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Client Gmail / Email Address <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="email" name="client_email" id="clientEmail" class="form-control bg-light" placeholder="client@gmail.com" value="{{ old('client_email', $client->client_email ?? '') }}" required>
                        @if(!isset($client))
                            <button type="button" id="btnSendOtp" class="btn btn-outline-primary fw-semibold px-3">
                                <span id="sendOtpSpinner" class="spinner-border spinner-border-sm d-none me-1"></span>
                                <i class="fa-solid fa-paper-plane me-1"></i> Send OTP
                            </button>
                        @endif
                    </div>
                    <div id="otpStatusMessage" class="small mt-1"></div>
                </div>

                @if(!isset($client))
                    <div class="col-12">
                        <div class="p-4 rounded-3 border border-primary-subtle bg-light">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold text-primary small">
                                    <i class="fa-solid fa-key me-1"></i> Account Security Setup
                                </span>
                                <span class="badge bg-warning-subtle text-dark border border-warning">
                                    <i class="fa-regular fa-clock me-1"></i> OTP Valid for 5 Minutes
                                </span>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-5">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label class="form-label fw-semibold small text-muted mb-0">Enter 6-Digit OTP Code <span class="text-danger">*</span></label>
                                        <button type="button" id="btnResendClientOtp" class="btn btn-link p-0 text-primary small text-decoration-none fw-semibold">
                                            <i class="fa-solid fa-rotate-right me-1"></i> Resend OTP
                                        </button>
                                    </div>
                                    <input type="text" name="otp" id="otpCodeField" class="form-control bg-white text-center fw-bold fs-5 tracking-wider" placeholder="• • • • • •" maxlength="6" pattern="\d{6}" required oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                    <div class="form-text small text-muted">Sent to client's Gmail address</div>
                                </div>

                                <div class="col-md-7">
                                    <label class="form-label fw-semibold small text-muted">Set Client Password <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" name="password" id="clientPasswordField" class="form-control bg-white" placeholder="Set custom password (min 6 chars)" minlength="6" required>
                                        <button type="button" class="btn btn-outline-secondary" id="btnTogglePassword">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="form-text small text-muted">Admin manually assigns this password. Credentials are automatically mailed to the client upon creation.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small text-muted">Reset Password (Optional)</label>
                        <input type="password" name="password" class="form-control bg-light" placeholder="Leave blank to keep existing password" minlength="6">
                    </div>
                @endif
            </div>

            <div class="d-flex justify-content-end gap-3 mt-5">
                <a href="{{ route('admin.clients.index') }}" class="btn btn-light px-4 border rounded-3 fw-semibold text-muted">Cancel</a>
                <button type="submit" id="btnSubmitForm" class="btn btn-primary px-5 rounded-3 fw-semibold">
                    <i class="fa-solid fa-check-circle me-1"></i> {{ isset($client) ? 'Update Client Details' : 'Verify OTP & Create Client' }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnSendOtp = document.getElementById('btnSendOtp');
        const btnResendOtp = document.getElementById('btnResendClientOtp');
        const spinner = document.getElementById('sendOtpSpinner');
        const otpStatusMessage = document.getElementById('otpStatusMessage');
        const emailInput = document.getElementById('clientEmail');
        const nameInput = document.getElementById('clientName');
        const projectInput = document.getElementById('projectTitle');
        
        const passField = document.getElementById('clientPasswordField');
        const btnTogglePass = document.getElementById('btnTogglePassword');

        function triggerSendClientOtp() {
            const email = emailInput.value.trim();
            const name = nameInput.value.trim() || 'Client';
            const project = projectInput ? projectInput.value.trim() : 'Client Project';

            if (!email) {
                alert('Please enter a valid Client Gmail / Email address first.');
                emailInput.focus();
                return;
            }

            if (btnSendOtp) btnSendOtp.disabled = true;
            if (btnResendOtp) btnResendOtp.disabled = true;
            if (spinner) spinner.classList.remove('d-none');
            otpStatusMessage.innerHTML = '<span class="text-muted"><i class="fa-solid fa-spinner fa-spin me-1"></i> Sending OTP code...</span>';

            fetch("{{ route('admin.clients.send-otp') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    email: email,
                    name: name,
                    project_title: project
                })
            })
            .then(res => res.json())
            .then(data => {
                if (btnSendOtp) {
                    btnSendOtp.disabled = false;
                    btnSendOtp.innerHTML = '<i class="fa-solid fa-paper-plane me-1"></i> Resend OTP';
                }
                if (btnResendOtp) btnResendOtp.disabled = false;
                if (spinner) spinner.classList.add('d-none');
                
                if (data.success) {
                    otpStatusMessage.innerHTML = '<span class="text-success fw-semibold"><i class="fa-solid fa-circle-check me-1"></i> 6-Digit OTP sent to ' + email + ' (Valid for 5 minutes).</span>';
                    const otpField = document.getElementById('otpCodeField');
                    if (otpField) otpField.focus();
                } else {
                    otpStatusMessage.innerHTML = '<span class="text-danger fw-semibold"><i class="fa-solid fa-circle-xmark me-1"></i> ' + (data.message || 'Failed to send OTP') + '</span>';
                }
            })
            .catch(err => {
                if (btnSendOtp) btnSendOtp.disabled = false;
                if (btnResendOtp) btnResendOtp.disabled = false;
                if (spinner) spinner.classList.add('d-none');
                otpStatusMessage.innerHTML = '<span class="text-danger fw-semibold">Error connecting to server.</span>';
            });
        }

        if (btnSendOtp) btnSendOtp.addEventListener('click', triggerSendClientOtp);
        if (btnResendOtp) btnResendOtp.addEventListener('click', triggerSendClientOtp);

        if (btnTogglePass && passField) {
            btnTogglePass.addEventListener('click', function() {
                const icon = this.querySelector('i');
                const isPassword = passField.type === 'password';
                passField.type = isPassword ? 'text' : 'password';
                icon.classList.toggle('fa-eye', !isPassword);
                icon.classList.toggle('fa-eye-slash', isPassword);
            });
        }
    });
</script>
@endpush
@endsection
