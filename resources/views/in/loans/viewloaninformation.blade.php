@extends('layouts.workingside')

@section('title', 'View Loan Information')

@section('page-title', 'Loan Information')

@section('content')

{{-- TOP ACTION ACTION BAR --}}
<div class="arbif-card no-print">
    <div class="arbif-card-body">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                @can('approve-loans')
                <a href="{{ route('approveloansinformations') }}" class="btn btn-success d-inline-flex align-items-center">
                    <i class="fas fa-check-circle me-2"></i> <span>Approve This Loan</span>
                </a>
                @endcan
                @can('register-loan-doubling')
                <a href="{{ route('doublethisloansinformations', encrypt($data->id)) }}" class="btn btn-success d-inline-flex align-items-center">
                    <i class="fas fa-check-circle me-2"></i> <span>Double This Loan</span>
                </a>
                @endcan
                @can('register-loan-refunding')
                <a href="{{ route('refundthisloansinformations', encrypt($data->id)) }}" class="btn btn-success d-inline-flex align-items-center">
                    <i class="fas fa-check-circle me-2"></i> <span>Refund This Loan</span>
                </a>
                @endcan
            </h4>
            <div>
                <button onclick="window.print()" class="arbif-btn-submit">
                    <i class="fas fa-print"></i> Print
                </button>
                <a href="{{ url()->previous() }}" class="arbif-btn-cancel">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
</div>

{{-- MAIN PROFILE DETAILS --}}
<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="d-flex justify-content-between align-items-center mb-4 no-print">
            <h4 class="mb-0">
                <i class="fas fa-file-invoice-dollar text-primary me-2"></i> Loan Profile
            </h4>
        </div>

        <div id="printArea">
            {{-- PRINT REVENUE BRAND HEADER --}}
            <div class="text-center mb-4">
                <img src="{{ asset('images/arbif.png') }}" alt="ARBIF Logo" style="height:100px;">
                <h2 class="mt-3 mb-1 fw-bold text-uppercase">AIR-BIF MICROFINANCE</h2>
                <h4 class="text-muted">Loan Information Profile</h4>
            </div>

            {{-- TRACKING METRICS --}}
            <div class="text-center mb-4">
                <span class="badge bg-primary p-3 fs-6 mx-1">
                    Loan Number: {{ $data->loan_number ?? 'N/A' }}
                </span>
                <span class="badge bg-dark p-3 fs-6 mx-1">
                    Currency: {{ $data->currency ?? 'USD' }}
                </span>
                @if($data->is_new_client)
                <span class="badge bg-info p-3 fs-6 mx-1 text-dark">
                    <i class="fas fa-user-plus"></i> New Client Profile
                </span>
                @endif
            </div>

            <div class="row g-4">
                {{-- CLIENT INFORMATION --}}
                <div class="col-md-6">
                    <h5 class="border-bottom pb-2 fw-bold text-primary">
                        <i class="fas fa-user me-2"></i>Client Information
                    </h5>
                    <table class="table table-bordered table-striped align-middle">
                        <tr>
                            <th width="40%">Client Name</th>
                            <td>{{ optional($data->client)->FullName ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td>{{ optional($data->client)->PhoneNumber ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Group Center</th>
                            <td>{{ optional($data->groupCenter)->center_name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Group</th>
                            <td>{{ optional($data->group)->group_name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Collection Officer</th>
                            <td>{{ optional($data->collectionOfficer)->name ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>

                {{-- LOAN PARAMETERS --}}
                <div class="col-md-6">
                    <h5 class="border-bottom pb-2 fw-bold text-primary">
                        <i class="fas fa-sliders-h me-2"></i>Loan Parameters
                    </h5>
                    <table class="table table-bordered table-striped align-middle">
                        <tr>
                            <th width="40%">Loan Category</th>
                            <td>{{ optional($data->loanCategory)->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Repayment Frequency</th>
                            <td class="text-capitalize">{{ $data->repayment_frequency ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Term Structural Max</th>
                            <td>
                                {{ $data->max_term_days ?? 0 }} Days / {{ $data->max_term_months ?? 0 }} Months
                            </td>
                        </tr>
                        <tr>
                            <th>Approval Status</th>
                            <td>
                                <span class="badge @if($data->ApprovalStatus === 'Approved') bg-success @elseif($data->ApprovalStatus === 'Rejected') bg-danger @else bg-warning @endif">
                                    {{ $data->ApprovalStatus ?? 'Pending Approval' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Operational Status</th>
                            <td>
                                <span class="badge @if($data->is_active) bg-success @else bg-secondary @endif">
                                    {{ $data->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- TIMELINE MILESTONES --}}
            <div class="mt-4">
                <h5 class="border-bottom pb-2 fw-bold text-primary">
                    <i class="fas fa-calendar-alt me-2"></i>Lifecycle & Timeline Matrix
                </h5>
                <table class="table table-bordered text-center table-striped align-middle">
                    <thead>
                        <tr class="table-light">
                            <th>Application Date</th>
                            <th>Disbursement Date</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Total Days Due</th>
                            <th>Days Left</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $data->application_date ? \Carbon\Carbon::parse($data->application_date)->format('d M Y') : 'N/A' }}</td>
                            <td>{{ $data->disbursement_date ? \Carbon\Carbon::parse($data->disbursement_date)->format('d M Y') : 'N/A' }}</td>
                            <td>{{ $data->start_date ? \Carbon\Carbon::parse($data->start_date)->format('d M Y') : 'N/A' }}</td>
                            <td>{{ $data->end_date ? \Carbon\Carbon::parse($data->end_date)->format('d M Y') : 'N/A' }}</td>
                            <td><span class="badge bg-secondary">{{ $data->total_days_due ?? 0 }} Days</span></td>
                            <td>
                                <span class="badge @if(($data->days_left ?? 0) <= 5) bg-danger @else bg-info text-dark @endif">
                                    {{ $data->days_left ?? 0 }} Days
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- FINANCIALS SUMMARY --}}
            <div class="mt-4">
                <h5 class="border-bottom pb-2 fw-bold text-primary">
                    <i class="fas fa-money-bill-wave me-2"></i>Principal & Core Financial Allocation
                </h5>
                <table class="table table-bordered text-center table-striped align-middle">
                    <thead>
                        <tr class="table-light">
                            <th>Amount Requested</th>
                            <th>Amount Disbursed</th>
                            <th>Interest Rate</th>
                            <th>Interest Calculated</th>
                            <th>Total Repayable</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="fw-bold">{{ number_format($data->amount_requested ?? 0, 2) }}</td>
                            <td class="fw-bold text-success">{{ number_format($data->amount_disbursed ?? 0, 2) }}</td>
                            <td>{{ number_format($data->interest_rate ?? 0, 2) }}%</td>
                            <td>{{ number_format($data->interest_amount ?? 0, 2) }}</td>
                            <td class="fw-bold table-primary">{{ number_format($data->repayable_amount ?? 0, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- CHARGES & FEES LEDGER --}}
            <div class="mt-4">
                <h5 class="border-bottom pb-2 fw-bold text-primary">
                    <i class="fas fa-hand-holding-usd me-2"></i>Fees & Charges Settlement Ledger
                </h5>
                <table class="table table-bordered table-striped align-middle">
                    <thead>
                        <tr class="table-light text-center">
                            <th>Fee Type</th>
                            <th>Imposed Assessment</th>
                            <th>Amount Settled / Paid</th>
                            <th>Outstanding Due</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>Membership Fee</th>
                            <td class="text-center">{{ number_format($data->membership_fee ?? 0, 2) }}</td>
                            <td class="text-center text-success">{{ number_format($data->membership_fee_paid ?? 0, 2) }}</td>
                            <td class="text-center text-danger fw-bold">{{ number_format(($data->membership_fee ?? 0) - ($data->membership_fee_paid ?? 0), 2) }}</td>
                        </tr>
                        <tr>
                            <th>Insurance Fee</th>
                            <td class="text-center">{{ number_format($data->insurance_fee ?? 0, 2) }}</td>
                            <td class="text-center text-success">{{ number_format($data->insurance_fee_paid ?? 0, 2) }}</td>
                            <td class="text-center text-danger fw-bold">{{ number_format(($data->insurance_fee ?? 0) - ($data->insurance_fee_paid ?? 0), 2) }}</td>
                        </tr>
                        <tr>
                            <th>Officer Visit Fee</th>
                            <td class="text-center">{{ number_format($data->officer_visit_fee ?? 0, 2) }}</td>
                            <td class="text-center text-success">{{ number_format($data->officer_visit_fee_paid ?? 0, 2) }}</td>
                            <td class="text-center text-danger fw-bold">{{ number_format(($data->officer_visit_fee ?? 0) - ($data->officer_visit_fee_paid ?? 0), 2) }}</td>
                        </tr>
                        <tr>
                            <th>Penalty Fee Assessment</th>
                            <td class="text-center">{{ number_format($data->penalty_fee ?? 0, 2) }}</td>
                            <td class="text-center text-success">{{ number_format($data->penalty_fee_paid ?? 0, 2) }}</td>
                            <td class="text-center text-danger fw-bold">{{ number_format(($data->penalty_fee ?? 0) - ($data->penalty_fee_paid ?? 0), 2) }}</td>
                        </tr>
                        <tr>
                            <th>Preclosure Settlement Fee</th>
                            <td class="text-center">{{ number_format($data->preclosure_fee ?? 0, 2) }}</td>
                            <td class="text-center text-success">{{ number_format($data->preclosure_fee_paid ?? 0, 2) }}</td>
                            <td class="text-center text-danger fw-bold">{{ number_format(($data->preclosure_fee ?? 0) - ($data->preclosure_fee_paid ?? 0), 2) }}</td>
                        </tr>
                        <tr>
                            <th>Other Miscellaneous Fees</th>
                            <td class="text-center">{{ number_format($data->other_fee ?? 0, 2) }}</td>
                            <td class="text-center text-success">{{ number_format($data->other_fee_paid ?? 0, 2) }}</td>
                            <td class="text-center text-danger fw-bold">{{ number_format(($data->other_fee ?? 0) - ($data->other_fee_paid ?? 0), 2) }}</td>
                        </tr>
                        <tr class="table-dark text-white fw-bold">
                            <th>TOTAL RUNNING BALANCES</th>
                            <td class="text-center text-warning">{{ number_format($data->total_fee, 2) }}</td>
                            <td class="text-center text-info">
                                {{ number_format(($data->membership_fee_paid ?? 0) + ($data->officer_visit_fee_paid ?? 0) + ($data->insurance_fee_paid ?? 0) + ($data->penalty_fee_paid ?? 0) + ($data->preclosure_fee_paid ?? 0) + ($data->other_fee_paid ?? 0), 2) }}
                            </td>
                            <td class="text-center text-danger">
                                {{ number_format(($data->total_fee) - (($data->membership_fee_paid ?? 0) + ($data->officer_visit_fee_paid ?? 0) + ($data->insurance_fee_paid ?? 0) + ($data->penalty_fee_paid ?? 0) + ($data->preclosure_fee_paid ?? 0) + ($data->other_fee_paid ?? 0)), 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- CLEARANCE & CURRENT OUTSTANDING BALANCE --}}
            <div class="mt-4">
                <h5 class="border-bottom pb-2 fw-bold text-primary">
                    <i class="fas fa-chart-line me-2"></i>Performance & Balances Breakdown
                </h5>
                <table class="table table-bordered text-center table-striped align-middle">
                    <thead>
                        <tr class="table-light">
                            <th>Principal Due</th>
                            <th>Interest Due</th>
                            <th>Total Running Balance Due</th>
                            <th>Gross Collected (All In)</th>
                            <th>Net Liquidity Profit / Loss</th>
                            <th>Current Outstanding Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ number_format($data->principal_due ?? 0, 2) }}</td>
                            <td>{{ number_format($data->interest_due ?? 0, 2) }}</td>
                            <td class="fw-bold">{{ number_format($data->total_due, 2) }}</td>
                            <td class="text-success fw-bold">{{ number_format($data->total_amount_paid, 2) }}</td>
                            <td class="fw-bold @if($data->profit_loss_amount >= 0) text-success @else text-danger @endif">
                                {{ number_format($data->profit_loss_amount, 2) }}
                            </td>
                            <td class="table-danger text-danger fw-bold fs-5">
                                {{ number_format($data->outstanding_balance, 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- CLOSURE AND REFUND PARAMETERS --}}
            @if($data->CloseStatus || $data->RefundStatus || $data->RejectReasons)
            <div class="mt-4">
                <h5 class="border-bottom pb-2 fw-bold text-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>Exceptions, System Closure & Refund Context
                </h5>
                <div class="row g-3">
                    @if($data->closed_at)
                    <div class="col-md-6">
                        <div class="card bg-light border-danger">
                            <div class="card-body">
                                <h6 class="fw-bold text-danger"><i class="fas fa-door-closed me-2"></i>Account Closure Parameters</h6>
                                <p class="mb-1"><strong>Status:</strong> {{ $data->CloseStatus ?? 'N/A' }}</p>
                                <p class="mb-1"><strong>Closed At:</strong> {{ \Carbon\Carbon::parse($data->closed_at)->format('d M Y H:i A') }}</p>
                                <p class="mb-1"><strong>Preclosure Collection Valuation:</strong> {{ number_format($data->amount_with_preclosure ?? 0, 2) }}</p>
                                <p class="mb-0 text-muted"><strong>Reason:</strong> {{ $data->closure_reason ?? 'None given' }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($data->refunded_at)
                    <div class="col-md-6">
                        <div class="card bg-light border-warning">
                            <div class="card-body">
                                <h6 class="fw-bold text-warning text-dark"><i class="fas fa-undo me-2"></i>Disbursement Refund Log</h6>
                                <p class="mb-1"><strong>Refund Status:</strong> {{ $data->RefundStatus ?? 'N/A' }}</p>
                                <p class="mb-1"><strong>Refunded At:</strong> {{ \Carbon\Carbon::parse($data->refunded_at)->format('d M Y') }}</p>
                                <p class="mb-1"><strong>Refund Principal Payout Valued:</strong> {{ number_format($data->amount_with_refund ?? 0, 2) }}</p>
                                <p class="mb-1"><strong>Authorized Clearing Clerk:</strong> {{ optional($data->refundedBy)->name ?? 'N/A' }}</p>
                                <p class="mb-0 text-muted"><strong>Reasoning Flag:</strong> {{ $data->refunding_reason ?? 'None given' }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                @if($data->RejectReasons)
                <div class="alert alert-danger mt-3 mb-0">
                    <strong><i class="fas fa-ban me-2"></i>Application Underwriting Rejection Reasons:</strong>
                    <p class="mb-0 mt-1">{{ $data->RejectReasons }}</p>
                </div>
                @endif
            </div>
            @endif

            {{-- INTERNAL METADATA CHECKSUM --}}
            <div class="mt-4">
                <h5 class="border-bottom pb-2 fw-bold text-secondary">
                    <i class="fas fa-shield-alt me-2"></i>System Auditing Control Identifiers
                </h5>
                <table class="table table-sm table-bordered text-center table-striped small align-middle">
                    <thead>
                        <tr class="table-light text-muted">
                            <th>System Ledger ID</th>
                            <th>Underwriter (Creator)</th>
                            <th>Audit Scope Level</th>
                            <th>Reporting Integration</th>
                            <th>Approver User Entity</th>
                            <th>Approved At</th>
                            <th>Last Modification Agent</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-secondary">
                            <td>#{{ $data->id }}</td>
                            <td>{{ optional($data->creator)->name ?? 'System Process' }}</td>
                            <td><span class="badge bg-secondary">{{ $data->AuditingStatus ?? 'Unverified' }}</span></td>
                            <td><span class="badge bg-secondary">{{ $data->ReportStatus ?? 'Pending' }}</span></td>
                            <td>{{ optional($data->approver)->name ?? 'Unverified' }}</td>
                            <td>{{ $data->approved_at ? \Carbon\Carbon::parse($data->approved_at)->format('d M Y H:i A') : 'Not Approved' }}</td>
                            <td>{{ optional($data->updater)->name ?? 'No Updates' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- REMARKS / NOTES BLOCK --}}
            <div class="mt-4">
                <h5 class="border-bottom pb-2 fw-bold text-primary">
                    <i class="fas fa-comment-alt me-2"></i>Underwriting Remarks / Notes
                </h5>
                <div class="bg-light border rounded p-3 text-dark">
                    {!! nl2br(e($data->remarks ?? 'No explicit remarks logged.')) !!}
                </div>
            </div>

            {{-- FOOTER SIGN OFF LINE --}}
            <div class="row mt-5 pt-3 border-top page-signoff">
                <div class="col-md-6">
                    <strong>Report Compiled By:</strong>
                    <br><span class="text-muted">{{ Auth()->user()->name }}</span>
                </div>
                <div class="col-md-6 text-end">
                    <strong>Verification Checksum Timestamp:</strong>
                    <br><span class="text-muted">{{ now()->format('d M Y H:i A') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
@media print {
    .no-print,
    .sidebar,
    .navbar,
    .btn,
    footer {
        display: none !important;
    }
    body {
        background: #fff !important;
        color: #000 !important;
        font-size: 12px;
    }
    .arbif-card {
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    .table td, .table th {
        padding: 4px 6px !important;
        font-size: 11px !important;
    }
    .badge {
        border: 1px solid #000 !important;
        color: #000 !important;
        background: transparent !important;
    }
    .page-signoff {
        margin-top: 60px !important;
    }
}
.table th {
    background: #f8f9fa;
    font-weight: 600;
}
</style>
@endpush