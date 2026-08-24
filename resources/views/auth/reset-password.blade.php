@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
<div class="auth-card row mx-2" style="max-width: 500px;">
    <div class="col-12 auth-left p-5 text-center">
        <i class="fa-solid fa-key mb-3" style="font-size: 50px; color: #3b5998;"></i>
        <h4 class="fw-bold mb-1">Set New Password</h4>
        <p class="text-muted mb-4 small">Your OTP has been verified. Please enter your new password below.</p>

        @if($errors->any())
            <div class="alert alert-danger p-2 mb-3 text-start">
                <ul class="mb-0 ps-3 small">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('password.reset') }}" method="POST">
            @csrf
            <div class="mb-3 text-start">
                <label class="form-label text-muted small fw-semibold">New Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-lock text-muted"></i></span>
                    <input type="password" name="password" id="passwordField" class="form-control border-start-0 border-end-0 ps-0" placeholder="Enter new password" required minlength="8">
                    <span class="input-group-text bg-light border-start-0" id="togglePassword" style="cursor: pointer;">
                        <i class="fa-regular fa-eye text-muted"></i>
                    </span>
                </div>
            </div>
            
            <div class="mb-4 text-start">
                <label class="form-label text-muted small fw-semibold">Confirm Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-lock text-muted"></i></span>
                    <input type="password" name="password_confirmation" id="passwordFieldConfirm" class="form-control border-start-0 border-end-0 ps-0" placeholder="Confirm new password" required minlength="8">
                    <span class="input-group-text bg-light border-start-0" id="togglePasswordConfirm" style="cursor: pointer;">
                        <i class="fa-regular fa-eye text-muted"></i>
                    </span>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3 rounded-3">Reset Password</button>
        </form>
    </div>
</div>

<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordField = document.getElementById('passwordField');
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

    document.getElementById('togglePasswordConfirm').addEventListener('click', function () {
        const passwordField = document.getElementById('passwordFieldConfirm');
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
</script>
@endsection
