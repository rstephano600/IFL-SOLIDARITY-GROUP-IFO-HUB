@extends('layouts.configside')
@section('title', 'Cost Centre Details')
@section('page-title', 'Cost Centre Details')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-calculator"></i></div>
        {{ $costcentre->cost_centre_name }} <small class="text-muted">({{ $costcentre->cost_centre_code }})</small>
    </h3>
    <a href="{{ url()->previous() }}" class="arbif-btn-submit">
        <i class="fas fa-arrow-left"></i> Back to List
    </a>
</div>

<div class="arbif-card mb-4">
    <div class="arbif-card-body">
        <h4 class="mb-3 text-navy font-weight-bold"><i class="fas fa-info-circle me-2"></i> Cost Centre Context</h4>
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0">Cost Centre Code</label>
                <span class="arbif-badge arbif-badge-navy mt-1" style="font-size: 0.95rem;">{{ $costcentre->cost_centre_code }}</span>
            </div>
            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0">Reporting Segment</label>
                <span class="fw-bold text-dark d-block mt-1">{{ $costcentre->reporting_segment ?? '—' }}</span>
            </div>
            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0">Status</label>
                <span class="fw-bold text-dark d-block mt-1">
                    <span class="badge bg-success">{{ $costcentre->Status ?? 'Active' }}</span>
                </span>
            </div>
            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0">Auditing Status / Report</label>
                <span class="fw-bold text-dark d-block mt-1">
                    <span class="badge bg-secondary">{{ $costcentre->AuditingStatus ?? 'Pending' }}</span>
                </span>
            </div>

            <div class="col-md-12">
                <hr class="my-2">
            </div>

            <div class="col-md-4">
                <label class="form-label d-block text-muted mb-0"><i class="fas fa-building me-1"></i> Assigned Company</label>
                <span class="text-dark d-block mt-1">{{ $costcentre->company->company_name ?? '—' }}</span>
            </div>
            <div class="col-md-4">
                <label class="form-label d-block text-muted mb-0"><i class="fas fa-network-wired"></i> Assigned Branch</label>
                <span class="text-dark d-block mt-1">{{ $costcentre->branch->branch_name ?? '—' }}</span>
            </div>
            <div class="col-md-4">
                <label class="form-label d-block text-muted mb-0"><i class="fas fa-sitemap"></i> Department Allocation</label>
                <span class="text-dark d-block mt-1">{{ $costcentre->department->department_name ?? '—' }}</span>
            </div>
        </div>

        <div class="mt-4 pt-3 border-top d-flex justify-content-between text-muted" style="font-size: 0.85rem;">
            <div>
                <strong>System Record Owner:</strong> {{ $costcentre->user->name ?? '—' }}
            </div>
            <div>
                <strong>Created By:</strong> {{ $costcentre->createdBy->name ?? '—' }} 
                <span class="mx-2">|</span> 
                <strong>Last Updated By:</strong> {{ $costcentre->updatedBy->name ?? '—' }}
            </div>
        </div>
    </div>
</div>
@endsection