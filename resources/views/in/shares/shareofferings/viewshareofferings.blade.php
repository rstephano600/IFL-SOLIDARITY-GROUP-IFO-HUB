@extends('layouts.configside')
@section('title', 'View Share Offering Details')
@section('page-title', 'Share Offering Profile')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-eye"></i></div>
        Offering Details: <span class="text-navy ms-1">{{ $shareOffering->OfferingName }}</span>
    </h3>
    <div>
        <a href="{{ route('editshareofferings', [encrypt($shareOffering->id)]) }}" class="arbif-btn-edit text-decoration-none me-2">
            <i class="fas fa-pencil me-1"></i> Edit Offering
        </a>
        <a href="{{ route('shareofferings') }}" class="arbif-btn-cancel text-decoration-none">
            <i class="fas fa-arrow-left me-1"></i> Back to Portfolio
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body p-4">
        
        <!-- SECTION 1: OFFERING INFORMATION -->
        <h5 class="text-navy mb-3 pb-2 border-bottom fw-bold">
            <i class="fas fa-hand-holding-usd me-2"></i> 1. Offering Overview
        </h5>
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <label class="text-muted small fw-bold d-block">Offering Reference No</label>
                <span class="arbif-badge arbif-badge-navy fs-6">{{ $shareOffering->OfferingRefNo ?? '—' }}</span>
            </div>

            <div class="col-md-3">
                <label class="text-muted small fw-bold d-block">Offering Name</label>
                <div class="fw-bold text-dark fs-6">{{ $shareOffering->OfferingName }}</div>
            </div>

            <div class="col-md-3">
                <label class="text-muted small fw-bold d-block">Share Type</label>
                <span class="badge bg-light text-dark border fw-bold fs-6">
                    {{ $shareOffering->shareType->TypeName ?? '—' }}
                </span>
            </div>

            <div class="col-md-3">
                <label class="text-muted small fw-bold d-block">Offering Status</label>
                @php
                    $statusClass = match($shareOffering->OfferingStatus) {
                        'Open' => 'bg-success',
                        'Closed' => 'bg-danger',
                        'Pending' => 'bg-warning text-dark',
                        default => 'bg-secondary'
                    };
                @endphp
                <span class="arbif-badge {{ $statusClass }} text-white fs-6">
                    {{ $shareOffering->OfferingStatus ?? 'Open' }}
                </span>
            </div>

            <div class="col-md-3">
                <label class="text-muted small fw-bold d-block">Total Allocated Shares</label>
                <div class="fw-bold text-dark fs-5">{{ number_format($shareOffering->TotalShares, 2) }}</div>
            </div>

            <div class="col-md-3">
                <label class="text-muted small fw-bold d-block">Price Per Share</label>
                <div class="fw-bold text-navy fs-5">{{ number_format($shareOffering->PricePerShare, 2) }}</div>
            </div>

            <div class="col-md-3">
                <label class="text-muted small fw-bold d-block">Total Capital Share</label>
                <div class="fw-bold text-navy fs-5">{{ number_format($shareOffering->TotalCapitalAmount, 2) }}</div>
            </div>

            <div class="col-md-3">
                <label class="text-muted small fw-bold d-block">Max % Per Member</label>
                <span class="badge bg-info text-dark fs-6">{{ number_format($shareOffering->MaxPercentPerMember, 2) }}%</span>
            </div>

            <div class="col-md-3">
                <label class="text-muted small fw-bold d-block">Offering Schedule</label>
                <div class="fw-bold text-dark">
                    {{ $shareOffering->OfferingStartDate ? $shareOffering->OfferingStartDate->format('d M Y') : '—' }} 
                    <span class="text-muted fw-normal">to</span> 
                    {{ $shareOffering->OfferingEndDate ? $shareOffering->OfferingEndDate->format('d M Y') : '—' }}
                </div>
            </div>
        </div>

        <!-- SECTION 2: ORGANIZATIONAL SCOPE & CREATOR -->
        <h5 class="text-navy mb-3 pb-2 border-bottom fw-bold">
            <i class="fas fa-building me-2"></i> 2. Scope & Ownership
        </h5>
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="text-muted small fw-bold d-block">Company Entity</label>
                <div class="fw-bold text-dark">{{ $shareOffering->company->company_name ?? '—' }}</div>
                <small class="text-muted">{{ $shareOffering->company->company_code ?? '' }}</small>
            </div>

            <div class="col-md-4">
                <label class="text-muted small fw-bold d-block">Operational Branch</label>
                <div class="fw-bold text-dark">{{ $shareOffering->branch->branch_name ?? '—' }}</div>
                <small class="text-muted">{{ $shareOffering->branch->branch_code ?? '' }}</small>
            </div>

            <div class="col-md-4">
                <label class="text-muted small fw-bold d-block">Created By User</label>
                <div class="fw-bold text-dark">{{ $shareOffering->user->name ?? 'System' }}</div>
                <small class="text-muted">{{ $shareOffering->user->email ?? '' }}</small>
            </div>
        </div>

        <!-- SECTION 3: SYSTEM AUDITING STATUS -->
        <h5 class="text-navy mb-3 pb-2 border-bottom fw-bold">
            <i class="fas fa-shield-alt me-2"></i> 3. Governance & Audit Info
        </h5>
        <div class="row g-3">
            <div class="col-md-3">
                <label class="text-muted small fw-bold d-block">Record Status</label>
                <span class="arbif-badge {{ $shareOffering->Status === 'Active' ? 'bg-success' : 'bg-danger' }} text-white">
                    {{ $shareOffering->Status ?? 'Active' }}
                </span>
            </div>

            <div class="col-md-3">
                <label class="text-muted small fw-bold d-block">Auditing Status</label>
                <span class="badge bg-light text-dark border">{{ $shareOffering->AuditingStatus ?? '—' }}</span>
            </div>

            <div class="col-md-3">
                <label class="text-muted small fw-bold d-block">Created At</label>
                <div class="text-dark">{{ $shareOffering->created_at ? $shareOffering->created_at->format('d M Y, H:i A') : '—' }}</div>
            </div>

            <div class="col-md-3">
                <label class="text-muted small fw-bold d-block">Last Updated</label>
                <div class="text-dark">{{ $shareOffering->updated_at ? $shareOffering->updated_at->format('d M Y, H:i A') : '—' }}</div>
            </div>
        </div>

    </div>
</div>
@endsection