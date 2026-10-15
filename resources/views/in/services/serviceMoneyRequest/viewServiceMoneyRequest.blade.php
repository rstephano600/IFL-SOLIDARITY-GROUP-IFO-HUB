@extends('layouts.workingside')

@section('title', 'View Money Request - ' . $data->money_request_no)
@section('page-title', 'Money Request Details')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-file-invoice-dollar"></i>
        </div>
        Money Request: {{ $data->money_request_no }}
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('serviceMoneyRequest') }}" class="btn btn-secondary px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to List
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-8">
        <div class="arbif-card">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">
                    <i class="fas fa-circle-info me-1"></i> General Details
                </h6>

                <div class="row g-3 small">
                    <div class="col-md-6">
                        <strong>Reference No:</strong> {{ $data->ReferenceNo }} <br>
                        <strong>Money Request No:</strong> {{ $data->money_request_no }} <br>
                        <strong>Date Prepared:</strong> {{ $data->money_request_date ? $data->money_request_date->format('d M, Y') : 'N/A' }} <br>
                        <strong>Prepared By:</strong> {{ $data->preparedBy->name ?? 'N/A' }} <br>
                    </div>
                    <div class="col-md-6">
                        <strong>Payee Name:</strong> <span class="fw-bold text-dark">{{ $data->payee_name }}</span> <br>
                        <strong>Branch:</strong> {{ $data->branch->branch_name ?? 'N/A' }} <br>
                        <strong>Company:</strong> {{ $data->company->company_name ?? 'IFL SOLIDARITY GROUP' }}[cite: 1] <br>
                        <strong>Parent SR Ref:</strong> {{ $data->serviceRequest->request_no ?? 'N/A' }} <br>
                    </div>

                    <div class="col-12 mt-3">
                        <div class="p-3 bg-light border rounded">
                            <strong>Payment Purpose:</strong> <br>
                            <p class="mb-0 text-muted">{{ $data->payment_purpose }}</p>
                        </div>
                    </div>

                    @if($data->cashier_comment)
                        <div class="col-12">
                            <div class="p-2 border rounded bg-white">
                                <strong>Cashier Remarks:</strong> {{ $data->cashier_comment }}
                            </div>
                        </div>
                    @endif
                </div>

                {{-- FINANCIAL SUMMARY BOX --}}
                <h6 class="fw-bold text-primary mt-4 mb-3 border-bottom pb-2">
                    <i class="fas fa-calculator me-1"></i> Financial Breakdown
                </h6>

                <div class="row text-center g-3">
                    <div class="col-md-4">
                        <div class="p-3 border rounded bg-light">
                            <small class="text-muted d-block text-uppercase">Amount Requested</small>
                            <span class="fs-5 fw-bold text-primary">{{ number_format($data->amount_requested, 2) }} {{ $data->currency_code }}</span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="p-3 border rounded bg-light">
                            <small class="text-muted d-block text-uppercase">Amount Approved</small>
                            <span class="fs-5 fw-bold text-success">{{ number_format($data->amount_approved, 2) }} {{ $data->currency_code }}</span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="p-3 border rounded bg-light">
                            <small class="text-muted d-block text-uppercase">Amount Processed</small>
                            <span class="fs-5 fw-bold text-dark">{{ number_format($data->amount_processed, 2) }} {{ $data->currency_code }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- WORKFLOW APPROVALS & DISBURSEMENT SIDEBAR --}}
    <div class="col-md-4">
        {{-- 1. FINANCE CHAIRMAN APPROVAL CARD --}}
        <div class="arbif-card mb-3">
            <div class="arbif-card-body p-3">
                <h6 class="fw-bold text-primary mb-2"><i class="fas fa-user-check me-1"></i> 1. Finance Chairman</h6>
                <p class="small mb-1">Status: 
                    <span class="badge {{ $data->finance_chairman_approval_status == 'Approved' ? 'bg-success' : ($data->finance_chairman_approval_status == 'Rejected' ? 'bg-danger' : 'bg-warning text-dark') }}">
                        {{ $data->finance_chairman_approval_status }}
                    </span>
                </p>

                @if($data->finance_chairman_approval_status == 'Approved')
                    <p class="small text-muted mb-0">By: {{ $data->financeChairmanApprover->name ?? 'N/A' }} on {{ $data->finance_chairman_approved_at ? $data->finance_chairman_approved_at->format('d M, Y H:i') : '' }}</p>
                @elseif($data->finance_chairman_approval_status == 'Pending')
                    <form method="POST" action="{{ route('financeChairmanApproval', encrypt($data->id)) }}" class="mt-3">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label small">Approved Amount ({{ $data->currency_code }})</label>
                            <input type="number" step="0.01" name="amount_approved" class="form-control form-control-sm" value="{{ $data->amount_requested }}" min="0" required>
                        </div>
                        <div class="mb-2">
                            <textarea name="comment" class="form-control form-control-sm" placeholder="Approval remark..."></textarea>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" name="status" value="Approved" class="btn btn-sm btn-success w-100">Approve</button>
                            <button type="submit" name="status" value="Rejected" class="btn btn-sm btn-danger w-100">Reject</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>

        {{-- 2. GROUP CHAIRMAN APPROVAL CARD --}}
        <div class="arbif-card mb-3">
            <div class="arbif-card-body p-3">
                <h6 class="fw-bold text-primary mb-2"><i class="fas fa-crown me-1"></i> 2. Group Chairman (Final)</h6>
                <p class="small mb-1">Status: 
                    <span class="badge {{ $data->group_chairman_approval_status == 'Approved' ? 'bg-success' : ($data->group_chairman_approval_status == 'Rejected' ? 'bg-danger' : 'bg-warning text-dark') }}">
                        {{ $data->group_chairman_approval_status }}
                    </span>
                </p>

                @if($data->group_chairman_approval_status == 'Approved')
                    <p class="small text-muted mb-0">By: {{ $data->groupChairmanApprover->name ?? 'N/A' }} on {{ $data->group_chairman_approved_at ? $data->group_chairman_approved_at->format('d M, Y H:i') : '' }}</p>
                @elseif($data->finance_chairman_approval_status == 'Approved' && $data->group_chairman_approval_status == 'Pending')
                    <form method="POST" action="{{ route('groupChairmanMoneyApproval', encrypt($data->id)) }}" class="mt-3">
                        @csrf
                        <div class="mb-2">
                            <textarea name="comment" class="form-control form-control-sm" placeholder="Final approval remark..."></textarea>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" name="status" value="Approved" class="btn btn-sm btn-success w-100">Approve</button>
                            <button type="submit" name="status" value="Rejected" class="btn btn-sm btn-danger w-100">Reject</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>

        {{-- 3. PAYMENT DISBURSEMENT CARD --}}
        <div class="arbif-card">
            <div class="arbif-card-body p-3">
                <h6 class="fw-bold text-primary mb-2"><i class="fas fa-money-bill-transfer me-1"></i> 3. Payment Disbursement</h6>
                <p class="small mb-1">Status: <span class="badge bg-secondary">{{ $data->money_status }}</span></p>

                @if($data->money_status == 'Paid')
                    <p class="small text-muted mb-0">Processed by {{ $data->processedBy->name ?? 'N/A' }} on {{ $data->processed_at ? $data->processed_at->format('d M, Y H:i') : '' }}</p>
                    <p class="small mb-0">Method: <strong>{{ $data->payment_method }}</strong> | Ref: <strong>{{ $data->payment_reference ?? 'N/A' }}</strong></p>
                @elseif($data->group_chairman_approval_status == 'Approved' && $data->money_status != 'Paid')
                    <form method="POST" action="{{ route('processPayment', encrypt($data->id)) }}" class="mt-3">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label small">Disbursed Amount ({{ $data->currency_code }})</label>
                            <input type="number" step="0.01" name="amount_processed" class="form-control form-control-sm" value="{{ $data->amount_approved }}" min="0.01" required>
                        </div>
                        <div class="mb-2">
                            <select name="payment_method" class="form-select form-select-sm" required>
                                <option value="Cash">Cash</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="Cheque">Cheque</option>
                                <option value="Mobile Money">Mobile Money</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <input type="text" name="payment_reference" class="form-control form-control-sm" placeholder="Ref/Transaction ID (Optional)">
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary w-100">Disburse Funds</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection