@extends('layouts.workingside')

@section('title', 'View Customer Invoice')
@section('page-title', 'Invoice Details')

@section('content')

<div class="arbif-page-header mb-4">
    <h3>
        <div class="page-icon">
            <i class="fas fa-receipt"></i>
        </div>
        Invoice: {{ $invoice->bill_no }}
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('printCustomerInvoice', encrypt($invoice->id)) }}" target="_blank" class="btn btn-outline-dark px-3">
            <i class="fas fa-print me-1"></i> Print Invoice
        </a>
        <a href="{{ route('viewsaleCustomer', encrypt($invoice->customer_id)) }}" class="btn btn-secondary px-3">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-8">
        <div class="arbif-card mb-4">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-list me-1"></i> Billed Sales Transactions</h6>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Description</th>
                                <th>Amount (TZS)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoice->items as $idx => $item)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td>{{ $item->description }}</td>
                                <td class="fw-bold">{{ number_format($item->amount, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" class="text-end fw-bold">Total Bill:</td>
                                <td class="fw-bold text-primary fs-6">{{ number_format($invoice->total_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="arbif-card">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-wallet me-1"></i> Financial Summary</h6>

                <ul class="list-group list-group-flush mb-3">
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Total Amount:</span>
                        <span class="fw-bold">TZS {{ number_format($invoice->total_amount, 2) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Paid Amount:</span>
                        <span class="fw-bold text-success">TZS {{ number_format($invoice->paid_amount, 2) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Balance Due:</span>
                        <span class="fw-bold text-danger fs-6">TZS {{ number_format($invoice->balance_amount, 2) }}</span>
                    </li>
                </ul>

                @if($invoice->balance_amount > 0)
                    <button type="button" class="arbif-btn-submit w-100 justify-content-center" data-bs-toggle="modal" data-bs-target="#invoicePaymentModal">
                        <i class="fas fa-money-bill-wave me-1"></i> Record Payment
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
{{-- REVISE INVOICE: ADD UNBILLED SALES SECTION --}}
@if($unbilledSales->count() > 0)
<div class="arbif-card mb-4">
    <div class="arbif-card-body p-4">
        <h6 class="fw-bold text-primary mb-3">
            <i class="fas fa-plus-circle me-1"></i> Revise Invoice: Add Unbilled Sales
        </h6>

        <form method="POST" action="{{ route('addSalesToInvoice', encrypt($invoice->id)) }}">
            @csrf
            <div class="table-responsive mb-3">
                <table class="table table-bordered table-sm align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 40px;" class="text-center">Select</th>
                            <th>Sale No.</th>
                            <th>Date</th>
                            <th>Total (TZS)</th>
                            <th>Paid (TZS)</th>
                            <th>Balance (TZS)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($unbilledSales as $sale)
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" name="sale_ids[]" value="{{ $sale->id }}" class="form-check-input">
                            </td>
                            <td><strong>{{ $sale->sale_no }}</strong></td>
                            <td>{{ \Carbon\Carbon::parse($sale->sale_date)->format('d M, Y') }}</td>
                            <td>{{ number_format($sale->total_amount, 2) }}</td>
                            <td class="text-success">{{ number_format($sale->paid_amount, 2) }}</td>
                            <td class="text-danger fw-bold">{{ number_format($sale->balance_amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <button type="submit" class="arbif-btn-submit">
                <i class="fas fa-save me-1"></i> Add Selected Sales to Invoice
            </button>
        </form>
    </div>
</div>
@endif
{{-- PAYMENT MODAL --}}
@if($invoice->balance_amount > 0)
<div class="modal fade arbif-modal" id="invoicePaymentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <h5 class="modal-title">Record Payment ({{ $invoice->bill_no }})</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('storeInvoicePayment', encrypt($invoice->id)) }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Outstanding Invoice Balance</label>
                        <input type="text" class="form-control fw-bold text-danger fs-6" value="TZS {{ number_format($invoice->balance_amount, 2) }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Payment Date <span class="text-danger">*</span></label>
                        <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Payment Amount (TZS) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="amount" class="form-control" max="{{ $invoice->balance_amount }}" value="{{ $invoice->balance_amount }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Transaction Ref / Cheque No.</label>
                        <input type="text" name="reference_transaction" class="form-control" placeholder="e.g. MPESA / BANK REF">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" rows="2" class="form-control" placeholder="Optional notes..."></textarea>
                    </div>

                    <div class="modal-footer px-0 pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="arbif-btn-submit">Submit Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@endsection