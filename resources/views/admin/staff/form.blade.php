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

        <form action="{{ isset($staff) ? route('admin.staff.update', $staff) : route('admin.staff.store') }}" method="POST" autocomplete="off">
            @csrf
            @if(isset($staff))
                @method('PUT')
            @endif

            <div class="row g-4 mb-4">
                <!-- Full Name -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Full Name</label>
                    <input type="text" name="name" class="form-control bg-light" placeholder="e.g. Alex Morgan" value="{{ old('name', $staff->name ?? '') }}" required>
                </div>

                <!-- Email -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Work Email Address</label>
                    <input type="email" name="email" class="form-control bg-light" placeholder="alex@itcompany.com" value="{{ old('email', $staff->email ?? '') }}" required>
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
                        <option value="Pending Activation" {{ old('status', $staff->status ?? 'Pending Activation') == 'Pending Activation' ? 'selected' : '' }}>Pending Activation (User sets password via OTP)</option>
                        <option value="Active" {{ old('status', $staff->status ?? '') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ old('status', $staff->status ?? '') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <!-- Department -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Department</label>
                    <select name="department" class="form-select bg-light" required>
                        <option value="">Select Department</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept }}" {{ old('department', $staff->department ?? '') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Designation / Role -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Job Designation</label>
                    <input type="text" name="designation" list="designationsList" class="form-control bg-light" placeholder="e.g. Lead Developer" value="{{ old('designation', $staff->designation ?? '') }}" required>
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

            <!-- Optional Initial Password -->
            <h5 class="fw-bold mb-3 mt-4 text-primary border-bottom border-2 border-primary pb-2 d-inline-block">Direct Password (Optional)</h5>
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">
                        {{ isset($staff) ? 'Reset Password (Optional)' : 'Set Password Immediately (Optional)' }}
                    </label>
                    <input type="password" name="password" class="form-control bg-light" placeholder="{{ isset($staff) ? 'Leave blank to keep existing password' : 'Leave blank to send Welcome OTP for user to create password' }}" minlength="8" autocomplete="new-password">
                    <div class="form-text small text-muted">
                        {{ isset($staff) ? 'Leave blank if you do not wish to change the password.' : 'If left blank, an automated Welcome Email with a 6-digit OTP code will be sent to the staff member to activate and set their password.' }}
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-3 mt-5">
                <a href="{{ route('admin.staff.index') }}" class="btn btn-light px-4 border rounded-3 fw-semibold text-muted">Cancel</a>
                <button type="submit" class="btn btn-primary px-5 rounded-3 fw-semibold">Save Staff Member</button>
            </div>
        </form>
    </div>
</div>
@endsection
