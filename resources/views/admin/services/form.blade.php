@extends('layouts.admin')

@section('title', isset($service) ? 'Edit Project - Admin Portal' : 'Assign Project to Client - Admin Portal')
@section('page_title', isset($service) ? 'Edit Project' : 'Assign Client Project')

@section('content')
<div class="card">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="fw-bold mb-1 text-primary border-bottom border-2 border-primary pb-2 d-inline-block">
                    {{ isset($service) ? 'Edit Client Project' : 'Assign New Project to Client' }}
                </h5>
                <p class="text-muted small mb-0">Define the project details, assign the technical staff team, and set the delivery timeline. This project will immediately display inside the client's portal.</p>
            </div>
            <a href="{{ route('admin.services.index') }}" class="btn btn-light border text-muted"><i class="fa-solid fa-arrow-left me-1"></i> Back to Projects</a>
        </div>

        @if(isset($errors) && $errors->any())
            <div class="alert alert-danger p-2 mb-4">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ isset($service) ? route('admin.services.update', $service) : route('admin.services.store') }}" method="POST" autocomplete="off">
            @csrf
            @if(isset($service))
                @method('PUT')
            @endif

            <div class="row g-4 mb-4">
                <!-- Select Client -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Select Target Client <span class="text-danger">*</span></label>
                    <select name="client_id" class="form-select bg-light" required {{ isset($service) ? 'disabled' : '' }}>
                        <option value="">-- Choose Client Company --</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->client_id }}" {{ (old('client_id', $service->client_id ?? '') == $client->client_id) ? 'selected' : '' }}>
                                {{ $client->client_company }} ({{ $client->client_name }} - #CL{{ sprintf('%03d', $client->client_id) }})
                            </option>
                        @endforeach
                    </select>
                    @if(isset($service))
                        <input type="hidden" name="client_id" value="{{ $service->client_id }}">
                    @endif
                </div>

                <!-- Project Title / Name -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Project Name / Service Title <span class="text-danger">*</span></label>
                    <input type="text" name="service_name" list="projectSuggestions" class="form-control bg-light" placeholder="e.g. Android & iOS Mobile Application" value="{{ old('service_name', $service->service_name ?? '') }}" required>
                    <datalist id="projectSuggestions">
                        <option value="Cross-Platform Mobile Application (Flutter/React Native)">
                        <option value="Custom ERP & Business Management Software">
                        <option value="Corporate Web Application & Customer Portal">
                        <option value="Cloud Architecture & API Infrastructure">
                        <option value="UI/UX Product Design & Wireframing">
                        <option value="Quality Assurance & Automated Testing">
                        <option value="Dedicated DevOps & Server Maintenance">
                    </datalist>
                </div>

                <!-- Project Status -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-muted">Project Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select bg-light" required>
                        <option value="Active" {{ old('status', $service->status ?? 'Active') == 'Active' ? 'selected' : '' }}>Active (Live)</option>
                        <option value="In Progress" {{ old('status', $service->status ?? '') == 'In Progress' ? 'selected' : '' }}>In Progress (Under Development)</option>
                        <option value="Planning" {{ old('status', $service->status ?? '') == 'Planning' ? 'selected' : '' }}>Planning & Architecture</option>
                        <option value="Completed" {{ old('status', $service->status ?? '') == 'Completed' ? 'selected' : '' }}>Completed & Delivered</option>
                        <option value="On Hold" {{ old('status', $service->status ?? '') == 'On Hold' ? 'selected' : '' }}>On Hold</option>
                        <option value="Under Maintenance" {{ old('status', $service->status ?? '') == 'Under Maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                    </select>
                </div>

                <!-- Start Date -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-muted">Project Start Date</label>
                    <input type="date" name="start_date" class="form-control bg-light" value="{{ old('start_date', isset($service) && $service->start_date ? $service->start_date->format('Y-m-d') : '') }}">
                </div>

                <!-- Target End Date -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-muted">Target Delivery / End Date</label>
                    <input type="date" name="end_date" class="form-control bg-light" value="{{ old('end_date', isset($service) && $service->end_date ? $service->end_date->format('Y-m-d') : '') }}">
                </div>

                <!-- Assigned Lead / Team -->
                <div class="col-md-12">
                    <label class="form-label fw-semibold small text-muted">Assigned Team / Lead Engineer</label>
                    <input type="text" name="assigned_team" list="staffSuggestions" class="form-control bg-light" placeholder="e.g. Mobile Engineering Team Alpha / Alex Morgan (Lead)" value="{{ old('assigned_team', $service->assigned_team ?? '') }}">
                    <datalist id="staffSuggestions">
                        @if(isset($staffMembers))
                            @foreach($staffMembers as $staff)
                                <option value="{{ $staff->name }} ({{ $staff->designation }})">
                            @endforeach
                        @endif
                    </datalist>
                </div>

                <!-- Project Scope Description -->
                <div class="col-md-12">
                    <label class="form-label fw-semibold small text-muted">Project Scope, Deliverables & Notes</label>
                    <textarea name="description" class="form-control bg-light" rows="4" placeholder="Detail the core project milestones, modules, deliverables, and expectations for the client...">{{ old('description', $service->description ?? '') }}</textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-3 mt-4">
                <a href="{{ route('admin.services.index') }}" class="btn btn-light px-4 border rounded-3 fw-semibold text-muted">Cancel</a>
                <button type="submit" class="btn btn-primary px-5 rounded-3 fw-semibold">
                    <i class="fa-solid fa-check-circle me-1"></i> {{ isset($service) ? 'Update Project' : 'Assign Project to Client' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
