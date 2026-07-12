@extends('layouts.configside')
@section('title', 'Business Code Details')
@section('page-title', 'Business Code Details')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-barcode"></i></div>
        {{ $businesscode->business_name }} <small class="text-muted">({{ $businesscode->business_code }})</small>
    </h3>
    <a href="{{ url()->previous() }}" class="arbif-btn-submit">
        <i class="fas fa-arrow-left"></i> Back to List
    </a>
</div>

<div class="arbif-card mb-4">
    <div class="arbif-card-body">
        <h4 class="mb-3 text-navy font-weight-bold"><i class="fas fa-info-circle me-2"></i> Business Code Context</h4>
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0">Business Code</label>
                <span class="arbif-badge arbif-badge-navy mt-1" style="font-size: 0.95rem;">{{ $businesscode->business_code }}</span>
            </div>
            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0">Activity Focus</label>
                <span class="fw-bold text-dark d-block mt-1">{{ $businesscode->business_activity ?? '—' }}</span>
            </div>
            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0">Segment</label>
                <span class="fw-bold text-dark d-block mt-1">{{ $businesscode->segment ?? '—' }}</span>
            </div>
            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0">Record Status</label>
                <span class="fw-bold text-dark d-block mt-1">
                    <span class="badge bg-success">{{ $businesscode->Status ?? 'Active' }}</span>
                </span>
            </div>

            <div class="col-md-12">
                <hr class="my-2">
            </div>

            <div class="col-md-4">
                <label class="form-label d-block text-muted mb-0"><i class="fas fa-building me-1"></i> Associated Corporate Entity</label>
                <span class="text-dark d-block mt-1">{{ $businesscode->company->company_name ?? '—' }}</span>
            </div>
            <div class="col-md-4">
                <label class="form-label d-block text-muted mb-0"><i class="fas fa-network-wired"></i> Assigned Branch Scope</label>
                <span class="text-dark d-block mt-1">{{ $businesscode->branch->branch_name ?? '—' }}</span>
            </div>
            <div class="col-md-4">
                <label class="form-label d-block text-muted mb-0">Auditing / Reporting Status</label>
                <span class="text-dark d-block mt-1">
                    <span class="badge bg-secondary">{{ $businesscode->AuditingStatus ?? 'Pending' }}</span>
                </span>
            </div>

            @if($businesscode->description)
            <div class="col-md-12">
                <hr class="my-2">
                <label class="form-label d-block text-muted mb-1">Code Scope & Activity Description</label>
                <div class="p-3 bg-light rounded text-secondary" style="font-size: 0.9rem; line-height: 1.5;">
                    {{ $businesscode->description }}
                </div>
            </div>
            @endif
        </div>

        <div class="mt-4 pt-3 border-top d-flex justify-content-between text-muted" style="font-size: 0.85rem;">
            <div>
                <strong>System Record Owner:</strong> {{ $businesscode->user->name ?? '—' }}
            </div>
            <div>
                <strong>Created By:</strong> {{ $businesscode->createdBy->name ?? '—' }} 
                <span class="mx-2">|</span> 
                <strong>Last Updated By:</strong> {{ $businesscode->updatedBy->name ?? '—' }}
            </div>
        </div>
    </div>
</div>
@endsection