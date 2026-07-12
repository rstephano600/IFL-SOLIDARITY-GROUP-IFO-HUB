@extends('layouts.workingside')
@section('title', 'Registered Members Portfolio')
@section('page-title', 'Members Portfolio Management')

@section('content')
<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-users"></i></div>Members Management Portfolio</h3>
    <button class="arbif-btn-submit" data-bs-toggle="modal" data-bs-target="#addFormModal2">
        <i class="fas fa-user-plus"></i> Onboard Exixting User
    </button>
    <button class="arbif-btn-submit" data-bs-toggle="modal" data-bs-target="#addFormModal">
        <i class="fas fa-user-plus"></i> Onboard New Member
    </button>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="membersDirectoryTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Member Code</th>
                        <th class="sortable">Full Display Name</th>
                        <th class="sortable">Category Classification</th>
                        <th class="sortable">Corporate Entity</th>
                        <th class="sortable">Assigned Branch</th>
                        <th class="sortable">Contact Phone</th>
                        <th class="sortable">Admission Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><span class="arbif-badge arbif-badge-navy">{{ $item->member_code }}</span></td>
                        <td>
                            <div class="fw-bold text-dark">{{ $item->member_name }}</div>
                            <small class="text-muted d-block" style="font-size: 0.8rem;">{{ $item->memberUser->email ?? 'No Linked Email Profile' }}</small>
                        </td>
                        <td>
                            <span class="arbif-badge bg-light text-navy border">
                                {{ $item->memberCategory->member_category_name ?? 'Unassigned' }}
                            </span>
                        </td>
                        <td>{{ $item->company->company_name ?? '—' }}</td>
                        <td>{{ $item->branch->branch_name ?? '—' }}</td>
                        <td>{{ $item->memberUser->phone ?? '—' }}</td>
                        <td>{{ $item->admission_date ? $item->admission_date->format('Y-m-d') : '—' }}</td>
                        <td>
                            <a href="{{ route('viewmemberinformations', [encrypt($item->id)]) }}" class="arbif-btn-edit"> <i class="fas fa-eye"></i> View </a>
                            <a href="{{ route('editmemberinformations', [encrypt($item->id)]) }}" class="arbif-btn-edit"> <i class="fas fa-pencil"></i> Edit </a>
                            <a onclick="confirmDelete()" href="{{ route('deletememberinformations', [encrypt($item->id)]) }}" class="arbif-btn-delete"> <i class="fas fa-trash"></i> Delete </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="arbif-table-empty">
                            <i class="bi bi-inbox"></i>
                            No primary member profiles exist within the ecosystem directory.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="arbif-pagination">
            <span class="arbif-pagination-info" data-table-info="membersDirectoryTable"></span>
            <div class="arbif-pagination-pages" data-table-pages="membersDirectoryTable"></div>
        </div>
    </div>
</div>


<div class="modal fade arbif-modal" id="addFormModal2" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon"><i class="fas fa-tags"></i></div>
                <h5 class="modal-title">Comprehensive Member Onboarding Portal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <form method="POST" id="dataFormFill" action="{{ route('storememberinformationsexist') }}" enctype="multipart/form-data">
                    @csrf
                    <h5 class="text-navy mb-3 pb-1 border-bottom fw-bold"><i class="fas fa-user-check me-2"></i>1. Existing User Account</h5>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Select User <span class="text-danger">*</span></label>
                            <select name="member_id" class="form-control" data-searchable data-placeholder="Search User..." required>
                                <option></option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ old('member_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->username }}
                                        -
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"> Member Code </label>
                            <input type="text" class="form-control" name="member_code" value="{{ $memberCode }}" readonly>
                        </div>
                    </div>
                    <br>

                    <h5 class="text-navy mb-3 pb-1 border-bottom fw-bold"><i class="fas fa-sitemap me-2"></i> 2. Organizational Matrix & Hierarchy Allocation</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Operational Branch Base <span class="text-danger">*</span></label>
                            <select style="width: 100%" name="branch_id" data-searchable data-placeholder="Select Base Station Location..." required>
                                <option></option>
                                @foreach($branches as $br)
                                    <option value="{{ $br->id }}" {{ old('branch_id') == $br->id ? 'selected' : '' }}>
                                        {{ $br->branch_code }} - {{ $br->branch_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Membership Category Tier <span class="text-danger">*</span></label>
                            <select style="width: 100%" name="member_category_id" data-searchable data-placeholder="Select Policy Group Classification..." required>
                                <option></option>
                                @foreach($membercategories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('member_category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->member_category_code }} - {{ $cat->member_category_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <br>
                    <h5 class="text-navy mb-3 pb-1 border-bottom fw-bold"><i class="fas fa-balance-scale me-2"></i> 3. Regulatory Identifiers & Administrative Benchmarks</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="nida">NIDA National ID Number</label>
                            <input type="text" id="nida" name="nida" class="form-control" placeholder="20-digit National ID Check" value="{{ old('nida') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="tin">TRA Tax Identification (TIN)</label>
                            <input type="text" id="tin" name="tin" class="form-control" placeholder="9-digit Statutory TIN Profile" value="{{ old('tin') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="work_permit">Work Permit Ref / Visa ID</label>
                            <input type="text" id="work_permit" name="work_permit" class="form-control" placeholder="Expatriate Reference (if applicable)" value="{{ old('work_permit') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="admission_date">Corporate Admission Date</label>
                            <input type="date" id="admission_date" name="admission_date" class="form-control" value="{{ old('admission_date') }}">
                        </div>
                    </div>
                    <br>
                    <!-- <h5 class="text-navy mb-3 pb-1 border-bottom fw-bold"><i class="fas fa-balance-scale me-2"></i> 3. Regulatory Identifiers & Administrative Benchmarks</h5> -->
                    <!-- <div class="row g-3">
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
                    </div> -->
                    <div class="modal-footer shadow-sm bg-light rounded-bottom p-3" style="margin-top: 30px;">
                        <button type="button" class="arbif-btn-cancel" data-bs-dismiss="modal">
                            <i class="bi bi-x"></i> Cancel
                        </button>
                        <button onclick="confirmSubmit()" type="submit" class="arbif-btn-submit">
                            <i class="bi bi-check2"></i> <span id="submitBtnText">Commit Transaction & Onboard</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade arbif-modal" id="addFormModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon"><i class="fas fa-tags"></i></div>
                <h5 class="modal-title">Comprehensive Member Onboarding Portal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <form method="POST" id="dataFormFill" action="{{ route('storememberinformations') }}" enctype="multipart/form-data">
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

                    <h5 class="text-navy mb-3 pb-1 border-bottom fw-bold"><i class="fas fa-id-card me-2"></i> 1. System Account Identity</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label" for="FirstName">First Name <span class="text-danger">*</span></label>
                            <input type="text" id="FirstName" name="FirstName" class="form-control" placeholder="First Name" value="{{ old('FirstName') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="MiddleName">Middle Name</label>
                            <input type="text" id="MiddleName" name="MiddleName" class="form-control" placeholder="Middle Name" value="{{ old('MiddleName') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="LastName">Last Name <span class="text-danger">*</span></label>
                            <input type="text" id="LastName" name="LastName" class="form-control" placeholder="Last Name" value="{{ old('LastName') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="email">Primary Email Address <span class="text-danger">*</span></label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="name@domain.com" value="{{ old('email') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="phone">Contact Telephone <span class="text-danger">*</span></label>
                            <input type="text" id="phone" name="phone" class="form-control" placeholder="e.g. +255..." value="{{ old('phone') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="Dob">Date of Birth</label>
                            <input type="date" id="Dob" name="Dob" class="form-control" value="{{ old('Dob') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="gender">Gender Flag <span class="text-danger">*</span></label>
                            <select class="form-select" id="gender" name="gender" required>
                                <option value="" disabled selected>Select Sex...</option>
                                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>
                    </div>
                    <br>

                    <h5 class="text-navy mb-3 pb-1 border-bottom fw-bold"><i class="fas fa-sitemap me-2"></i> 2. Organizational Matrix & Hierarchy Allocation</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Operational Branch Base <span class="text-danger">*</span></label>
                            <select style="width: 100%" name="branch_id" data-searchable data-placeholder="Select Base Station Location..." required>
                                <option></option>
                                @foreach($branches as $br)
                                    <option value="{{ $br->id }}" {{ old('branch_id') == $br->id ? 'selected' : '' }}>
                                        {{ $br->branch_code }} - {{ $br->branch_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Membership Category Tier <span class="text-danger">*</span></label>
                            <select style="width: 100%" name="member_category_id" data-searchable data-placeholder="Select Policy Group Classification..." required>
                                <option></option>
                                @foreach($membercategories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('member_category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->member_category_code }} - {{ $cat->member_category_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <br>
                    <h5 class="text-navy mb-3 pb-1 border-bottom fw-bold"><i class="fas fa-balance-scale me-2"></i> 3. Regulatory Identifiers & Administrative Benchmarks</h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="member_code">Unique Member ID Code <span class="text-danger">*</span></label>
                            <input type="text" id="member_code" name="member_code" class="form-control" placeholder="e.g. MBR-2026-001" value="{{ old('member_code') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="nida">NIDA National ID Number</label>
                            <input type="text" id="nida" name="nida" class="form-control" placeholder="20-digit National ID Check" value="{{ old('nida') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="tin">TRA Tax Identification (TIN)</label>
                            <input type="text" id="tin" name="tin" class="form-control" placeholder="9-digit Statutory TIN Profile" value="{{ old('tin') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="work_permit">Work Permit Ref / Visa ID</label>
                            <input type="text" id="work_permit" name="work_permit" class="form-control" placeholder="Expatriate Reference (if applicable)" value="{{ old('work_permit') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="admission_date">Corporate Admission Date</label>
                            <input type="date" id="admission_date" name="admission_date" class="form-control" value="{{ old('admission_date') }}">
                        </div>
                    </div>
                    <br>
                    <!-- <h5 class="text-navy mb-3 pb-1 border-bottom fw-bold"><i class="fas fa-balance-scale me-2"></i> 3. Regulatory Identifiers & Administrative Benchmarks</h5> -->
                    <div class="row g-3">
                        <div class="col-12 mt-4">
                            <h5 class="arbif-section-title">Attachments</h5>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Profile Picture</label>
                            <input type="file" name="profile_picture" class="form-control" accept="image/*">
                        </div>

                        <!-- <div class="col-md-6">
                            <label class="form-label">CV Attachment</label>
                            <input type="file" name="cv_attachment" class="form-control" accept=".pdf,.doc,.docx">
                        </div> -->
                    </div>
                    <div class="modal-footer shadow-sm bg-light rounded-bottom p-3" style="margin-top: 30px;">
                        <button type="button" class="arbif-btn-cancel" data-bs-dismiss="modal">
                            <i class="bi bi-x"></i> Cancel
                        </button>
                        <button onclick="confirmSubmit()" type="submit" class="arbif-btn-submit">
                            <i class="bi bi-check2"></i> <span id="submitBtnText">Commit Transaction & Onboard</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection