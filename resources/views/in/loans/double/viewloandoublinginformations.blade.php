@extends('layouts.workingside')

@section('title', 'Loan Doubling Detailed Ledger')

@section('page-title', 'Loan Doubling Detailed Ledger')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-file-invoice-dollar"></i>
        </div>
        Loan Doubling Details — Reference #{{ $loanNumber ?? 'N/A' }}
    </h3>

    <a href="{{ url()->previous() }}" class="arbif-btn-cancel text-decoration-none">
        <i class="fas fa-arrow-left"></i> Back to List
    </a>
</div>

{{-- METRIC SUMMARY CARDS --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 bg-light p-3">
            <small class="text-muted text-uppercase fw-bold d-block mb-1">New Requested Capital</small>
            <h4 class="text-primary mb-0">{{ number_format($loan->requested_amount, 2) }} Tsh</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 bg-light p-3">
            <small class="text-muted text-uppercase fw-bold d-block mb-1">Old Balance Deducted</small>
            <h4 class="text-danger mb-0">{{ number_format($loan->total_outstanding, 2) }} Tsh</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 bg-light p-3">
            <small class="text-muted text-uppercase fw-bold d-block mb-1">Top-up Office Charge</small>
            <h4 class="text-warning mb-0">{{ number_format($loan->topup_fee, 2) }} Tsh</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 bg-light p-3 border-start border-success border-3">
            <small class="text-muted text-uppercase fw-bold d-block mb-1">Net Physical Payout</small>
            <h4 class="text-success mb-0 fw-bold">{{ number_format($loan->net_disbursed, 2) }} Tsh</h4>
        </div>
    </div>
</div>

<div class="row g-4">
    
    {{-- LEFT SIDEBAR: PROFILE & CORE INFO --}}
    <div class="col-md-4">
        <div class="arbif-card h-100">
            <div class="arbif-card-body">
                <h5 class="arbif-section-title mb-3"><i class="bi bi-person-badge-fill me-2"></i>Affiliation Profile</h5>
                
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Client Full Name</span>
                        <strong class="text-end">{{ $loan->client->name ?? 'N/A' }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Center Unit</span>
                        <span class="text-end fw-bold">{{ $loan->groupCenter->name ?? 'N/A' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Assigned Group</span>
                        <span class="text-end">{{ $loan->group->name ?? 'N/A' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Execution Date</span>
                        <span class="text-end text-dark font-monospace">{{ $loan->topup_date ? \Carbon\Carbon::parse($loan->topup_date)->format('Y-m-d') : 'N/A' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Workflow Tracking</span>
                        <div>
                            @if($loan->ApprovalStatus == 'Approved')
                                <span class="badge bg-success">Approved</span>
                            @elseif($loan->ApprovalStatus == 'Pending')
                                <span class="badge bg-warning text-dark">Pending Audit</span>
                            @else
                                <span class="badge bg-danger">{{ $loan->ApprovalStatus }}</span>
                            @endif
                        </div>
                    </li>
                </ul>

                <div class="mt-4 p-3 bg-light rounded">
                    <small class="text-muted fw-bold d-block mb-1">Top-up Operation Justification</small>
                    <p class="mb-0 text-secondary small">{{ $loan->topup_reason ?? 'No detailed justification supplied.' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT MAIN BLOCK: DETAILED BREAKDOWN TRACKERS --}}
    <div class="col-md-8">
        <div class="arbif-card h-100">
            <div class="arbif-card-body">
                
                {{-- STEP A: RECOUPED OLD ACCOUNT VALUES --}}
                <h5 class="arbif-section-title text-danger mb-3">
                    <i class="bi bi-arrow-down-left-square me-2"></i>
                    1. Recouped Old Account Liabilities Balance
                </h5>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-striped align-middle small">
                        <thead class="table-dark">
                            <tr>
                                <th>Old Loan Reference Number</th>
                                <th>Principal Bal</th>
                                <th>Interest Bal</th>
                                <th>Penalty Bal</th>
                                <th>Other Fees Bal</th>
                                <th class="table-danger text-dark fw-bold">Sum Cleaned</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-monospace fw-bold text-primary">{{ $loan->oldLoan->loan_number ?? 'N/A' }}</td>
                                <td>{{ number_format($loan->outstanding_principal ?? 0, 2) }}</td>
                                <td>{{ number_format($loan->outstanding_interest ?? 0, 2) }}</td>
                                <td>{{ number_format($loan->outstanding_penalty ?? 0, 2) }}</td>
                                <td>{{ number_format($loan->outstanding_other_fee ?? 0, 2) }}</td>
                                <td class="fw-bold text-danger">{{ number_format($loan->total_outstanding ?? 0, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- STEP B: UPCOMING STRUCTURE CONFIGURATIONS --}}
                <h5 class="arbif-section-title text-success mb-3">
                    <i class="bi bi-arrow-up-right-square me-2"></i>
                    2. Configured Upgraded Capital Assets Allocation
                </h5>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-striped align-middle small">
                        <thead class="table-dark">
                            <tr>
                                <th>Target New Loan Ref</th>
                                <th>Gross Requested</th>
                                <th>Approved Level</th>
                                <th>Gross Disbursed</th>
                                <th class="table-warning text-dark">Deduction Holds</th>
                                <th class="table-success text-dark fw-bold">Actual Net Issued</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-monospace fw-bold text-primary">{{ $loan->newLoan->loan_number ?? 'System Pending' }}</td>
                                <td>{{ number_format($loan->requested_amount ?? 0, 2) }}</td>
                                <td>{{ number_format($loan->approved_amount ?? 0, 2) }}</td>
                                <td>{{ number_format($loan->amount_disbursed ?? 0, 2) }}</td>
                                <td class="text-warning fw-bold">{{ number_format($loan->total_deductions ?? 0, 2) }}</td>
                                <td class="fw-bold text-success">{{ number_format($loan->net_disbursed ?? 0, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- AUDITING SYSTEM CHECKS --}}
                <h5 class="arbif-section-title mb-2"><i class="bi bi-shield-check me-2"></i>3. System Operations Verification Logs</h5>
                <div class="row g-3 bg-light p-3 rounded mx-0">
                    <div class="col-md-4">
                        <small class="text-muted d-block">Created By</small>
                        <span class="fw-bold text-dark">{{ $loan->user->name ?? 'System Process' }}</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Authorized Auditor</small>
                        <span class="fw-bold text-dark">{{ $loan->approver->name ?? 'Unchecked' }}</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">System Audit Status</small>
                        <span class="badge bg-secondary">{{ $loan->AuditingStatus ?? 'Unassigned' }}</span>
                    </div>
                    <div class="col-12 mt-2 border-top pt-2">
                        <small class="text-muted d-block mb-1">Internal Operations Notes/Remarks</small>
                        <div class="p-2 border rounded bg-white text-secondary small italic">
                            {{ $loan->remarks ?? 'No extra operations notes recorded for this rollover.' }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

@endsection