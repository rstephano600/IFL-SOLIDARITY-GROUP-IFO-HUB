@extends('layouts.app')
@section('title', 'My Profile')
@section('page-title', 'User Profile')

@section('content')
<div class="container py-4">
    
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="bi bi-person-circle me-2"></i> Your Profile Information</h4>
            <a href="{{ route('profile.edit') }}" class="btn btn-warning btn-sm shadow-sm">
                <i class="bi bi-pencil-square me-1"></i> Edit Profile
            </a>
        </div>
        <div class="card-body p-4">
            
            <div class="row g-4">
                
                {{-- ACCOUNT DETAILS COLUMN --}}
                <div class="col-md-5">
                    <h5 class="text-primary border-bottom pb-2 mb-3">Account & Authentication</h5>
                    
                    <p class="mb-2"><strong>Username:</strong> <span class="float-end">{{ $user->username }}</span></p>
                    <p class="mb-2"><strong>Email:</strong> <span class="float-end">{{ $user->email }}</span></p>
                    <p class="mb-2"><strong>Phone:</strong> <span class="float-end">{{ $user->phone ?? 'N/A' }}</span></p>
                    <p class="mb-2"><strong>Role:</strong> <span class="float-end badge bg-info">{{ ucfirst($user->role) }}</span></p>
                    <p class="mb-2"><strong>Account Status:</strong> 
                        <span class="float-end badge bg-{{ $user->status == 'active' ? 'success' : 'danger' }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </p>
                    <p class="mb-2"><strong>Member Since:</strong> <span class="float-end">{{ $user->created_at->format('M d, Y') }}</span></p>

                    <hr class="mt-4 mb-3">
                    <button class="btn btn-outline-danger w-100">Change Password</button>
                    {{-- Note: Implement password change logic in ProfileController::updatePassword --}}

                </div>

                {{-- EMPLOYEE DETAILS COLUMN --}}
                <div class="col-md-7 border-start ps-4">
                    <h5 class="text-primary border-bottom pb-2 mb-3">Personal & Employment Details</h5>
                    
                    @if ($user->employee)
                        <div class="row">
                            <div class="col-sm-6">
                                <p class="mb-2"><strong>First Name:</strong> <span class="float-end">{{ $user->employee->first_name ?? 'N/A' }}</span></p>
                                <p class="mb-2"><strong>Last Name:</strong> <span class="float-end">{{ $user->employee->last_name ?? 'N/A' }}</span></p>
                                <p class="mb-2"><strong>Gender:</strong> <span class="float-end">{{ ucfirst($user->employee->gender) ?? 'N/A' }}</span></p>
                                <p class="mb-2"><strong>Date of Birth:</strong> <span class="float-end">{{ $user->employee->date_of_birth ?? 'N/A' }}</span></p>
                                <p class="mb-2"><strong>Marital Status:</strong> <span class="float-end">{{ ucfirst($user->employee->marital_status) ?? 'N/A' }}</span></p>
                            </div>
                            <div class="col-sm-6">
                                <p class="mb-2"><strong>NIDA:</strong> <span class="float-end">{{ $user->employee->nida ?? 'N/A' }}</span></p>
                                <p class="mb-2"><strong>Address:</strong> <span class="float-end">{{ $user->employee->address ?? 'N/A' }}</span></p>
                                <p class="mb-2"><strong>Position:</strong> <span class="float-end badge bg-secondary">{{ $user->employee->position ?? 'N/A' }}</span></p>
                                <p class="mb-2"><strong>Department:</strong> <span class="float-end">{{ $user->employee->department ?? 'N/A' }}</span></p>
                                <p class="mb-2"><strong>Hire Date:</strong> <span class="float-end">{{ $user->employee->date_of_hire ?? 'N/A' }}</span></p>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info">
                            Personal and Employment details are managed separately and appear to be missing for your account.
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
