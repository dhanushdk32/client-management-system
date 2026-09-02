@extends('layouts.admin')

@section('title', 'Staff Management - Admin Portal')
@section('page_title', 'Staff Team Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1">Engineering & Support Staff</h5>
        <p class="text-muted small mb-0">Manage IT staff members, departments, and client assignments</p>
    </div>
    <a href="{{ route('admin.staff.create') }}" class="btn btn-primary rounded-3 px-4">
        <i class="fa-solid fa-user-plus me-2"></i> Add New Staff
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success py-2 mb-4">{{ session('success') }}</div>
@endif

<!-- Filters & Search -->
<div class="card mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.staff.index') }}" class="row g-2 align-items-center">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-start-0 ps-0" placeholder="Search by name, email, role..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="department" class="form-select bg-light">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select bg-light">
                    <option value="">All Statuses</option>
                    <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Pending Activation" {{ request('status') == 'Pending Activation' ? 'selected' : '' }}>Pending Activation</option>
                    <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-secondary w-100"><i class="fa-solid fa-filter me-1"></i> Filter</button>
                @if(request()->hasAny(['search', 'department', 'status']))
                    <a href="{{ route('admin.staff.index') }}" class="btn btn-light border" title="Reset"><i class="fa-solid fa-rotate-left"></i></a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Staff Table -->
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Staff Member</th>
                    <th>Department & Role</th>
                    <th>Assigned Clients</th>
                    <th>Active Tickets</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($staffMembers as $staff)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    {{ strtoupper(substr($staff->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $staff->name }}</div>
                                    <div class="small text-muted"><i class="fa-regular fa-envelope me-1"></i> {{ $staff->email }}</div>
                                    @if($staff->phone)
                                        <div class="small text-muted" style="font-size: 11px;"><i class="fa-solid fa-phone me-1"></i> {{ $staff->phone }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $staff->designation }}</div>
                            <span class="badge bg-light text-dark border">{{ $staff->department }}</span>
                        </td>
                        <td>
                            <span class="badge bg-info-subtle text-info fw-semibold px-2 py-1">
                                <i class="fa-solid fa-building me-1"></i> {{ $staff->assigned_clients_count }} Clients
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-warning-subtle text-warning fw-semibold px-2 py-1">
                                <i class="fa-solid fa-ticket me-1"></i> {{ $staff->assigned_tickets_count }} Tickets
                            </span>
                        </td>
                        <td>
                            @if($staff->status === 'Active')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1">Active</span>
                            @elseif($staff->status === 'Pending Activation')
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-1">Pending OTP</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-inline-flex gap-2">
                                <a href="{{ route('admin.staff.edit', $staff) }}" class="btn btn-sm btn-outline-primary" title="Edit Staff">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form action="{{ route('admin.staff.destroy', $staff) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this staff member?');" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Staff">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-users-slash fa-3x mb-3 d-block opacity-50"></i>
                            No staff members found. Click <strong>"Add New Staff"</strong> to onboard your team!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($staffMembers->hasPages())
        <div class="card-footer bg-white border-top py-3">
            {{ $staffMembers->links() }}
        </div>
    @endif
</div>
@endsection
