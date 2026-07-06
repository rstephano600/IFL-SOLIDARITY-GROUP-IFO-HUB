@extends('layouts.app')

@section('title', 'Employee')
@section('page-title', 'Employee Details')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Employees</a></li>
    <li class="breadcrumb-item active">{{ $employee->full_name }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Action Buttons -->
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between">
            <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
            <div>
                <a href="{{ route('employees.edit', $employee) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <form action="{{ route('employees.toggle-status', $employee) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-{{ $employee->is_active ? 'secondary' : 'success' }}">
                        <i class="bi bi-toggle-{{ $employee->is_active ? 'off' : 'on' }}"></i>
                        {{ $employee->is_active ? 'Deactivate' : 'Activate' }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-md-4">
            <!-- Profile Card -->
            <div class="card mb-4">
                <div class="card-body text-center">
                    <img src="{{ $employee->profile_picture_url }}" 
                         alt="{{ $employee->full_name }}" 
                         class="rounded-circle mb-3" 
                         width="150" height="150"
                         style="object-fit: cover; border: 5px solid #ddd;">
                    <h4 class="mb-1">{{ $employee->full_name }}</h4>
                    <p class="text-muted mb-2">{{ $employee->position }}</p>
                    <span class="badge bg-info mb-3">{{ $employee->department }}</span>
                    
                    @if($employee->is_active)
                        <span class="badge bg-success d-block mb-2">
                            <i class="bi bi-check-circle"></i> Active
                        </span>
                    @else
                        <span class="badge bg-secondary d-block mb-2">
                            <i class="bi bi-x-circle"></i> Inactive
                        </span>
                    @endif

                    <hr>

                    <div class="text-start">
                        <p class="mb-2">
                            <i class="bi bi-person-badge text-primary"></i>
                            <strong>Employee ID:</strong> {{ $employee->employee_id }}
                        </p>
                        <p class="mb-2">
                            <i class="bi bi-envelope text-primary"></i>
                            <strong>Email:</strong><br>
                            <a href="mailto:{{ $employee->user->email }}">{{ $employee->user->email }}</a>
                        </p>
                        <p class="mb-2">
                            <i class="bi bi-phone text-primary"></i>
                            <strong>Phone:</strong><br>
                            {{ $employee->user->phone ?? 'N/A' }}
                        </p>
                        <p class="mb-2">
                            <i class="bi bi-calendar text-primary"></i>
                            <strong>Age:</strong> {{ $employee->age }} years
                        </p>
                        <p class="mb-0">
                            <i class="bi bi-briefcase text-primary"></i>
                            <strong>Years of Service:</strong> {{ $employee->years_of_service }} years
                        </p>
                    </div>

                    @if($employee->cv_url)
                        <hr>
                        <a href="{{ $employee->cv_url }}" class="btn btn-outline-primary btn-sm" target="_blank">
                            <i class="bi bi-file-earmark-pdf"></i> Download CV
                        </a>
                    @endif
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="bi bi-graph-up"></i> Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Role:</span>
                        <strong>{{ ucfirst(str_replace('_', ' ', $employee->user->role)) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Account Status:</span>
                        <span class="badge bg-{{ $employee->user->status == 'active' ? 'success' : 'secondary' }}">
                            {{ ucfirst($employee->user->status) }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Date of Hire:</span>
                        <strong>{{ $employee->date_of_hire->format('d/m/Y') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Registered:</span>
                        <strong>{{ $employee->created_at->format('d/m/Y') }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-md-8">
            <!-- Personal Information -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-person"></i> Personal Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Gender</label>
                            <p class="mb-0"><strong>{{ ucfirst($employee->gender) }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Date of Birth</label>
                            <p class="mb-0"><strong>{{ $employee->date_of_birth->format('d/m/Y') }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Marital Status</label>
                            <p class="mb-0"><strong>{{ $employee->marital_status ? ucfirst($employee->marital_status) : 'N/A' }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">NIDA</label>
                            <p class="mb-0"><strong>{{ $employee->nida ?? 'N/A' }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Tribe</label>
                            <p class="mb-0"><strong>{{ $employee->tribe ?? 'N/A' }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Religion</label>
                            <p class="mb-0"><strong>{{ $employee->religion ?? 'N/A' }}</strong></p>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="text-muted small">Address</label>
                            <p class="mb-0"><strong>{{ $employee->address ?? 'N/A' }}</strong></p>
                        </div>
                        <div class="col-12">
                            <label class="text-muted small">Education Level</label>
                            <p class="mb-0"><strong>{{ $employee->education_level ?? 'N/A' }}</strong></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employment Information -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-briefcase"></i> Employment Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Position</label>
                            <p class="mb-0"><strong>{{ $employee->position }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Department</label>
                            <p class="mb-0"><strong>{{ $employee->department }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Date of Hire</label>
                            <p class="mb-0"><strong>{{ $employee->date_of_hire->format('d/m/Y') }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Years of Service</label>
                            <p class="mb-0"><strong>{{ $employee->years_of_service }} years</strong></p>
                        </div>
                        @if($employee->other_information)
                        <div class="col-12">
                            <label class="text-muted small">Other Information</label>
                            <p class="mb-0">{{ $employee->other_information }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Next of Kin -->
            @if($employee->nextOfKin)
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="bi bi-person-hearts"></i> Next of Kin</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Full Name</label>
                            <p class="mb-0"><strong>{{ $employee->nextOfKin->full_name }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Gender</label>
                            <p class="mb-0"><strong>{{ $employee->nextOfKin->gender ? ucfirst($employee->nextOfKin->gender) : 'N/A' }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Email</label>
                            <p class="mb-0">
                                <strong>
                                    @if($employee->nextOfKin->email)
                                        <a href="mailto:{{ $employee->nextOfKin->email }}">{{ $employee->nextOfKin->email }}</a>
                                    @else
                                        N/A
                                    @endif
                                </strong>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Phone</label>
                            <p class="mb-0"><strong>{{ $employee->nextOfKin->phone ?? 'N/A' }}</strong></p>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="text-muted small">Address</label>
                            <p class="mb-0"><strong>{{ $employee->nextOfKin->address ?? 'N/A' }}</strong></p>
                        </div>
                        @if($employee->nextOfKin->other_informations)
                        <div class="col-12">
                            <label class="text-muted small">Other Information</label>
                            <p class="mb-0">{{ $employee->nextOfKin->other_informations }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Referees -->
            <div class="card">
                <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-people"></i> Referees</h5>
                    <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#addRefereeModal">
                        <i class="bi bi-plus"></i> Add
                    </button>
                </div>
                <div class="card-body">
                    @forelse($employee->referees as $referee)
                    <div class="border rounded p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-2">{{ $referee->full_name }}</h6>
                                <p class="mb-1 small">
                                    <i class="bi bi-gender-{{ $referee->gender == 'male' ? 'male' : 'female' }}"></i>
                                    {{ ucfirst($referee->gender ?? 'N/A') }}
                                </p>
                                <p class="mb-1 small">
                                    <i class="bi bi-envelope"></i> 
                                    @if($referee->email)
                                        <a href="mailto:{{ $referee->email }}">{{ $referee->email }}</a>
                                    @else
                                        N/A
                                    @endif
                                </p>
                                <p class="mb-1 small">
                                    <i class="bi bi-phone"></i> {{ $referee->phone ?? 'N/A' }}
                                </p>
                                @if($referee->address)
                                <p class="mb-1 small">
                                    <i class="bi bi-geo-alt"></i> {{ $referee->address }}
                                </p>
                                @endif
                                @if($referee->other_informations)
                                <p class="mb-0 small text-muted">{{ $referee->other_informations }}</p>
                                @endif
                            </div>
                            <form action="{{ route('referees.destroy', $referee) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" 
                                        onclick="return confirm('Are you sure you want to delete this referee?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted text-center py-4">
                        <i class="bi bi-info-circle"></i> No referees registered
                    </p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- System Information -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-info-circle"></i> System Information</h6>
                </div>
                <div class="card-body">
                    <div class="row small">
                        <div class="col-md-3">
                            <label class="text-muted">Registered by:</label>
                            <p class="mb-0"><strong>{{ $employee->creator->username ?? 'System' }}</strong></p>
                        </div>
                        <div class="col-md-3">
                            <label class="text-muted">Registration Date:</label>
                            <p class="mb-0"><strong>{{ $employee->created_at->format('d/m/Y H:i') }}</strong></p>
                        </div>
                        <div class="col-md-3">
                            <label class="text-muted">Updated by:</label>
                            <p class="mb-0"><strong>{{ $employee->updater->username ?? 'N/A' }}</strong></p>
                        </div>
                        <div class="col-md-3">
                            <label class="text-muted">Last Updated:</label>
                            <p class="mb-0"><strong>{{ $employee->updated_at->format('d/m/Y H:i') }}</strong></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Referee Modal -->
<div class="modal fade" id="addRefereeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('employees.referees.store', $employee) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Referee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Gender <span class="text-danger">*</span></label>
                            <select name="gender" class="form-select" required>
                                <option value="">Select</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Phone <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Other Information</label>
                            <textarea name="other_informations" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection