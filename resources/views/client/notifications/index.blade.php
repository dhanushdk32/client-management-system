@extends('layouts.client')

@section('title', 'Notifications - Client Portal')
@section('page_title', 'My Notifications')

@section('content')
<div class="card">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0">Recent Notifications</h5>
        </div>

        @if(session('success'))
            <div class="alert alert-success py-2">{{ session('success') }}</div>
        @endif

        <div class="notification-list">
            @forelse($notifications as $notification)
                <div class="card mb-3 {{ $notification->is_read ? 'bg-light border-0' : 'border-primary shadow-sm' }}">
                    <div class="card-body p-3 d-flex gap-3 align-items-start">
                        <div class="mt-1">
                            <i class="fa-solid fa-bell {{ $notification->is_read ? 'text-muted' : 'text-primary' }} fa-lg"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="fw-bold mb-0 {{ $notification->is_read ? 'text-muted' : 'text-dark' }}">{{ $notification->title }}</h6>
                                <span class="small text-muted">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</span>
                            </div>
                            <p class="mb-2 {{ $notification->is_read ? 'text-muted' : 'text-dark' }}">{{ $notification->message }}</p>
                            
                            @if(!$notification->is_read)
                                <form action="{{ route('client.notifications.read', $notification->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-link text-decoration-none p-0 fw-semibold text-primary">Mark as read</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-5">
                    <i class="fa-regular fa-bell-slash fa-3x mb-3 text-light"></i>
                    <p class="mb-0">You have no notifications.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $notifications->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
