@extends('layouts.admin')

@section('title', isset($service) ? 'Edit Service - Admin Portal' : 'Assign Service - Admin Portal')
@section('page_title', isset($service) ? 'Edit Service' : 'Assign Service')

@section('content')
<div class="card">
    <div class="card-body p-4">
        
        @if($errors->any())
            <div class="alert alert-danger p-2 mb-4">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ isset($service) ? route('admin.services.update', $service) : route('admin.services.store') }}" method="POST">
            @csrf
            @if(isset($service))
                @method('PUT')
            @endif

            <div class="row g-4 mb-4">
                <!-- Client Selection -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Select Client</label>
                    <select name="client_id" class="form-select bg-light" required {{ isset($service) ? 'disabled' : '' }}>
                        <option value="">Select a client...</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->client_id }}" {{ (old('client_id', $service->client_id ?? '') == $client->client_id) ? 'selected' : '' }}>
                                {{ $client->client_company }} (CL{{ sprintf('%03d', $client->client_id) }})
                            </option>
                        @endforeach
                    </select>
                    @if(isset($service))
                        <!-- Pass hidden client_id since select is disabled -->
                        <input type="hidden" name="client_id" value="{{ $service->client_id }}">
                    @endif
                </div>
                
                <!-- Service Name -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Service Name</label>
                    <input type="text" name="service_name" class="form-control bg-light" placeholder="e.g. Background Verification" value="{{ old('service_name', $service->service_name ?? '') }}" required>
                </div>

                <!-- Status -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-muted">Status</label>
                    <select name="status" class="form-select bg-light" required>
                        <option value="Active" {{ old('status', $service->status ?? '') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Pending" {{ old('status', $service->status ?? '') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Completed" {{ old('status', $service->status ?? '') == 'Completed' ? 'selected' : '' }}>Completed</option>
                        <option value="Suspended" {{ old('status', $service->status ?? '') == 'Suspended' ? 'selected' : '' }}>Suspended</option>
                        <option value="Cancelled" {{ old('status', $service->status ?? '') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <!-- Start Date -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-muted">Start Date</label>
                    <input type="date" name="start_date" class="form-control bg-light" value="{{ old('start_date', isset($service) && $service->start_date ? $service->start_date->format('Y-m-d') : '') }}">
                </div>

                <!-- End Date -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-muted">End Date</label>
                    <input type="date" name="end_date" class="form-control bg-light" value="{{ old('end_date', isset($service) && $service->end_date ? $service->end_date->format('Y-m-d') : '') }}">
                </div>

                <!-- Assigned Team -->
                <div class="col-md-12">
                    <label class="form-label fw-semibold small text-muted">Assigned Team / Department</label>
                    <input type="text" name="assigned_team" class="form-control bg-light" placeholder="e.g. Verification Team Alpha" value="{{ old('assigned_team', $service->assigned_team ?? '') }}">
                </div>

                <!-- Description -->
                <div class="col-md-12">
                    <label class="form-label fw-semibold small text-muted">Service Description</label>
                    <textarea name="description" class="form-control bg-light" rows="4" placeholder="Brief description of the service and terms...">{{ old('description', $service->description ?? '') }}</textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-3 mt-4">
                <a href="{{ route('admin.services.index') }}" class="btn btn-light px-4 border rounded-3 fw-semibold text-muted">Cancel</a>
                <button type="submit" class="btn btn-primary px-5 rounded-3 fw-semibold">Save Service</button>
            </div>
        </form>
    </div>
</div>
@endsection
