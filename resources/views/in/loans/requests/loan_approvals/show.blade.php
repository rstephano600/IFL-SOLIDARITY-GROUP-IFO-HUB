@extends('layouts.app')

@section('title', 'Review Loan for Approval')
@section('page-title', 'Review Loan for Approval')

@section('content')
<div class="container-fluid py-4">

    {{-- ================================================================= --}}
    {{-- HEADER & LOAN STATUS --}}
    {{-- ================================================================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <h2 class="h3 mb-0 text-dark">
            <i class="bi bi-file-text me-2 text-info"></i> Loan Review: <span class="text-primary">{{ $loan->loan_number }}</span>
        </h2>
        <span class="badge bg-warning text-dark fs-6 fw-bold shadow-sm">
            <i class="bi bi-hourglass-split me-1"></i> Status: {{ ucfirst($loan->status) }}
        </span>
    </div>

    <div class="row g-4">
        
        {{-- ================================================================= --}}
        {{-- COLUMN 1: CLIENT AND LOAN DETAILS --}}
        {{-- ================================================================= --}}
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-primary text-white fw-bold">
                    <i class="bi bi-person-lines-fill me-2"></i> Client & Loan Overview
                </div>
                <div class="card-body">
                    <dl class="row mb-0 loan-details-dl">
                        
                        <dt class="col-sm-5 fw-bold text-dark">Client Name:</dt>
                        <dd class="col-sm-7">{{ $loan->client?->first_name }} {{ $loan->client?->last_name }}</dd>

                        <dt class="col-sm-5 fw-bold text-dark">Loan Category:</dt>
                        <dd class="col-sm-7">{{ $loan->loanCategory?->name ?? 'N/A' }}</dd>

                        <dt class="col-sm-5 fw-bold text-dark">Requested On:</dt>
                        <dd class="col-sm-7">{{ $loan->created_at->format('Y-m-d H:i') }}</dd>

                        <dt class="col-sm-5 fw-bold text-dark">Term / Duration:</dt>
                        <dd class="col-sm-7">{{ $loan->loanCategory?->duration ?? 'N/A' }} {{ $loan->loanCategory?->duration_unit ?? 'months' }}</dd>

                        <dt class="col-sm-5 fw-bold text-dark">Interest Rate:</dt>
                        <dd class="col-sm-7">{{ $loan->loanCategory?->interest_rate ?? '0.00' }}%</dd>

                        <dt class="col-sm-5 fw-bold text-dark border-top mt-2 pt-2">Total Amount Paid (Historical)</dt>
                        <dd class="col-sm-7 border-top mt-2 pt-2 fw-bold text-success">
                            {{ number_format($loan->amount_paid, 2) }} {{ $loan->loanCategory?->currency ?? 'TZS' }}
                        </dd>
                    </dl>
                </div>
            </div>
        </div>

        {{-- ================================================================= --}}
        {{-- COLUMN 2: FINANCIAL SUMMARY & ACTION --}}
        {{-- ================================================================= --}}
        <div class="col-lg-5">
            <div class="card shadow-lg border-0 bg-light-subtle h-100">
                <div class="card-header bg-light fw-bold text-danger">
                    <i class="bi bi-cash-stack me-2"></i> Financial Summary (Approval View)
                </div>
                <div class="card-body">
                    <dl class="row mb-0 summary-dl">
                        
                        {{-- Requested Amount --}}
                        <dt class="col-sm-6 text-dark">Amount Requested:</dt>
                        <dd class="col-sm-6 fs-5 fw-bold text-primary">
                            {{ number_format($loan->amount_requested, 2) }} {{ $loan->loanCategory?->currency ?? 'TZS' }}
                        </dd>

                        <div class="col-12"><hr class="my-2"></div>
                        
                        <!-- {{-- Fees Section --}}
                        <dt class="col-sm-6 text-dark small">(-) Membership Fee:</dt>
                        <dd class="col-sm-6 small text-danger fw-semibold">
                            ({{ number_format($loan->membership_fee , 2) }}) {{ $loan->loanCategory?->currency ?? 'TZS' }}
                        </dd>
                        
                        <dt class="col-sm-6 text-dark small">(-) Insurance Fee:</dt>
                        <dd class="col-sm-6 small text-danger fw-semibold">
                            ({{ number_format($loan->loanCategory?->insurance_fee, 2) }}) {{ $loan->loanCategory?->currency ?? 'TZS' }}
                        </dd>

                        <dt class="col-sm-6 text-dark small">(-) Officer Visit Fee:</dt>
                        <dd class="col-sm-6 small text-danger fw-semibold">
                            ({{ number_format($loan->loanCategory?->officer_visit_fee, 2) }}) {{ $loan->loanCategory?->currency ?? 'TZS' }}
                        </dd> -->
                        
                        <!-- <div class="col-12"><hr class="my-2"></div> -->

                        {{-- Net Disbursement --}}
                        <dt class="col-sm-6 text-dark fs-5">Net Amount to Disburse:</dt>
                        <dd class="col-sm-6 fs-4 fw-bold text-success">
                            {{-- Calculate the net amount for display --}}
                            @php
                                $netDisbursement = $loan->loanCategory?->amount_disbursed;
                            @endphp
                            {{ number_format($netDisbursement, 2) }} {{ $loan->loanCategory?->currency ?? 'TZS' }}
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    {{-- ================================================================= --}}
    {{-- APPROVAL/REJECTION ACTIONS --}}
    {{-- ================================================================= --}}
    <div class="card shadow mt-4">
        <div class="card-body d-flex justify-content-between align-items-center">
            
            <a href="{{ route('loan-approvals.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Queue
            </a>

            <div class="d-flex gap-3">
                
                {{-- REJECT BUTTON (Opens a Modal for Reason) --}}
                <button type="button" class="btn btn-danger btn-lg shadow-sm" data-bs-toggle="modal" data-bs-target="#rejectModal">
                    <i class="bi bi-x-octagon-fill me-2"></i> Reject Loan
                </button>

                {{-- APPROVE BUTTON (Confirmation added via onclick) --}}
                <form action="{{ route('loan-approvals.approve', $loan->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success btn-lg shadow-sm" 
                            onclick="return confirm('Are you sure you want to approve Loan #{{ $loan->loan_number }}? This action is final.');">
                        <i class="bi bi-check-circle-fill me-2"></i> Approve Loan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ================================================================= --}}
{{-- REJECTION MODAL --}}
{{-- ================================================================= --}}
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('loan-approvals.reject', $loan->id) }}" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="rejectModalLabel">Confirm Rejection</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Please provide a reason for rejecting **Loan #{{ $loan->loan_number }}**.</p>
                    <div class="mb-3">
                        <label for="reject_reason" class="form-label">Rejection Reason</label>
                        <textarea class="form-control" id="reject_reason" name="rejection_reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Permanently</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Custom styling for the data list to improve visual clarity */
.loan-details-dl dd {
    margin-bottom: 0.5rem;
}
.summary-dl dd {
    text-align: right;
}
.summary-dl .fs-4 {
    border-top: 2px dashed #a3d9a5; /* Light green dashed line for separation */
    padding-top: 5px;
    margin-top: 5px;
}
</style>
@endsection