@extends('layouts.workingside')
@section('title', 'Member Profile Details')
@section('page-title', 'Member Profile Details')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-user"></i></div>
        {{ $member->member_name }} <small class="text-muted">({{ $member->member_code }})</small>
    </h3>
    <a href="{{ url()->previous() }}" class="arbif-btn-submit">
        <i class="fas fa-arrow-left"></i> Back to Directory
    </a>
</div>

<div class="arbif-card mb-4">
    <div class="arbif-card-body">
        <h4 class="mb-3 text-navy font-weight-bold"><i class="fas fa-id-badge me-2"></i> Core Account & Identity</h4>
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0">First Name</label>
                <span class="fw-bold text-dark d-block mt-1">{{ $member->memberUser->FirstName ?? '—' }}</span>
            </div>
            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0">Middle Name</label>
                <span class="fw-bold text-dark d-block mt-1">{{ $member->memberUser->MiddleName ?? '—' }}</span>
            </div>
            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0">Last Name</label>
                <span class="fw-bold text-dark d-block mt-1">{{ $member->memberUser->LastName ?? '—' }}</span>
            </div>
            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0">System Username</label>
                <span class="arbif-badge arbif-badge-navy mt-1">{{ $member->memberUser->username ?? $member->member_code }}</span>
            </div>

            <div class="col-md-12"><hr class="my-1"></div>

            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0">Email Address</label>
                <span class="text-dark d-block mt-1 fw-bold">{{ $member->memberUser->email ?? '—' }}</span>
            </div>
            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0">Phone Number</label>
                <span class="text-dark d-block mt-1 fw-bold">{{ $member->memberUser->phone ?? '—' }}</span>
            </div>
            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0">Date of Birth</label>
                <span class="text-dark d-block mt-1">{{ $member->memberUser->Dob ? \Carbon\Carbon::parse($member->memberUser->Dob)->format('Y-m-d') : '—' }}</span>
            </div>
            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0">Gender Flag</label>
                <span class="text-dark d-block mt-1">{{ $member->memberUser->gender ?? '—' }}</span>
            </div>
        </div>
    </div>
</div>

<div class="arbif-card mb-4">
    <div class="arbif-card-body">
        <h4 class="mb-3 text-navy font-weight-bold"><i class="fas fa-sitemap me-2"></i> Organizational & Regulatory Matrix</h4>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label d-block text-muted mb-0">Assigned Company Context</label>
                <span class="text-dark d-block mt-1 fw-bold">{{ $member->company->company_name ?? '—' }}</span>
            </div>
            <div class="col-md-4">
                <label class="form-label d-block text-muted mb-0">Operational Branch Base</label>
                <span class="text-dark d-block mt-1 fw-bold">{{ $member->branch->branch_name ?? '—' }}</span>
            </div>
            <div class="col-md-4">
                <label class="form-label d-block text-muted mb-0">Membership Classification Category</label>
                <span class="text-dark d-block mt-1 fw-bold text-navy">{{ $member->memberCategory->member_category_name ?? '—' }}</span>
            </div>

            <div class="col-md-12"><hr class="my-1"></div>

            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0">NIDA National ID</label>
                <span class="text-dark d-block mt-1 text-monospace fw-bold">{{ $member->nida ?? '—' }}</span>
            </div>
            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0">TRA TIN Profile</label>
                <span class="text-dark d-block mt-1 text-monospace fw-bold">{{ $member->tin ?? '—' }}</span>
            </div>
            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0">Work Permit Reference</label>
                <span class="text-dark d-block mt-1">{{ $member->work_permit ?? '—' }}</span>
            </div>
            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0">Admission Date</label>
                <span class="text-dark d-block mt-1 fw-bold text-success">{{ $member->admission_date ? $member->admission_date->format('Y-m-d') : '—' }}</span>
            </div>
        </div>

        <div class="mt-4 pt-3 border-top d-flex justify-content-between text-muted" style="font-size: 0.85rem;">
            <div>
                <strong>Operational Record Owner:</strong> {{ $member->user->name ?? '—' }}
                <span class="mx-2">|</span>
                <strong>Status:</strong> <span class="badge bg-success">{{ $member->Status ?? 'Active' }}</span>
            </div>
            <div>
                <strong>Created By:</strong> {{ $member->createdBy->name ?? '—' }} 
                <span class="mx-2">|</span> 
                <strong>Last Modified By:</strong> {{ $member->updatedBy->name ?? '—' }}
            </div>
        </div>
    </div>
</div>
@endsection