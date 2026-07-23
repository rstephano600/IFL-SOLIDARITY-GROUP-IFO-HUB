@extends('layouts.configside')
@section('title', 'View Share Type Details')
@section('page-title', 'Share Type Profile')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-eye"></i></div>
        Share Type Details: <span class="text-navy ms-1">{{ $shareType->TypeName }}</span>
    </h3>
    <div>
        <a href="{{ route('editsharetypes', [encrypt($shareType->id)]) }}" class="arbif-btn-edit text-decoration-none me-2">
            <i class="fas fa-pencil me-1"></i> Edit Profile
        </a>
        <a href="{{ route('sharetypes') }}" class="arbif-btn-cancel text-decoration-none">
            <i class="fas fa-arrow-left me-1"></i> Back to Portfolio
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body p-4">
        
        <!-- SECTION 1: IDENTITY & PRICING -->
        <h5 class="text-navy mb-3 pb-2 border-bottom fw-bold">
            <i class="fas fa-info-circle me-2"></i> 1. Profile Information
        </h5>
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <label class="text-muted small fw-bold d-block">Type Reference No</label>
                <span class="arbif-badge arbif-badge-navy fs-6">{{ $shareType->TypeRefNo ?? '—' }}</span>
            </div>

            <div class="col-md-3">
                <label class="text-muted small fw-bold d-block">Type Code</label>
                <div class="fw-bold text-dark fs-6">{{ $shareType->TypeCode }}</div>
            </div>

            <div class="col-md-3">
                <label class="text-muted small fw-bold d-block">Type Name</label>
                <div class="fw-bold text-dark fs-6">{{ $shareType->TypeName }}</div>
            </div>

            <div class="col-md-3">
                <label class="text-muted small fw-bold d-block">Status</label>
                <span class="arbif-badge {{ $shareType->Status === 'Active' ? 'bg-success' : 'bg-danger' }} text-white">
                    {{ $shareType->Status ?? 'Active' }}
                </span>
            </div>

            <div class="col-md-3">
                <label class="text-muted small fw-bold d-block">Nominal Value</label>
                <div class="fw-bold text-navy fs-5">{{ number_format($shareType->NominalValue, 2) }}</div>
            </div>

            <div class="col-md-3">
                <label class="text-muted small fw-bold d-block">Dividend Eligibility</label>
                <div>
                    @if($shareType->DividendEligible)
                        <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Eligible</span>
                    @else
                        <span class="badge bg-secondary"><i class="fas fa-times-circle me-1"></i> Not Eligible</span>
                    @endif
                </div>
            </div>

            <div class="col-md-6">
                <label class="text-muted small fw-bold d-block">Description</label>
                <div class="text-dark">{{ $shareType->Description ?? 'No description provided.' }}</div>
            </div>
        </div>

        <!-- SECTION 2: ORGANIZATIONAL ASSIGNMENT -->
        <h5 class="text-navy mb-3 pb-2 border-bottom fw-bold">
            <i class="fas fa-building me-2"></i> 2. Scope & Ownership
        </h5>
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="text-muted small fw-bold d-block">Company Entity</label>
                <div class="fw-bold text-dark">{{ $shareType->company->company_name ?? '—' }}</div>
                <small class="text-muted">{{ $shareType->company->company_code ?? '' }}</small>
            </div>

            <div class="col-md-4">
                <label class="text-muted small fw-bold d-block">Operational Branch</label>
                <div class="fw-bold text-dark">{{ $shareType->branch->branch_name ?? '—' }}</div>
                <small class="text-muted">{{ $shareType->branch->branch_code ?? '' }}</small>
            </div>

            <div class="col-md-4">
                <label class="text-muted small fw-bold d-block">Created By User</label>
                <div class="fw-bold text-dark">{{ $shareType->user->name ?? 'System' }}</div>
                <small class="text-muted">{{ $shareType->user->email ?? '' }}</small>
            </div>
        </div>

        <!-- SECTION 3: SYSTEM AUDITING STATUS -->
        <h5 class="text-navy mb-3 pb-2 border-bottom fw-bold">
            <i class="fas fa-shield-alt me-2"></i> 3. Governance & System Info
        </h5>
        <div class="row g-3">
            <div class="col-md-3">
                <label class="text-muted small fw-bold d-block">Auditing Status</label>
                <span class="badge bg-light text-dark border">{{ $shareType->AuditingStatus ?? '—' }}</span>
            </div>

            <div class="col-md-3">
                <label class="text-muted small fw-bold d-block">Report Status</label>
                <span class="badge bg-light text-dark border">{{ $shareType->ReportStatus ?? '—' }}</span>
            </div>

            <div class="col-md-3">
                <label class="text-muted small fw-bold d-block">Created At</label>
                <div class="text-dark">{{ $shareType->created_at ? $shareType->created_at->format('d M Y, H:i A') : '—' }}</div>
            </div>

            <div class="col-md-3">
                <label class="text-muted small fw-bold d-block">Last Updated</label>
                <div class="text-dark">{{ $shareType->updated_at ? $shareType->updated_at->format('d M Y, H:i A') : '—' }}</div>
            </div>
        </div>

    </div>
</div>
@endsection