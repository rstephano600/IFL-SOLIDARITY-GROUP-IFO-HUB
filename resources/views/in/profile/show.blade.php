@extends('layouts.app')
@section('title', 'Edit Profile')
@section('page-title', 'Edit Profile')

@section('content')
<div class="container py-4">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-warning text-dark py-3">
            <h4 class="mb-0"><i class="bi bi-person-fill-lock me-2"></i> Update Your Profile</h4>
            <p class="text-muted mb-0">Manage your account information and personal details below.</p>
        </div>
        <div class="card-body p-4">

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    
                    {{-- ACCOUNT INFORMATION (USERS TABLE) --}}
                    <div class="col-12">
                        <h5 class="text-primary border-bottom pb-2 mb-3"><i class="bi bi-shield-lock me-2"></i>Account Details</h5>
                        <div class="row g-3">
                            
                            {{-- Username --}}
                            <div class="col-md-4">
                                <label for="username" class="form-label fw-bold">Username <span class="text-danger">*</span></label>
                                <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror" 
                                       value="{{ old('username', $user->username) }}" required>
                                @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Email --}}
                            <div class="col-md-4">
                                <label for="email" class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email', $user->email) }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Phone --}}
                            <div class="col-md-4">
                                <label for="phone" class="form-label fw-bold">Phone</label>
                                <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" 
                                       value="{{ old('phone', $user->phone) }}">
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- PERSONAL DETAILS (EMPLOYEES TABLE) --}}
                    @if ($user->employee)
                    <div class="col-12 border-top pt-4">
                        <h5 class="text-primary border-bottom pb-2 mb-3"><i class="bi bi-person-vcard me-2"></i>Personal Details</h5>
                        <div class="row g-3">
                            
                            {{-- First Name --}}
                            <div class="col-md-6">
                                <label for="first_name" class="form-label fw-bold">First Name <span class="text-danger">*</span></label>
                                <input type="text" name="first_name" id="first_name" class="form-control @error('first_name') is-invalid @enderror" 
                                       value="{{ old('first_name', $user->employee->first_name) }}" required>
                                @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Last Name --}}
                            <div class="col-md-6">
                                <label for="last_name" class="form-label fw-bold">Last Name <span class="text-danger">*</span></label>
                                <input type="text" name="last_name" id="last_name" class="form-control @error('last_name') is-invalid @enderror" 
                                       value="{{ old('last_name', $user->employee->last_name) }}" required>
                                @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- NIDA --}}
                            <div class="col-md-6">
                                <label for="nida" class="form-label fw-bold">NIDA (National ID)</label>
                                <input type="text" name="nida" id="nida" class="form-control @error('nida') is-invalid @enderror" 
                                       value="{{ old('nida', $user->employee->nida) }}">
                                @error('nida')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Date of Birth --}}
                            <div class="col-md-6">
                                <label for="date_of_birth" class="form-label fw-bold">Date of Birth</label>
                                <input type="date" name="date_of_birth" id="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                       value="{{ old('date_of_birth', $user->employee->date_of_birth) }}">
                                @error('date_of_birth')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            
                            {{-- Gender --}}
                            <div class="col-md-6">
                                <label for="gender" class="form-label fw-bold">Gender</label>
                                <select name="gender" id="gender" class="form-select @error('gender') is-invalid @enderror">
                                    <option value="">Select Gender</option>
                                    @foreach(['male', 'female', 'other'] as $g)
                                        <option value="{{ $g }}" {{ old('gender', $user->employee->gender) == $g ? 'selected' : '' }}>
                                            {{ ucfirst($g) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Marital Status --}}
                            <div class="col-md-6">
                                <label for="marital_status" class="form-label fw-bold">Marital Status</label>
                                <select name="marital_status" id="marital_status" class="form-select @error('marital_status') is-invalid @enderror">
                                    <option value="">Select Status</option>
                                    @foreach(['single', 'married', 'divorced', 'widowed'] as $m)
                                        <option value="{{ $m }}" {{ old('marital_status', $user->employee->marital_status) == $m ? 'selected' : '' }}>
                                            {{ ucfirst($m) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('marital_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            
                            {{-- Address --}}
                            <div class="col-12">
                                <label for="address" class="form-label fw-bold">Address</label>
                                <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror" 
                                          rows="2">{{ old('address', $user->employee->address) }}</textarea>
                                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                        </div>
                    </div>
                    @else
                    <div class="col-12 border-top pt-4">
                        <div class="alert alert-info">
                            Cannot edit personal details as no linked employee record was found.
                        </div>
                    </div>
                    @endif

                </div>

                {{-- Form Actions --}}
                <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                    <a href="{{ route('profile.show') }}" class="btn btn-secondary shadow-sm">
                        <i class="bi bi-x-lg me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary shadow-sm">
                        <i class="bi bi-save me-1"></i> Update Profile
                    </button>
                    <a href="{{ route('profile.password.edit') }}" class="btn btn-outline-primary mt-3">
    Change Password
</a>

                </div>
            </form>
        </div>
    </div>
</div>
@endsection
