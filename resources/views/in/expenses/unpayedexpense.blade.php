@extends('layouts.workingside')

@section('title', 'Disbursement Queue')

@section('page-title', 'Disbursement & Cash Management')

@section('content')

<div class="arbif-page-header mb-4">
    <h3>
        <div class="page-icon">
            <i class="fas fa-money-check-alt"></i>
        </div>
        Pending & Partial Outlays Queue
    </h3>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="unpaidExpensesTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Voucher Code</th>
                        <th>Expense Title</th>
                        <th>Total Cost</th>
                        <th>Amount Paid</th>
                        <th>Remaining Balance</th>
                        <th>Payment Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                        @php
                            $total = (float)$item->total_amount;
                            $paid = (float)($item->amount_paid ?? 0);
                            $balance = $total - $paid;
                        @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong class="text-primary">{{ $item->expense_number ?? 'N/A' }}</strong></td>
                        <td>
                            <div><strong>{{ $item->expense_title }}</strong></div>
                            <small class="text-muted"><i class="fas fa-folder me-1"></i> {{ optional($item->category)->name ?? 'Uncategorized' }}</small>
                        </td>
                        <td><strong>{{ $item->currency }} {{ number_format($total, 2) }}</strong></td>
                        <td class="text-success">{{ $item->currency }} {{ number_format($paid, 2) }}</td>
                        <td class="text-danger font-weight-bold">{{ $item->currency }} {{ number_format($balance, 2) }}</td>
                        <td>
                            @if($item->PaymentStatus == 'Partially Paid')
                                <span class="badge bg-warning text-dark"><i class="fas fa-adjust me-1"></i> Partial ({{ number_format(($paid/$total)*100, 0) }}%)</span>
                            @else
                                <span class="badge bg-secondary"><i class="fas fa-clock me-1"></i> Unpaid</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <button type="button" 
                                        class="btn btn-primary btn-sm py-1 px-2 trigger-pay-modal" 
                                        style="font-size: 0.8rem;"
                                        data-url="{{ route('payexpense', encrypt($item->id)) }}"
                                        data-code="{{ $item->expense_number }}"
                                        data-title="{{ $item->expense_title }}"
                                        data-currency="{{ $item->currency }}"
                                        data-balance="{{ $balance }}"
                                        data-total="{{ $total }}">
                                    <i class="fas fa-coins me-1"></i> Pay
                                </button>
                                <a href="{{ route('viewexpense', encrypt($item->id)) }}" class="arbif-btn-view py-1 px-2" style="font-size: 0.8rem;">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="fas fa-check-double fa-2x text-success opacity-25 d-block mb-2"></i>
                            All approved vouchers have been completely settled.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- SYSTEM DISBURSEMENT FORM MODAL --}}
<div class="modal fade" id="processPaymentModal" tabindex="-1" aria-labelledby="processPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
        <form action="" method="POST" id="paymentSubmissionForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title font-weight-bold" id="processPaymentModalLabel">
                        <i class="fas fa-cash-register text-success me-2"></i>Record Cash Disbursal
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="bg-light p-3 rounded border mb-3">
                        <div class="small text-muted mb-1">Voucher Target:</div>
                        <div class="font-weight-bold text-dark" id="modalVoucherTitle"></div>
                        <div class="badge bg-primary mt-1" id="modalVoucherCode"></div>
                    </div>

                    <div class="row g-2 mb-3 text-center">
                        <div class="col-6">
                            <div class="border rounded p-2 bg-light">
                                <small class="text-muted d-block">Voucher Total</small>
                                <strong class="text-dark id-currency-prefix" id="modalTotalLabel">0.00</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2 bg-light">
                                <small class="text-muted d-block">Outstanding Debt</small>
                                <strong class="text-danger id-currency-prefix" id="modalBalanceLabel">0.00</strong>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Amount to Pay (<span class="currency-placeholder"></span>) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text currency-placeholder">$</span>
                            <input type="number" step="0.01" name="amount_to_pay" id="amountToPayInput" class="form-control form-control-lg font-weight-bold text-primary" required>
                        </div>
                        <div class="form-text d-flex justify-content-between mt-1">
                            <span>Enter partial or full amount.</span>
                            <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none" id="payFullShortcutBtn">Pay Full Balance</button>
                        </div>
                    </div>

                    <div class="row g-3">

                        <!-- Payment Method -->
                        <div class="col-md-6">
                            <label class="form-label font-weight-bold">
                                Payment Method <span class="text-danger">*</span>
                            </label>
                            <select name="payment_method" id="paymentMethodInput" class="form-select" required>
                                <option value="">-- Select Payment Method --</option>
                                <option value="Cash">Cash</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="Mobile Money">Mobile Money</option>
                                <option value="Cheque">Cheque</option>
                                <option value="Card">Card</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <!-- Reference Number -->
                        <div class="col-md-6">
                            <label class="form-label font-weight-bold">
                                Reference Number
                            </label>
                            <input
                                type="text"
                                name="reference_number"
                                id="referenceNumberInput"
                                class="form-control"
                                placeholder="Transaction ID / Cheque No. / Receipt No.">
                            <small class="text-muted">
                                Optional for cash payments.
                            </small>
                        </div>

                        <!-- Description / Comment -->
                        <div class="col-12">
                            <label class="form-label font-weight-bold">
                                Description / Comment
                            </label>
                            <textarea
                                name="description"
                                id="descriptionInput"
                                class="form-control"
                                rows="3"
                                placeholder="Enter any additional comments..."></textarea>
                        </div>

                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-paper-plane me-1"></i> Post Payment</button>
                </div>
            </div>
        </form>
    </div>
    </div>
</div>

{{-- MODAL HANDLER SCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const payModalElement = new bootstrap.Modal(document.getElementById('processPaymentModal'));
    const paymentForm = document.getElementById('paymentSubmissionForm');
    const modalVoucherTitle = document.getElementById('modalVoucherTitle');
    const modalVoucherCode = document.getElementById('modalVoucherCode');
    const modalTotalLabel = document.getElementById('modalTotalLabel');
    const modalBalanceLabel = document.getElementById('modalBalanceLabel');
    const amountInput = document.getElementById('amountToPayInput');
    const payFullBtn = document.getElementById('payFullShortcutBtn');
    
    let activeBalance = 0;

    document.querySelectorAll('.trigger-pay-modal').forEach(button => {
        button.addEventListener('click', function () {
            const url = this.getAttribute('data-url');
            const code = this.getAttribute('data-code');
            const title = this.getAttribute('data-title');
            const currency = this.getAttribute('data-currency');
            const total = parseFloat(this.getAttribute('data-total')) || 0;
            activeBalance = parseFloat(this.getAttribute('data-balance')) || 0;

            // Set Form Attributes
            paymentForm.setAttribute('action', url);
            modalVoucherTitle.textContent = title;
            modalVoucherCode.textContent = code;
            
            // Set labels and limits
            modalTotalLabel.textContent = `${currency} ${total.toLocaleString('en-US', {minimumFractionDigits:2})}`;
            modalBalanceLabel.textContent = `${currency} ${activeBalance.toLocaleString('en-US', {minimumFractionDigits:2})}`;
            
            document.querySelectorAll('.currency-placeholder').forEach(el => el.textContent = currency);
            
            amountInput.value = '';
            amountInput.setAttribute('max', activeBalance);
            
            payModalElement.show();
        });
    });

    payFullBtn.addEventListener('click', function() {
        amountInput.value = activeBalance.toFixed(2);
    });
});
</script>

@endsection