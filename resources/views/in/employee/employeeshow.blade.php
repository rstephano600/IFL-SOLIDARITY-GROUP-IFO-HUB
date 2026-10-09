@extends('layouts.workingside')
@section('title', 'Employee Profile')
@section('page-title', 'Employee Profile')

@section('content')

@php
    $user = $employee->user;
    $fullName = trim(($user->FirstName ?? '') . ' ' . ($user->MiddleName ?? '') . ' ' . ($user->LastName ?? ''));
@endphp

<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-id-card"></i></div>{{ $fullName ?: 'Employee Profile' }}</h3>
    <div class="d-flex gap-2">
        <a href="{{ route('editemployeeinfo', [encrypt($employee->id)]) }}" class="arbif-btn-edit">
            <i class="fas fa-pencil"></i> Edit
        </a>
        <a href="{{ route('employeeinfo') }}" class="arbif-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>
</div>

<div class="arbif-card mb-3">
    <div class="arbif-card-body d-flex align-items-center gap-4">
        @if($employee->profile_picture)
            <img src="{{ asset('storage/' . $employee->profile_picture) }}" width="90" height="90" style="border-radius:50%;object-fit:cover;">
        @else
            <div class="arbif-badge arbif-badge-warning">No Photo</div>
        @endif
        <div>
            <h4 class="mb-1">{{ $fullName }}</h4>
            <span class="arbif-badge arbif-badge-navy">{{ $employee->EmployeeID ?? 'N/A' }}</span>
            @if($employee->Status == 'Active')
                <span class="arbif-badge arbif-badge-success">Active</span>
            @else
                <span class="arbif-badge arbif-badge-danger">Inactive</span>
            @endif
            <p class="mt-2 mb-0">{{ $employee->position }} @if($employee->department) &middot; {{ $employee->department }} @endif</p>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="arbif-card h-100">
            <div class="arbif-card-body">
                <h5 class="arbif-section-title">Personal Information</h5>
                <table class="table table-sm mb-0">
                    <tr><th width="40%">Email</th><td>{{ $user->email ?? '—' }}</td></tr>
                    <tr><th>Phone</th><td>{{ $user->phone ?? '—' }}</td></tr>
                    <tr><th>Gender</th><td>{{ $user->gender ?? '—' }}</td></tr>
                    <tr><th>Date of Birth</th><td>{{ optional($user->Dob)->format('d M Y') ?? '—' }}</td></tr>
                    <tr><th>Marital Status</th><td>{{ $employee->marital_status ?? '—' }}</td></tr>
                    <tr><th>NIDA</th><td>{{ $employee->nida ?? '—' }}</td></tr>
                    <tr><th>Tribe</th><td>{{ $employee->tribe ?? '—' }}</td></tr>
                    <tr><th>Religion</th><td>{{ $employee->religion ?? '—' }}</td></tr>
                    <tr><th>Education Level</th><td>{{ $employee->education_level ?? '—' }}</td></tr>
                    <tr><th>Address</th><td>{{ $employee->address ?? '—' }}</td></tr>
                    <tr><th>CV</th><td>
                        @if($employee->cv)
                            <a href="{{ asset('storage/' . $employee->cv) }}" target="_blank">Download CV</a>
                        @else — @endif
                    </td></tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="arbif-card h-100">
            <div class="arbif-card-body">
                <h5 class="arbif-section-title">Employment Information</h5>
                <table class="table table-sm mb-0">
                    <tr><th width="40%">Branch</th><td>{{ $user->branch->branch_name ?? '—' }}</td></tr>
                    <tr><th>Role</th><td>{{ $user->Role ?? '—' }}</td></tr>
                    <tr><th>Position</th><td>{{ $employee->position ?? '—' }}</td></tr>
                    <tr><th>Department</th><td>{{ $employee->department ?? '—' }}</td></tr>
                    <tr><th>Date of Hire</th><td>{{ optional($employee->date_of_hire)->format('d M Y') ?? '—' }}</td></tr>
                    <tr><th>Employment Type</th><td>{{ $employee->employment_type ? ucfirst($employee->employment_type) : '—' }}</td></tr>
                    <tr><th>Supervisor</th><td>{{ optional($employee->supervisor)->user->FirstName ?? '—' }} {{ optional($employee->supervisor)->user->LastName ?? '' }}</td></tr>
                    <tr><th>Contract Period</th>
                        <td>
                            {{ optional($employee->contract_start_date)->format('d M Y') ?? '—' }}
                            &rarr;
                            {{ optional($employee->contract_end_date)->format('d M Y') ?? '—' }}
                        </td>
                    </tr>
                    <tr><th>Leave Balance</th><td>{{ $employee->leave_balance ?? '—' }} days</td></tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="arbif-card h-100">
            <div class="arbif-card-body">
                <h5 class="arbif-section-title">Payment &amp; Salary</h5>
                <table class="table table-sm mb-0">
                    <tr><th width="40%">Basic Salary</th><td>{{ number_format((float) $employee->basic_salary, 2) }}</td></tr>
                    <tr><th>Housing Allowance</th><td>{{ $employee->housing_allowance ? number_format((float) $employee->housing_allowance, 2) : '—' }}</td></tr>
                    <tr><th>Transport Allowance</th><td>{{ $employee->transport_allowance ? number_format((float) $employee->transport_allowance, 2) : '—' }}</td></tr>
                    <tr><th>Medical Allowance</th><td>{{ $employee->medical_allowance ? number_format((float) $employee->medical_allowance, 2) : '—' }}</td></tr>
                    <tr><th>Overtime Rate</th><td>{{ $employee->overtime_rate ? number_format((float) $employee->overtime_rate, 2) : '—' }}</td></tr>
                    <tr><th>Payment Method</th><td>{{ $employee->payment_method ? ucfirst(str_replace('_',' ',$employee->payment_method)) : '—' }}</td></tr>
                    @if($employee->payment_method == 'bank')
                        <tr><th>Bank</th><td>{{ $employee->bank_name }} — {{ $employee->bank_branch }}</td></tr>
                        <tr><th>Account</th><td>{{ $employee->bank_account_name }} ({{ $employee->bank_account_number }})</td></tr>
                    @elseif($employee->payment_method == 'mobile_money')
                        <tr><th>Mobile Money</th><td>{{ ucfirst($employee->mobile_money_provider ?? '') }} — {{ $employee->mobile_money_number }}</td></tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="arbif-card h-100">
            <div class="arbif-card-body">
                <h5 class="arbif-section-title">Statutory &amp; Compliance</h5>
                <table class="table table-sm mb-0">
                    <tr><th width="40%">TIN Number</th><td>{{ $employee->tin_number ?? '—' }}</td></tr>
                    <tr><th>NSSF Number</th><td>{{ $employee->nssf_number ?? '—' }}</td></tr>
                    <tr><th>PSSSF Number</th><td>{{ $employee->pssss_number ?? '—' }}</td></tr>
                    <tr><th>NHIF Number</th><td>{{ $employee->nhif_number ?? '—' }}</td></tr>
                    <tr><th>WCF Number</th><td>{{ $employee->wcf_number ?? '—' }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    @if($employee->Status != 'Active' || $employee->termination_date)
    <div class="col-12">
        <div class="arbif-card">
            <div class="arbif-card-body">
                <h5 class="arbif-section-title">Separation</h5>
                <table class="table table-sm mb-0">
                    <tr><th width="20%">Termination Date</th><td>{{ optional($employee->termination_date)->format('d M Y') ?? '—' }}</td></tr>
                    <tr><th>Reason</th><td>{{ $employee->termination_reason ?? '—' }}</td></tr>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>

@endsection