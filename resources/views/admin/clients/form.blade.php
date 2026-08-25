@extends('layouts.admin')

@section('title', isset($client) ? 'Edit Client - Admin Portal' : 'Add New Client - Admin Portal')
@section('page_title', isset($client) ? 'Edit Client' : 'Add New Client')

@section('content')
<div class="card">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0 text-primary border-bottom border-2 border-primary pb-2 d-inline-block">Basic Information</h5>
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

        <form action="{{ isset($client) ? route('admin.clients.update', $client) : route('admin.clients.store') }}" method="POST" autocomplete="off">
            @csrf
            @if(isset($client))
                @method('PUT')
            @endif

            <div class="row g-4 mb-4">
                <!-- Company Name -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Company Name</label>
                    <input type="text" name="client_company" class="form-control bg-light" value="{{ old('client_company', $client->client_company ?? '') }}" required>
                </div>
                
                <!-- Industry -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Industry</label>
                    <select name="industry" class="form-select bg-light">
                        <option value="">Select Industry</option>
                        <option value="IT Services" {{ old('industry', $client->industry ?? '') == 'IT Services' ? 'selected' : '' }}>IT Services</option>
                        <option value="Finance" {{ old('industry', $client->industry ?? '') == 'Finance' ? 'selected' : '' }}>Finance</option>
                        <option value="Healthcare" {{ old('industry', $client->industry ?? '') == 'Healthcare' ? 'selected' : '' }}>Healthcare</option>
                        <option value="Manufacturing" {{ old('industry', $client->industry ?? '') == 'Manufacturing' ? 'selected' : '' }}>Manufacturing</option>
                        <option value="Retail" {{ old('industry', $client->industry ?? '') == 'Retail' ? 'selected' : '' }}>Retail</option>
                    </select>
                </div>

                <!-- Company Size -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Company Size</label>
                    <select name="company_size" class="form-select bg-light">
                        <option value="">Select Size</option>
                        <option value="1 - 10" {{ old('company_size', $client->company_size ?? '') == '1 - 10' ? 'selected' : '' }}>1 - 10</option>
                        <option value="11 - 50" {{ old('company_size', $client->company_size ?? '') == '11 - 50' ? 'selected' : '' }}>11 - 50</option>
                        <option value="51 - 100" {{ old('company_size', $client->company_size ?? '') == '51 - 100' ? 'selected' : '' }}>51 - 100</option>
                        <option value="101 - 500" {{ old('company_size', $client->company_size ?? '') == '101 - 500' ? 'selected' : '' }}>101 - 500</option>
                        <option value="500+" {{ old('company_size', $client->company_size ?? '') == '500+' ? 'selected' : '' }}>500+</option>
                    </select>
                </div>

                <!-- Website -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Website</label>
                    <input type="text" name="website" class="form-control bg-light" placeholder="Enter website" value="{{ old('website', $client->website ?? '') }}">
                </div>

                <!-- GST Number -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">GST Number</label>
                    <input type="text" name="client_gst" class="form-control bg-light" placeholder="Enter GST number" value="{{ old('client_gst', $client->client_gst ?? '') }}">
                </div>

                <!-- Status -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Account Status</label>
                    <select name="client_status" class="form-select bg-light" required>
                        <option value="Active" {{ old('client_status', $client->client_status ?? '') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ old('client_status', $client->client_status ?? '') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <h5 class="fw-bold mb-4 mt-5 text-primary border-bottom border-2 border-primary pb-2 d-inline-block">Primary Contact</h5>

            <div class="row g-4 mb-4">
                <!-- Contact Name -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Name</label>
                    <input type="text" name="client_name" class="form-control bg-light" placeholder="Enter name" value="{{ old('client_name', $client->client_name ?? '') }}" required>
                </div>
                
                <!-- Email -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Email</label>
                    <input type="email" name="client_email" id="clientEmailInput" class="form-control bg-light" placeholder="Enter email" value="{{ old('client_email', $client->client_email ?? '') }}" required autocomplete="off">
                </div>

                <!-- Primary Contact -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Phone / Primary Contact</label>
                    <input type="text" name="primary_contact" class="form-control bg-light" placeholder="Enter phone" value="{{ old('primary_contact', $client->primary_contact ?? '') }}" required>
                </div>
                
                <!-- Secondary Contact -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Secondary Contact</label>
                    <input type="text" name="secondary_contact" class="form-control bg-light" placeholder="Optional" value="{{ old('secondary_contact', $client->secondary_contact ?? '') }}">
                </div>


            </div>

            <h5 class="fw-bold mb-4 mt-5 text-primary border-bottom border-2 border-primary pb-2 d-inline-block">Account Login & Password</h5>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">
                        {{ isset($client) ? 'Reset Client Password (Optional)' : 'Initial Password (Optional)' }}
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-key text-muted"></i></span>
                        <input type="password" name="password" id="clientPasswordField" class="form-control border-start-0 border-end-0 ps-0" placeholder="{{ isset($client) ? 'Leave blank to keep existing password' : 'Leave blank to auto-generate password' }}" minlength="6" autocomplete="new-password">
                        <span class="input-group-text bg-light border-start-0" id="toggleClientPassword" style="cursor: pointer;" title="Show/Hide Password">
                            <i class="fa-regular fa-eye text-muted"></i>
                        </span>
                        <button type="button" class="btn btn-outline-secondary px-3" id="generatePasswordBtn" title="Generate Random Password">
                            <i class="fa-solid fa-dice me-1"></i> Auto-Generate
                        </button>
                    </div>
                    <div class="form-text small text-muted">
                        {{ isset($client) ? 'If left blank, the client\'s current password will not be modified.' : 'If left blank, a secure 10-character password will be auto-generated and emailed to the client.' }}
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-3 mt-5">
                <a href="{{ route('admin.clients.index') }}" class="btn btn-light px-4 border rounded-3 fw-semibold text-muted">Cancel</a>
                <button type="submit" class="btn btn-primary px-5 rounded-3 fw-semibold">Save Client</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const emailField = document.getElementById('clientEmailInput');
        const passwordField = document.getElementById('clientPasswordField');
        const toggleBtn = document.getElementById('toggleClientPassword');
        const generateBtn = document.getElementById('generatePasswordBtn');

        // Prevent browser password manager from auto-filling admin email/password on create form
        @if(!isset($client))
            setTimeout(() => {
                if (emailField && emailField.value.includes('admin')) {
                    emailField.value = '';
                }
                if (passwordField && !passwordField.value.includes('{{ old('password') }}')) {
                    passwordField.value = '';
                }
            }, 100);
        @endif

        if (toggleBtn && passwordField) {
            toggleBtn.addEventListener('click', function() {
                const icon = this.querySelector('i');
                if (passwordField.type === 'password') {
                    passwordField.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    passwordField.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        }

        if (generateBtn && passwordField) {
            generateBtn.addEventListener('click', function() {
                const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789!@#$%&*';
                let randomPassword = '';
                for (let i = 0; i < 10; i++) {
                    randomPassword += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                passwordField.value = randomPassword;
                passwordField.type = 'text';
                const icon = toggleBtn.querySelector('i');
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
