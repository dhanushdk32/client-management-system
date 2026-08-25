@extends('layouts.client')

@section('title', 'My Profile - Client Portal')
@section('page_title', 'My Profile')

@section('content')
<div class="card">
    <div class="card-body p-4">
        
        @if(session('success'))
            <div class="alert alert-success py-2 mb-4">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger py-2 mb-4">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('client.profile.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0 text-primary border-bottom border-2 border-primary pb-2 d-inline-block">Company Information</h5>
                <button type="button" id="editProfileBtn" class="btn btn-outline-primary px-4 rounded-3"><i class="fa-solid fa-pen me-2"></i> Edit Profile</button>
            </div>

            <div class="row g-4 mb-5">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Company Name</label>
                    <input type="text" name="client_company" class="form-control editable-field bg-light" value="{{ old('client_company', $client->client_company) }}" readonly required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Industry</label>
                    <select name="industry" class="form-select editable-field bg-light" disabled>
                        <option value="">Select Industry</option>
                        <option value="IT Services" {{ old('industry', $client->industry) == 'IT Services' ? 'selected' : '' }}>IT Services</option>
                        <option value="Finance" {{ old('industry', $client->industry) == 'Finance' ? 'selected' : '' }}>Finance</option>
                        <option value="Healthcare" {{ old('industry', $client->industry) == 'Healthcare' ? 'selected' : '' }}>Healthcare</option>
                        <option value="Manufacturing" {{ old('industry', $client->industry) == 'Manufacturing' ? 'selected' : '' }}>Manufacturing</option>
                        <option value="Retail" {{ old('industry', $client->industry) == 'Retail' ? 'selected' : '' }}>Retail</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Company Size</label>
                    <select name="company_size" class="form-select editable-field bg-light" disabled>
                        <option value="">Select Size</option>
                        <option value="1 - 10" {{ old('company_size', $client->company_size) == '1 - 10' ? 'selected' : '' }}>1 - 10</option>
                        <option value="11 - 50" {{ old('company_size', $client->company_size) == '11 - 50' ? 'selected' : '' }}>11 - 50</option>
                        <option value="51 - 100" {{ old('company_size', $client->company_size) == '51 - 100' ? 'selected' : '' }}>51 - 100</option>
                        <option value="101 - 500" {{ old('company_size', $client->company_size) == '101 - 500' ? 'selected' : '' }}>101 - 500</option>
                        <option value="500+" {{ old('company_size', $client->company_size) == '500+' ? 'selected' : '' }}>500+</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Website</label>
                    <input type="text" name="website" class="form-control editable-field bg-light" value="{{ old('website', $client->website) }}" placeholder="https://example.com" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">GST Number</label>
                    <input type="text" name="client_gst" class="form-control editable-field bg-light" value="{{ old('client_gst', $client->client_gst) }}" placeholder="Enter GST number" readonly>
                </div>
            </div>

            <h5 class="fw-bold mb-4 text-primary border-bottom border-2 border-primary pb-2 d-inline-block">Contact Information</h5>

            <div class="row g-4 mb-5">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Primary Contact Person</label>
                    <input type="text" name="client_name" class="form-control editable-field bg-light" value="{{ old('client_name', $client->client_name) }}" readonly required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Primary Email Address</label>
                    <input type="email" name="client_email" class="form-control editable-field bg-light" value="{{ old('client_email', $client->client_email) }}" readonly required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Primary Phone</label>
                    <input type="text" name="primary_contact" class="form-control editable-field bg-light" value="{{ old('primary_contact', $client->primary_contact) }}" readonly required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Secondary Phone / Mobile</label>
                    <input type="text" name="secondary_contact" class="form-control editable-field bg-light" value="{{ old('secondary_contact', $client->secondary_contact) }}" placeholder="Optional" readonly>
                </div>
            </div>

            <h5 class="fw-bold mb-4 text-primary border-bottom border-2 border-primary pb-2 d-inline-block">Address & Location</h5>

            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-muted">City</label>
                    <input type="text" name="city" class="form-control editable-field bg-light" value="{{ old('city', $client->city) }}" placeholder="e.g. New York" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-muted">State / Province</label>
                    <input type="text" name="state" class="form-control editable-field bg-light" value="{{ old('state', $client->state) }}" placeholder="e.g. NY" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-muted">Country</label>
                    <input type="text" name="country" class="form-control editable-field bg-light" value="{{ old('country', $client->country) }}" placeholder="e.g. USA" readonly>
                </div>
            </div>



            <div id="saveActions" class="d-none justify-content-end gap-3 mt-5 border-top pt-4">
                <button type="button" id="cancelBtn" class="btn btn-light px-4 border rounded-3 fw-semibold text-muted">Cancel</button>
                <button type="submit" class="btn btn-primary px-5 rounded-3 fw-semibold">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
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
                    field.removeAttribute('disabled');
                    field.classList.remove('bg-light');
                } else {
                    field.setAttribute('readonly', 'readonly');
                    if (field.tagName === 'SELECT') {
                        field.setAttribute('disabled', 'disabled');
                    }
                    field.classList.add('bg-light');
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
@endsection
