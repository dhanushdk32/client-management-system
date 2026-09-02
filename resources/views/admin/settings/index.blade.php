@extends('layouts.admin')

@section('title', 'System & Brand Settings - Admin Portal')
@section('page_title', 'Brand, Company & Account Settings')

@section('content')
<!-- Navigation Pills for Settings Tabs -->
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="fw-bold mb-1 text-primary">System & Entity Configuration</h5>
        <p class="text-muted small mb-0">Configure your company identity, managing director details, custom logos, branding text, and account security.</p>
    </div>
</div>

@if($errors->any())
    <div class="alert alert-danger p-3 mb-4 rounded-3">
        <div class="fw-bold mb-1"><i class="fa-solid fa-circle-exclamation me-1"></i> Please resolve the following errors:</div>
        <ul class="mb-0 ps-3 small">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row g-4">
    <!-- 1. Brand Appearance & Logo Studio -->
    <div class="col-lg-6">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header bg-white p-4 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-primary">
                    <i class="fa-solid fa-palette me-2"></i> Brand Appearance & Logo
                </h5>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1">Identity</span>
            </div>
            <div class="card-body p-4">
                @if(session('success_brand'))
                    <div class="alert alert-success py-2 px-3 small rounded-3 mb-4">
                        <i class="fa-solid fa-circle-check me-1"></i> {{ session('success_brand') }}
                    </div>
                @endif

                <form action="{{ route('admin.settings.brand') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Brand Name & Tagline -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-muted">Brand Display Title <span class="text-danger">*</span></label>
                            <input type="text" name="brand_name" id="inputBrandName" class="form-control bg-light" value="{{ old('brand_name', $settings['brand_name'] ?? 'RORIRI') }}" required placeholder="e.g. RORIRI" oninput="updateLiveBrandPreview()">
                            <div class="form-text small text-muted">Shown on the top navbar across all portals.</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-muted">Active Tab Subtitle</label>
                            <input type="text" name="brand_tagline" id="inputBrandTagline" class="form-control bg-light" value="{{ old('brand_tagline', $settings['brand_tagline'] ?? 'Software Solution') }}" placeholder="e.g. Software Solution" oninput="updateLiveBrandPreview()">
                            <div class="form-text small text-muted">Shown in the top workspace tab.</div>
                        </div>
                    </div>

                    <!-- Live Topbar Preview Card -->
                    <label class="form-label fw-semibold small text-muted mb-2">Live Navbar Brand Preview</label>
                    <div class="p-3 rounded-3 mb-4 border bg-dark text-white d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-white p-1 d-flex align-items-center justify-content-center shadow-sm" style="width: 38px; height: 38px;">
                                <img id="livePreviewLogo" src="{{ \App\Models\SystemSetting::getBrandLogoUrl() }}" alt="Logo" style="width: 28px; height: 28px; object-fit: contain; border-radius: 50%;">
                            </div>
                            <div>
                                <div class="fw-bold text-white fs-6" id="livePreviewBrandText">{{ $settings['brand_name'] ?? 'RORIRI' }}</div>
                                <span class="badge bg-primary-subtle text-primary border" style="font-size: 10px;" id="livePreviewTagline">{{ $settings['brand_tagline'] ?? 'Software Solution' }}</span>
                            </div>
                        </div>
                        <span class="badge bg-secondary small">Live Preview</span>
                    </div>

                    <!-- Logo Selection Option -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold small text-muted mb-2">Choose Brand Logo <span class="text-danger">*</span></label>
                        <div class="d-flex gap-3 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="brand_logo_type" id="typePreset" value="preset" {{ old('brand_logo_type', $settings['brand_logo_type'] ?? 'preset') === 'preset' ? 'checked' : '' }} onchange="toggleLogoSource()">
                                <label class="form-check-label fw-semibold small" for="typePreset">
                                    Select from Preset Logos
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="brand_logo_type" id="typeCustom" value="custom_upload" {{ old('brand_logo_type', $settings['brand_logo_type'] ?? '') === 'custom_upload' ? 'checked' : '' }} onchange="toggleLogoSource()">
                                <label class="form-check-label fw-semibold small" for="typeCustom">
                                    Upload Custom Logo File
                                </label>
                            </div>
                        </div>

                        <!-- 6 Preset Logos Grid -->
                        <div id="presetLogosSection" class="p-3 rounded-3 bg-light border mb-3">
                            <div class="row g-3">
                                @foreach($presets as $key => $preset)
                                    <div class="col-md-4 col-6">
                                        <label class="card h-100 p-2 text-center border cursor-pointer preset-card {{ (old('brand_logo_preset', $settings['brand_logo_preset'] ?? 'original') === $key) ? 'border-primary shadow-sm bg-white' : 'bg-light' }}" style="cursor: pointer;">
                                            <input type="radio" name="brand_logo_preset" value="{{ $key }}" class="d-none" {{ (old('brand_logo_preset', $settings['brand_logo_preset'] ?? 'original') === $key) ? 'checked' : '' }} onchange="selectPresetLogo('{{ $preset['preview'] }}', this)">
                                            <div class="my-2">
                                                <img src="{{ $preset['preview'] }}" alt="{{ $preset['name'] }}" style="width: 44px; height: 44px; object-fit: contain;">
                                            </div>
                                            <div class="small fw-semibold text-dark text-truncate" style="font-size: 11px;">{{ $preset['name'] }}</div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Custom Logo Upload Box -->
                        <div id="customUploadSection" class="p-3 rounded-3 bg-light border mb-3 d-none">
                            <label class="form-label fw-semibold small text-muted">Upload Custom Image (PNG, SVG, JPG - Max 2MB)</label>
                            <input type="file" name="custom_logo" id="customLogoInput" class="form-control bg-white" accept="image/*" onchange="previewCustomUpload(event)">
                            <div class="form-text small text-muted">Transparent square PNG or SVG recommended (512x512px).</div>
                        </div>
                    </div>

                    <!-- Theme Mode Preference -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold small text-muted mb-2">Default Portal Theme</label>
                        <div class="d-flex gap-3">
                            <div class="form-check card p-3 bg-light border w-50">
                                <input class="form-check-input ms-0 me-2" type="radio" name="default_theme" id="themeLight" value="light" {{ old('default_theme', $settings['default_theme'] ?? 'light') === 'light' ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold small" for="themeLight">
                                    <i class="fa-regular fa-sun text-warning me-1"></i> Light Theme
                                </label>
                            </div>
                            <div class="form-check card p-3 bg-light border w-50">
                                <input class="form-check-input ms-0 me-2" type="radio" name="default_theme" id="themeDark" value="dark" {{ old('default_theme', $settings['default_theme'] ?? '') === 'dark' ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold small" for="themeDark">
                                    <i class="fa-regular fa-moon text-primary me-1"></i> Dark Theme
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary px-4 rounded-3 fw-semibold">
                            <i class="fa-solid fa-check me-1"></i> Save Brand Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 2. Company & Managing Director (MD) Profile -->
    <div class="col-lg-6">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header bg-white p-4 border-bottom d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-0 text-primary">
                        <i class="fa-solid fa-building-user me-2"></i> Company & MD Profile
                    </h5>
                </div>
                <span class="badge bg-warning-subtle text-dark border border-warning px-3 py-1">
                    <i class="fa-solid fa-crown me-1 text-warning"></i> MD / Owner
                </span>
            </div>
            <div class="card-body p-4">
                @if(session('success_company'))
                    <div class="alert alert-success py-2 px-3 small rounded-3 mb-4">
                        <i class="fa-solid fa-circle-check me-1"></i> {{ session('success_company') }}
                    </div>
                @endif

                <div class="alert alert-info py-2 px-3 small rounded-3 mb-4">
                    <i class="fa-solid fa-circle-info me-1"></i> <strong>Note:</strong> As the Admin and Managing Director (MD), this profile configures your official corporate credentials across invoices, client communications, and headers.
                </div>

                <form action="{{ route('admin.settings.company') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-muted">Company Legal Name <span class="text-danger">*</span></label>
                            <input type="text" name="company_name" class="form-control bg-light" value="{{ old('company_name', $settings['company_name'] ?? 'RORIRI Software Solutions Pvt Ltd') }}" required placeholder="e.g. RORIRI Software Solutions">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-muted">Managing Director (MD) Name <span class="text-danger">*</span></label>
                            <input type="text" name="md_name" class="form-control bg-light" value="{{ old('md_name', $settings['md_name'] ?? 'Dhanush Kumar') }}" required placeholder="e.g. Dhanush Kumar">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-muted">Official Corporate Email <span class="text-danger">*</span></label>
                            <input type="email" name="company_email" class="form-control bg-light" value="{{ old('company_email', $settings['company_email'] ?? 'contact@roriri.com') }}" required placeholder="contact@roriri.com">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-muted">Contact / Support Hotline</label>
                            <input type="tel" name="company_phone" class="form-control bg-light" value="{{ old('company_phone', $settings['company_phone'] ?? '+91 98765 43210') }}" placeholder="+91 98765 43210">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold small text-muted">Headquarters / Street Address</label>
                            <input type="text" name="company_address" class="form-control bg-light" value="{{ old('company_address', $settings['company_address'] ?? 'Tech Park, Main Boulevard') }}" placeholder="Street Address, Building, Suite">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold small text-muted">City</label>
                            <input type="text" name="company_city" class="form-control bg-light" value="{{ old('company_city', $settings['company_city'] ?? 'Chennai') }}" placeholder="e.g. Chennai">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold small text-muted">State</label>
                            <input type="text" name="company_state" class="form-control bg-light" value="{{ old('company_state', $settings['company_state'] ?? 'Tamil Nadu') }}" placeholder="e.g. Tamil Nadu">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold small text-muted">Country</label>
                            <input type="text" name="company_country" class="form-control bg-light" value="{{ old('company_country', $settings['company_country'] ?? 'India') }}" placeholder="e.g. India">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-muted">GSTIN / Corporate Tax ID</label>
                            <input type="text" name="company_gstin" class="form-control bg-light" value="{{ old('company_gstin', $settings['company_gstin'] ?? '33AABCR1234F1Z5') }}" placeholder="e.g. 33AABCR1234F1Z5">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-muted">Official Website URL</label>
                            <input type="url" name="company_website" class="form-control bg-light" value="{{ old('company_website', $settings['company_website'] ?? 'https://roriri.com') }}" placeholder="https://roriri.com">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold small text-muted">Company Tagline / Mission</label>
                            <textarea name="company_about" class="form-control bg-light" rows="2" placeholder="e.g. Empowering enterprises with next-generation digital technology solutions...">{{ old('company_about', $settings['company_about'] ?? '') }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary px-4 rounded-3 fw-semibold">
                            <i class="fa-solid fa-save me-1"></i> Save Company Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 3. Admin Login Credentials -->
    <div class="col-md-6">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header bg-white p-4 border-bottom">
                <h5 class="fw-bold mb-0 text-primary">
                    <i class="fa-solid fa-user-shield me-2"></i> Admin Account Credentials
                </h5>
            </div>
            <div class="card-body p-4">
                @if(session('success_profile'))
                    <div class="alert alert-success py-2 px-3 small rounded-3 mb-4">
                        <i class="fa-solid fa-circle-check me-1"></i> {{ session('success_profile') }}
                    </div>
                @endif
                
                <form action="{{ route('admin.settings.profile') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted">Admin Full Name</label>
                        <input type="text" name="name" class="form-control bg-light" value="{{ old('name', $admin->name) }}" required placeholder="e.g. Dhanush Kumar">
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold small text-muted">Admin Login Email Address</label>
                        <input type="email" name="email" class="form-control bg-light" value="{{ old('email', $admin->email) }}" required>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary px-4 rounded-3 fw-semibold">Update Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 4. Security & Password -->
    <div class="col-md-6">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header bg-white p-4 border-bottom">
                <h5 class="fw-bold mb-0 text-danger">
                    <i class="fa-solid fa-lock me-2"></i> Security (Change Password)
                </h5>
            </div>
            <div class="card-body p-4">
                @if(session('success_password'))
                    <div class="alert alert-success py-2 px-3 small rounded-3 mb-4">
                        <i class="fa-solid fa-circle-check me-1"></i> {{ session('success_password') }}
                    </div>
                @endif
                @if(session('error_password'))
                    <div class="alert alert-danger py-2 px-3 small rounded-3 mb-4">
                        <i class="fa-solid fa-circle-xmark me-1"></i> {{ session('error_password') }}
                    </div>
                @endif

                <form action="{{ route('admin.settings.password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted">Current Password</label>
                        <div class="input-group">
                            <input type="password" name="current_password" id="adminCurrentPass" class="form-control bg-light border-end-0" required>
                            <button class="btn btn-light border border-start-0" type="button" onclick="togglePassVisibility('adminCurrentPass', this)">
                                <i class="fa-regular fa-eye text-muted"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted">New Password (Min 6 Characters)</label>
                        <div class="input-group">
                            <input type="password" name="new_password" id="adminNewPass" class="form-control bg-light border-end-0" minlength="6" required>
                            <button class="btn btn-light border border-start-0" type="button" onclick="togglePassVisibility('adminNewPass', this)">
                                <i class="fa-regular fa-eye text-muted"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold small text-muted">Confirm New Password</label>
                        <div class="input-group">
                            <input type="password" name="new_password_confirmation" id="adminConfirmPass" class="form-control bg-light border-end-0" minlength="6" required>
                            <button class="btn btn-light border border-start-0" type="button" onclick="togglePassVisibility('adminConfirmPass', this)">
                                <i class="fa-regular fa-eye text-muted"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-danger px-4 rounded-3 fw-semibold">
                            <i class="fa-solid fa-key me-1"></i> Update Security Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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
    function updateLiveBrandPreview() {
        const nameVal = document.getElementById('inputBrandName').value || 'RORIRI';
        const taglineVal = document.getElementById('inputBrandTagline').value || 'Software Solution';
        document.getElementById('livePreviewBrandText').innerText = nameVal;
        document.getElementById('livePreviewTagline').innerText = taglineVal;
    }

    function toggleLogoSource() {
        const isCustom = document.getElementById('typeCustom').checked;
        const presetSection = document.getElementById('presetLogosSection');
        const customSection = document.getElementById('customUploadSection');

        if (isCustom) {
            presetSection.classList.add('d-none');
            customSection.classList.remove('d-none');
        } else {
            presetSection.classList.remove('d-none');
            customSection.classList.add('d-none');
        }
    }

    function selectPresetLogo(imgSrc, radioEl) {
        document.getElementById('livePreviewLogo').src = imgSrc;
        document.querySelectorAll('.preset-card').forEach(card => {
            card.classList.remove('border-primary', 'shadow-sm', 'bg-white');
            card.classList.add('bg-light');
        });
        radioEl.closest('.preset-card').classList.add('border-primary', 'shadow-sm', 'bg-white');
        radioEl.closest('.preset-card').classList.remove('bg-light');
    }

    function previewCustomUpload(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('livePreviewLogo').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        toggleLogoSource();
    });
</script>
@endpush
@endsection
