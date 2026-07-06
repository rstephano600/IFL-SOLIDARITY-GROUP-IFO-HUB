@extends('layouts.configside')
@section('title', 'System Users Informations')
@section('page-title', 'System Users Informations')

@section('content')
<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-globe"></i></div>System Users Informations</h3>
    <button class="arbif-btn-submit" onclick="openCreateForm()"><i class="fas fa-plus"></i> Add Users Informations</button>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="countryTable">
                <thead class="table-light">
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">User Name</th>
                        <th class="sortable">Name</th>
                        <th class="sortable">Email</th>
                        <th class="sortable">Phone</th>
                        <!-- <th class="sortable">Role</th> -->
                        <!-- <th class="sortable">Status</th> -->
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->username }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->email ?? 'N/A' }}</td>
                            <td>{{ $item->phone ?? 'N/A' }}</td>
                            <!-- <td><span class="badge bg-info text-dark">{{ ucfirst($item->role) }}</span></td>
                            <td>
                                <span class="badge 
                                    @if($item->status == 'active') bg-success 
                                    @elseif($item->status == 'suspended') bg-warning 
                                    @else bg-danger @endif">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td> -->
                            <td class="text-center">
                            <a href="{{ route('editsystemUsers', [encrypt($item->id)]) }}" class="arbif-btn-edit"> <i class="fas fa-pencil"></i> Edit </a>
                            <a onclick="confirmDelete()" href="{{ route('destroysystemUsers', [encrypt($item->id)]) }}" class="arbif-btn-delete"> <i class="fas fa-trash"></i> Delete </a>
                                <button class="btn btn-sm btn-warning"  data-bs-toggle="modal"  data-bs-target="#resetPasswordModal" data-user-id="{{ $item->id }}" data-username="{{ $item->username }}">
                                    Reset Password
                                </button>
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

<div class="modal fade arbif-modal" id="addFormModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon"><i class="fas fa-plus"></i></div>
                <h5 class="modal-title">Add Users Informations</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <form method="POST" id="dataFormFill" action="{{route('storesystemUsers')}}" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <div class="col-sm-4">
                            <label class="form-label" for="FirstName">First Name</label>
                            <input type="text" id="FirstName" name="FirstName" class="form-control" placeholder="e.g. Robert" required>
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label" for="MiddleName">Middle Name</label>
                            <input type="text" id="MiddleName" name="MiddleName" class="form-control" placeholder="e.g. John" >
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label" for="LastName">Last Name</label>
                            <input type="text" id="LastName" name="LastName" class="form-control" placeholder="e.g. James" required>
                        </div>
                    </div>
<br>
                    <div class="row g-3">
                        <div class="col-sm-4">
                            <label class="form-label" for="email">Email Address</label>
                            <input type="text" id="email" name="email" class="form-control" placeholder="e.g. robert.james@arbif.co.tz" required>
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label" for="phone">Phone Number</label>
                            <input type="text" id="phone" name="phone" class="form-control" placeholder="e.g. 0657856790" >
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label" for="LastName">Role</label>
                            <select name="Role" class="form-select @error('role') is-invalid @enderror">
                                <option value="">-- Select Role --</option>
                                @foreach($roles as $r)
                                    <option value="{{ $r }}" {{ old('role') == $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
                                @endforeach
                            </select>
                            @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    
                    <div class="modal-footer" style="margin-top: 20px;">
                        <button type="button" class="arbif-btn-cancel" data-bs-dismiss="modal">
                            <i class="bi bi-x"></i> Cancel
                        </button>
                        <button onclick="confirmSubmit()"  type="submit" class="arbif-btn-submit">
                            <i class="bi bi-check2"></i> <span id="submitBtnText">Save User Informations</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
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
        resetForm.action = `/resetPassword/${userId}/`;
    });
});
</script>
@endsection
