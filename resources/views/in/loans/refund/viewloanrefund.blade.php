@extends('layouts.workingside')

@section('title', 'View Loan Refund Details')

@section('page-title', 'View Loan Refund Details')

@section('content')

<div class="arbif-page-header d-flex justify-content-between align-items-center mb-4">
    <h3>
        <div class="page-icon">
            <i class="fas fa-search-dollar"></i>
        </div>
        Refund Details: {{ $data->refund_number }}
    </h3>
    
    <div>
        <a href="{{ route('pendingloanrefund') }}" class="btn btn-secondary btn-sm me-2">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>

        @if($data->ApprovalStatus == 'Pending')
            {{-- Approve Form Action --}}
            <form action="{{ route('approveloanrefund', encrypt($data->id)) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to APPROVE this loan refund?');">
                @csrf
                <button type="submit" class="btn btn-success btn-sm me-2">
                    <i class="fas fa-check-circle"></i> Approve Refund
                </button>
            </form>

            {{-- Reject Form Action --}}
            <form action="{{ route('rejectloanrefund', encrypt($data->id)) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to REJECT this loan refund?');">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm">
                    <i class="fas fa-times-circle"></i> Reject Refund
                </button>
            </form>
        @endif
    </div>
</div>

{{-- 1. META CONTEXT GRID --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="arbif-card p-3 h-100">
            <small class="text-muted uppercase font-weight-bold d-block mb-1">Client Profile</small>
            <h5>{{ optional($data->client)->name ?? 'N/A' }}</h5>
            <span class="badge bg-light text-dark">ID: #{{ $data->client_id }}</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="arbif-card p-3 h-100">
            <small class="text-muted uppercase font-weight-bold d-block mb-1">Associated Loan</small>
            <h5>{{ optional($data->loan)->loan_number ?? 'N/A' }}</h5>
            <span class="badge bg-primary">Amt: {{ number_format(optional($data->loan)->amount_requested ?? 0, 2) }}</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="arbif-card p-3 h-100">
            <small class="text-muted uppercase font-weight-bold d-block mb-1">Group & Center</small>
            <p class="mb-0"><strong>G:</strong> {{ optional($data->group)->group_name ?? 'N/A' }}</p>
            <p class="mb-0"><strong>C:</strong> {{ optional($data->groupCenter)->center_name ?? 'N/A' }}</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="arbif-card p-3 h-100">
            <small class="text-muted uppercase font-weight-bold d-block mb-1">Current State</small>
            <div>
                @if($data->ApprovalStatus == 'Pending')
                    <span class="arbif-badge arbif-badge-warning d-inline-block px-3 py-2">Pending Review</span>
                @elseif($data->ApprovalStatus == 'Approved')
                    <span class="arbif-badge arbif-badge-success d-inline-block px-3 py-2">Approved</span>
                @else
                    <span class="arbif-badge arbif-badge-danger d-inline-block px-3 py-2">{{ $data->ApprovalStatus }}</span>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- 2. DETAILED BREAKDOWNS --}}
<div class="row g-4">
    {{-- Operational Matrix --}}
    <div class="col-md-6">
        <div class="arbif-card">
            <div class="arbif-card-body">
                <h5 class="arbif-section-title mb-3">Operational Matrix</h5>
                <table class="table table-bordered table-striped custom-view-table">
                    <tbody>
                        <tr>
                            <th width="40%">Refund Document ID</th>
                            <td>{{ $data->refund_number }}</td>
                        </tr>
                        <tr>
                            <th>Initialization Date</th>
                            <td>{{ $data->refund_date ? $data->refund_date->format('d M Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Requested Allocation</th>
                            <td>{{ number_format($data->requested_refund, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Approved Allocation</th>
                            <td>{{ number_format($data->approved_refund, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Disbursed Reimbursement</th>
                            <td>{{ number_format($data->refunded_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Primary Reason</th>
                            <td><p class="mb-0 text-wrap">{{ $data->refund_reason ?? 'No primary reason provided.' }}</p></td>
                        </tr>
                        <tr>
                            <th>Internal Ledger Remarks</th>
                            <td><p class="mb-0 text-wrap text-muted"><em>{{ $data->remarks ?? 'No remarks appended.' }}</em></p></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Fee Breakdown --}}
    <div class="col-md-6">
        <div class="arbif-card">
            <div class="arbif-card-body">
                <h5 class="arbif-section-title mb-3">Reimbursable Components</h5>
                <table class="table table-bordered table-striped custom-view-table">
                    <tbody>
                        <tr>
                            <th width="50%">Membership Fee Component</th>
                            <td class="text-end">{{ number_format($data->membership_fee_refund, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Insurance Fee Component</th>
                            <td class="text-end">{{ number_format($data->insurance_fee_refund, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Officer Site-Visit Component</th>
                            <td class="text-end">{{ number_format($data->officer_visit_fee_refund, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Penalty Fee Mitigation</th>
                            <td class="text-end">{{ number_format($data->penalty_fee_refund, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Preclosure Liquidation Component</th>
                            <td class="text-end">{{ number_format($data->preclosure_fee_refund, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Miscellaneous Surcharges</th>
                            <td class="text-end">{{ number_format($data->other_fee_refund, 2) }}</td>
                        </tr>
                        <tr class="table-dark">
                            <th>Gross Evaluated Balance</th>
                            <td class="text-end font-weight-bold">
                                <strong>{{ number_format($data->total_refund, 2) }}</strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <div class="alert alert-secondary mt-3 mb-0 py-2 px-3" style="font-size: 0.9rem;">
                    <i class="fas fa-info-circle me-1"></i> <strong>Audit Workflow Status:</strong> Audit: <code>{{ $data->AuditingStatus }}</code> | Report: <code>{{ $data->ReportStatus }}</code>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection