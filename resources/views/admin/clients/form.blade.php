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

        <form action="{{ isset($client) ? route('admin.clients.update', $client) : route('admin.clients.store') }}" method="POST">
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
                    <input type="email" name="client_email" class="form-control bg-light" placeholder="Enter email" value="{{ old('client_email', $client->client_email ?? '') }}" required>
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

            <div class="d-flex justify-content-end gap-3 mt-5">
                <a href="{{ route('admin.clients.index') }}" class="btn btn-light px-4 border rounded-3 fw-semibold text-muted">Cancel</a>
                <button type="submit" class="btn btn-primary px-5 rounded-3 fw-semibold">Save Client</button>
            </div>
        </form>
    </div>
</div>
@endsection
