@extends('layouts.admin')

@section('title', isset($staff) ? 'Edit Staff Member - Admin Portal' : 'Add New Staff Member - Admin Portal')
@section('page_title', isset($staff) ? 'Edit Staff Member' : 'Add New Staff Member')

@section('content')
<div class="card">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0 text-primary border-bottom border-2 border-primary pb-2 d-inline-block">Staff Member Information</h5>
            <a href="{{ route('admin.staff.index') }}" class="btn btn-light border text-muted"><i class="fa-solid fa-arrow-left me-1"></i> Back to Staff List</a>
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

        <form id="staffForm" action="{{ isset($staff) ? route('admin.staff.update', $staff) : route('admin.staff.store') }}" method="POST" autocomplete="off">
            @csrf
            @if(isset($staff))
                @method('PUT')
            @endif

            <!-- Hidden dummy fields to neutralize aggressive browser auto-fill -->
            <input type="text" style="display:none">
            <input type="password" style="display:none">

            <div class="row g-4 mb-4">
                <!-- Full Name -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="staffNameInput" class="form-control bg-light" placeholder="e.g. Alex Morgan" value="{{ old('name', $staff->name ?? '') }}" required pattern="[A-Za-z\s\.\'-]+" title="Name should only contain letters and spaces" oninput="this.value = this.value.replace(/[^a-zA-Z\s\.\'-]/g, '')">
                    <div class="form-text small text-muted">Only alphabets and spaces allowed.</div>
                </div>

                <!-- Email -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Work Email Address <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="email" name="email" id="staffEmailInput" class="form-control bg-light" placeholder="e.g. alex@company.com" value="{{ old('email', $staff->email ?? '') }}" required autocomplete="off">
                        @if(!isset($staff))
                            <button type="button" id="btnAdminSendStaffOtp" class="btn btn-outline-primary fw-semibold px-3">
                                <span id="staffSendOtpSpinner" class="spinner-border spinner-border-sm d-none me-1"></span>
                                <i class="fa-solid fa-paper-plane me-1"></i> Send OTP
                            </button>
                        @endif
                    </div>
                    <div id="staffAdminOtpStatus" class="small mt-1"></div>
                </div>

                <!-- Primary Phone -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Primary Contact Number <span class="text-danger">*</span></label>
                    <input type="tel" name="phone" id="staffPrimaryPhone" class="form-control bg-light" placeholder="e.g. 9876543210" value="{{ old('phone', $staff->phone ?? '') }}" required pattern="[0-9+\s\-]{7,15}" title="Numbers only (7-15 digits)" oninput="this.value = this.value.replace(/[^0-9+\s\-]/g, '')">
                    <div class="form-text small text-muted">Digits only (e.g. 9876543210).</div>
                </div>

                <!-- Secondary Phone -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Secondary Contact Number (Optional)</label>
                    <input type="tel" name="secondary_phone" id="staffSecondaryPhone" class="form-control bg-light" placeholder="e.g. 9123456780" value="{{ old('secondary_phone', $staff->secondary_phone ?? '') }}" pattern="[0-9+\s\-]{7,15}" title="Numbers only (7-15 digits)" oninput="this.value = this.value.replace(/[^0-9+\s\-]/g, '')">
                </div>

                <!-- Status -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-muted">Account Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select bg-light" required>
                        <option value="Active" {{ old('status', $staff->status ?? 'Active') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ old('status', $staff->status ?? '') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <!-- Department -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-muted">Department <span class="text-danger">*</span></label>
                    <select name="department" class="form-select bg-light" required>
                        <option value="">Select Department</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept }}" {{ old('department', $staff->department ?? '') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Designation / Role -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-muted">Job Designation <span class="text-danger">*</span></label>
                    <input type="text" name="designation" id="staffDesigInput" list="designationsList" class="form-control bg-light" placeholder="e.g. Lead Developer" value="{{ old('designation', $staff->designation ?? '') }}" required>
                    <datalist id="designationsList">
                        @foreach($designations as $desig)
                            <option value="{{ $desig }}">
                        @endforeach
                    </datalist>
                </div>
            </div>

            <!-- Client Assignments Section -->
            <h5 class="fw-bold mb-3 mt-4 text-primary border-bottom border-2 border-primary pb-2 d-inline-block">Assigned Clients</h5>
            <p class="text-muted small mb-3">Select the clients this staff member will manage and receive support requests from:</p>

            <div class="row g-3 mb-4">
                @forelse($clients as $client)
                    <div class="col-md-4">
                        <div class="form-check card p-3 bg-light border-0 shadow-sm h-100">
                            <input class="form-check-input ms-0 me-2" type="checkbox" name="assigned_clients[]" value="{{ $client->client_id }}" id="client_{{ $client->client_id }}" {{ in_array($client->client_id, old('assigned_clients', $selectedClients ?? [])) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="client_{{ $client->client_id }}">
                                {{ $client->client_name }}
                                @if($client->client_company && $client->client_company !== $client->client_name)
                                    <span class="d-block small text-muted fw-normal">({{ $client->client_company }})</span>
                                @endif
                            </label>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-muted small">No active clients available for assignment.</div>
                @endforelse
            </div>

            @if(!isset($staff))
                <!-- OTP & Manual Password Setup Box for New Staff -->
                <div class="p-4 rounded-3 mb-4 border border-primary-subtle bg-light">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-bold mb-0 text-primary">
                            <i class="fa-solid fa-shield-halved me-1"></i> Email Verification & Password Setup
                        </h6>
                        <span class="badge bg-warning-subtle text-dark border border-warning">
                            <i class="fa-regular fa-clock me-1"></i> OTP Valid for 5 Minutes
                        </span>
                    </div>
                    <p class="text-muted small mb-3">
                        Click <strong>"Send OTP"</strong> above. A 6-digit code will be sent to the staff email. Enter the OTP code below and set their account password manually.
                    </p>

                    <div class="row g-4">
                        <div class="col-md-5">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label fw-semibold small text-muted mb-0">Enter 6-Digit OTP Code <span class="text-danger">*</span></label>
                                <button type="button" id="btnResendStaffOtp" class="btn btn-link p-0 text-primary small text-decoration-none fw-semibold">
                                    <i class="fa-solid fa-rotate-right me-1"></i> Resend OTP
                                </button>
                            </div>
                            <input type="text" name="otp" id="staffOtpCodeField" class="form-control bg-white text-center fw-bold fs-5 tracking-wider" placeholder="• • • • • •" maxlength="6" pattern="\d{6}" required autocomplete="off" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            <div class="form-text small text-muted">Enter the 6-digit code sent to staff email</div>
                        </div>

                        <div class="col-md-7">
                            <label class="form-label fw-semibold small text-muted">Set Staff Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" name="password" id="staffPasswordField" class="form-control bg-white" placeholder="Type your custom password (min 6 chars)" minlength="6" required autocomplete="new-password">
                                <button type="button" class="btn btn-outline-secondary" id="btnToggleStaffPass">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                            <div class="form-text small text-muted">Admin manually assigns the password. The staff member will receive their confirmation credentials email upon account creation.</div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Optional Password Change for Existing Staff -->
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small text-muted">Reset Password (Optional)</label>
                        <input type="password" name="password" class="form-control bg-light" placeholder="Leave blank to keep existing password" minlength="6" autocomplete="new-password">
                    </div>
                </div>
            @endif

            <div class="d-flex justify-content-end gap-3 mt-5">
                <a href="{{ route('admin.staff.index') }}" class="btn btn-light px-4 border rounded-3 fw-semibold text-muted">Cancel</a>
                <button type="submit" class="btn btn-primary px-5 rounded-3 fw-semibold">
                    <i class="fa-solid fa-check-circle me-1"></i> {{ isset($staff) ? 'Update Staff Member' : 'Verify OTP & Create Staff Account' }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnSendOtp = document.getElementById('btnAdminSendStaffOtp');
        const btnResendOtp = document.getElementById('btnResendStaffOtp');
        const spinner = document.getElementById('staffSendOtpSpinner');
        const otpStatus = document.getElementById('staffAdminOtpStatus');
        const emailInput = document.getElementById('staffEmailInput');
        const nameInput = document.getElementById('staffNameInput');
        const desigInput = document.getElementById('staffDesigInput');
        
        const passField = document.getElementById('staffPasswordField');
        const btnTogglePass = document.getElementById('btnToggleStaffPass');

        @if(!isset($staff) && !old('email'))
            // Clear browser autofilled credentials on load
            setTimeout(() => {
                if (emailInput && !emailInput.dataset.userTyped) emailInput.value = '';
                if (passField && !passField.dataset.userTyped) passField.value = '';
            }, 50);
        @endif

        if (emailInput) {
            emailInput.addEventListener('input', function() { this.dataset.userTyped = 'true'; });
        }
        if (passField) {
            passField.addEventListener('input', function() { this.dataset.userTyped = 'true'; });
        }

        function triggerSendOtp() {
            const email = emailInput.value.trim();
            const name = nameInput.value.trim() || 'Staff Member';
            const designation = desigInput.value.trim() || 'Staff';

            if (!email) {
                alert('Please enter a valid Work Email Address first.');
                emailInput.focus();
                return;
            }

            if (btnSendOtp) btnSendOtp.disabled = true;
            if (btnResendOtp) btnResendOtp.disabled = true;
            if (spinner) spinner.classList.remove('d-none');
            otpStatus.innerHTML = '<span class="text-muted"><i class="fa-solid fa-spinner fa-spin me-1"></i> Sending OTP code...</span>';

            fetch("{{ route('admin.staff.send-otp') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    email: email,
                    name: name,
                    designation: designation
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
                    otpStatus.innerHTML = '<span class="text-success fw-semibold"><i class="fa-solid fa-circle-check me-1"></i> OTP code sent to ' + email + ' (Valid for 5 minutes).</span>';
                    const otpField = document.getElementById('staffOtpCodeField');
                    if (otpField) otpField.focus();
                } else {
                    otpStatus.innerHTML = '<span class="text-danger fw-semibold"><i class="fa-solid fa-circle-xmark me-1"></i> ' + (data.message || 'Failed to send OTP') + '</span>';
                }
            })
            .catch(err => {
                if (btnSendOtp) btnSendOtp.disabled = false;
                if (btnResendOtp) btnResendOtp.disabled = false;
                if (spinner) spinner.classList.add('d-none');
                otpStatus.innerHTML = '<span class="text-danger fw-semibold">Error connecting to server.</span>';
            });
        }

        if (btnSendOtp) btnSendOtp.addEventListener('click', triggerSendOtp);
        if (btnResendOtp) btnResendOtp.addEventListener('click', triggerSendOtp);

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
