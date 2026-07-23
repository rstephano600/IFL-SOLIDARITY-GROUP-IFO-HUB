@extends('layouts.configside')
@section('title', 'Product Details')
@section('page-title', 'Product Overview')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-box"></i></div>
        {{ $product->product_name }} 
        <small class="text-muted">({{ $product->product_code }})</small>
    </h3>
    <a href="{{ url()->previous() }}" class="arbif-btn-submit text-decoration-none">
        <i class="fas fa-arrow-left me-1"></i> Back to Products
    </a>
</div>

<!-- Primary Identity Panel -->
<div class="arbif-card mb-4">
    <div class="arbif-card-body">
        <h4 class="mb-3 text-navy font-weight-bold"><i class="fas fa-info-circle me-2"></i> Primary Product Specifications</h4>
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0">Product Code</label>
                <span class="arbif-badge arbif-badge-navy mt-1">{{ $product->product_code }}</span>
            </div>
            <div class="col-md-4">
                <label class="form-label d-block text-muted mb-0">Product Display Name</label>
                <span class="fw-bold text-dark d-block mt-1">{{ $product->product_name }}</span>
            </div>
            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0">Category</label>
                <span class="text-dark fw-bold d-block mt-1">{{ $product->category->category_name ?? '—' }}</span>
            </div>
            <div class="col-md-2">
                <label class="form-label d-block text-muted mb-0">Status</label>
                <span class="arbif-badge {{ $product->Status === 'Active' ? 'bg-success' : 'bg-danger' }} text-white mt-1">
                    {{ $product->Status ?? 'Active' }}
                </span>
            </div>

            <div class="col-md-12"><hr class="my-1"></div>

            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-0">Unit of Measure</label>
                <span class="text-dark d-block mt-1 fw-bold">{{ $product->unit->UnitName ?? '—' }}</span>
            </div>
            <div class="col-md-9">
                <label class="form-label d-block text-muted mb-0">Description & Specification Notes</label>
                <p class="text-dark mt-1 mb-0">{{ $product->description ?? 'No detailed description provided.' }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Financial & Pricing Panel -->
<div class="arbif-card mb-4">
    <div class="arbif-card-body">
        <h4 class="mb-3 text-navy font-weight-bold"><i class="fas fa-tag me-2"></i> Financial & Pricing Matrix</h4>
        <div class="row g-3">
            <div class="col-md-2-4 col-sm-6">
                <label class="form-label d-block text-muted mb-0">Cost Price</label>
                <span class="fs-5 fw-bold text-dark mt-1 d-block">{{ number_format($product->cost_price, 2) }}</span>
            </div>
            <div class="col-md-2-4 col-sm-6">
                <label class="form-label d-block text-muted mb-0">Selling Price</label>
                <span class="fs-5 fw-bold text-navy mt-1 d-block">{{ number_format($product->selling_price, 2) }}</span>
            </div>
            <div class="col-md-2-4 col-sm-6">
                <label class="form-label d-block text-muted mb-0">Minimum Price</label>
                <span class="text-dark mt-1 d-block font-monospace">{{ number_format($product->minimum_price, 2) }}</span>
            </div>
            <div class="col-md-2-4 col-sm-6">
                <label class="form-label d-block text-muted mb-0">Maximum Price</label>
                <span class="text-dark mt-1 d-block font-monospace">{{ number_format($product->maximum_price, 2) }}</span>
            </div>
            <div class="col-md-2-4 col-sm-6">
                <label class="form-label d-block text-muted mb-0">Applicable Tax Rate</label>
                <span class="badge bg-secondary mt-1 fs-6">{{ number_format($product->tax_rate, 2) }}%</span>
            </div>
        </div>
    </div>
</div>

<!-- GL Accounts Mapping -->
<div class="arbif-card mb-4">
    <div class="arbif-card-body">
        <h4 class="mb-3 text-navy font-weight-bold"><i class="fas fa-calculator me-2"></i> General Ledger Accounting Mappings</h4>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label d-block text-muted mb-0">Income GL Account</label>
                <span class="text-dark fw-bold d-block mt-1">{{ $product->incomeAccount->ThirdAccountName ?? '—' }}</span>
                <small class="text-muted">{{ $product->incomeAccount->ThirdAccountCode ?? '' }}</small>
            </div>
            <div class="col-md-4">
                <label class="form-label d-block text-muted mb-0">Expense GL Account</label>
                <span class="text-dark fw-bold d-block mt-1">{{ $product->expenseAccount->ThirdAccountName ?? '—' }}</span>
                <small class="text-muted">{{ $product->expenseAccount->ThirdAccountCode ?? '' }}</small>
            </div>
            <div class="col-md-4">
                <label class="form-label d-block text-muted mb-0">Inventory GL Account</label>
                <span class="text-dark fw-bold d-block mt-1">{{ $product->inventoryAccount->ThirdAccountName ?? '—' }}</span>
                <small class="text-muted">{{ $product->inventoryAccount->ThirdAccountCode ?? '' }}</small>
            </div>
        </div>
    </div>
</div>

<!-- Operational Configuration Flags & Scope -->
<div class="arbif-card mb-4">
    <div class="arbif-card-body">
        <h4 class="mb-3 text-navy font-weight-bold"><i class="fas fa-sliders-h me-2"></i> Scope & System Flags</h4>
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-1">Tracked Stock Item</label>
                @if($product->is_stock_item)
                    <span class="badge bg-success"><i class="fas fa-check me-1"></i> Yes</span>
                @else
                    <span class="badge bg-secondary"><i class="fas fa-times me-1"></i> No</span>
                @endif
            </div>
            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-1">Allow Discount</label>
                @if($product->allow_discount)
                    <span class="badge bg-success"><i class="fas fa-check me-1"></i> Allowed</span>
                @else
                    <span class="badge bg-danger"><i class="fas fa-times me-1"></i> Disabled</span>
                @endif
            </div>
            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-1">Requires Member</label>
                @if($product->requires_member)
                    <span class="badge bg-warning text-dark"><i class="fas fa-user-check me-1"></i> Required</span>
                @else
                    <span class="badge bg-secondary"><i class="fas fa-times me-1"></i> Optional</span>
                @endif
            </div>
            <div class="col-md-3">
                <label class="form-label d-block text-muted mb-1">Requires Approval</label>
                @if($product->requires_approval)
                    <span class="badge bg-warning text-dark"><i class="fas fa-user-shield me-1"></i> Required</span>
                @else
                    <span class="badge bg-secondary"><i class="fas fa-times me-1"></i> Auto Approved</span>
                @endif
            </div>

            <div class="col-md-12"><hr class="my-1"></div>

            <div class="col-md-4">
                <label class="form-label d-block text-muted mb-0">Business Line</label>
                <span class="text-dark mt-1 d-block fw-bold">({{ $product->business->business_code }} ){{ $product->business->business_name }}</span>
            </div>
            <div class="col-md-4">
                <label class="form-label d-block text-muted mb-0">Company Entity</label>
                <span class="text-dark mt-1 d-block fw-bold">{{ $product->company->company_name ?? '—' }}</span>
            </div>
            <div class="col-md-4">
                <label class="form-label d-block text-muted mb-0">Branch Office</label>
                <span class="text-dark mt-1 d-block fw-bold">{{ $product->branch->branch_name ?? '—' }}</span>
            </div>
        </div>
    </div>
</div>

<!-- System Audit Logs Footer -->
<div class="arbif-card">
    <div class="arbif-card-body py-3">
        <div class="d-flex justify-content-between text-muted" style="font-size: 0.85rem;">
            <div>
                <strong>Record Owner:</strong> {{ $product->user->name ?? '—' }}
            </div>
            <div>
                <strong>Created By:</strong> {{ $product->creator->name ?? '—' }}
                <span class="mx-2">|</span>
                <strong>Last Updated By:</strong> {{ $product->updater->name ?? '—' }}
                @if($product->deleter)
                    <span class="mx-2">|</span>
                    <strong>Deleted By:</strong> {{ $product->deleter->name }}
                @endif
            </div>
        </div>
    </div>
</div>
@endsection