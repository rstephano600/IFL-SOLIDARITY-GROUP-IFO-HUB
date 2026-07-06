@extends('layouts.workingside')

@section('title', 'Loan Doubling Informations')

@section('page-title', 'Loan Doubling Informations')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-hand-holding-usd"></i>
        </div>
        Loan Doubling / Top-up Informations
    </h3>

    <button class="arbif-btn-submit" data-bs-toggle="modal" data-bs-target="#addFormModal">
        <i class="fas fa-plus"></i> Register Loan Doubling
    </button>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="loanTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Client Full Name</th>
                        <th class="sortable">Old Loan Ref</th>
                        <th class="sortable">New Loan Ref</th>
                        <th class="sortable">Requested Amount</th>
                        <th class="sortable">Total Outstanding Cleaned</th>
                        <th class="sortable">Topup Fee</th>
                        <th class="sortable">Net Disbursed</th>
                        <th class="sortable">Date Given</th>
                        <th class="sortable">Approval Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->client->client->name ?? 'N/A' }}</td>
                        <td><span class="badge bg-secondary">{{ $item->oldLoan->loan_number ?? 'N/A' }}</span></td>
                        <td><span class="badge bg-primary">{{ $item->newLoan->loan_number ?? 'N/A' }}</span></td>
                        <td>{{ number_format($item->requested_amount ?? 0, 2) }}</td>
                        <td>{{ number_format($item->total_outstanding ?? 0, 2) }}</td>
                        <td>{{ number_format($item->topup_fee ?? 0, 2) }}</td>
                        <td><strong>{{ number_format($item->net_disbursed ?? 0, 2) }}</strong></td>
                        <td>{{ $item->topup_date ? \Carbon\Carbon::parse($item->topup_date)->format('Y-m-d') : 'N/A' }}</td>
                        <td>
                            @if($item->ApprovalStatus == 'Approved')
                                <span class="arbif-badge arbif-badge-success">Approved</span>
                            @elseif($item->ApprovalStatus == 'Pending')
                                <span class="arbif-badge arbif-badge-warning">Pending</span>
                            @else
                                <span class="arbif-badge arbif-badge-danger">{{ $item->ApprovalStatus }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('viewloandoublinginformations', encrypt($item->id)) }}" class="arbif-btn-view">
                                <i class="fas fa-eye"></i> View details
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center">No Loan Doubling Informations Found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL LAYOUT FOR REGISTRATION --}}
<div class="modal fade arbif-modal" id="addFormModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <h5 class="modal-title">Register Loan Doubling Process</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form method="POST" action="{{ route('storeloandoublinginformations') }}">
                    @csrf

                    <div class="row g-3">
                        <div class="col-12">
                            <h5 class="arbif-section-title">Step 1: Select Eligible Target Loan</h5>
                        </div>

                        {{-- ELIGIBLE TARGET LOAN LIST --}}
                        <div class="col-md-12">
                            <label class="form-label">Eligible Pending Loan <span class="text-danger">*</span></label>
                            <select class="form-select" name="old_loan_id" id="old_loan_id" data-searchable data-placeholder="Search ..." required>
                                <option value="">Select Loan</option>
                                @foreach($loan as $item)
                                    @php
                                        $totalRepayable = $item->total_repayable;
                                        $amountPaid = $item->total_amount_paid;
                                        $remainingBalance = max(0, $totalRepayable - $amountPaid);
                                    @endphp
                                    <option value="{{ $item->id }}" 
                                            data-repayable="{{ number_format($totalRepayable, 2, '.', '') }}"
                                            data-paid="{{ number_format($amountPaid, 2, '.', '') }}"
                                            data-outstanding="{{ number_format($remainingBalance, 2, '.', '') }}"
                                            data-client="{{ $item->client_id }}"
                                            data-group="{{ $item->group_id }}"
                                            data-center="{{ $item->group_center_id }}">
                                        {{ $item->client->client->name ?? 'Unknown Client' }} ({{ $item->loan_number }}) — TRepay: {{ number_format($totalRepayable, 2) }} - TPayed: {{ number_format($amountPaid, 2) }} -  Owed: {{ number_format($remainingBalance, 2) }} ({{ $item->loanCategory->name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- PLACE THIS DIRECTLY BELOW STEP 2 IN YOUR MODAL ROW CONTAINER --}}

                        <div class="col-md-6">
                            <label class="form-label">Topup Date <span class="text-danger">*</span></label>
                            <input type="date" 
                                name="topup_date" 
                                id="topup_date" 
                                class="form-control" 
                                value="{{ date('Y-m-d') }}" 
                                required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Doubling Reason / Category <span class="text-danger">*</span></label>
                            <select name="topup_reason" id="topup_reason" class="form-select" data-searchable data-placeholder="Search ..." required>
                                <option value="">Select Reason / Category</option>
                                <option value="Client requested structural rollover setup">Client requested structural rollover setup</option>
                                <option value="Insufficient operational capital clearance">Insufficient operational capital clearance</option>
                                <option value="Parallel business project expansion refinancing">Parallel business project expansion refinancing</option>
                                <option value="Other structural loan doubling intervention">Other structural loan doubling intervention</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Internal Audit Remarks / Notes</label>
                            <textarea name="remarks" 
                                    id="remarks" 
                                    class="form-control" 
                                    rows="2" 
                                    placeholder="Add extra contextual office processing remarks if necessary..."></textarea>
                        </div>

                        <div class="col-12 mt-4">
                            <h5 class="arbif-section-title">Step 2: New Loan Parameters Configuration (Options)</h5>
                        </div>

                        {{-- LOAN CATEGORY --}}
                        <div class="col-md-6">
                            <label class="form-label"> Loan Category </label>
                            <select name="loan_category_id" data-searchable data-placeholder="Search ..." required>
                                <option value="">Select Loan Category</option>
                                @foreach($loanCategories as $category)
                                    @php
                                        $totalRepayable = $category->repayable_amount;
                                        $principaldue = $category->principal_due;
                                    @endphp
                                    <option value="{{ $category->id }}"
                                            data-interest="{{ $category->interest_rate }}"
                                            data-frequency="{{ $category->repayment_frequency }}">
                                        {{ $category->name }} - ({{ $category->amount_disbursed }}) - Due: {{ number_format($principaldue, 2) }} - TPayable: {{ number_format($totalRepayable, 2) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Loan Application Date <span class="text-danger"></span></label>
                            <input type="date" 
                                name="application_date" 
                                id="application_date" 
                                class="form-control" 
                                value="{{ date('Y-m-d') }}" >
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">MemberShip Fee (Tsh)</label>
                            <input type="number" step="0.01" name="membership_fee" id="membership_fee" class="form-control" value="0">
                        </div>
                        
                        {{-- AUTOMATIC CALCULATED DEDUCTION TRUCKS --}}
                        <div class="col-md-6">
                            <label class="form-label">Inter pre closure Fee (Tsh)</label>
                            <input type="number" step="0.01" name="topup_fee" id="topup_fee" class="form-control bg-light" readonly value="5000.00">
                        </div>

                    </div>

                    <div class="modal-footer mt-4">
                        <button type="button" class="arbif-btn-cancel" data-bs-dismiss="modal">
                            <i class="bi bi-x"></i> Cancel
                        </button>
                        <button type="submit" class="arbif-btn-submit">
                            <i class="bi bi-check-circle"></i> Complete Processing
                        </button>
                    </div>
                    <div class="modal-footer mt-4">

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- MATHEMATICAL PIPELINE ENGINE SCRIPT --}}
<script>
(function() {
    function initLoanTopupEngine() {
        const $select = $('#old_loan_id');
        const reqAmountInput = document.getElementById('requested_amount');

        if (typeof $ !== 'undefined' && $select.length) {
            $select.on('change select2:select', function () {
                runCalculationPipeline();
            });
        } else {
            const loanSelect = document.getElementById('old_loan_id');
            if (loanSelect) {
                loanSelect.addEventListener('change', runCalculationPipeline);
            }
        }

        if (reqAmountInput) {
            reqAmountInput.addEventListener('input', runCalculationPipeline);
        }
    }

    function runCalculationPipeline() {
        const select = document.getElementById('old_loan_id');
        const reqAmountInput = document.getElementById('requested_amount');
        
        const displayRepayable = document.getElementById('display_total_repayable');
        const displayPaid = document.getElementById('display_amount_paid');
        const displayOutstanding = document.getElementById('display_outstanding');
        
        const hiddenClient = document.getElementById('hidden_client_id');
        const hiddenGroup = document.getElementById('hidden_group_id');
        const hiddenCenter = document.getElementById('hidden_group_center_id');
        
        const totalDeductionsInput = document.getElementById('total_deductions');
        const netDisbursedInput = document.getElementById('net_disbursed');

        let outstanding = 0;
        let officeFee = 5000;

        if (select && select.value !== "") {
            const opt = select.options[select.selectedIndex];
            
            let repayable = parseFloat(opt.getAttribute('data-repayable') || 0);
            let paid = parseFloat(opt.getAttribute('data-paid') || 0);
            outstanding = parseFloat(opt.getAttribute('data-outstanding') || 0);

            // Populate system layout display
            displayRepayable.value = repayable.toLocaleString('en-US', { minimumFractionDigits: 2 });
            displayPaid.value = paid.toLocaleString('en-US', { minimumFractionDigits: 2 });
            displayOutstanding.value = outstanding.toFixed(2);

            // Populate relational hidden keys 
            hiddenClient.value = opt.getAttribute('data-client') || '';
            hiddenGroup.value = opt.getAttribute('data-group') || '';
            hiddenCenter.value = opt.getAttribute('data-center') || '';
        } else {
            displayRepayable.value = '';
            displayPaid.value = '';
            displayOutstanding.value = '';
            hiddenClient.value = '';
            hiddenGroup.value = '';
            hiddenCenter.value = '';
        }

        // Compute requested values math
        let requestedAmount = parseFloat(reqAmountInput.value || 0);
        let absoluteDeductions = outstanding + officeFee;
        let physicalNetPayout = requestedAmount - absoluteDeductions;

        totalDeductionsInput.value = absoluteDeductions.toFixed(2);
        netDisbursedInput.value = physicalNetPayout.toFixed(2);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initLoanTopupEngine);
    } else {
        initLoanTopupEngine();
    }
})();
</script>
@endsection