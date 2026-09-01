@extends('layouts.auth')

@section('title', 'Set Your Password - Client Management System')

@section('content')
    <div class="auth-card row mx-2">
        <div class="col-md-6 auth-left">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <h4 class="fw-bold mb-0 text-success">Create Password</h4>
                <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1 fw-semibold">
                    <i class="fa-solid fa-check-circle me-1"></i> OTP Verified
                </span>
            </div>
            <p class="text-muted mb-4">Set a strong password for your new account</p>

            @if($errors->any())
                <div class="alert alert-danger p-2 mb-3">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('account.activate.save-password') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label text-muted fw-semibold small">Account Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-regular fa-envelope text-muted"></i></span>
                        <input type="email" class="form-control bg-light border-start-0 ps-0 text-muted" value="{{ session('activation_email') }}" readonly>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted fw-semibold small">New Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-lock text-muted"></i></span>
                        <input type="password" name="password" id="actPasswordField" class="form-control border-start-0 border-end-0 ps-0" placeholder="At least 8 characters" minlength="8" required autofocus>
                        <span class="input-group-text bg-light border-start-0" id="toggleActPass" style="cursor: pointer;">
                            <i class="fa-regular fa-eye text-muted"></i>
                        </span>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label text-muted fw-semibold small">Confirm New Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-lock-open text-muted"></i></span>
                        <input type="password" name="password_confirmation" id="actConfirmPasswordField" class="form-control border-start-0 ps-0" placeholder="Re-type your password" minlength="8" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-success w-100 py-2 fw-semibold rounded-3 mb-3">
                    Save Password & Activate Account <i class="fa-solid fa-check ms-2"></i>
                </button>
            </form>
        </div>

        <div class="col-md-6 auth-right text-center d-flex flex-column justify-content-center p-4">
            <div class="auth-right-content">
                <div class="mb-4">
                    <div class="d-inline-flex p-4 rounded-circle bg-white shadow-sm mb-3 text-success">
                        <i class="fa-solid fa-lock fa-3x"></i>
                    </div>
                </div>
                <h4 class="fw-bold mb-2">Password Guidelines</h4>
                <p class="text-muted mb-0 small">Choose a strong password with at least 8 characters including uppercase letters, numbers, and special characters.</p>
            </div>
        </div>
    </div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passField = document.getElementById('actPasswordField');
        const confirmField = document.getElementById('actConfirmPasswordField');
        const toggleBtn = document.getElementById('toggleActPass');

        if (toggleBtn && passField) {
            toggleBtn.addEventListener('click', function() {
                const icon = this.querySelector('i');
                const isPassword = passField.type === 'password';
                passField.type = isPassword ? 'text' : 'password';
                confirmField.type = isPassword ? 'text' : 'password';
                icon.classList.toggle('fa-eye', !isPassword);
                icon.classList.toggle('fa-eye-slash', isPassword);
            });
        }
    });
</script>
@endpush
@endsection
