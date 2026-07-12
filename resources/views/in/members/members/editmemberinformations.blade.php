@extends('layouts.workingside')
@section('title', 'Modify Member Profile')
@section('page-title', 'Modify Member Profile')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-user-edit"></i></div>
        Edit Member File: {{ $member->member_name }}
    </h3>
    <a href="{{ url()->previous() }}" class="arbif-btn-cancel text-decoration-none d-inline-flex align-items-center">
        <i class="fas fa-arrow-left me-1"></i> Cancel & Return
    </a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <form method="POST" id="dataFormFill" action="{{ route('updatememberinformations', [encrypt($member->id)]) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <h5 class="text-navy mb-3 pb-1 border-bottom fw-bold"><i class="fas fa-id-card me-2"></i> 1. System Account Identity</h5>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label" for="FirstName">First Name <span class="text-danger">*</span></label>
                    <input type="text" id="FirstName" name="FirstName" class="form-control" value="{{ old('FirstName', $member->memberUser->FirstName ?? '') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="MiddleName">Middle Name</label>
                    <input type="text" id="MiddleName" name="MiddleName" class="form-control" value="{{ old('MiddleName', $member->memberUser->MiddleName ?? '') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="LastName">Last Name <span class="text-danger">*</span></label>
                    <input type="text" id="LastName" name="LastName" class="form-control" value="{{ old('LastName', $member->memberUser->LastName ?? '') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="member_name">Full Display Name <span class="text-danger">*</span></label>
                    <input type="text" id="member_name" name="member_name" class="form-control" value="{{ old('member_name', $member->member_name) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="email">Primary Email Profile <span class="text-danger">*</span></label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $member->memberUser->email ?? '') }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="phone">Contact Telephone <span class="text-danger">*</span></label>
                    <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', $member->memberUser->phone ?? '') }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="Dob">Date of Birth</label>
                    <input type="date" id="Dob" name="Dob" class="form-control" value="{{ old('Dob', $member->memberUser->Dob ?? '') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="gender">Gender Flag <span class="text-danger">*</span></label>
                    <select class="form-select" id="gender" name="gender" required>
                        <option value="Male" {{ old('gender', $member->memberUser->gender ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender', $member->memberUser->gender ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Assigned Core Access Role System Flag</label>
                    <input type="text" class="form-control bg-light" value="{{ $member->memberUser->Role ?? 'MEMBER' }}" disabled>
                </div>
            </div>

            <h5 class="text-navy mb-3 pb-1 border-bottom fw-bold"><i class="fas fa-sitemap me-2"></i> 2. Organizational Matrix & Hierarchy Allocation</h5>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Corporate Body Placement <span class="text-danger">*</span></label>
                    <select style="width: 100%" name="company_id" data-searchable data-placeholder="Select Entity Context..." required>
                        <option></option>
                        @foreach($companies as $comp)
                            <option value="{{ $comp->id }}" {{ old('company_id', $member->company_id) == $comp->id ? 'selected' : '' }}>
                                {{ $comp->company_code }} - {{ $comp->company_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Operational Branch Base <span class="text-danger">*</span></label>
                    <select style="width: 100%" name="branch_id" data-searchable data-placeholder="Select Branch Anchor..." required>
                        <option></option>
                        @foreach($branches as $br)
                            <option value="{{ $br->id }}" {{ old('branch_id', $member->branch_id) == $br->id ? 'selected' : '' }}>
                                {{ $br->branch_code }} - {{ $br->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Membership Category Tier <span class="text-danger">*</span></label>
                    <select style="width: 100%" name="member_category_id" data-searchable data-placeholder="Select Class Group..." required>
                        <option></option>
                        @foreach($membercategories as $cat)
                            <option value="{{ $cat->id }}" {{ old('member_category_id', $member->member_category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->member_category_code }} - {{ $cat->member_category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <h5 class="text-navy mb-3 pb-1 border-bottom fw-bold"><i class="fas fa-balance-scale me-2"></i> 3. Regulatory Identifiers & Administrative Benchmarks</h5>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label" for="member_code">Unique Member ID Code <span class="text-danger">*</span></label>
                    <input type="text" id="member_code" name="member_code" class="form-control" value="{{ old('member_code', $member->member_code) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="nida">NIDA National ID Number</label>
                    <input type="text" id="nida" name="nida" class="form-control" value="{{ old('nida', $member->nida) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="tin">TRA Tax Identification (TIN)</label>
                    <input type="text" id="tin" name="tin" class="form-control" value="{{ old('tin', $member->tin) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="work_permit">Work Permit Ref / Visa ID</label>
                    <input type="text" id="work_permit" name="work_permit" class="form-control" value="{{ old('work_permit', $member->work_permit) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="admission_date">Corporate Admission Date</label>
                    <input type="date" id="admission_date" name="admission_date" class="form-control" value="{{ old('admission_date', $member->admission_date ? $member->admission_date->format('Y-m-d') : '') }}">
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 border-top pt-3" style="margin-top: 30px;">
                <a href="{{ url()->previous() }}" class="arbif-btn-cancel text-decoration-none">
                    <i class="bi bi-x"></i> Cancel Changes
                </a>
                <button onclick="confirmSubmit()" type="submit" class="arbif-btn-submit">
                    <i class="bi bi-check2"></i> <span id="submitBtnText">Commit Structural Profile Updates</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection