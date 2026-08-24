@extends('layouts.auth')

@section('title', 'Client Portal Login')

@section('content')
    <div class="auth-card row mx-2">
        <!-- Left Side: Form -->
        <div class="col-md-6 auth-left">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <h4 class="fw-bold mb-0">Client Portal</h4>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 fw-semibold">Client Login</span>
            </div>
            <p class="text-muted mb-4">Sign in to manage your services and documents</p>

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

            <form action="{{ route('client.login') }}" method="POST" autocomplete="off">
                @csrf
                <div class="mb-3">
                    <label class="form-label text-muted small fw-semibold">Client Email / Username</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-regular fa-user text-muted"></i></span>
                        <input type="text" name="email" id="clientEmailField" class="form-control border-start-0 ps-0" placeholder="Enter your registered email" required value="{{ old('email') }}" autocomplete="off">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted small fw-semibold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-lock text-muted"></i></span>
                        <input type="password" name="password" id="passwordField" class="form-control border-start-0 border-end-0 ps-0" placeholder="Enter password" required autocomplete="new-password">
                        <span class="input-group-text bg-light border-start-0" id="togglePassword" style="cursor: pointer;">
                            <i class="fa-regular fa-eye text-muted"></i>
                        </span>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember">
                        <label class="form-check-label text-muted small" for="remember">Remember me</label>
                    </div>
                    <a href="{{ route('password.request') }}" class="small fw-semibold text-decoration-none">Forgot Password?</a>
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-2 rounded-3 fw-bold py-2">SIGN IN TO CLIENT PORTAL</button>
            </form>
        </div>

        <!-- Right Side: Branding & Details -->
        <div class="col-md-6 auth-right d-none d-md-flex">
            <div class="text-center">
                <i class="fa-solid fa-building-user mb-3" style="font-size: 60px; color: #3b5998;"></i>
                <h3 class="fw-bold" style="color: #2b3a8c;">Client Service Portal</h3>
                <p class="text-muted mb-4">Access your project status, service requests, and confidential documents.</p>
                
                <div class="card border-0 bg-transparent text-start w-100 mt-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-white rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 40px; height: 40px;">
                            <i class="fa-regular fa-envelope" style="color: #3b5998;"></i>
                        </div>
                        <div>
                            <div class="small text-muted fw-bold text-uppercase" style="font-size: 11px;">Client Support Email</div>
                            <div class="fw-medium" style="color: #2b3a8c;">support@company.com</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-white rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 40px; height: 40px;">
                            <i class="fa-solid fa-phone" style="color: #3b5998;"></i>
                        </div>
                        <div>
                            <div class="small text-muted fw-bold text-uppercase" style="font-size: 11px;">Helpdesk Contact</div>
                            <div class="fw-medium" style="color: #2b3a8c;">+1 (800) 555-0199</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="bg-white rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 40px; height: 40px;">
                            <i class="fa-solid fa-shield-halved" style="color: #3b5998;"></i>
                        </div>
                        <div>
                            <div class="small text-muted fw-bold text-uppercase" style="font-size: 11px;">Security Guarantee</div>
                            <div class="fw-medium" style="color: #2b3a8c;">End-to-End Encrypted Data</div>
                        </div>
                    </div>
                </div>
            </div>
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

        // Ensure browser autofill does not populate admin credentials into client portal
        window.addEventListener('DOMContentLoaded', () => {
            const emailInput = document.getElementById('clientEmailField');
            const pwdInput = document.getElementById('passwordField');
            if (emailInput && !emailInput.value.includes('{{ old('email') }}')) {
                setTimeout(() => {
                    if (emailInput.value.includes('admin')) {
                        emailInput.value = '';
                        if (pwdInput) pwdInput.value = '';
                    }
                }, 100);
            }
        });
    </script>
@endsection
