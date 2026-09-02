@extends('layouts.staff')

@section('title', 'Staff Settings - Profile & Security')
@section('page_title', 'Staff Profile & Settings')

@section('content')
<div class="row g-4">
    <!-- Left Column: Staff Profile Info -->
    <div class="col-md-6">
        <div class="card p-4 border-0 shadow-sm">
            <h5 class="fw-bold mb-3 text-primary border-bottom pb-2">Profile Information</h5>
            
            <div class="mb-3">
                <label class="form-label text-muted small fw-semibold">Full Name</label>
                <input type="text" class="form-control bg-light" value="{{ $staff->name }}" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label text-muted small fw-semibold">Work Email Address</label>
                <input type="email" class="form-control bg-light" value="{{ $staff->email }}" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label text-muted small fw-semibold">Primary Contact</label>
                <input type="text" class="form-control bg-light" value="{{ $staff->phone ?? 'Not set' }}" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label text-muted small fw-semibold">Designation & Department</label>
                <input type="text" class="form-control bg-light" value="{{ $staff->designation }} ({{ $staff->department }})" readonly>
            </div>

            <div>
                <label class="form-label text-muted small fw-semibold">Account Status</label>
                <div>
                    <span class="badge bg-success-subtle text-success border px-3 py-1">{{ $staff->status }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Change Password -->
    <div class="col-md-6">
        <div class="card p-4 border-0 shadow-sm">
            <h5 class="fw-bold mb-3 text-primary border-bottom pb-2">Change Password</h5>

            @if(session('success'))
                <div class="alert alert-success py-2 mb-3"><i class="fa-solid fa-circle-check me-1"></i> {{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger py-2 mb-3"><i class="fa-solid fa-circle-xmark me-1"></i> {{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger py-2 mb-3">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('staff.password.update') }}" method="POST">
                @csrf
                <!-- Current Password -->
                <div class="mb-3">
                    <label class="form-label text-muted small fw-semibold">Current Password</label>
                    <div class="input-group">
                        <input type="password" name="current_password" id="staffCurrentPass" class="form-control bg-light border-end-0" placeholder="Enter current password" required>
                        <button class="btn btn-light border border-start-0" type="button" onclick="togglePassVisibility('staffCurrentPass', this)">
                            <i class="fa-regular fa-eye text-muted"></i>
                        </button>
                    </div>
                </div>

                <!-- New Password -->
                <div class="mb-3">
                    <label class="form-label text-muted small fw-semibold">New Password</label>
                    <div class="input-group">
                        <input type="password" name="new_password" id="staffNewPass" class="form-control bg-light border-end-0" placeholder="At least 8 characters" minlength="8" required>
                        <button class="btn btn-light border border-start-0" type="button" onclick="togglePassVisibility('staffNewPass', this)">
                            <i class="fa-regular fa-eye text-muted"></i>
                        </button>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <label class="form-label text-muted small fw-semibold">Confirm New Password</label>
                    <div class="input-group">
                        <input type="password" name="new_password_confirmation" id="staffConfirmPass" class="form-control bg-light border-end-0" placeholder="Re-type new password" minlength="8" required>
                        <button class="btn btn-light border border-start-0" type="button" onclick="togglePassVisibility('staffConfirmPass', this)">
                            <i class="fa-regular fa-eye text-muted"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                    <i class="fa-solid fa-key me-1"></i> Update Password & Send Confirmation
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function togglePassVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye', 'fa-regular');
            icon.classList.add('fa-eye-slash', 'fa-solid', 'text-primary');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash', 'fa-solid', 'text-primary');
            icon.classList.add('fa-eye', 'fa-regular', 'text-muted');
        }
    }
</script>
@endpush
