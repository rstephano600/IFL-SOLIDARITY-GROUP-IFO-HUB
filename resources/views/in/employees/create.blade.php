@extends('layouts.app')

@section('title', 'Add Employee')
@section('page-title', 'Add a New Employee')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Employees</a></li>
    <li class="breadcrumb-item active">Add New</li>
@endsection

@section('content')
<div class="container-fluid">
    <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- User Account Information -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-person-lock"></i> Account Information</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- <div class="col-md-4">
                        <label class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" 
                               value="{{ old('username') }}" required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div> -->
                    <div class="col-md-6">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                               value="{{ old('phone') }}" placeholder="+255...">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Role/Level <span class="text-danger">*</span></label>
                        <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $role)) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Personal Information -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-person"></i> Personal Information</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" 
                               value="{{ old('first_name') }}" required>
                        @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Middle Name</label>
                        <input type="text" name="middle_name" class="form-control @error('middle_name') is-invalid @enderror" 
                               value="{{ old('middle_name') }}">
                        @error('middle_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" 
                               value="{{ old('last_name') }}" required>
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Gender <span class="text-danger">*</span></label>
                        <select name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                            <option value="">Select</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                        <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" 
                               value="{{ old('date_of_birth') }}" required>
                        @error('date_of_birth')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Marital Status</label>
                        <select name="marital_status" class="form-select @error('marital_status') is-invalid @enderror">
                            <option value="">Select</option>
                            <option value="single" {{ old('marital_status') == 'single' ? 'selected' : '' }}>Single</option>
                            <option value="married" {{ old('marital_status') == 'married' ? 'selected' : '' }}>Married</option>
                            <option value="divorced" {{ old('marital_status') == 'divorced' ? 'selected' : '' }}>Divorced</option>
                            <option value="widowed" {{ old('marital_status') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                        </select>
                        @error('marital_status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">NIDA Number</label>
                        <input type="text" name="nida" class="form-control @error('nida') is-invalid @enderror" 
                               value="{{ old('nida') }}" placeholder="19XXXXXXXXXX">
                        @error('nida')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tribe</label>
                        <input type="text" name="tribe" class="form-control @error('tribe') is-invalid @enderror" 
                               value="{{ old('tribe') }}">
                        @error('tribe')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Religion</label>
                        <input type="text" name="religion" class="form-control @error('religion') is-invalid @enderror" 
                               value="{{ old('religion') }}">
                        @error('religion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Position <span class="text-danger">*</span></label>
                        <input type="text" name="position" class="form-control @error('position') is-invalid @enderror" 
                               value="{{ old('position') }}" required>
                        @error('position')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Department <span class="text-danger">*</span></label>
                        <input type="text" name="department" class="form-control @error('department') is-invalid @enderror" 
                               value="{{ old('department') }}" required>
                        @error('department')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Date of Hire <span class="text-danger">*</span></label>
                        <input type="date" name="date_of_hire" class="form-control @error('date_of_hire') is-invalid @enderror" 
                               value="{{ old('date_of_hire') }}" required>
                        @error('date_of_hire')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Education Level</label>
                        <input type="text" name="education_level" class="form-control @error('education_level') is-invalid @enderror" 
                               value="{{ old('education_level') }}" placeholder="e.g., Degree, Diploma">
                        @error('education_level')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Profile Picture</label>
                        <input type="file" name="profile_picture" class="form-control @error('profile_picture') is-invalid @enderror" accept="image/*">
                        @error('profile_picture')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">JPG, PNG. Max: 2MB</small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">CV (Resume)</label>
                        <input type="file" name="cv" class="form-control @error('cv') is-invalid @enderror" accept=".pdf,.doc,.docx">
                        @error('cv')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">PDF, DOC, DOCX. Max: 5MB</small>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Other Information</label>
                        <textarea name="other_information" class="form-control @error('other_information') is-invalid @enderror" 
                                  rows="3">{{ old('other_information') }}</textarea>
                        @error('other_information')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Next of Kin Information -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="bi bi-person-hearts"></i> Next of Kin Information</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">First Name</label>
                        <input type="text" name="nok_first_name" class="form-control @error('nok_first_name') is-invalid @enderror" 
                               value="{{ old('nok_first_name') }}">
                        @error('nok_first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="nok_last_name" class="form-control @error('nok_last_name') is-invalid @enderror" 
                               value="{{ old('nok_last_name') }}">
                        @error('nok_last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Gender</label>
                        <select name="nok_gender" class="form-select @error('nok_gender') is-invalid @enderror">
                            <option value="">Select</option>
                            <option value="male" {{ old('nok_gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('nok_gender') == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('nok_gender') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('nok_gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Email</label>
                        <input type="email" name="nok_email" class="form-control @error('nok_email') is-invalid @enderror" 
                               value="{{ old('nok_email') }}">
                        @error('nok_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="nok_phone" class="form-control @error('nok_phone') is-invalid @enderror" 
                               value="{{ old('nok_phone') }}">
                        @error('nok_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Address</label>
                        <input type="text" name="nok_address" class="form-control @error('nok_address') is-invalid @enderror" 
                               value="{{ old('nok_address') }}">
                        @error('nok_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Other Information</label>
                        <textarea name="nok_other_informations" class="form-control @error('nok_other_informations') is-invalid @enderror" 
                                  rows="2">{{ old('nok_other_informations') }}</textarea>
                        @error('nok_other_informations')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Referees Information -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="bi bi-people"></i> Referees Information</h5>
            </div>
            <div class="card-body">
                <h6 class="mb-3">First Referee</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label">First Name</label>
                        <input type="text" name="ref1_first_name" class="form-control" value="{{ old('ref1_first_name') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="ref1_last_name" class="form-control" value="{{ old('ref1_last_name') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Gender</label>
                        <select name="ref1_gender" class="form-select">
                            <option value="">Select</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Email</label>
                        <input type="email" name="ref1_email" class="form-control" value="{{ old('ref1_email') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Phone</label>
                        <input type="text" name="ref1_phone" class="form-control" value="{{ old('ref1_phone') }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Address & Information</label>
                        <textarea name="ref1_other_informations" class="form-control" rows="2">{{ old('ref1_other_informations') }}</textarea>
                    </div>
                </div>

                <hr>

                <h6 class="mb-3">Second Referee</h6>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">First Name</label>
                        <input type="text" name="ref2_first_name" class="form-control" value="{{ old('ref2_first_name') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="ref2_last_name" class="form-control" value="{{ old('ref2_last_name') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Gender</label>
                        <select name="ref2_gender" class="form-select">
                            <option value="">Select</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Email</label>
                        <input type="email" name="ref2_email" class="form-control" value="{{ old('ref2_email') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Phone</label>
                        <input type="text" name="ref2_phone" class="form-control" value="{{ old('ref2_phone') }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Address & Information</label>
                        <textarea name="ref2_other_informations" class="form-control" rows="2">{{ old('ref2_other_informations') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-save"></i> Save Employee
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection