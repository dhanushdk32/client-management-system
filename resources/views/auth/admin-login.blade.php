@extends('layouts.auth')

@section('title', 'Admin Portal Login')

@section('content')
    <div class="auth-card row mx-2">
        <!-- Left Side: Form -->
        <div class="col-md-6 auth-left">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <h4 class="fw-bold mb-0">Admin Portal</h4>
                <span class="badge bg-dark text-white rounded-pill px-3 py-1 fw-semibold"><i class="fa-solid fa-lock me-1"></i> Admin Area</span>
            </div>
            <p class="text-muted mb-4">Enter your administrative credentials to continue</p>

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

            <form action="{{ route('admin.login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label text-muted small fw-semibold">Admin Username / Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-user-shield text-muted"></i></span>
                        <input type="text" name="email" class="form-control border-start-0 ps-0" placeholder="Enter admin username or email" required value="{{ old('email') }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted small fw-semibold">Admin Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-key text-muted"></i></span>
                        <input type="password" name="password" id="passwordField" class="form-control border-start-0 border-end-0 ps-0" placeholder="Enter password" required>
                        <span class="input-group-text bg-light border-start-0" id="togglePassword" style="cursor: pointer;">
                            <i class="fa-regular fa-eye text-muted"></i>
                        </span>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember">
                        <label class="form-check-label text-muted small" for="remember">Remember this device</label>
                    </div>
                    <a href="{{ route('password.request') }}" class="small fw-semibold text-decoration-none text-muted">Forgot Password?</a>
                </div>

                <button type="submit" class="btn btn-dark w-100 mb-2 rounded-3 fw-bold py-2 shadow-sm" style="background: #1e293b; border-color: #1e293b;">
                    <i class="fa-solid fa-right-to-bracket me-2"></i> ACCESS ADMIN CONSOLE
                </button>
            </form>
        </div>

        <!-- Right Side: Branding & Details -->
        <div class="col-md-6 auth-right d-none d-md-flex" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #fff;">
            <div class="text-center">
                <div class="mb-3 d-inline-flex p-3 rounded-circle" style="background: rgba(255,255,255,0.08);">
                    <i class="fa-solid fa-server" style="font-size: 50px; color: #38bdf8;"></i>
                </div>
                <h3 class="fw-bold text-white">Administration Center</h3>
                <p class="text-light opacity-75 mb-4 small">Central management console for clients, services, tickets, and audits.</p>
                
                <div class="card border-0 bg-transparent text-start w-100 mt-4 text-white">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 40px; height: 40px; background: rgba(255,255,255,0.12);">
                            <i class="fa-solid fa-lock" style="color: #38bdf8;"></i>
                        </div>
                        <div>
                            <div class="text-white-50 fw-bold text-uppercase" style="font-size: 11px;">Access Level</div>
                            <div class="fw-medium text-white">Restricted Administrator Area</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 40px; height: 40px; background: rgba(255,255,255,0.12);">
                            <i class="fa-solid fa-network-wired" style="color: #38bdf8;"></i>
                        </div>
                        <div>
                            <div class="text-white-50 fw-bold text-uppercase" style="font-size: 11px;">Audit & Logging</div>
                            <div class="fw-medium text-white">Real-Time Activity Tracking</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 40px; height: 40px; background: rgba(255,255,255,0.12);">
                            <i class="fa-solid fa-shield-halved" style="color: #38bdf8;"></i>
                        </div>
                        <div>
                            <div class="text-white-50 fw-bold text-uppercase" style="font-size: 11px;">Security Standard</div>
                            <div class="fw-medium text-white">Dual Guard Session Isolation</div>
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
    </script>
@endsection
