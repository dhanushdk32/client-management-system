@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('content')
<div class="auth-card row mx-2" style="max-width: 500px;">
    <div class="col-12 auth-left p-5 text-center">
        <i class="fa-solid fa-lock mb-3" style="font-size: 50px; color: #3b5998;"></i>
        <h4 class="fw-bold mb-1">Forgot Password</h4>
        <p class="text-muted mb-4 small">Enter your email address and we'll send you a 6-digit OTP to reset your password.</p>

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

        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            <div class="mb-4 text-start">
                <label class="form-label text-muted small fw-semibold">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-regular fa-envelope text-muted"></i></span>
                    <input type="email" name="email" class="form-control border-start-0 ps-0" placeholder="Enter your email" required value="{{ old('email') }}">
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3 rounded-3">Send OTP</button>

            <div class="text-center text-muted small">
                Remember your password? <a href="{{ route('login') }}" class="fw-bold">Back to Login</a>
            </div>
        </form>
    </div>
</div>
@endsection
