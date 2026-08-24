@extends('layouts.client')

@section('title', 'My Profile - Client Portal')
@section('page_title', 'My Profile')

@section('content')
<div class="card">
    <div class="card-body p-4">
        
        @if(session('success'))
            <div class="alert alert-success py-2 mb-4">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger py-2 mb-4">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('client.profile.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0 text-primary border-bottom border-2 border-primary pb-2 d-inline-block">Company Information</h5>
                <button type="button" id="editProfileBtn" class="btn btn-outline-primary px-4 rounded-3"><i class="fa-solid fa-pen me-2"></i> Edit Profile</button>
            </div>

            <div class="row g-4 mb-5">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Company Name</label>
                    <input type="text" class="form-control bg-light" value="{{ $client->client_company }}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Industry</label>
                    <input type="text" class="form-control bg-light" value="{{ $client->industry }}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Company Size</label>
                    <input type="text" class="form-control bg-light" value="{{ $client->company_size }}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Website</label>
                    <input type="text" name="website" class="form-control editable-field bg-light" value="{{ $client->website }}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">GST Number</label>
                    <input type="text" class="form-control bg-light" value="{{ $client->client_gst }}" readonly>
                </div>
            </div>

            <h5 class="fw-bold mb-4 text-primary border-bottom border-2 border-primary pb-2 d-inline-block">Contact Information</h5>

            <div class="row g-4 mb-4">
                <h6 class="fw-bold mb-2">Contacts</h6>
                <div class="col-md-6 mt-2">
                    <label class="form-label fw-semibold small text-muted">Primary Name</label>
                    <input type="text" class="form-control bg-light" value="{{ $client->client_name }}" readonly>
                </div>
                <div class="col-md-6 mt-2">
                    <label class="form-label fw-semibold small text-muted">Primary Email</label>
                    <input type="text" class="form-control bg-light" value="{{ $client->client_email }}" readonly>
                </div>
                <div class="col-md-6 mt-2">
                    <label class="form-label fw-semibold small text-muted">Primary Phone</label>
                    <input type="text" name="primary_contact" class="form-control editable-field bg-light" value="{{ $client->primary_contact }}" readonly>
                </div>
                <div class="col-md-6 mt-2">
                    <label class="form-label fw-semibold small text-muted">Secondary Contact</label>
                    <input type="text" name="secondary_contact" class="form-control editable-field bg-light" value="{{ $client->secondary_contact }}" readonly>
                </div>
            </div>



            <div id="saveActions" class="d-none justify-content-end gap-3 mt-5 border-top pt-4">
                <button type="button" id="cancelBtn" class="btn btn-light px-4 border rounded-3 fw-semibold text-muted">Cancel</button>
                <button type="submit" class="btn btn-primary px-5 rounded-3 fw-semibold">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editBtn = document.getElementById('editProfileBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        const saveActions = document.getElementById('saveActions');
        const editableFields = document.querySelectorAll('.editable-field');

        function toggleEdit(isEditing) {
            editableFields.forEach(field => {
                if (isEditing) {
                    field.removeAttribute('readonly');
                    field.classList.remove('bg-light');
                } else {
                    field.setAttribute('readonly', 'readonly');
                    field.classList.add('bg-light');
                }
            });
            
            if (isEditing) {
                editBtn.classList.add('d-none');
                saveActions.classList.remove('d-none');
                saveActions.classList.add('d-flex');
            } else {
                editBtn.classList.remove('d-none');
                saveActions.classList.add('d-none');
                saveActions.classList.remove('d-flex');
            }
        }

        editBtn.addEventListener('click', () => toggleEdit(true));
        cancelBtn.addEventListener('click', () => toggleEdit(false));
    });
</script>
@endsection
