@extends('layouts.staff')

@section('title', isset($service) ? 'Edit Project - Staff Portal' : 'Add Client Project - Staff Portal')
@section('page_title', isset($service) ? 'Edit Project' : 'Add Client Project')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h5 class="fw-bold mb-1 text-primary border-bottom border-2 border-primary pb-2 d-inline-block">
                    {{ isset($service) ? 'Edit Client Project' : 'Add / Assign Project to Client' }}
                </h5>
                <p class="text-muted small mb-0">Define the project details, assign the technical squad leader & members, and set the delivery timeline.</p>
            </div>
            <a href="{{ route('staff.services.index') }}" class="btn btn-light border rounded-pill px-3 text-muted"><i class="fa-solid fa-arrow-left me-1"></i> Back to Projects</a>
        </div>

        @if(isset($errors) && $errors->any())
            <div class="alert alert-danger p-3 mb-4 rounded-3">
                <div class="fw-bold mb-1"><i class="fa-solid fa-circle-exclamation me-1"></i> Please resolve the following errors:</div>
                <ul class="mb-0 ps-3 small">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ isset($service) ? route('staff.services.update', $service) : route('staff.services.store') }}" method="POST" autocomplete="off">
            @csrf
            @if(isset($service))
                @method('PUT')
            @endif

            <!-- 1. Project & Client Info -->
            <h6 class="fw-bold mb-3 text-secondary border-bottom pb-2">
                <i class="fa-solid fa-folder-open me-1 text-primary"></i> 1. Project Specifications
            </h6>
            <div class="row g-4 mb-4">
                <!-- Select Client -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Select Target Client <span class="text-danger">*</span></label>
                    <select name="client_id" class="form-select bg-light" required {{ isset($service) ? 'disabled' : '' }}>
                        <option value="">-- Choose Client --</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->client_id }}" {{ (old('client_id', $service->client_id ?? '') == $client->client_id) ? 'selected' : '' }}>
                                {{ $client->client_company ?: $client->client_name }} (#CL{{ sprintf('%03d', $client->client_id) }})
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
                    <input type="date" name="start_date" class="form-control bg-light" value="{{ old('start_date', isset($service) && $service->start_date ? $service->start_date->format('Y-m-d') : date('Y-m-d')) }}">
                </div>

                <!-- Target End Date -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-muted">Target Delivery / End Date</label>
                    <input type="date" name="end_date" class="form-control bg-light" value="{{ old('end_date', isset($service) && $service->end_date ? $service->end_date->format('Y-m-d') : '') }}">
                </div>
            </div>

            <!-- 2. Technical Team Assignment -->
            <h6 class="fw-bold mb-3 mt-4 text-secondary border-bottom pb-2">
                <i class="fa-solid fa-users-gear me-1 text-primary"></i> 2. Technical Team & Squad Allocation
            </h6>

            <div class="row g-4 mb-4">
                <!-- Team Name -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Team Name</label>
                    <input type="text" name="team_name" class="form-control bg-light" placeholder="e.g. Alpha Mobile Squad / Core Dev Team" value="{{ old('team_name', $service->team_name ?? '') }}">
                    <div class="form-text small text-muted">Custom name for this project's dedicated engineering squad.</div>
                </div>

                <!-- Team Leader Selection -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Team Leader <span class="text-danger">*</span></label>
                    <select name="team_leader_id" class="form-select bg-light">
                        <option value="">-- Select Team Leader from Staff --</option>
                        @if(isset($staffMembers))
                            @foreach($staffMembers as $staff)
                                <option value="{{ $staff->id }}" {{ (old('team_leader_id', $service->team_leader_id ?? '') == $staff->id) ? 'selected' : '' }}>
                                    {{ $staff->name }} ({{ $staff->designation }} - {{ $staff->department }})
                                </option>
                            @endforeach
                        @endif
                    </select>
                    <div class="form-text small text-muted">Select the primary lead engineer responsible for this project.</div>
                </div>

                <!-- Team Members -->
                <div class="col-md-12">
                    <label class="form-label fw-semibold small text-muted mb-2">Team Members</label>
                    <div class="p-3 rounded-3 bg-light border">
                        <div class="row g-3">
                            @php
                                $selectedMembers = old('team_members', (isset($service) && is_array($service->team_members)) ? $service->team_members : []);
                            @endphp
                            @if(isset($staffMembers) && $staffMembers->count() > 0)
                                @foreach($staffMembers as $staff)
                                    <div class="col-md-4">
                                        <div class="form-check card p-2 bg-white border shadow-xs h-100">
                                            <input class="form-check-input ms-0 me-2" type="checkbox" name="team_members[]" value="{{ $staff->id }}" id="team_member_{{ $staff->id }}" {{ in_array($staff->id, $selectedMembers) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold small" for="team_member_{{ $staff->id }}">
                                                {{ $staff->name }}
                                                <span class="d-block text-muted" style="font-size: 11px; font-weight: normal;">
                                                    {{ $staff->designation }} ({{ $staff->department }})
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-12 text-muted small">No active staff members available.</div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Project Scope Description -->
                <div class="col-md-12">
                    <label class="form-label fw-semibold small text-muted">Project Scope, Deliverables & Notes</label>
                    <textarea name="description" class="form-control bg-light" rows="4" placeholder="Detail the core project milestones, modules, deliverables, and expectations for the client...">{{ old('description', $service->description ?? '') }}</textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-3 mt-4">
                <a href="{{ route('staff.services.index') }}" class="btn btn-light px-4 border rounded-pill fw-semibold text-muted">Cancel</a>
                <button type="submit" class="btn btn-primary px-5 rounded-pill fw-semibold">
                    <i class="fa-solid fa-check-circle me-1"></i> {{ isset($service) ? 'Update Project' : 'Save & Assign Project' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
