@extends('layouts.admin')

@section('title', 'Settings - Admin Portal')
@section('page_title', 'System Settings & Profile')

@section('content')
<div class="row g-4">
    <!-- Profile Settings -->
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-white p-4 border-bottom">
                <h5 class="fw-bold mb-0">Profile Information</h5>
            </div>
            <div class="card-body p-4">
                
                @if(session('success') && session('success') == 'Profile updated successfully.')
                    <div class="alert alert-success py-2">{{ session('success') }}</div>
                @endif
                
                <form action="{{ route('admin.settings.profile') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted">Admin Username</label>
                        <input type="text" name="username" class="form-control bg-light @error('username') is-invalid @enderror" value="{{ old('username', $admin->username) }}" required>
                        @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold small text-muted">Admin Email Address</label>
                        <input type="email" name="email" class="form-control bg-light @error('email') is-invalid @enderror" value="{{ old('email', $admin->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <button type="submit" class="btn btn-primary px-4 rounded-3 fw-semibold">Save Changes</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Security Settings -->
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-white p-4 border-bottom">
                <h5 class="fw-bold mb-0">Security (Update Password)</h5>
            </div>
            <div class="card-body p-4">
                
                @if(session('success') && session('success') == 'Password updated successfully.')
                    <div class="alert alert-success py-2">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger py-2">{{ session('error') }}</div>
                @endif

                <form action="{{ route('admin.settings.password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted">Current Password</label>
                        <input type="password" name="current_password" class="form-control bg-light @error('current_password') is-invalid @enderror" required>
                        @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted">New Password</label>
                        <input type="password" name="new_password" class="form-control bg-light @error('new_password') is-invalid @enderror" required>
                        @error('new_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold small text-muted">Confirm New Password</label>
                        <input type="password" name="new_password_confirmation" class="form-control bg-light" required>
                    </div>

                    <button type="submit" class="btn btn-danger px-4 rounded-3 fw-semibold"><i class="fa-solid fa-lock me-2"></i> Update Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
