@extends('layouts.auth')

@section('title', 'Staff Portal Login - IT Operations')

@section('content')
    <div class="auth-card row mx-2">
        <!-- Left Side: Form -->
        <div class="col-md-6 auth-left">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <h4 class="fw-bold mb-0 text-primary">Staff Portal</h4>
                <span class="badge bg-primary text-white rounded-pill px-3 py-1 fw-semibold"><i class="fa-solid fa-code me-1"></i> Team Access</span>
            </div>
            <p class="text-muted mb-4">Sign in to access your assigned projects and client tickets</p>

            @if(session('success'))
                <div class="alert alert-success p-2 mb-3">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger p-2 mb-3">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('staff.login.submit') }}" method="POST" autocomplete="off">
                @csrf
                <div class="mb-3">
                    <label class="form-label text-muted fw-semibold small">Staff Work Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-regular fa-envelope text-muted"></i></span>
                        <input type="email" name="email" id="staffEmail" class="form-control border-start-0 ps-0" placeholder="developer@itcompany.com" value="{{ old('email') }}" required autofocus autocomplete="off">
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <label class="form-label text-muted fw-semibold small mb-0">Password</label>
                        <a href="{{ route('password.request') }}" class="text-decoration-none small text-primary">Forgot Password?</a>
                    </div>
                    <div class="input-group mt-1">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-lock text-muted"></i></span>
                        <input type="password" name="password" id="staffPassword" class="form-control border-start-0 border-end-0 ps-0" placeholder="••••••••" required autocomplete="new-password">
                        <span class="input-group-text bg-light border-start-0" id="togglePassword" style="cursor: pointer;">
                            <i class="fa-regular fa-eye text-muted"></i>
                        </span>
                    </div>
                </div>

                <div class="mb-4 d-flex justify-content-between align-items-center">
                    <div class="form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="rememberMe">
                        <label class="form-check-label small text-muted" for="rememberMe">Remember me</label>
                    </div>
                    <a href="{{ route('account.activate') }}" class="small text-decoration-none text-muted">Activate Account</a>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold rounded-3 mb-3">
                    Sign In to Staff Console <i class="fa-solid fa-arrow-right-to-bracket ms-2"></i>
                </button>
            </form>
        </div>

        <!-- Right Side: Marketing / Info -->
        <div class="col-md-6 auth-right text-center d-flex flex-column justify-content-center p-4" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);">
            <div class="auth-right-content text-white">
                <div class="mb-4">
                    <div class="d-inline-flex p-4 rounded-circle bg-white bg-opacity-10 shadow-sm mb-3">
                        <i class="fa-solid fa-laptop-code fa-3x text-white"></i>
                    </div>
                </div>
                <h4 class="fw-bold mb-2">Engineering & Support Workspace</h4>
                <p class="text-white-50 mb-0 small">Collaborate with client teams, resolve service requests, and track project deliverables seamlessly.</p>
            </div>
        </div>
    </div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('togglePassword');
        const passField = document.getElementById('staffPassword');

        if (toggleBtn && passField) {
            toggleBtn.addEventListener('click', function() {
                const icon = this.querySelector('i');
                const isPassword = passField.type === 'password';
                passField.type = isPassword ? 'text' : 'password';
                icon.classList.toggle('fa-eye', !isPassword);
                icon.classList.toggle('fa-eye-slash', isPassword);
            });
        }
    });
</script>
@endpush
@endsection
