@extends('layouts.workingside')

@section('title', 'View Sale Transaction')
@section('page-title', 'Sale Details')

@section('content')

<div class="arbif-page-header mb-4">
    <h3>
        <div class="page-icon">
            <i class="fas fa-file-invoice"></i>
        </div>
        Sale Transaction: {{ $data->sale_no }}
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('sales') }}" class="btn btn-secondary px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to List
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-8">
        <div class="arbif-card mb-4">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-info-circle me-1"></i> Sale Summary</h6>

                <table class="table table-borderless align-middle">
                    <tr>
                        <th style="width: 30%;" class="text-muted">Sale Reference:</th>
                        <td><span class="arbif-badge arbif-badge-navy">{{ $data->ReferenceNo }}</span></td>
                    </tr>
                    <tr>
                        <th class="text-muted">Sale Number:</th>
                        <td class="fw-bold">{{ $data->sale_no }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Sale Date:</th>
                        <td>{{ $data->sale_date ? \Carbon\Carbon::parse($data->sale_date)->format('d M, Y H:i') : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Sales Type:</th>
                        <td>{{ $data->salesType->type_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Customer:</th>
                        <td>{{ $data->customer->customer_name ?? 'Walk-In Customer' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Cashier:</th>
                        <td>{{ $data->cashier->name ?? 'System' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Payment Terms:</th>
                        <td><span class="badge bg-info text-dark">{{ $data->payment_terms }}</span></td>
                    </tr>
                    <tr>
                        <th class="text-muted">Notes:</th>
                        <td>{{ $data->notes ?? 'No additional notes.' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- ITEMS TABLE --}}
        <div class="arbif-card mb-4">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-list me-1"></i> Purchased Items</h6>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Product Item</th>
                                <th>Quantity</th>
                                <th>Unit Price (TZS)</th>
                                <th>Discount (TZS)</th>
                                <th>Line Total (TZS)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data->items as $idx => $item)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td>
                                    <strong>{{ $item->product->product_name ?? 'N/A' }}</strong>
                                    @if($item->notes)
                                        <br><small class="text-muted">{{ $item->notes }}</small>
                                    @endif
                                </td>
                                <td>{{ number_format($item->quantity, 3) }}</td>
                                <td>{{ number_format($item->unit_price, 2) }}</td>
                                <td>{{ number_format($item->discount_amount, 2) }}</td>
                                <td class="fw-bold">{{ number_format($item->line_total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" class="text-end fw-bold">Subtotal:</td>
                                <td class="fw-bold">{{ number_format($data->subtotal, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-end fw-bold">Total Discount:</td>
                                <td class="text-danger fw-bold">- {{ number_format($data->discount_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-end fw-bold fs-6">Grand Total:</td>
                                <td class="fw-bold text-primary fs-6">{{ number_format($data->total_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- PAYMENT HISTORY --}}
        <div class="arbif-card">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-history me-1"></i> Payment Transactions</h6>

                <div class="table-responsive">
                    <table class="table table-sm table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Payment No.</th>
                                <th>Date</th>
                                <th>Amount (TZS)</th>
                                <th>Reference</th>
                                <th>Received By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data->payments as $pay)
                            <tr>
                                <td><strong>{{ $pay->payment_no }}</strong></td>
                                <td>{{ $pay->payment_date ? \Carbon\Carbon::parse($pay->payment_date)->format('d M, Y') : 'N/A' }}</td>
                                <td class="fw-bold text-success">{{ number_format($pay->amount, 2) }}</td>
                                <td>{{ $pay->reference_transaction ?? 'Cash' }}</td>
                                <td>{{ $pay->receiver->name ?? 'System' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No payments recorded yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="arbif-card mb-4">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-wallet me-1"></i> Financial Status</h6>

                <ul class="list-group list-group-flush mb-3">
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Payment Status:</span>
                        <span class="badge bg-primary fs-6">{{ $data->payment_status }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Total Bill:</span>
                        <span class="fw-bold">TZS {{ number_format($data->total_amount, 2) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Total Paid:</span>
                        <span class="fw-bold text-success">TZS {{ number_format($data->paid_amount, 2) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Balance Due:</span>
                        <span class="fw-bold text-danger fs-6">TZS {{ number_format($data->balance_amount, 2) }}</span>
                    </li>
                </ul>

                @if($data->balance_amount > 0)
                    <button type="button" class="arbif-btn-submit w-100 justify-content-center" data-bs-toggle="modal" data-bs-target="#viewPaymentModal">
                        <i class="fas fa-money-bill-wave me-1"></i> Record Payment
                    </button>
                @endif
            </div>
        </div>

        <div class="arbif-card">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-building me-1"></i> System Metadata</h6>

                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Branch:</span>
                        <span class="fw-bold">{{ $data->branch->branch_name ?? 'Global' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Created By:</span>
                        <span>{{ $data->creator->name ?? 'System' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Created At:</span>
                        <span>{{ $data->created_at ? $data->created_at->format('d M, Y H:i') : 'N/A' }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- RECORD PAYMENT MODAL --}}
@if($data->balance_amount > 0)
<div class="modal fade arbif-modal" id="viewPaymentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <h5 class="modal-title">Record Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('storeSalePayment', encrypt($data->id)) }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Outstanding Balance</label>
                        <input type="text" class="form-control fw-bold text-danger fs-6" value="TZS {{ number_format($data->balance_amount, 2) }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Payment Date <span class="text-danger">*</span></label>
                        <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Payment Amount (TZS) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="amount" class="form-control" max="{{ $data->balance_amount }}" value="{{ $data->balance_amount }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Transaction Ref / Cheque No.</label>
                        <input type="text" name="reference_transaction" class="form-control" placeholder="e.g. MPESA-1029384 / CHQ-99201">
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