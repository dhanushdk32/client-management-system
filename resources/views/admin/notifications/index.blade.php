@extends('layouts.admin')

@section('title', 'Notifications - Admin Portal')
@section('page_title', 'Dispatch Notifications')

@section('content')
<div class="card">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0">Sent Notifications</h5>
            <a href="{{ route('admin.notifications.create') }}" class="btn btn-primary px-4 rounded-3"><i class="fa-solid fa-paper-plane me-2"></i> Dispatch Notification</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success py-2">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Recipient (Client)</th>
                        <th>Notification Title</th>
                        <th>Message Preview</th>
                        <th>Status</th>
                        <th>Sent Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notifications as $notification)
                        <tr>
                            <td>
                                <div class="fw-medium">{{ $notification->client->client_company ?? 'N/A' }}</div>
                                <div class="small text-muted">ID: CL{{ sprintf('%03d', $notification->client_id) }}</div>
                            </td>
                            <td class="fw-medium">{{ $notification->title }}</td>
                            <td class="small text-muted">{{ Str::limit($notification->message, 50) }}</td>
                            <td>
                                @if($notification->is_read)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">Read</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 py-1">Unread</span>
                                @endif
                            </td>
                            <td class="small text-muted">{{ \Carbon\Carbon::parse($notification->created_at)->format('d M Y h:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                <i class="fa-regular fa-bell-slash fa-3x mb-3 text-light"></i>
                                <p class="mb-0">No notifications dispatched yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $notifications->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
