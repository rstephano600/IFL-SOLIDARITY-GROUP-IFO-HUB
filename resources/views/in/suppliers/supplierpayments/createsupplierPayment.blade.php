@extends('layouts.workingside')

@section('title', 'Process Supplier Payment')
@section('page-title', 'Process Supplier Payment')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-hand-holding-dollar"></i>
        </div>
        Process Payment for Inv: {{ $invoice->invoice_no }}
    </h3>

    <div>
        <a href="{{ route('unPaidSupplierInvoices') }}" class="btn btn-secondary px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back
        </a>
    </div>
</div>

{{-- INVOICE SUMMARY HEADER --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="arbif-card p-3 bg-light">
            <small class="text-muted d-block">Supplier</small>
            <strong class="fs-6 text-dark">{{ $invoice->supplier->SupplierName ?? 'N/A' }}</strong>
        </div>
    </div>
    <div class="col-md-4">
        <div class="arbif-card p-3 bg-light">
            <small class="text-muted d-block">Total Invoice Amount</small>
            <strong class="fs-6 text-dark">{{ number_format($invoice->total_amount, 2) }} {{ $invoice->currency_code }}</strong>
        </div>
    </div>
    <div class="col-md-4">
        <div class="arbif-card p-3 bg-light border-start border-4 border-danger">
            <small class="text-muted d-block">Current Outstanding Balance</small>
            <strong class="fs-5 text-danger">{{ number_format($invoice->outstanding_amount, 2) }} {{ $invoice->currency_code }}</strong>
        </div>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body p-4">
        <form method="POST" action="{{ route('storesupplierPayment', encrypt($invoice->id)) }}" enctype="multipart/form-data" id="paymentForm">
            @csrf

            <h6 class="fw-bold text-primary mb-3"><i class="fas fa-credit-card me-1"></i> Payment Information</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Payment Date <span class="text-danger">*</span></label>
                    <input type="date" name="payment_date" class="form-control" value="{{ old('payment_date', date('Y-m-d')) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Amount to Pay ({{ $invoice->currency_code }}) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="payment_amount" class="form-control fw-bold text-success" value="{{ old('payment_amount', $invoice->outstanding_amount) }}" max="{{ $invoice->outstanding_amount }}" min="0.01" required>
                    <small class="text-muted">Enter partial or full amount (Max: {{ number_format($invoice->outstanding_amount, 2) }})</small>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                    <select name="payment_method" class="form-select" required>
                        <option value="Bank Transfer" {{ old('payment_method') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer / EFT</option>
                        <option value="Cheque" {{ old('payment_method') == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                        <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                        <option value="Mobile Money" {{ old('payment_method') == 'Mobile Money' ? 'selected' : '' }}>Mobile Money</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Payment Reference / Transaction ID</label>
                    <input type="text" name="payment_reference" class="form-control" value="{{ old('payment_reference') }}" placeholder="e.g. TRX-90182390">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Paying Bank Account</label>
                    <input type="text" name="bank_account" class="form-control" value="{{ old('bank_account') }}" placeholder="e.g. CRDB Bank - 0150293847">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Cheque Number (if applicable)</label>
                    <input type="text" name="cheque_no" class="form-control" value="{{ old('cheque_no') }}" placeholder="e.g. CHQ-00129">
                </div>

                <div class="col-md-12">
                    <label class="form-label">Attach Proof of Payment (Bank Slip / Receipt)</label>
                    <input type="file" name="attachment" class="form-control">
                </div>

                <div class="col-12">
                    <label class="form-label">Payment Remarks / Notes</label>
                    <textarea name="comment" class="form-control" rows="2" placeholder="Optional comments regarding this transaction...">{{ old('comment') }}</textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('unPaidSupplierInvoices') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-success px-4 fw-bold">Confirm & Post Payment</button>
            </div>
        </form>
    </div>
</div>

@endsection