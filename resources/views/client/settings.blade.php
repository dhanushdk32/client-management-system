@extends('layouts.client')

@section('title', 'Settings - Client Portal')
@section('page_title', 'Security Settings')

@section('content')
<div class="row g-4 justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white p-4 border-bottom">
                <h5 class="fw-bold mb-0">Update Password</h5>
            </div>
            <div class="card-body p-4">
                
                @if(session('success'))
                    <div class="alert alert-success py-2">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger py-2">{{ session('error') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger py-2">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('client.settings.password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted">Current Password</label>
                        <input type="password" name="current_password" class="form-control bg-light" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted">New Password</label>
                        <input type="password" name="new_password" class="form-control bg-light" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold small text-muted">Confirm New Password</label>
                        <input type="password" name="new_password_confirmation" class="form-control bg-light" required>
                    </div>

                    <button type="submit" class="btn btn-primary px-4 rounded-3 fw-semibold"><i class="fa-solid fa-lock me-2"></i> Update Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
