@extends('layouts.configside')
@section('title', 'Product Category Details')
@section('page-title', 'Product Category Overview')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-boxes"></i></div>
        {{ $productCategory->category_name }} 
        <small class="text-muted">({{ $productCategory->category_code }})</small>
    </h3>
    <a href="{{ url()->previous() }}" class="arbif-btn-submit text-decoration-none">
        <i class="fas fa-arrow-left me-1"></i> Back to Categories
    </a>
</div>

<!-- Primary Category Specifications -->
<div class="arbif-card mb-4">
    <div class="arbif-card-body">
        <h4 class="mb-3 text-navy font-weight-bold"><i class="fas fa-info-circle me-2"></i> Category Profile Specifications</h4>
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0">Category Code</label>
                <span class="arbif-badge arbif-badge-navy mt-1">{{ $productCategory->category_code }}</span>
            </div>
            <div class="col-md-4">
                <label class="form-label d-block text-muted mb-0">Category Display Name</label>
                <span class="fw-bold text-dark d-block mt-1">{{ $productCategory->category_name }}</span>
            </div>
            <div class="col-md-2">
                <label class="form-label d-block text-muted mb-0">Display Order</label>
                <span class="badge bg-secondary mt-1">{{ $productCategory->display_order ?? 0 }}</span>
            </div>
            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0">System Status</label>
                <span class="arbif-badge {{ $productCategory->Status === 'Active' ? 'bg-success' : 'bg-danger' }} text-white mt-1">
                    {{ $productCategory->Status ?? 'Active' }}
                </span>
            </div>

            <div class="col-md-12"><hr class="my-1"></div>

            <div class="col-md-12">
                <label class="form-label d-block text-muted mb-0">Description / Scope Overview</label>
                <p class="text-dark mt-1 mb-0">{{ $productCategory->description ?? 'No detailed description attached.' }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Organizational Scope & Business Line Context -->
<div class="arbif-card mb-4">
    <div class="arbif-card-body">
        <h4 class="mb-3 text-navy font-weight-bold"><i class="fas fa-sitemap me-2"></i> Organizational Matrix Allocation</h4>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label d-block text-muted mb-0">Company Business Line</label>
                <span class="text-dark d-block mt-1 fw-bold">{{ $productCategory->business->code_name ?? $productCategory->business->business_code ?? '—' }}</span>
            </div>
            <div class="col-md-4">
                <label class="form-label d-block text-muted mb-0">Assigned Corporate Body</label>
                <span class="text-dark d-block mt-1 fw-bold">{{ $productCategory->company->company_name ?? '—' }}</span>
            </div>
            <div class="col-md-4">
                <label class="form-label d-block text-muted mb-0">Operational Branch Base</label>
                <span class="text-dark d-block mt-1 fw-bold">{{ $productCategory->branch->branch_name ?? '—' }}</span>
            </div>
        </div>
    </div>
</div>



<!-- System Audit Logs Footer -->
<div class="arbif-card">
    <div class="arbif-card-body py-3">
        <div class="d-flex justify-content-between text-muted" style="font-size: 0.85rem;">
            <div>
                <strong>Record Owner:</strong> {{ $productCategory->user->name ?? '—' }}
            </div>
            <div>
                <strong>Created By:</strong> {{ $productCategory->creator->name ?? '—' }}
                <span class="mx-2">|</span>
                <strong>Last Updated By:</strong> {{ $productCategory->updater->name ?? '—' }}
                @if($productCategory->deleter)
                    <span class="mx-2">|</span>
                    <strong>Deleted By:</strong> {{ $productCategory->deleter->name }}
                @endif
            </div>
        </div>
    </div>
</div>
@endsection