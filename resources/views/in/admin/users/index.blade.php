@extends('layouts.configside')
@section('title', 'System Users Informations')
@section('page-title', 'System Users Informations')

@section('content')
<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-globe"></i></div>System Users Informations</h3>
    <button class="arbif-btn-submit" onclick="openCreateForm()"><i class="fas fa-plus"></i> Add Users Informations</button>
</div>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Manage Users</h4>
        <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle"></i> Add New User
        </a>
    </div>

    {{-- Filter/Search --}}
    <form method="GET" action="{{ route('users.index') }}" class="row g-2 mb-3">
        <div class="col-md-3">
            <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Search username, email or phone">
        </div>
        <div class="col-md-3">
            <select name="role" class="form-select">
                <option value="">-- All Roles --</option>
                @foreach($roles as $r)
                    <option value="{{ $r }}" {{ $role == $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">-- All Status --</option>
                <option value="active" {{ $status == 'active' ? 'selected' : '' }}>Active</option>
                <option value="suspended" {{ $status == 'suspended' ? 'selected' : '' }}>Suspended</option>
                <option value="disabled" {{ $status == 'disabled' ? 'selected' : '' }}>Disabled</option>
            </select>
        </div>
        <div class="col-md-3">
            <button class="btn btn-secondary w-100" type="submit">Filter</button>
        </div>
    </form>

    {{-- Table --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                            <tr>
                                <td>{{ $users->firstItem() + $index }}</td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->email ?? 'N/A' }}</td>
                                <td>{{ $user->phone ?? 'N/A' }}</td>
                                <td><span class="badge bg-info text-dark">{{ ucfirst($user->role) }}</span></td>
                                <td>
                                    <span class="badge 
                                        @if($user->status == 'active') bg-success 
                                        @elseif($user->status == 'suspended') bg-warning 
                                        @else bg-danger @endif">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-primary">Edit</a>

                                    {{-- 🔒 Reset Password Button --}}
                                    <button class="btn btn-sm btn-warning" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#resetPasswordModal"
                                        data-user-id="{{ $user->id }}"
                                        data-username="{{ $user->username }}">
                                        Reset Password
                                    </button>

                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Delete this user permanently?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-3">No users found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-3">
        {{ $users->withQueryString()->links() }}
    </div>
</div>

{{-- 🔐 Reset Password Modal --}}
<div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="resetPasswordForm">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="resetPasswordModalLabel">Reset Password for <span id="resetUsername"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Reset Password</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- JS for setting form action dynamically --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const resetModal = document.getElementById('resetPasswordModal');
    const resetForm = document.getElementById('resetPasswordForm');
    const resetUsername = document.getElementById('resetUsername');

    resetModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const userId = button.getAttribute('data-user-id');
        const username = button.getAttribute('data-username');

        // Update modal label
        resetUsername.textContent = username;

        // Set form action dynamically
        resetForm.action = `/users/${userId}/reset-password`;
    });
});
</script>
@endsection
