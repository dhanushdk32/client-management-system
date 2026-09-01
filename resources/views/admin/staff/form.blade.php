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
            <div class="alert alert-danger p-2 mb-4">
                <ul class="mb-0 ps-3">
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

            <div class="row g-4 mb-4">
                <!-- Full Name -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="staffNameInput" class="form-control bg-light" placeholder="e.g. Alex Morgan" value="{{ old('name', $staff->name ?? '') }}" required>
                </div>

                <!-- Email -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Work Email Address <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="email" name="email" id="staffEmailInput" class="form-control bg-light" placeholder="alex@itcompany.com" value="{{ old('email', $staff->email ?? '') }}" required>
                        @if(!isset($staff))
                            <button type="button" id="btnAdminSendStaffOtp" class="btn btn-outline-primary fw-semibold px-3">
                                <span id="staffSendOtpSpinner" class="spinner-border spinner-border-sm d-none me-1"></span>
                                <i class="fa-solid fa-paper-plane me-1"></i> Send OTP to Email
                            </button>
                        @endif
                    </div>
                    <div id="staffAdminOtpStatus" class="small mt-1"></div>
                </div>

                <!-- Phone -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Phone Number</label>
                    <input type="text" name="phone" class="form-control bg-light" placeholder="+1 (555) 000-0000" value="{{ old('phone', $staff->phone ?? '') }}">
                </div>

                <!-- Status -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Account Status</label>
                    <select name="status" class="form-select bg-light" required>
                        <option value="Active" {{ old('status', $staff->status ?? 'Active') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ old('status', $staff->status ?? '') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <!-- Department -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Department <span class="text-danger">*</span></label>
                    <select name="department" class="form-select bg-light" required>
                        <option value="">Select Department</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept }}" {{ old('department', $staff->department ?? '') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Designation / Role -->
                <div class="col-md-6">
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
                                {{ $client->client_company }}
                                <span class="d-block small text-muted fw-normal">{{ $client->client_name }}</span>
                            </label>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-muted small">No active clients available for assignment.</div>
                @endforelse
            </div>

            @if(!isset($staff))
                <!-- OTP & Password Setup Box for New Staff -->
                <div class="p-4 rounded-3 mb-4 border border-primary-subtle bg-light">
                    <h6 class="fw-bold mb-2 text-primary">
                        <i class="fa-solid fa-shield-halved me-1"></i> Email Verification & Staff Password Setup
                    </h6>
                    <p class="text-muted small mb-3">
                        Click <strong>"Send OTP to Email"</strong> above. A welcome email containing a 6-digit OTP code will be sent to the staff member. Enter the OTP code below and set their password.
                    </p>

                    <div class="row g-4">
                        <div class="col-md-5">
                            <label class="form-label fw-semibold small text-muted">Enter 6-Digit OTP Code <span class="text-danger">*</span></label>
                            <input type="text" name="otp" id="staffOtpCodeField" class="form-control bg-white text-center fw-bold fs-5 tracking-wider" placeholder="• • • • • •" maxlength="6" pattern="\d{6}" required>
                            <div class="form-text small text-muted">Received on the staff member's email</div>
                        </div>

                        <div class="col-md-7">
                            <label class="form-label fw-semibold small text-muted">Create Staff Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" name="password" id="staffPasswordField" class="form-control bg-white" placeholder="Minimum 6 characters" minlength="6" required>
                                <button type="button" class="btn btn-outline-secondary" id="btnToggleStaffPass">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-outline-primary fw-semibold" id="btnGenStaffPass">
                                    <i class="fa-solid fa-wand-magic-sparkles me-1"></i> Auto-Generate
                                </button>
                            </div>
                            <div class="form-text small text-muted">This password will be automatically emailed to the staff member along with their Staff Portal login link upon confirmation.</div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Optional Password Change for Existing Staff -->
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small text-muted">Reset Password (Optional)</label>
                        <input type="password" name="password" class="form-control bg-light" placeholder="Leave blank to keep existing password" minlength="6">
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
        const spinner = document.getElementById('staffSendOtpSpinner');
        const otpStatus = document.getElementById('staffAdminOtpStatus');
        const emailInput = document.getElementById('staffEmailInput');
        const nameInput = document.getElementById('staffNameInput');
        const desigInput = document.getElementById('staffDesigInput');
        
        const passField = document.getElementById('staffPasswordField');
        const btnTogglePass = document.getElementById('btnToggleStaffPass');
        const btnGenPass = document.getElementById('btnGenStaffPass');

        if (btnSendOtp) {
            btnSendOtp.addEventListener('click', function() {
                const email = emailInput.value.trim();
                const name = nameInput.value.trim() || 'Staff Member';
                const designation = desigInput.value.trim() || 'Staff';

                if (!email) {
                    alert('Please enter a valid Work Email Address first.');
                    emailInput.focus();
                    return;
                }

                btnSendOtp.disabled = true;
                spinner.classList.remove('d-none');
                otpStatus.innerHTML = '<span class="text-muted"><i class="fa-solid fa-spinner fa-spin me-1"></i> Sending welcome email with OTP...</span>';

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
                    btnSendOtp.disabled = false;
                    spinner.classList.add('d-none');
                    if (data.success) {
                        otpStatus.innerHTML = '<span class="text-success fw-semibold"><i class="fa-solid fa-circle-check me-1"></i> ' + data.message + '</span>';
                        document.getElementById('staffOtpCodeField').focus();
                    } else {
                        otpStatus.innerHTML = '<span class="text-danger fw-semibold"><i class="fa-solid fa-circle-xmark me-1"></i> ' + (data.message || 'Failed to send OTP') + '</span>';
                    }
                })
                .catch(err => {
                    btnSendOtp.disabled = false;
                    spinner.classList.add('d-none');
                    otpStatus.innerHTML = '<span class="text-danger fw-semibold">Error connecting to server.</span>';
                });
            });
        }

        if (btnTogglePass && passField) {
            btnTogglePass.addEventListener('click', function() {
                const icon = this.querySelector('i');
                const isPassword = passField.type === 'password';
                passField.type = isPassword ? 'text' : 'password';
                icon.classList.toggle('fa-eye', !isPassword);
                icon.classList.toggle('fa-eye-slash', isPassword);
            });
        }

        if (btnGenPass && passField) {
            btnGenPass.addEventListener('click', function() {
                const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789!@#$%';
                let randomPass = '';
                for (let i = 0; i < 10; i++) {
                    randomPass += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                passField.value = randomPass;
                passField.type = 'text';
                const icon = btnTogglePass.querySelector('i');
                if (icon) {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                }
            });
        }
    });
</script>
@endpush
@endsection
