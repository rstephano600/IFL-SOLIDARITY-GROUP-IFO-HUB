@extends('layouts.app')

@section('title', 'Employees')
@section('page-title', 'Employees List')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Employees</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h3 class="mb-0">
                <i class="bi bi-person-badge text-primary"></i>
                Employees List
            </h3>
            <p class="text-muted mb-0">Manage and view all company employees</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('employees.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add Employee
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0"><i class="bi bi-funnel me-2"></i>Filter Employees</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('employees.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Name, ID, NIDA, Email..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Department</label>
                    <select name="department" class="form-select">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>
                                {{ $dept }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
                            Active
                        </option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-primary border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-primary">Total Employees</h6>
                            <h2 class="fw-bold">{{ $employees->total() }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-people text-primary fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-success border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-success">Active Employees</h6>
                            <h2 class="fw-bold">{{ $employees->where('is_active', true)->count() }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-person-check text-success fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-warning border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-warning">Departments</h6>
                            <h2 class="fw-bold">{{ $departments->count() }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-building text-warning fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-info border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-info">This Page</h6>
                            <h2 class="fw-bold">{{ $employees->count() }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-list-ol text-info fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Employees Table -->
    <div class="card">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h6 class="mb-0"><i class="bi bi-table me-2"></i>Employees Records</h6>
            <span class="badge bg-primary">Showing {{ $employees->count() }} of {{ $employees->total() }} employees</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Photo</th>
                            <th>Employee ID</th>
                            <th>Full Name</th>
                            <th>Contact Info</th>
                            <th>Position</th>
                            <th>Department</th>
                            <th>Hire Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $employee)
                        <tr>
                            <td class="fw-bold">{{ $loop->iteration + ($employees->currentPage() - 1) * $employees->perPage() }}</td>
                            <td>
                                <img src="{{ $employee->profile_picture_url }}" 
                                     alt="{{ $employee->full_name }}" 
                                     class="rounded-circle" 
                                     width="40" height="40"
                                     style="object-fit: cover;"
                                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($employee->full_name) }}&background=667eea&color=fff&size=64'">
                            </td>
                            <td>
                                <strong class="text-primary">{{ $employee->employee_id }}</strong>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $employee->full_name }}</strong><br>
                                    <small class="text-muted text-capitalize">{{ $employee->gender ?? 'N/A' }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="small">
                                    <i class="bi bi-envelope text-muted"></i> {{ $employee->user->email }}<br>
                                    <i class="bi bi-phone text-muted"></i> {{ $employee->user->phone ?? 'N/A' }}
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">{{ $employee->position }}</span>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $employee->department }}</span>
                            </td>
                            <td>
                                <span class="small">{{ $employee->date_of_hire->format('M d, Y') }}</span>
                            </td>
                            <td>
                                @if($employee->is_active)
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>Active
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-x-circle me-1"></i>Inactive
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('employees.show', $employee) }}" 
                                       class="btn btn-outline-info" 
                                       title="View Details"
                                       data-bs-toggle="tooltip">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('employees.edit', $employee) }}" 
                                       class="btn btn-outline-warning" 
                                       title="Edit Employee"
                                       data-bs-toggle="tooltip">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('employees.toggle-status', $employee) }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        <button type="submit" 
                                                class="btn btn-{{ $employee->is_active ? 'outline-secondary' : 'outline-success' }}" 
                                                title="{{ $employee->is_active ? 'Deactivate' : 'Activate' }}"
                                                data-bs-toggle="tooltip">
                                            <i class="bi bi-toggle-{{ $employee->is_active ? 'off' : 'on' }}"></i>
                                        </button>
                                    </form>
                                    <button type="button" 
                                            class="btn btn-outline-danger" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal{{ $employee->id }}"
                                            title="Delete Employee"
                                            data-bs-toggle="tooltip">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal{{ $employee->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-danger">
                                                    <i class="bi bi-exclamation-triangle me-2"></i>Confirm Deletion
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure you want to delete <strong>{{ $employee->full_name }}</strong>?</p>
                                                <p class="text-muted small">This action cannot be undone. All employee records will be permanently removed.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    <i class="bi bi-x-circle me-1"></i>Cancel
                                                </button>
                                                <form action="{{ route('employees.destroy', $employee) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="bi bi-trash me-1"></i>Delete Employee
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <div class="py-4">
                                    <i class="bi bi-people display-1 text-muted"></i>
                                    <h5 class="text-muted mt-3">No Employees Found</h5>
                                    <p class="text-muted">No employees match your search criteria.</p>
                                    @if(request()->anyFilled(['search', 'department', 'status']))
                                        <a href="{{ route('employees.index') }}" class="btn btn-primary mt-2">
                                            <i class="bi bi-arrow-clockwise me-1"></i>Reset Filters
                                        </a>
                                    @else
                                        <a href="{{ route('employees.create') }}" class="btn btn-primary mt-2">
                                            <i class="bi bi-plus-circle me-1"></i>Add First Employee
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($employees->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted small">
                    Showing {{ $employees->firstItem() }} to {{ $employees->lastItem() }} of {{ $employees->total() }} entries
                </div>
                <div>
                    {{ $employees->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .table th {
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e9ecef;
    }
    
    .btn-group .btn {
        border-radius: 4px;
        margin: 0 2px;
    }
    
    .card {
        box-shadow: 0 2px 4px rgba(0,0,0,0.04);
        border: 1px solid #e9ecef;
    }
</style>

<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endsection