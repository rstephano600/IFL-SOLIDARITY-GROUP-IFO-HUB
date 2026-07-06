@extends('layouts.workingside')
@section('title', 'Employee Registrations')
@section('page-title', 'Employee Registrations')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-users"></i></div>
        Employee Registrations
    </h3>

    <div class="d-flex gap-2">
        <button type="button" class="arbif-btn-submit btn btn-primary btn-sm d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#registerEmployeeModal">
            <i class="fas fa-plus"></i> Add Employee
        </button>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="employeeTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Photo</th>
                        <th class="sortable">Employee ID</th>
                        <th class="sortable">Full Name</th>
                        <th class="sortable">Email</th>
                        <th class="sortable">Phone</th>
                        <th class="sortable">Department</th>
                        <th class="sortable">Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>

                        <td>
                            @if($item->profile_picture)
                                <img src="{{ asset($item->profile_picture) }}"
                                     width="40"
                                     height="40"
                                     style="border-radius: 50%; object-fit: cover;">
                            @else
                                <span class="arbif-badge arbif-badge-warning">No Photo</span>
                            @endif
                        </td>

                        <td>
                            <span class="arbif-badge arbif-badge-navy">
                                {{ $item->EmployeeID ?? 'N/A' }}
                            </span>
                        </td>

                        <td>
                            {{ $item->employee->FirstName ?? '' }}
                            {{ $item->employee->MiddleName ?? '' }}
                            {{ $item->employee->LastName ?? '' }}
                        </td>

                        <td>{{ $item->employee->email ?? '—' }}</td>
                        <td>{{ $item->employee->phone ?? '—' }}</td>
                        <td>{{ $item->department ?? '—' }}</td>

                        <td>
                            @if($item->is_active == 1)
                                <span class="arbif-badge arbif-badge-success">Active</span>
                            @else
                                <span class="arbif-badge arbif-badge-danger">Inactive</span>
                            @endif
                        </td>

                        <td>
                            <a href="{{ route('editemployeeinfo', [encrypt($item->id)]) }}" class="arbif-btn-edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>

                            <a onclick="confirmDelete(event)" href="{{ route('destroyemployeeinfo', [encrypt($item->id)]) }}" class="arbif-btn-delete">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="arbif-table-empty">
                            <i class="bi bi-inbox"></i> No employee records found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="arbif-pagination">
            <span class="arbif-pagination-info" data-table-info="employeeTable"></span>
            <div class="arbif-pagination-pages" data-table-pages="employeeTable"></div>
        </div>
    </div>
</div>

<!-- Register Employee Modal -->
<div class="modal fade" id="registerEmployeeModal" tabindex="-1" aria-labelledby="registerEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon"><i class="bi bi-person-plus"></i></div>
                <h5 class="modal-title" id="registerEmployeeModalLabel">Register Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form method="POST" id="dataFormFill" action="{{ route('storenewemployeeinfo') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <div class="col-12">
                            <h5 class="arbif-section-title">User Account Information</h5>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Role</label>
                            <select name="Role" class="form-control select2_demo_3" style="width:100%" required>
                                <option value="">Select Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="Enter email address" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control" placeholder="Enter phone number" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Tribe</label>
                            <input type="text" name="tribe" class="form-control" placeholder="Enter tribe">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Religion</label>
                            <input type="text" name="religion" class="form-control" placeholder="Enter religion">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Education Level</label>
                            <input type="text" name="education_level" class="form-control" placeholder="Enter education level">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Other Information</label>
                            <input type="text" name="other_information" class="form-control" placeholder="Enter other information">
                        </div>

                        <div class="col-12 mt-4">
                            <h5 class="arbif-section-title">Personal Information</h5>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">First Name</label>
                            <input type="text" name="FirstName" class="form-control" placeholder="Enter first name" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Middle Name</label>
                            <input type="text" name="MiddleName" class="form-control" placeholder="Enter middle name">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="LastName" class="form-control" placeholder="Enter last name" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-control" required>
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="Dob" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Marital Status</label>
                            <select name="marital_status" class="form-control">
                                <option value="">Select Status</option>
                                <option value="Single">Single</option>
                                <option value="Married">Married</option>
                                <option value="Divorced">Divorced</option>
                                <option value="Widowed">Widowed</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">National ID</label>
                            <input type="text" name="nida" class="form-control" placeholder="Enter national ID">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nationality</label>
                            <input type="text" name="nationality" class="form-control" placeholder="Enter nationality">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Residential Address</label>
                            <textarea name="address" rows="3" class="form-control" placeholder="Enter residential address"></textarea>
                        </div>

                        <div class="col-12 mt-4">
                            <h5 class="arbif-section-title">Employment Information</h5>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Employee ID</label>
                            <input type="text" name="EmployeeID" class="form-control" placeholder="Enter employee ID">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Department</label>
                            <input type="text" name="department" class="form-control" placeholder="Enter department">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Position</label>
                            <input type="text" name="position" class="form-control" placeholder="Enter position">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Employment Date</label>
                            <input type="date" name="date_of_hire" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Basic Salary</label>
                            <input type="number" step="0.01" name="basic_salary" class="form-control" placeholder="Enter salary">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Weekly Allowance Amount</label>
                            <input type="number" step="0.01" name="weekly_allowance_amount" class="form-control" placeholder="Weekly Allowance">
                        </div>

                        <div class="col-12 mt-4">
                            <h5 class="arbif-section-title">Next of Kin Information</h5>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">First Name</label>
                            <input type="text" name="nok_first_name" class="form-control" placeholder="Enter Kin First name">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="nok_last_name" class="form-control" placeholder="Enter Kin Last name">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Relationship</label>
                            <input type="text" name="nok_relationship" class="form-control" placeholder="Enter relationship">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Kin Email</label>
                            <input type="email" name="nok_email" class="form-control" placeholder="Enter Kin email">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Kin Phone Number</label>
                            <input type="text" name="nok_phone" class="form-control" placeholder="Enter phone number">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Gender</label>
                            <select name="nok_gender" class="form-control" required>
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Other Information</label>
                            <textarea name="nok_other_informations" rows="2" class="form-control" placeholder="Enter Other info"></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Kin Address</label>
                            <textarea name="nok_address" rows="2" class="form-control" placeholder="Enter Kin address"></textarea>
                        </div>

                        <div class="col-12 mt-4">
                            <h5 class="arbif-section-title">Referee Information</h5>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Referee First Name</label>
                            <input type="text" name="ref1_first_name" class="form-control" placeholder="Enter referee first name">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Referee Last Name</label>
                            <input type="text" name="ref1_last_name" class="form-control" placeholder="Enter referee last name">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Gender</label>
                            <select name="ref1_gender" class="form-control" required>
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Referee Email</label>
                            <input type="email" name="ref1_email" class="form-control" placeholder="Enter Email">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Referee Phone</label>
                            <input type="text" name="ref1_phone" class="form-control" placeholder="Enter referee phone">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Referee Occupation</label>
                            <input type="text" name="ref1_occupation" class="form-control" placeholder="Enter occupation">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Other Information</label>
                            <textarea name="ref1_other_informations" rows="2" class="form-control" placeholder="Enter Other info"></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Referee Address</label>
                            <textarea name="ref1_address" rows="2" class="form-control" placeholder="Enter referee address"></textarea>
                        </div>

                        <div class="col-12 mt-4">
                            <h5 class="arbif-section-title">Attachments</h5>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Profile Picture</label>
                            <input type="file" name="profile_picture" class="form-control" accept="image/*">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">CV Attachment</label>
                            <input type="file" name="cv_attachment" class="form-control" accept=".pdf,.doc,.docx">
                        </div>
                    </div>

                    <div class="modal-footer" style="margin-top: 20px;">
                        <button type="button" class="arbif-btn-cancel" data-bs-dismiss="modal">
                            <i class="bi bi-x"></i> Cancel
                        </button>
                        <button type="submit" class="arbif-btn-submit">
                            <i class="bi bi-check-circle"></i> Register Employee
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection