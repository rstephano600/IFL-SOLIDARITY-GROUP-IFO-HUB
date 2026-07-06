@extends('layouts.workingside')
@section('title', 'Edit Employee')
@section('page-title', 'Edit Employee')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-user-edit"></i></div>
        Edit Employee
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('employeeinfo') }}" class="arbif-btn-cancel">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('updateemployeeinfo', [encrypt($employee->id)]) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-12">
                    <h5 class="arbif-section-title">User Account Information</h5>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Role</label>
                    <select name="Role" class="form-control select2_demo_3" style="width:100%" required>
                        <option value="">Select Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role }}" {{ old('Role', $employee->employee->Role ?? '') == $role ? 'selected' : '' }}>
                                {{ ucfirst($role) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control"
                           value="{{ old('email', $employee->employee->email ?? '') }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" class="form-control"
                           value="{{ old('phone', $employee->employee->phone ?? '') }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tribe</label>
                    <input type="text" name="tribe" class="form-control"
                           value="{{ old('tribe', $employee->tribe) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Religion</label>
                    <input type="text" name="religion" class="form-control"
                           value="{{ old('religion', $employee->religion) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Education Level</label>
                    <input type="text" name="education_level" class="form-control"
                           value="{{ old('education_level', $employee->education_level) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Other Information</label>
                    <input type="text" name="other_information" class="form-control"
                           value="{{ old('other_information', $employee->other_information) }}">
                </div>

                <div class="col-12 mt-4">
                    <h5 class="arbif-section-title">Personal Information</h5>
                </div>

                <div class="col-md-4">
                    <label class="form-label">First Name</label>
                    <input type="text" name="FirstName" class="form-control"
                           value="{{ old('FirstName', $employee->employee->FirstName ?? '') }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Middle Name</label>
                    <input type="text" name="MiddleName" class="form-control"
                           value="{{ old('MiddleName', $employee->employee->MiddleName ?? '') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Last Name</label>
                    <input type="text" name="LastName" class="form-control"
                           value="{{ old('LastName', $employee->employee->LastName ?? '') }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-control" required>
                        <option value="">Select Gender</option>
                        @php $currentGender = old('gender', $employee->employee->gender ?? ''); @endphp
                        <option value="Male" {{ $currentGender == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ $currentGender == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Date of Birth</label>
                    <input type="date" name="Dob" class="form-control"
                           value="{{ old('Dob', optional($employee->employee->Dob ?? null)->format('Y-m-d')) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Marital Status</label>
                    <select name="marital_status" class="form-control">
                        <option value="">Select Status</option>
                        @php $currentMarital = old('marital_status', $employee->marital_status); @endphp
                        @foreach(['Single','Married','Divorced','Widowed'] as $status)
                            <option value="{{ $status }}" {{ $currentMarital == $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">National ID</label>
                    <input type="text" name="nida" class="form-control"
                           value="{{ old('nida', $employee->nida) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nationality</label>
                    <input type="text" name="nationality" class="form-control"
                           value="{{ old('nationality', $employee->nationality ?? '') }}">
                </div>

                <div class="col-md-12">
                    <label class="form-label">Residential Address</label>
                    <textarea name="address" rows="3" class="form-control">{{ old('address', $employee->address) }}</textarea>
                </div>

                <div class="col-12 mt-4">
                    <h5 class="arbif-section-title">Employment Information</h5>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Employee ID</label>
                    <input type="text" name="EmployeeID" class="form-control"
                           value="{{ old('EmployeeID', $employee->EmployeeID) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Department</label>
                    <input type="text" name="department" class="form-control"
                           value="{{ old('department', $employee->department) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Position</label>
                    <input type="text" name="position" class="form-control"
                           value="{{ old('position', $employee->position) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Employment Date</label>
                    <input type="date" name="date_of_hire" class="form-control"
                           value="{{ old('date_of_hire', optional($employee->date_of_hire)->format('Y-m-d')) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Basic Salary</label>
                    <input type="number" step="0.01" name="basic_salary" class="form-control"
                           value="{{ old('basic_salary', $employee->basic_salary) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Weekly Allowance Amount</label>
                    <input type="number" step="0.01" name="weekly_allowance_amount" class="form-control"
                           value="{{ old('weekly_allowance_amount', $employee->weekly_allowance_amount) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="is_active" class="form-control">
                        <option value="1" {{ $employee->is_active ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ !$employee->is_active ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="col-12 mt-4">
                    <h5 class="arbif-section-title">Next of Kin Information</h5>
                </div>

                <div class="col-md-4">
                    <label class="form-label">First Name</label>
                    <input type="text" name="nok_first_name" class="form-control"
                           value="{{ old('nok_first_name', $employee->nextOfKin->first_name ?? '') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Last Name</label>
                    <input type="text" name="nok_last_name" class="form-control"
                           value="{{ old('nok_last_name', $employee->nextOfKin->last_name ?? '') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Relationship</label>
                    <input type="text" name="nok_relationship" class="form-control"
                           value="{{ old('nok_relationship', $employee->nextOfKin->relationship ?? '') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Kin Email</label>
                    <input type="email" name="nok_email" class="form-control"
                           value="{{ old('nok_email', $employee->nextOfKin->email ?? '') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Kin Phone Number</label>
                    <input type="text" name="nok_phone" class="form-control"
                           value="{{ old('nok_phone', $employee->nextOfKin->phone ?? '') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Gender</label>
                    <select name="nok_gender" class="form-control">
                        <option value="">Select Gender</option>
                        @php $nokGender = old('nok_gender', $employee->nextOfKin->gender ?? ''); @endphp
                        <option value="Male" {{ $nokGender == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ $nokGender == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Other Information</label>
                    <textarea name="nok_other_informations" rows="2" class="form-control">{{ old('nok_other_informations', $employee->nextOfKin->other_informations ?? '') }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Kin Address</label>
                    <textarea name="nok_address" rows="2" class="form-control">{{ old('nok_address', $employee->nextOfKin->address ?? '') }}</textarea>
                </div>

                <div class="col-12 mt-4">
                    <h5 class="arbif-section-title">Referee Information</h5>
                </div>

                @php $referee = $employee->referees->first(); @endphp

                <div class="col-md-4">
                    <label class="form-label">Referee First Name</label>
                    <input type="text" name="ref1_first_name" class="form-control"
                           value="{{ old('ref1_first_name', $referee->first_name ?? '') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Referee Last Name</label>
                    <input type="text" name="ref1_last_name" class="form-control"
                           value="{{ old('ref1_last_name', $referee->last_name ?? '') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Gender</label>
                    <select name="ref1_gender" class="form-control">
                        <option value="">Select Gender</option>
                        @php $refGender = old('ref1_gender', $referee->gender ?? ''); @endphp
                        <option value="Male" {{ $refGender == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ $refGender == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Referee Email</label>
                    <input type="email" name="ref1_email" class="form-control"
                           value="{{ old('ref1_email', $referee->email ?? '') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Referee Phone</label>
                    <input type="text" name="ref1_phone" class="form-control"
                           value="{{ old('ref1_phone', $referee->phone ?? '') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Referee Occupation</label>
                    <input type="text" name="ref1_occupation" class="form-control"
                           value="{{ old('ref1_occupation', $referee->occupation ?? '') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Other Information</label>
                    <textarea name="ref1_other_informations" rows="2" class="form-control">{{ old('ref1_other_informations', $referee->other_informations ?? '') }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Referee Address</label>
                    <textarea name="ref1_address" rows="2" class="form-control">{{ old('ref1_address', $referee->address ?? '') }}</textarea>
                </div>

                <div class="col-12 mt-4">
                    <h5 class="arbif-section-title">Attachments</h5>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Profile Picture</label>
                    @if($employee->profile_picture)
                        <div class="mb-2">
                            <img src="{{ asset($employee->profile_picture) }}" width="60" height="60"
                                 style="border-radius: 50%; object-fit: cover;">
                        </div>
                    @endif
                    <input type="file" name="profile_picture" class="form-control" accept="image/*">
                    <small class="text-muted">Leave empty to keep the current photo.</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label">CV Attachment</label>
                    @if($employee->cv)
                        <div class="mb-2">
                            <a href="{{ asset($employee->cv) }}" target="_blank">
                                <i class="fas fa-file-pdf"></i> View current CV
                            </a>
                        </div>
                    @endif
                    <input type="file" name="cv" class="form-control" accept=".pdf,.doc,.docx">
                    <small class="text-muted">Leave empty to keep the current file.</small>
                </div>
            </div>

            <div class="modal-footer" style="margin-top: 20px;">
                <a href="{{ route('employeeinfo') }}" class="arbif-btn-cancel">
                    <i class="bi bi-x"></i> Cancel
                </a>
                <button type="submit" class="arbif-btn-submit">
                    <i class="bi bi-check-circle"></i> Update Employee
                </button>
            </div>
        </form>
    </div>
</div>
@endsection