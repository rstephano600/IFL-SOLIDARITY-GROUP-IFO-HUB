@extends('layouts.workingside')
@section('title', 'Edit Employee')
@section('page-title', 'Edit Employee')

@section('content')

@php
    $user = $employee->user;
@endphp

<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-user-edit"></i></div>Edit Employee — {{ $employee->EmployeeID ?? '' }}</h3>
    <a href="{{ route('employeeinfo') }}" class="arbif-btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to List
    </a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <form method="POST" action="{{ route('updateemployeeinfo', [encrypt($employee->id)]) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

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
                            <option value="{{ $branch->id }}" @selected(old('branch_id', $user->branch_id) == $branch->id)>{{ $branch->branch_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Role <span class="text-danger">*</span></label>
                    <select name="Role" class="form-control" data-searchable data-placeholder="Search ..." required>
                        <option value="">Select Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role }}" @selected(old('Role', $user->Role) == $role)>{{ ucfirst($role) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Email Address <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>

                {{-- ── PERSONAL INFORMATION ── --}}
                <div class="col-12 mt-4">
                    <h5 class="arbif-section-title">Personal Information</h5>
                </div>

                <div class="col-md-4">
                    <label class="form-label">First Name <span class="text-danger">*</span></label>
                    <input type="text" name="FirstName" class="form-control" value="{{ old('FirstName', $user->FirstName) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Middle Name</label>
                    <input type="text" name="MiddleName" class="form-control" value="{{ old('MiddleName', $user->MiddleName) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Last Name <span class="text-danger">*</span></label>
                    <input type="text" name="LastName" class="form-control" value="{{ old('LastName', $user->LastName) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Gender <span class="text-danger">*</span></label>
                    <select name="gender" class="form-control" required>
                        <option value="">Select Gender</option>
                        <option value="Male" @selected(old('gender', $user->gender) == 'Male')>Male</option>
                        <option value="Female" @selected(old('gender', $user->gender) == 'Female')>Female</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                    <input type="date" name="Dob" class="form-control" value="{{ old('Dob', optional($user->Dob)->format('Y-m-d')) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Marital Status</label>
                    <select name="marital_status" class="form-control">
                        <option value="">Select Status</option>
                        @foreach(['Single','Married','Divorced','Widowed'] as $status)
                            <option value="{{ $status }}" @selected(old('marital_status', $employee->marital_status) == $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">National ID (NIDA)</label>
                    <input type="text" name="nida" class="form-control" value="{{ old('nida', $employee->nida) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tribe</label>
                    <input type="text" name="tribe" class="form-control" value="{{ old('tribe', $employee->tribe) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Religion</label>
                    <input type="text" name="religion" class="form-control" value="{{ old('religion', $employee->religion) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Education Level</label>
                    <input type="text" name="education_level" class="form-control" value="{{ old('education_level', $employee->education_level) }}">
                </div>

                <div class="col-md-8">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control" value="{{ old('address', $employee->address) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Profile Picture</label>
                    @if($employee->profile_picture)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $employee->profile_picture) }}" width="60" height="60" style="border-radius:50%;object-fit:cover;">
                        </div>
                    @endif
                    <input type="file" name="profile_picture" class="form-control" accept="image/*">
                </div>

                <div class="col-md-6">
                    <label class="form-label">CV Attachment</label>
                    @if($employee->cv)
                        <div class="mb-2">
                            <a href="{{ asset('storage/' . $employee->cv) }}" target="_blank">View current CV</a>
                        </div>
                    @endif
                    <input type="file" name="cv_attachment" class="form-control" accept=".pdf,.doc,.docx">
                </div>

                <div class="col-12">
                    <label class="form-label">Other Information</label>
                    <textarea name="other_information" class="form-control" rows="2">{{ old('other_information', $employee->other_information) }}</textarea>
                </div>

                {{-- ── EMPLOYMENT INFORMATION ── --}}
                <div class="col-12 mt-4">
                    <h5 class="arbif-section-title">Employment Information</h5>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Employee ID</label>
                    <input type="text" name="EmployeeID" class="form-control" value="{{ old('EmployeeID', $employee->EmployeeID) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Position <span class="text-danger">*</span></label>
                    <input type="text" name="position" class="form-control" value="{{ old('position', $employee->position) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Department</label>
                    <input type="text" name="department" class="form-control" value="{{ old('department', $employee->department) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Date of Hire <span class="text-danger">*</span></label>
                    <input type="date" name="date_of_hire" class="form-control" value="{{ old('date_of_hire', optional($employee->date_of_hire)->format('Y-m-d')) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Employment Type</label>
                    <select name="employment_type" class="form-control">
                        <option value="">Select Type</option>
                        @foreach(['permanent','contract','probation','casual'] as $type)
                            <option value="{{ $type }}" @selected(old('employment_type', $employee->employment_type) == $type)>{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Supervisor</label>
                    <select name="supervisor_id" class="form-select" data-searchable data-placeholder="Search ...">
                        <option value="">-- None --</option>
                        @foreach($supervisors ?? [] as $sup)
                            @continue($sup->id == $employee->id)
                            <option value="{{ $sup->id }}" @selected(old('supervisor_id', $employee->supervisor_id) == $sup->id)>
                                {{ $sup->user->FirstName ?? '' }} {{ $sup->user->LastName ?? '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Contract Start Date</label>
                    <input type="date" name="contract_start_date" class="form-control" value="{{ old('contract_start_date', optional($employee->contract_start_date)->format('Y-m-d')) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Contract End Date</label>
                    <input type="date" name="contract_end_date" class="form-control" value="{{ old('contract_end_date', optional($employee->contract_end_date)->format('Y-m-d')) }}">
                </div>

                {{-- ── PAYMENT / SALARY INFORMATION ── --}}
                <div class="col-12 mt-4">
                    <h5 class="arbif-section-title">Payment &amp; Salary Information</h5>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Basic Salary</label>
                    <input type="number" step="0.000001" name="basic_salary" class="form-control" value="{{ old('basic_salary', $employee->basic_salary) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Housing Allowance</label>
                    <input type="number" step="0.000001" name="housing_allowance" class="form-control" value="{{ old('housing_allowance', $employee->housing_allowance) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Transport Allowance</label>
                    <input type="number" step="0.000001" name="transport_allowance" class="form-control" value="{{ old('transport_allowance', $employee->transport_allowance) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Medical Allowance</label>
                    <input type="number" step="0.000001" name="medical_allowance" class="form-control" value="{{ old('medical_allowance', $employee->medical_allowance) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Overtime Rate (per hour)</label>
                    <input type="number" step="0.000001" name="overtime_rate" class="form-control" value="{{ old('overtime_rate', $employee->overtime_rate) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Payment Method</label>
                    <select name="payment_method" id="payment_method" class="form-control">
                        <option value="">Select Method</option>
                        @foreach(['bank' => 'Bank', 'mobile_money' => 'Mobile Money', 'cash' => 'Cash'] as $val => $label)
                            <option value="{{ $val }}" @selected(old('payment_method', $employee->payment_method) == $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                @php $pm = old('payment_method', $employee->payment_method); @endphp

                <div class="col-md-3 payment-bank-field {{ $pm !== 'bank' ? 'd-none' : '' }}">
                    <label class="form-label">Bank Name</label>
                    <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name', $employee->bank_name) }}">
                </div>

                <div class="col-md-3 payment-bank-field {{ $pm !== 'bank' ? 'd-none' : '' }}">
                    <label class="form-label">Bank Account Number</label>
                    <input type="text" name="bank_account_number" class="form-control" value="{{ old('bank_account_number', $employee->bank_account_number) }}">
                </div>

                <div class="col-md-3 payment-bank-field {{ $pm !== 'bank' ? 'd-none' : '' }}">
                    <label class="form-label">Bank Account Name</label>
                    <input type="text" name="bank_account_name" class="form-control" value="{{ old('bank_account_name', $employee->bank_account_name) }}">
                </div>

                <div class="col-md-3 payment-bank-field {{ $pm !== 'bank' ? 'd-none' : '' }}">
                    <label class="form-label">Bank Branch</label>
                    <input type="text" name="bank_branch" class="form-control" value="{{ old('bank_branch', $employee->bank_branch) }}">
                </div>

                <div class="col-md-4 payment-mobile-field {{ $pm !== 'mobile_money' ? 'd-none' : '' }}">
                    <label class="form-label">Mobile Money Provider</label>
                    <select name="mobile_money_provider" class="form-control">
                        <option value="">Select Provider</option>
                        @foreach(['mpesa' => 'M-Pesa', 'tigopesa' => 'Tigo Pesa', 'airtelmoney' => 'Airtel Money', 'halopesa' => 'HaloPesa', 'other' => 'Other'] as $val => $label)
                            <option value="{{ $val }}" @selected(old('mobile_money_provider', $employee->mobile_money_provider) == $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 payment-mobile-field {{ $pm !== 'mobile_money' ? 'd-none' : '' }}">
                    <label class="form-label">Mobile Money Number</label>
                    <input type="text" name="mobile_money_number" class="form-control" value="{{ old('mobile_money_number', $employee->mobile_money_number) }}">
                </div>

                {{-- ── STATUTORY / COMPLIANCE ── --}}
                <div class="col-12 mt-4">
                    <h5 class="arbif-section-title">Statutory &amp; Compliance</h5>
                </div>

                <div class="col-md-4">
                    <label class="form-label">TIN Number</label>
                    <input type="text" name="tin_number" class="form-control" value="{{ old('tin_number', $employee->tin_number) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">NSSF Number</label>
                    <input type="text" name="nssf_number" class="form-control" value="{{ old('nssf_number', $employee->nssf_number) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">PSSSF Number</label>
                    <input type="text" name="pssss_number" class="form-control" value="{{ old('pssss_number', $employee->pssss_number) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">NHIF Number</label>
                    <input type="text" name="nhif_number" class="form-control" value="{{ old('nhif_number', $employee->nhif_number) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">WCF Number</label>
                    <input type="text" name="wcf_number" class="form-control" value="{{ old('wcf_number', $employee->wcf_number) }}">
                </div>

                {{-- ── SEPARATION (only relevant once inactive) ── --}}
                <div class="col-12 mt-4">
                    <h5 class="arbif-section-title">Separation</h5>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Termination Date</label>
                    <input type="date" name="termination_date" class="form-control" value="{{ old('termination_date', optional($employee->termination_date)->format('Y-m-d')) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Leave Balance (days)</label>
                    <input type="number" step="0.000001" name="leave_balance" class="form-control" value="{{ old('leave_balance', $employee->leave_balance) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Termination Reason</label>
                    <input type="text" name="termination_reason" class="form-control" value="{{ old('termination_reason', $employee->termination_reason) }}">
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-end gap-2">
                <a href="{{ route('employeeinfo') }}" class="arbif-btn-secondary">Cancel</a>
                <button type="submit" class="arbif-btn-submit">
                    <i class="fas fa-save"></i> Update Employee
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
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
</script>
@endpush