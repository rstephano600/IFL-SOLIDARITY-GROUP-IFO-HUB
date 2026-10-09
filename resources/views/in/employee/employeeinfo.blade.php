@extends('layouts.workingside')
@section('title', 'Employee Registrations')
@section('page-title', 'Employee Registrations')

@section('content')

<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-users"></i></div>Employee Registrations</h3>
    <div class="d-flex gap-2">
        <a href="{{ route('trashedemployeeinfo') }}" class="arbif-btn-secondary">
            <i class="fas fa-trash-restore"></i> Trashed Employees
        </a>
        <button class="arbif-btn-submit" data-bs-toggle="modal" data-bs-target="#addFormModal">
            <i class="fas fa-plus"></i> Add Employee Information
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
                        <th class="sortable">Payment Method</th>
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
                                <img src="{{ asset('storage/' . $item->profile_picture) }}"
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
                            {{ $item->user->FirstName ?? '' }}
                            {{ $item->user->MiddleName ?? '' }}
                            {{ $item->user->LastName ?? '' }}
                        </td>

                        <td>{{ $item->user->email ?? '—' }}</td>
                        <td>{{ $item->user->phone ?? '—' }}</td>
                        <td>{{ $item->department ?? '—' }}</td>

                        <td>
                            @if($item->payment_method)
                                <span class="arbif-badge arbif-badge-info">{{ ucfirst(str_replace('_',' ', $item->payment_method)) }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        <td>
                            @if($item->Status == 'Active')
                                <span class="arbif-badge arbif-badge-success">Active</span>
                            @else
                                <span class="arbif-badge arbif-badge-danger">Inactive</span>
                            @endif
                            @if($item->user && $item->user->locked_until && \Carbon\Carbon::parse($item->user->locked_until)->isFuture())
                                <span class="arbif-badge arbif-badge-warning"><i class="fas fa-lock"></i> Locked</span>
                            @endif
                        </td>

                        <td class="d-flex flex-wrap gap-1">
                            <a href="{{ route('showemployeeinfo', [encrypt($item->id)]) }}" class="arbif-btn-view" title="View">
                                <i class="fas fa-eye"></i>
                            </a>

                            <a href="{{ route('editemployeeinfo', [encrypt($item->id)]) }}" class="arbif-btn-edit" title="Edit">
                                <i class="fas fa-pencil"></i>
                            </a>

                            @if($item->user && $item->user->locked_until && \Carbon\Carbon::parse($item->user->locked_until)->isFuture())
                                <a onclick="confirmAction(event, 'Unlock this account?')"
                                   href="{{ route('unlockemployeeaccount', [encrypt($item->id)]) }}"
                                   class="arbif-btn-secondary" title="Unlock">
                                    <i class="fas fa-lock-open"></i>
                                </a>
                            @else
                                <a onclick="confirmAction(event, 'Lock this account?')"
                                   href="{{ route('lockemployeeaccount', [encrypt($item->id)]) }}"
                                   class="arbif-btn-secondary" title="Lock">
                                    <i class="fas fa-lock"></i>
                                </a>
                            @endif

                            <a onclick="confirmDelete(event)" href="{{ route('destroyemployeeinfo', [encrypt($item->id)]) }}" class="arbif-btn-delete" title="Deactivate">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="arbif-table-empty text-center py-4">
                            <i class="fas fa-inbox d-block mb-2 fs-3 text-muted"></i>
                            No employee records found.
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

{{-- ── REGISTRATION MODAL ── --}}
<div class="modal fade arbif-modal" id="addFormModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon"><i class="fas fa-user-plus"></i></div>
                <h5 class="modal-title">Register Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form method="POST" id="dataFormFill" action="{{ route('storenewemployeeinfo') }}" enctype="multipart/form-data">
                    @csrf
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row g-3">
                        {{-- ── USER ACCOUNT INFORMATION ── --}}
                        <div class="col-12">
                            <h5 class="arbif-section-title">User Account Information</h5>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Assigned Branch <span class="text-danger">*</span></label>
                            <select name="branch_id" class="form-select" data-searchable data-placeholder="Search ..." required>
                                <option value="">-- Select Branch --</option>
                                @foreach($branchies as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select name="Role" class="form-control" data-searchable data-placeholder="Search ..." required>
                                <option value="">Select Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" placeholder="Enter email address" required>
                        </div>

                        {{-- ── PERSONAL INFORMATION ── --}}
                        <div class="col-12 mt-4">
                            <h5 class="arbif-section-title">Personal Information</h5>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="FirstName" class="form-control" placeholder="Enter first name" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Middle Name</label>
                            <input type="text" name="MiddleName" class="form-control" placeholder="Enter middle name">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="LastName" class="form-control" placeholder="Enter last name" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control" placeholder="Enter phone number">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Gender <span class="text-danger">*</span></label>
                            <select name="gender" class="form-control" required>
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
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

                        <div class="col-md-4">
                            <label class="form-label">National ID (NIDA)</label>
                            <input type="text" name="nida" class="form-control" placeholder="Enter national ID">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Tribe</label>
                            <input type="text" name="tribe" class="form-control" placeholder="Tribe">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Religion</label>
                            <input type="text" name="religion" class="form-control" placeholder="Religion">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Education Level</label>
                            <input type="text" name="education_level" class="form-control" placeholder="Education level">
                        </div>

                        <div class="col-md-8">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" class="form-control" placeholder="Residential address">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Profile Picture</label>
                            <input type="file" name="profile_picture" class="form-control" accept="image/*">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">CV Attachment</label>
                            <input type="file" name="cv_attachment" class="form-control" accept=".pdf,.doc,.docx">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Other Information</label>
                            <textarea name="other_information" class="form-control" rows="2" placeholder="Other information"></textarea>
                        </div>

                        {{-- ── EMPLOYMENT INFORMATION ── --}}
                        <div class="col-12 mt-4">
                            <h5 class="arbif-section-title">Employment Information</h5>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Employee ID</label>
                            <input type="text" name="EmployeeID" class="form-control" placeholder="Leave blank to auto-generate">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Position <span class="text-danger">*</span></label>
                            <input type="text" name="position" class="form-control" placeholder="Job position/title" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Department</label>
                            <input type="text" name="department" class="form-control" placeholder="Department">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Date of Hire <span class="text-danger">*</span></label>
                            <input type="date" name="date_of_hire" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Employment Type</label>
                            <select name="employment_type" class="form-control">
                                <option value="">Select Type</option>
                                <option value="permanent">Permanent</option>
                                <option value="contract">Contract</option>
                                <option value="probation">Probation</option>
                                <option value="casual">Casual</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Supervisor</label>
                            <select name="supervisor_id" class="form-select" data-searchable data-placeholder="Search ...">
                                <option value="">-- None --</option>
                                @foreach($supervisors ?? [] as $sup)
                                    <option value="{{ $sup->id }}">{{ $sup->user->FirstName ?? '' }} {{ $sup->user->LastName ?? '' }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Contract Start Date</label>
                            <input type="date" name="contract_start_date" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Contract End Date</label>
                            <input type="date" name="contract_end_date" class="form-control">
                        </div>

                        {{-- ── PAYMENT / SALARY INFORMATION ── --}}
                        <div class="col-12 mt-4">
                            <h5 class="arbif-section-title">Payment &amp; Salary Information</h5>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Basic Salary</label>
                            <input type="number" step="0.000001" name="basic_salary" class="form-control" placeholder="0.00">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Housing Allowance</label>
                            <input type="number" step="0.000001" name="housing_allowance" class="form-control" placeholder="0.00">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Transport Allowance</label>
                            <input type="number" step="0.000001" name="transport_allowance" class="form-control" placeholder="0.00">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Medical Allowance</label>
                            <input type="number" step="0.000001" name="medical_allowance" class="form-control" placeholder="0.00">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Overtime Rate (per hour)</label>
                            <input type="number" step="0.000001" name="overtime_rate" class="form-control" placeholder="0.00">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Payment Method</label>
                            <select name="payment_method" id="payment_method" class="form-control">
                                <option value="">Select Method</option>
                                <option value="bank">Bank</option>
                                <option value="mobile_money">Mobile Money</option>
                                <option value="cash">Cash</option>
                            </select>
                        </div>

                        <div class="col-md-3 payment-bank-field d-none">
                            <label class="form-label">Bank Name</label>
                            <input type="text" name="bank_name" class="form-control" placeholder="Bank name">
                        </div>

                        <div class="col-md-3 payment-bank-field d-none">
                            <label class="form-label">Bank Account Number</label>
                            <input type="text" name="bank_account_number" class="form-control" placeholder="Account number">
                        </div>

                        <div class="col-md-3 payment-bank-field d-none">
                            <label class="form-label">Bank Account Name</label>
                            <input type="text" name="bank_account_name" class="form-control" placeholder="Account name">
                        </div>

                        <div class="col-md-3 payment-bank-field d-none">
                            <label class="form-label">Bank Branch</label>
                            <input type="text" name="bank_branch" class="form-control" placeholder="Branch">
                        </div>

                        <div class="col-md-4 payment-mobile-field d-none">
                            <label class="form-label">Mobile Money Provider</label>
                            <select name="mobile_money_provider" class="form-control">
                                <option value="">Select Provider</option>
                                <option value="mpesa">M-Pesa</option>
                                <option value="tigopesa">Tigo Pesa</option>
                                <option value="airtelmoney">Airtel Money</option>
                                <option value="halopesa">HaloPesa</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div class="col-md-4 payment-mobile-field d-none">
                            <label class="form-label">Mobile Money Number</label>
                            <input type="text" name="mobile_money_number" class="form-control" placeholder="e.g. 0712345678">
                        </div>

                        {{-- ── STATUTORY / COMPLIANCE ── --}}
                        <div class="col-12 mt-4">
                            <h5 class="arbif-section-title">Statutory &amp; Compliance</h5>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">TIN Number</label>
                            <input type="text" name="tin_number" class="form-control" placeholder="TIN number">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">NSSF Number</label>
                            <input type="text" name="nssf_number" class="form-control" placeholder="NSSF number">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">PSSSF Number</label>
                            <input type="text" name="pssss_number" class="form-control" placeholder="PSSSF number">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">NHIF Number</label>
                            <input type="text" name="nhif_number" class="form-control" placeholder="NHIF number">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">WCF Number</label>
                            <input type="text" name="wcf_number" class="form-control" placeholder="WCF number">
                        </div>
                    </div>

                    <div class="modal-footer mt-4">
                        <button type="button" class="arbif-btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="arbif-btn-submit">
                            <i class="fas fa-save"></i> Register Employee
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Re-open the modal automatically if validation errors were returned
    @if ($errors->any())
        document.addEventListener('DOMContentLoaded', function () {
            new bootstrap.Modal(document.getElementById('addFormModal')).show();
        });
    @endif

    // Toggle bank / mobile money fields based on selected payment method
    document.getElementById('payment_method')?.addEventListener('change', function () {
        const bankFields   = document.querySelectorAll('.payment-bank-field');
        const mobileFields = document.querySelectorAll('.payment-mobile-field');

        bankFields.forEach(el => el.classList.add('d-none'));
        mobileFields.forEach(el => el.classList.add('d-none'));

        if (this.value === 'bank') {
            bankFields.forEach(el => el.classList.remove('d-none'));
        } else if (this.value === 'mobile_money') {
            mobileFields.forEach(el => el.classList.remove('d-none'));
        }
    });

    function confirmAction(event, message) {
        event.preventDefault();
        const url = event.currentTarget.href;
        Swal.fire({
            title: 'Are you sure?',
            text: message,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, proceed',
        }).then((result) => {
            if (result.isConfirmed) window.location.href = url;
        });
    }
</script>
@endpush