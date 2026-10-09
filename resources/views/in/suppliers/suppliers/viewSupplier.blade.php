@extends('layouts.configside')

@section('title', 'Supplier Details')
@section('page-title', 'Supplier Details')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-truck"></i>
        </div>
        Supplier Profile
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('suppliers') }}" class="btn btn-secondary d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back
        </a>
        <a href="{{ route('editSupplier', encrypt($data->id)) }}" class="arbif-btn-submit text-decoration-none">
            <i class="fas fa-pencil me-1"></i> Edit Supplier
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="arbif-card mb-4">
            <div class="arbif-card-body p-4">
                <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-3">
                    <div>
                        <h4 class="fw-bold text-primary mb-1">{{ $data->SupplierName }}</h4>
                        <span class="arbif-badge arbif-badge-navy">{{ $data->SupplierRefNo }}</span>
                        @if($data->SupplierCode)
                            <span class="badge bg-light text-dark border ms-2">Code: {{ $data->SupplierCode }}</span>
                        @endif
                    </div>
                    <div>
                        @if($data->SupplierType === 'Member')
                            <span class="badge bg-info text-dark p-2"><i class="fas fa-users me-1"></i> Member Supplier</span>
                        @else
                            <span class="badge bg-secondary p-2"><i class="fas fa-building me-1"></i> External Supplier</span>
                        @endif
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <strong>Category:</strong>
                        <p class="text-muted mb-2">{{ $data->category->CategoryName ?? 'N/A' }} ({{ $data->category->SupplierGroup ?? 'N/A' }})</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Branch:</strong>
                        <p class="text-muted mb-2">{{ $data->branch->branch_name ?? 'Global / Central' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Contact Person:</strong>
                        <p class="text-muted mb-2">{{ $data->ContactPerson ?? 'N/A' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Phone / Alt Phone:</strong>
                        <p class="text-muted mb-2">{{ $data->Phone ?? 'N/A' }} {{ $data->AlternativePhone ? '/ ' . $data->AlternativePhone : '' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Email Address:</strong>
                        <p class="text-muted mb-2">{{ $data->Email ?? 'N/A' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Location / Region:</strong>
                        <p class="text-muted mb-2">{{ $data->City ?? 'N/A' }}, {{ $data->Region ?? '' }} ({{ $data->Country ?? 'Tanzania' }})</p>
                    </div>

                    <div class="col-12">
                        <strong>Physical Address:</strong>
                        <p class="text-muted mb-0">{{ $data->Address ?? 'No physical address recorded.' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="arbif-card">
            <div class="arbif-card-body p-4">
                <h5 class="fw-bold mb-3 border-bottom pb-2"><i class="fas fa-file-invoice-dollar me-2 text-success"></i>Financial & Tax Information</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <strong>TIN Number:</strong>
                        <p class="text-muted mb-2">{{ $data->TinNumber ?? 'N/A' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>VAT Number:</strong>
                        <p class="text-muted mb-2">{{ $data->VatNumber ?? 'N/A' }}</p>
                    </div>

                    <div class="col-md-4">
                        <strong>Credit Limit:</strong>
                        <p class="fw-bold text-dark mb-2">TZS {{ number_format($data->CreditLimit, 2) }}</p>
                    </div>

                    <div class="col-md-4">
                        <strong>Opening Balance:</strong>
                        <p class="fw-bold text-dark mb-2">TZS {{ number_format($data->OpeningBalance, 2) }}</p>
                    </div>

                    <div class="col-md-4">
                        <strong>Payment Terms:</strong>
                        <p class="text-muted mb-2">{{ $data->PaymentTerms ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="arbif-card mb-4">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold mb-3 border-bottom pb-2">Account Linkage</h6>

                @if($data->SupplierType === 'Member' && $data->member)
                    <div class="mb-3">
                        <small class="text-muted d-block">Linked Group Member</small>
                        <span class="fw-bold text-dark">{{ $data->member->full_name ?? $data->member->name }}</span>
                        <br><small class="text-primary">Member No: {{ $data->member->member_no ?? 'N/A' }}</small>
                    </div>
                @elseif($data->supplierUser)
                    <div class="mb-3">
                        <small class="text-muted d-block">System Account Username</small>
                        <span class="arbif-badge arbif-badge-navy">{{ $data->supplierUser->username }}</span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Full Name in System</small>
                        <span class="fw-bold text-dark">{{ $data->supplierUser->name }}</span>
                    </div>
                @else
                    <p class="text-muted small">No direct system user mapped.</p>
                @endif
            </div>
        </div>

        <div class="arbif-card">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold mb-3 border-bottom pb-2">System Footprint</h6>

                <div class="mb-3">
                    <small class="text-muted d-block">Record Status</small>
                    <span class="badge bg-success">{{ $data->Status }}</span>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block">Auditing Status</small>
                    <span class="badge bg-warning text-dark">{{ $data->AuditingStatus }}</span>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block">Report Status</small>
                    <span class="badge bg-info text-dark">{{ $data->ReportStatus }}</span>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block">Registered By</small>
                    <span class="fw-bold text-dark">{{ $data->user->name ?? 'N/A' }}</span>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block">Created At</small>
                    <span class="text-muted">{{ $data->created_at ? $data->created_at->format('d M, Y H:i A') : 'N/A' }}</span>
                </div>

                <div>
                    <small class="text-muted d-block">Last Updated</small>
                    <span class="text-muted">{{ $data->updated_at ? $data->updated_at->format('d M, Y H:i A') : 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection