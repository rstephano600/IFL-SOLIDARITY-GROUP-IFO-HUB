@extends('layouts.workingside')

@section('title', 'Supplier Category Details')
@section('page-title', 'Supplier Category Details')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-truck-loading"></i>
        </div>
        Supplier Category Profile
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('supplierCategory') }}" class="btn btn-secondary d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back
        </a>
        <a href="{{ route('editsupplierCategory', encrypt($data->id)) }}" class="arbif-btn-submit text-decoration-none">
            <i class="fas fa-pencil me-1"></i> Edit Category
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="arbif-card">
            <div class="arbif-card-body p-4">
                <h5 class="fw-bold mb-3 text-primary">{{ $data->CategoryName }}</h5>
                <hr>

                <div class="row g-3">
                    <div class="col-md-6">
                        <strong>Category Code:</strong>
                        <p><span class="arbif-badge arbif-badge-navy">{{ $data->CategoryCode }}</span></p>
                    </div>

                    <div class="col-md-6">
                        <strong>Supplier Group:</strong>
                        <p class="text-muted">{{ $data->SupplierGroup }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Company:</strong>
                        <p class="text-muted">{{ $data->company->company_name ?? 'N/A' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Branch:</strong>
                        <p class="text-muted">{{ $data->branch->branch_name ?? 'Global / Central' }}</p>
                    </div>

                    <div class="col-12">
                        <strong>Description:</strong>
                        <p class="text-muted">{{ $data->Description ?? 'No description provided.' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="arbif-card">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold mb-3">Audit & System Info</h6>
                <hr>

                <div class="mb-3">
                    <small class="text-muted d-block">Status</small>
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