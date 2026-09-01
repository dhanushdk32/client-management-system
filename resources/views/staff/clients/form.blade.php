@extends('layouts.staff')

@section('title', 'Onboard New Client - Staff Portal')
@section('page_title', 'Onboard New Client')

@section('content')
<div class="card">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="fw-bold mb-0 text-primary border-bottom border-2 border-primary pb-2 d-inline-block">Client Company Details</h5>
                <p class="text-muted small mt-2 mb-0">Fill in the client details and send a Welcome Verification OTP to their Gmail. Once verified, you can create their password and finalize their account.</p>
            </div>
            <a href="{{ route('staff.clients.index') }}" class="btn btn-light border text-muted"><i class="fa-solid fa-arrow-left me-1"></i> Back to Clients</a>
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

        <form id="staffClientForm" action="{{ route('staff.clients.store') }}" method="POST" autocomplete="off">
            @csrf

            <div class="row g-4 mb-4">
                <!-- Company Name -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Company / Organization Name <span class="text-danger">*</span></label>
                    <input type="text" name="client_company" id="staffClientCompany" class="form-control bg-light" placeholder="e.g. Acme Corp" value="{{ old('client_company') }}" required>
                </div>

                <!-- Industry -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Industry Sector</label>
                    <select name="industry" class="form-select bg-light">
                        <option value="">Select Industry</option>
                        @foreach($industries as $ind)
                            <option value="{{ $ind }}" {{ old('industry') == $ind ? 'selected' : '' }}>{{ $ind }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Company Size -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Company Size</label>
                    <select name="company_size" class="form-select bg-light">
                        <option value="">Select Size</option>
                        @foreach($companySizes as $size)
                            <option value="{{ $size }}" {{ old('company_size') == $size ? 'selected' : '' }}>{{ $size }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Website -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Company Website</label>
                    <input type="text" name="website" class="form-control bg-light" placeholder="https://example.com" value="{{ old('website') }}">
                </div>

                <!-- GST Number -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">GST / Tax Identification</label>
                    <input type="text" name="client_gst" class="form-control bg-light" placeholder="Tax ID / GST Number" value="{{ old('client_gst') }}">
                </div>
            </div>

            <!-- Primary Contact Section -->
            <h5 class="fw-bold mb-4 mt-4 text-primary border-bottom border-2 border-primary pb-2 d-inline-block">Primary Client Contact</h5>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Contact Person Name <span class="text-danger">*</span></label>
                    <input type="text" name="client_name" id="staffClientName" class="form-control bg-light" placeholder="e.g. Sarah Jenkins" value="{{ old('client_name') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Primary Email Address (Login & OTP) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="email" name="client_email" id="staffClientEmail" class="form-control bg-light" placeholder="sarah@acme.com" value="{{ old('client_email') }}" required>
                        <button type="button" id="btnStaffSendOtp" class="btn btn-outline-primary fw-semibold px-3">
                            <span id="staffOtpSpinner" class="spinner-border spinner-border-sm d-none me-1"></span>
                            <i class="fa-solid fa-paper-plane me-1"></i> Send OTP to Email
                        </button>
                    </div>
                    <div id="staffOtpStatusMessage" class="small mt-1"></div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Primary Phone <span class="text-danger">*</span></label>
                    <input type="text" name="primary_contact" class="form-control bg-light" placeholder="+1 (555) 000-0000" value="{{ old('primary_contact') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Secondary Phone (Optional)</label>
                    <input type="text" name="secondary_contact" class="form-control bg-light" placeholder="Optional" value="{{ old('secondary_contact') }}">
                </div>
            </div>

            <!-- OTP & Password Setup Box -->
            <div id="staffVerificationSection" class="p-4 rounded-3 mb-4 border border-primary-subtle bg-light">
                <h6 class="fw-bold mb-2 text-primary">
                    <i class="fa-solid fa-shield-halved me-1"></i> Email Verification & Password Setup
                </h6>
                <p class="text-muted small mb-3">
                    Click <strong>"Send OTP to Email"</strong> above. A welcome email containing a 6-digit OTP code will be sent to the client. Enter the OTP code below and set their password.
                </p>

                <div class="row g-4">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold small text-muted">Enter 6-Digit OTP Code <span class="text-danger">*</span></label>
                        <input type="text" name="otp" id="staffOtpCodeField" class="form-control bg-white text-center fw-bold fs-5 tracking-wider" placeholder="• • • • • •" maxlength="6" pattern="\d{6}" required>
                        <div class="form-text small text-muted">Received on the client's email</div>
                    </div>

                    <div class="col-md-7">
                        <label class="form-label fw-semibold small text-muted">Create Client Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" name="password" id="staffClientPasswordField" class="form-control bg-white" placeholder="Minimum 6 characters" minlength="6" required>
                            <button type="button" class="btn btn-outline-secondary" id="btnStaffTogglePassword">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                            <button type="button" class="btn btn-outline-primary fw-semibold" id="btnStaffGeneratePassword">
                                <i class="fa-solid fa-wand-magic-sparkles me-1"></i> Auto-Generate
                            </button>
                        </div>
                        <div class="form-text small text-muted">This password will be automatically emailed to the client along with their login link upon confirmation.</div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-3 mt-5">
                <a href="{{ route('staff.clients.index') }}" class="btn btn-light px-4 border rounded-3 fw-semibold text-muted">Cancel</a>
                <button type="submit" class="btn btn-primary px-5 rounded-3 fw-semibold">
                    <i class="fa-solid fa-check-circle me-1"></i> Verify OTP & Create Account
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnSendOtp = document.getElementById('btnStaffSendOtp');
        const spinner = document.getElementById('staffOtpSpinner');
        const otpStatusMessage = document.getElementById('staffOtpStatusMessage');
        const emailInput = document.getElementById('staffClientEmail');
        const nameInput = document.getElementById('staffClientName');
        const companyInput = document.getElementById('staffClientCompany');
        
        const passField = document.getElementById('staffClientPasswordField');
        const btnTogglePass = document.getElementById('btnStaffTogglePassword');
        const btnGenPass = document.getElementById('btnStaffGeneratePassword');

        if (btnSendOtp) {
            btnSendOtp.addEventListener('click', function() {
                const email = emailInput.value.trim();
                const name = nameInput.value.trim() || 'Client';
                const company = companyInput.value.trim() || 'Company';

                if (!email) {
                    alert('Please enter a valid Client Email Address first.');
                    emailInput.focus();
                    return;
                }

                btnSendOtp.disabled = true;
                spinner.classList.remove('d-none');
                otpStatusMessage.innerHTML = '<span class="text-muted"><i class="fa-solid fa-spinner fa-spin me-1"></i> Sending welcome email with OTP...</span>';

                fetch("{{ route('staff.clients.send-otp') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        email: email,
                        name: name,
                        company: company
                    })
                })
                .then(res => res.json())
                .then(data => {
                    btnSendOtp.disabled = false;
                    spinner.classList.add('d-none');
                    if (data.success) {
                        otpStatusMessage.innerHTML = '<span class="text-success fw-semibold"><i class="fa-solid fa-circle-check me-1"></i> ' + data.message + '</span>';
                        document.getElementById('staffOtpCodeField').focus();
                    } else {
                        otpStatusMessage.innerHTML = '<span class="text-danger fw-semibold"><i class="fa-solid fa-circle-xmark me-1"></i> ' + (data.message || 'Failed to send OTP') + '</span>';
                    }
                })
                .catch(err => {
                    btnSendOtp.disabled = false;
                    spinner.classList.add('d-none');
                    otpStatusMessage.innerHTML = '<span class="text-danger fw-semibold">Error connecting to server.</span>';
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
