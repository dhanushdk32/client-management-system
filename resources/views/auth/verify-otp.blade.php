@extends('layouts.auth')

@section('title', 'Verify OTP')

@section('content')
<div class="auth-card row mx-2" style="max-width: 500px;">
    <div class="col-12 auth-left p-5 text-center">
        <i class="fa-solid fa-envelope-open-text mb-3" style="font-size: 50px; color: #3b5998;"></i>
        <h4 class="fw-bold mb-1">Verify OTP</h4>
        <p class="text-muted mb-4 small">We sent a 6-digit code to <strong>{{ session('reset_email') }}</strong>. Please enter it below.</p>

        @if($errors->any())
            <div class="alert alert-danger p-2 mb-3 text-start">
                <ul class="mb-0 ps-3 small">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger p-2 mb-3 text-start small">
                {{ session('error') }}
            </div>
        @endif
        
        @if(session('success'))
            <div class="alert alert-success p-2 mb-3 text-start small">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('password.verify') }}" method="POST">
            @csrf
            <div class="mb-4 text-start">
                <label class="form-label text-muted small fw-semibold">6-Digit OTP</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-key text-muted"></i></span>
                    <input type="text" name="otp" class="form-control border-start-0 ps-0 text-center fw-bold" placeholder="• • • • • •" required maxlength="6" style="letter-spacing: 5px; font-size: 18px;">
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3 rounded-3">Verify Code</button>
        </form>
    </div>
</div>
@endsection
