@extends('layouts.workingside')

@section('title', 'Approve Loan Application')

@section('page-title', 'Underwriting & Loan Approval')

@section('content')

{{-- ACTION HEADER --}}
<div class="arbif-card mb-4">
    <div class="arbif-card-body d-flex justify-content-between align-items-center">
        <h4 class="mb-0 text-warning fw-bold">
            <i class="fas fa-gavel me-2"></i> Pending Loan Review
        </h4>
        <a href="{{ url()->previous() }}" class="arbif-btn-cancel text-decoration-none">
            <i class="fas fa-arrow-left me-1"></i> Cancel & Back
        </a>
    </div>
</div>

<div class="row">
    {{-- LEFT: APPLICATION SUMMARY --}}
    <div class="col-lg-8">
        <div class="arbif-card mb-4">
            <div class="arbif-card-body">
                <h5 class="border-bottom pb-2 fw-bold text-primary mb-3">
                    <i class="fas fa-file-invoice-dollar me-2"></i> Loan Core Details
                </h5>
                
                <table class="table table-bordered table-striped align-middle mb-4">
                    <tr>
                        <th width="35%">Loan Reference Number</th>
                        <td class="fw-bold text-primary">{{ $data->loan_number ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Applicant Client</th>
                        <td>{{ optional($data->client)->FullName ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Loan Category / Product</th>
                        <td>{{ optional($data->loanCategory)->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Group Center Name</th>
                        <td>{{ optional($data->groupCenter)->center_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Assigned Group</th>
                        <td>{{ optional($data->group)->group_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Application Request Date</th>
                        <td>{{ $data->application_date ? \Carbon\Carbon::parse($data->application_date)->format('d M Y') : 'N/A' }}</td>
                    </tr>
                </table>

                <h5 class="border-bottom pb-2 fw-bold text-primary mb-3">
                    <i class="fas fa-calculator me-2"></i> Financial Computations
                </h5>
                <div class="row g-3 text-center mb-2">
                    <div class="col-md-4">
                        <div class="p-3 border rounded bg-light">
                            <small class="text-muted d-block text-uppercase small fw-bold">Requested Allocation</small>
                            <span class="fs-5 fw-bold text-dark">{{ number_format($data->amount_requested ?? 0, 2) }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 border rounded bg-light">
                            <small class="text-muted d-block text-uppercase small fw-bold">Interest Percentage</small>
                            <span class="fs-5 fw-bold text-dark">{{ number_format($data->interest_rate ?? 0, 2) }}%</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 border rounded bg-light">
                            <small class="text-muted d-block text-uppercase small fw-bold">Interest Value Yield</small>
                            <span class="fs-5 fw-bold text-dark">{{ number_format($data->interest_amount ?? 0, 2) }}</span>
                        </div>
                    </div>
                </div>

                <table class="table table-bordered table-striped align-middle mt-4">
                    <tr class="table-primary fw-bold">
                        <th width="35%">Expected Gross Repayable</th>
                        <td>{{ number_format($data->repayable_amount ?? 0, 2) }} {{ $data->currency ?? 'USD' }}</td>
                    </tr>
                    <tr>
                        <th>Repayment Structural Frequency</th>
                        <td class="text-capitalize">{{ $data->repayment_frequency ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Client Installment Target</th>
                        <td class="fw-bold">{{ number_format($data->client_payable_frequency ?? 0, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- REPAYMENT ACTIVATION MONITOR --}}
        <div class="mt-4">
            @php
                $totalCollectedSoFar = ($data->amount_paid ?? 0) 
                                     + ($data->membership_fee_paid ?? 0) 
                                     + ($data->officer_visit_fee_paid ?? 0) 
                                     + ($data->insurance_fee_paid ?? 0) 
                                     + ($data->preclosure_fee_paid ?? 0) 
                                     + ($data->penalty_fee_paid ?? 0) 
                                     + ($data->other_fee_paid ?? 0);
            @endphp

            @if($totalCollectedSoFar > 0)
                <div class="alert alert-success d-flex align-items-center p-3 mb-4 border border-success" role="alert">
                    <i class="fas fa-chart-line fa-2x me-3 text-success"></i>
                    <div>
                        <h5 class="alert-heading mb-1 fw-bold text-success">Active Repayment Cycle Enforced</h5>
                        <p class="mb-0 text-dark small">This account has initialized collections. System ledgers are actively capturing amortization installments.</p>
                    </div>
                    <span class="badge bg-success ms-auto p-2 fs-6">
                        <i class="fas fa-spinner fa-spin me-1"></i> In Amortization
                    </span>
                </div>
            @else
                <div class="alert alert-warning d-flex align-items-center p-3 mb-4 border border-warning" role="alert">
                    <i class="fas fa-hourglass-start fa-2x me-3 text-warning"></i>
                    <div>
                        <h5 class="alert-heading mb-1 fw-bold">Awaiting Initial Repayment</h5>
                        <p class="mb-0 text-dark small">Account is active but no amortization principal, interest, or fee payments have been cleared yet.</p>
                    </div>
                    <span class="badge bg-warning text-dark ms-auto p-2 fs-6">No Payments Yet</span>
                </div>
            @endif

            <h5 class="border-bottom pb-2 fw-bold text-success">
                <i class="fas fa-receipt me-2"></i>Amortization & Collected Fees Ledger
            </h5>
            
            <table class="table table-bordered table-striped align-middle">
                <thead>
                    <tr class="table-success text-dark text-center small fw-bold">
                        <th width="50%">Repayment Stream Allocation</th>
                        <th width="50%">Total Amount Settled ({{ $data->currency ?? 'USD' }})</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th class="ps-3"><i class="fas fa-file-invoice-dollar text-success me-2"></i>Principal / Interest Amount Paid</th>
                        <td class="text-center fw-bold @if(($data->amount_paid ?? 0) > 0) text-success @else text-muted @endif">
                            {{ number_format($data->amount_paid ?? 0, 2) }}
                        </td>
                    </tr>
                    <tr>
                        <th class="ps-3"><i class="fas fa-id-card text-muted me-2"></i>Membership Fee Paid</th>
                        <td class="text-center @if(($data->membership_fee_paid ?? 0) > 0) text-success fw-bold @else text-muted @endif">
                            {{ number_format($data->membership_fee_paid ?? 0, 2) }}
                        </td>
                    </tr>
                    <tr>
                        <th class="ps-3"><i class="fas fa-user-check text-muted me-2"></i>Officer Visit Fee Paid</th>
                        <td class="text-center @if(($data->officer_visit_fee_paid ?? 0) > 0) text-success fw-bold @else text-muted @endif">
                            {{ number_format($data->officer_visit_fee_paid ?? 0, 2) }}
                        </td>
                    </tr>
                    <tr>
                        <th class="ps-3"><i class="fas fa-shield-alt text-muted me-2"></i>Insurance Fee Paid</th>
                        <td class="text-center @if(($data->insurance_fee_paid ?? 0) > 0) text-success fw-bold @else text-muted @endif">
                            {{ number_format($data->insurance_fee_paid ?? 0, 2) }}
                        </td>
                    </tr>
                    <tr>
                        <th class="ps-3"><i class="fas fa-handshake-slash text-muted me-2"></i>Preclosure Fee Paid</th>
                        <td class="text-center @if(($data->preclosure_fee_paid ?? 0) > 0) text-success fw-bold @else text-muted @endif">
                            {{ number_format($data->preclosure_fee_paid ?? 0, 2) }}
                        </td>
                    </tr>
                    <tr>
                        <th class="ps-3"><i class="fas fa-exclamation-triangle text-danger me-2"></i>Penalty Fee Paid</th>
                        <td class="text-center @if(($data->penalty_fee_paid ?? 0) > 0) text-danger fw-bold @else text-muted @endif">
                            {{ number_format($data->penalty_fee_paid ?? 0, 2) }}
                        </td>
                    </tr>
                    <tr>
                        <th class="ps-3"><i class="fas fa-coins text-muted me-2"></i>Other Fees Paid</th>
                        <td class="text-center @if(($data->other_fee_paid ?? 0) > 0) text-success fw-bold @else text-muted @endif">
                            {{ number_format($data->other_fee_paid ?? 0, 2) }}
                        </td>
                    </tr>
                    <tr class="table-dark text-white fw-bold">
                        <th class="ps-3 text-uppercase"><i class="fas fa-calculator text-warning me-2"></i>Total Gross Funds Recovered</th>
                        <td class="text-center text-warning fs-5">
                            {{ number_format($totalCollectedSoFar, 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- RIGHT: UNDERWRITING DECISION HUB --}}
    <div class="col-lg-4">
        <div class="arbif-card">
            <div class="arbif-card-body">
                <h5 class="border-bottom pb-2 fw-bold text-danger mb-3">
                    <i class="fas fa-shield-alt me-2"></i> Approval Processing
                </h5>
                
                {{-- Updated action pathway mapping safely to update route --}}
                <form action="{{ route('updateproductCategory', $data->id) }}" method="POST" id="approvalForm">
                    @csrf
                    @method('PUT')
                    
                    {{-- Hidden helper tracks decision string safely across forms --}}
                    <input type="hidden" name="action_type" id="action_type" value="approve">

                    <div class="mb-3" id="disbursementBlock">
                        <label for="amount_disbursed" class="form-label fw-bold">Amount to Disburse</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light fw-bold">{{ $data->currency ?? 'USD' }}</span>
                            <input type="number" 
                                   step="0.01" 
                                   class="form-control fw-bold text-success" 
                                   id="amount_disbursed" 
                                   name="amount_disbursed" 
                                   value="{{ $data->amount_requested }}" 
                                   required>
                        </div>
                        <div class="form-text">Defaults to requested total unless underwriting adjusts down.</div>
                    </div>

                    <div class="mb-3">
                        <label for="remarks" class="form-label fw-bold">Underwriting Notes / Feedback</label>
                        <textarea class="form-control" 
                                  id="remarks" 
                                  name="remarks" 
                                  rows="4" 
                                  placeholder="Provide authorization remarks or context regarding decision guidelines..."></textarea>
                    </div>

                    <div class="mb-3 d-none" id="rejectionReasonBlock">
                        <label for="RejectReasons" class="form-label fw-bold text-danger">Explicit Reason for Rejection</label>
                        <textarea class="form-control border-danger" 
                                  id="RejectReasons" 
                                  name="RejectReasons" 
                                  rows="3" 
                                  placeholder="State explicitly why this application was denied..."></textarea>
                    </div>

                    <div class="d-grid gap-2 pt-2">
                        <button type="submit" 
                                id="btnSubmitApprove"
                                class="btn btn-success btn-lg d-flex align-items-center justify-content-center">
                            <i class="fas fa-check-circle me-2"></i> Authorize & Approve
                        </button>
                        
                        <button type="button" 
                                id="btnTriggerReject"
                                class="btn btn-outline-danger d-flex align-items-center justify-content-center">
                            <i class="fas fa-times-circle me-2"></i> Reject Application
                        </button>

                        <button type="submit" 
                                id="btnSubmitReject"
                                class="btn btn-danger btn-lg d-none align-items-center justify-content-center">
                            <i class="fas fa-ban me-2"></i> Confirm Rejection
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Get all the elements safely
        const form = document.getElementById('approvalForm');
        const btnTriggerReject = document.getElementById('btnTriggerReject');
        const btnSubmitReject = document.getElementById('btnSubmitReject');
        const btnSubmitApprove = document.getElementById('btnSubmitApprove');
        const rejectionReasonBlock = document.getElementById('rejectionReasonBlock');
        const amountDisbursedInput = document.getElementById('amount_disbursed');
        const actionTypeInput = document.getElementById('action_type');

        // 2. Prevent errors if elements aren't rendered on the page
        if (!btnTriggerReject) {
            console.error("Reject button trigger element not found in DOM.");
            return;
        }

        // 3. Handle the click event to switch layout to rejection mode
        btnTriggerReject.addEventListener('click', function (e) {
            e.preventDefault(); // Stop any unintended form submission string tracking
            
            // Show rejection block and submission confirmation button
            rejectionReasonBlock.classList.remove('d-none');
            btnSubmitReject.classList.remove('d-none');
            
            // Hide the initial trigger state buttons
            btnTriggerReject.classList.add('d-none');
            btnSubmitApprove.classList.add('d-none');
            
            // Alter payloads safely
            actionTypeInput.value = 'reject';
            amountDisbursedInput.removeAttribute('required');
        });

        // 4. Form intercept validation logic
        form.addEventListener('submit', function (e) {
            if (actionTypeInput.value === 'reject') {
                const reason = document.getElementById('RejectReasons').value.trim();
                if (reason === '') {
                    e.preventDefault();
                    alert('You must provide an explicit validation reason explaining why this application is rejected.');
                    return false;
                }
                if (!confirm('Are you sure you want to REJECT this application configuration?')) {
                    e.preventDefault();
                    return false;
                }
            } else {
                if (!confirm('Are you sure you want to AUTHORIZE and APPROVE this loan application?')) {
                    e.preventDefault();
                    return false;
                }
            }
        });
    });
</script>
@endpush
@endsection
