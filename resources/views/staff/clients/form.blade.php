@extends('layouts.staff')

@section('title', 'Onboard New Client - Staff Portal')
@section('page_title', 'Onboard New Client')

@section('content')
<div class="card">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="fw-bold mb-0 text-primary border-bottom border-2 border-primary pb-2 d-inline-block">Client Company Details</h5>
                <p class="text-muted small mt-2 mb-0">The system will automatically send an automated 6-digit activation OTP code to the client's email address to verify their identity and set their password.</p>
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

        <form action="{{ route('staff.clients.store') }}" method="POST" autocomplete="off">
            @csrf

            <div class="row g-4 mb-4">
                <!-- Company Name -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Company / Organization Name</label>
                    <input type="text" name="client_company" class="form-control bg-light" placeholder="e.g. Acme Corp" value="{{ old('client_company') }}" required>
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
                    <label class="form-label fw-semibold small text-muted">Contact Person Name</label>
                    <input type="text" name="client_name" class="form-control bg-light" placeholder="e.g. Sarah Jenkins" value="{{ old('client_name') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Primary Email Address (Login & OTP)</label>
                    <input type="email" name="client_email" class="form-control bg-light" placeholder="sarah@acme.com" value="{{ old('client_email') }}" required>
                    <div class="form-text small text-muted">The 6-digit activation code will be delivered here.</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Primary Phone</label>
                    <input type="text" name="primary_contact" class="form-control bg-light" placeholder="+1 (555) 000-0000" value="{{ old('primary_contact') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Secondary Phone (Optional)</label>
                    <input type="text" name="secondary_contact" class="form-control bg-light" placeholder="Optional" value="{{ old('secondary_contact') }}">
                </div>
            </div>

            <div class="d-flex justify-content-end gap-3 mt-5">
                <a href="{{ route('staff.clients.index') }}" class="btn btn-light px-4 border rounded-3 fw-semibold text-muted">Cancel</a>
                <button type="submit" class="btn btn-primary px-5 rounded-3 fw-semibold">
                    <i class="fa-solid fa-paper-plane me-2"></i> Save Client & Send Welcome OTP
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
