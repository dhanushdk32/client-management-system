@extends('layouts.auth')

@section('title', 'Activate Account - Client Management System')

@section('content')
    <div class="auth-card row mx-2">
        <div class="col-md-6 auth-left">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <h4 class="fw-bold mb-0 text-primary">Activate Account</h4>
                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1 fw-semibold">
                    <i class="fa-solid fa-shield-halved me-1"></i> OTP Verification
                </span>
            </div>
            <p class="text-muted mb-4">Enter the 6-digit activation code sent to your email</p>

            @if(session('success'))
                <div class="alert alert-success p-2 mb-3">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger p-2 mb-3">
                    {{ session('error') }}
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

            <form action="{{ route('account.activate.verify') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label text-muted fw-semibold small">Registered Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-regular fa-envelope text-muted"></i></span>
                        <input type="email" name="email" class="form-control border-start-0 ps-0" placeholder="your-email@domain.com" value="{{ old('email', request('email')) }}" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label text-muted fw-semibold small">6-Digit Activation OTP Code</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-key text-muted"></i></span>
                        <input type="text" name="otp" class="form-control border-start-0 ps-0 text-center fw-bold fs-5 tracking-wide" placeholder="• • • • • •" maxlength="6" pattern="\d{6}" required autofocus>
                    </div>
                    <div class="form-text small text-muted">Check your spam folder if you do not see the email in your inbox.</div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold rounded-3 mb-3">
                    Verify Code & Continue <i class="fa-solid fa-arrow-right ms-2"></i>
                </button>
            </form>

            <div class="text-center mt-3 pt-3 border-top">
                <span class="text-muted small">Already activated?</span>
                <a href="{{ route('login') }}" class="text-primary text-decoration-none fw-semibold small ms-1">Go to Login</a>
            </div>
        </div>

        <div class="col-md-6 auth-right text-center d-flex flex-column justify-content-center p-4">
            <div class="auth-right-content">
                <div class="mb-4">
                    <div class="d-inline-flex p-4 rounded-circle bg-white shadow-sm mb-3 text-primary">
                        <i class="fa-solid fa-shield-virus fa-3x"></i>
                    </div>
                </div>
                <h4 class="fw-bold mb-2">Secure Account Activation</h4>
                <p class="text-muted mb-0 small">Verify your identity with single-use OTP encryption before creating your private password.</p>
            </div>
        </div>
    </div>
@endsection
