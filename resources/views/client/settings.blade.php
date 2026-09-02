@extends('layouts.client')

@section('title', 'Settings - Client Portal')
@section('page_title', 'Security Settings')

@section('content')
<div class="row g-4 justify-content-center">
    <div class="col-md-7">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white p-4 border-bottom">
                <h5 class="fw-bold mb-0 text-primary"><i class="fa-solid fa-lock me-2"></i> Update Password</h5>
                <p class="text-muted small mb-0 mt-1">Manage your client account password and login security credentials.</p>
            </div>
            <div class="card-body p-4">
                
                @if(session('success'))
                    <div class="alert alert-success py-2 px-3 small rounded-3 mb-3"><i class="fa-solid fa-circle-check me-1"></i> {{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger py-2 px-3 small rounded-3 mb-3"><i class="fa-solid fa-circle-xmark me-1"></i> {{ session('error') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger py-2 px-3 small rounded-3 mb-3">
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
                    
                    <!-- Current Password -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted">Current Password</label>
                        <div class="input-group">
                            <input type="password" name="current_password" id="clientCurrentPass" class="form-control bg-light border-end-0" placeholder="Enter current password" required>
                            <button class="btn btn-light border border-start-0" type="button" onclick="togglePassVisibility('clientCurrentPass', this)">
                                <i class="fa-regular fa-eye text-muted"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- New Password -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted">New Password</label>
                        <div class="input-group">
                            <input type="password" name="new_password" id="clientNewPass" class="form-control bg-light border-end-0" placeholder="At least 6 characters" minlength="6" required>
                            <button class="btn btn-light border border-start-0" type="button" onclick="togglePassVisibility('clientNewPass', this)">
                                <i class="fa-regular fa-eye text-muted"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold small text-muted">Confirm New Password</label>
                        <div class="input-group">
                            <input type="password" name="new_password_confirmation" id="clientConfirmPass" class="form-control bg-light border-end-0" placeholder="Re-type new password" minlength="6" required>
                            <button class="btn btn-light border border-start-0" type="button" onclick="togglePassVisibility('clientConfirmPass', this)">
                                <i class="fa-regular fa-eye text-muted"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary px-4 py-2 rounded-3 fw-semibold">
                            <i class="fa-solid fa-key me-1"></i> Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function togglePassVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye', 'fa-regular');
            icon.classList.add('fa-eye-slash', 'fa-solid', 'text-primary');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash', 'fa-solid', 'text-primary');
            icon.classList.add('fa-eye', 'fa-regular', 'text-muted');
        }
    }
</script>
@endpush
