@extends('layouts.configside')

@section('title', 'Procurement Type Details')
@section('page-title', 'Procurement Type Profile')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-boxes-packing"></i>
        </div>
        Procurement Type Profile
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('procurementType') }}" class="btn btn-secondary d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back
        </a>
        <a href="{{ route('editprocurementType', encrypt($data->id)) }}" class="arbif-btn-submit text-decoration-none">
            <i class="fas fa-pencil me-1"></i> Edit Type
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="arbif-card mb-4">
            <div class="arbif-card-body p-4">
                <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-3">
                    <div>
                        <h4 class="fw-bold text-primary mb-1">{{ $data->procurement_name }}</h4>
                        <span class="arbif-badge arbif-badge-navy">{{ $data->procurement_code }}</span>
                    </div>
                    <div>
                        <span class="badge bg-light text-dark border">Display Order: {{ $data->display_order }}</span>
                    </div>
                </div>

                <div class="mb-3">
                    <strong>Branch:</strong>
                    <p class="text-muted">{{ $data->branch->branch_name ?? 'Global / Central' }}</p>
                </div>

                <div class="mb-3">
                    <strong>Description:</strong>
                    <p class="text-muted">{{ $data->description ?? 'No description provided.' }}</p>
                </div>
            </div>
        </div>

        <div class="arbif-card mb-4">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold mb-3 border-bottom pb-2"><i class="fas fa-list-check me-2 text-primary"></i>Configured Workflow Steps</h6>

                <div class="row g-3">
                    @php
                        $steps = [
                            'Requisition / Request' => $data->requires_request,
                            'Supplier Quotations' => $data->requires_quotation,
                            'Quotation Evaluation' => $data->requires_quotation_evaluation,
                            'Purchase Order (PO)' => $data->requires_purchase_order,
                            'Goods/Service Receiving' => $data->requires_receiving,
                            'Quality Inspection' => $data->requires_inspection,
                            'Supplier Invoice' => $data->requires_invoice,
                            'Payment Approval' => $data->requires_payment_approval,
                        ];
                    @endphp

                    @foreach($steps as $name => $active)
                        <div class="col-md-6">
                            <div class="p-2 border rounded d-flex justify-content-between align-items-center">
                                <span>{{ $name }}</span>
                                @if($active)
                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i> Required</span>
                                @else
                                    <span class="badge bg-secondary">Optional / Disabled</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="arbif-card">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold mb-3 border-bottom pb-2"><i class="fas fa-chart-pie me-2 text-success"></i>Impact & Governance</h6>

                <div class="row g-3">
                    <div class="col-md-4">
                        <small class="text-muted d-block">Stock Impact</small>
                        @if($data->creates_stock) <span class="badge bg-success">Creates Inventory</span> @else <span class="badge bg-light text-muted border">No Stock Impact</span> @endif
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Expense Impact</small>
                        @if($data->creates_expense) <span class="badge bg-warning text-dark">Direct Expense</span> @else <span class="badge bg-light text-muted border">No Expense</span> @endif
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Asset Impact</small>
                        @if($data->creates_asset) <span class="badge bg-info text-dark">Capital Asset</span> @else <span class="badge bg-light text-muted border">No Asset</span> @endif
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Budget Requirement</small>
                        @if($data->requires_budget) <span class="badge bg-primary">Budget Check Enforced</span> @else <span class="badge bg-secondary">Bypasses Budget</span> @endif
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Approval Requirement</small>
                        @if($data->requires_approval) <span class="badge bg-primary">Management Approval Required</span> @else <span class="badge bg-secondary">Auto Approval</span> @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="arbif-card">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold mb-3 border-bottom pb-2">Audit Information</h6>

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
                    <small class="text-muted d-block">Created By</small>
                    <span class="fw-bold text-dark">{{ $data->creator->name ?? ($data->user->name ?? 'N/A') }}</span>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block">Created At</small>
                    <span class="text-muted">{{ $data->created_at ? $data->created_at->format('d M, Y H:i A') : 'N/A' }}</span>
                </div>

                <div>
                    <small class="text-muted d-block">Last Updated By</small>
                    <span class="fw-bold text-dark">{{ $data->updater->name ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection