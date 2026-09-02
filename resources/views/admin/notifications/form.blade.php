@extends('layouts.admin')

@section('title', 'Dispatch Notification - Admin Portal')
@section('page_title', 'Dispatch Notification')

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

        <form action="{{ route('admin.notifications.store') }}" method="POST">
            @csrf
            
            <div class="row g-4 mb-4">
                <!-- Client Selection -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Select Recipient (Client)</label>
                    <select name="client_id" class="form-select bg-light" required>
                        <option value="">-- Choose Client --</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->client_id }}" {{ old('client_id') == $client->client_id ? 'selected' : '' }}>
                                {{ $client->client_name }} (#CL{{ sprintf('%03d', $client->client_id) }})
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Notification Title -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Notification Title</label>
                    <input type="text" name="title" class="form-control bg-light" placeholder="e.g. Document Verified" value="{{ old('title') }}" required>
                </div>

                <!-- Message -->
                <div class="col-md-12">
                    <label class="form-label fw-semibold small text-muted">Notification Message</label>
                    <textarea name="message" class="form-control bg-light" rows="4" required placeholder="Detailed message..."></textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-3 mt-4">
                <a href="{{ route('admin.notifications.index') }}" class="btn btn-light px-4 border rounded-3 fw-semibold text-muted">Cancel</a>
                <button type="submit" class="btn btn-primary px-5 rounded-3 fw-semibold"><i class="fa-solid fa-paper-plane me-2"></i> Send Notification</button>
            </div>
        </form>
    </div>
</div>
@endsection
