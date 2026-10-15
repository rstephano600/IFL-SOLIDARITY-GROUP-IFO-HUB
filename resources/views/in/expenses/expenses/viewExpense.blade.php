@extends('layouts.workingside')

@section('title', 'Expense Details')
@section('page-title', 'Expense Details')

@section('content')

@php
    $balance  = (float) $data->total_amount - (float) $data->amount_paid;
    $appClass = ['Approved' => 'success', 'Rejected' => 'danger', 'Pending' => 'warning text-dark'][$data->AppStatus] ?? 'secondary';
    $payClass = ['Paid' => 'success', 'Partially Paid' => 'info text-dark', 'Unpaid' => 'secondary'][$data->PaymentStatus] ?? 'secondary';
@endphp

<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-receipt"></i></div>
        {{ $data->expense_number }}
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('expenses') }}" class="btn btn-secondary text-white"><i class="fas fa-arrow-left me-1"></i> Back</a>

        @if($data->AppStatus !== 'Approved' && $data->PaymentStatus !== 'Paid')
            <a href="{{ route('editExpense', encrypt($data->id)) }}" class="btn btn-warning"><i class="fas fa-pencil me-1"></i> Edit</a>
        @endif

        @if($data->AppStatus === 'Pending')
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal">
                <i class="fas fa-check-circle me-1"></i> Approve / Reject
            </button>
        @endif

        @if($data->AppStatus === 'Approved' && $data->PaymentStatus !== 'Paid')
            <button class="arbif-btn-submit" data-bs-toggle="modal" data-bs-target="#payModal">
                <i class="fas fa-money-bill-wave me-1"></i> Record Payment
            </button>
        @endif
    </div>
</div>

<div class="row g-3 mb-4">
    {{-- SUMMARY --}}
    <div class="col-lg-8">
        <div class="arbif-card h-100">
            <div class="arbif-card-body">
                <h6 class="fw-bold mb-3">{{ $data->expense_title }}</h6>
                <div class="row g-3">
                    <div class="col-md-4"><small class="text-muted d-block">Category</small><strong>{{ $data->category->name ?? 'N/A' }}</strong></div>
                    <div class="col-md-4"><small class="text-muted d-block">Branch</small><strong>{{ $data->branch->branch_name ?? 'N/A' }}</strong></div>
                    <div class="col-md-4"><small class="text-muted d-block">Expense Date</small><strong>{{ \Carbon\Carbon::parse($data->expense_date)->format('d M Y') }}</strong></div>
                    <div class="col-md-4"><small class="text-muted d-block">Requested By</small><strong>{{ $data->creator->name ?? $data->user->name ?? 'N/A' }}</strong></div>
                    <div class="col-md-4"><small class="text-muted d-block">Last Edited By</small><strong>{{ $data->editor->name ?? '-' }}</strong></div>
                    <div class="col-md-4"><small class="text-muted d-block">Created At</small><strong>{{ $data->created_at?->format('d M Y H:i') }}</strong></div>
                    <div class="col-md-12">
                        <small class="text-muted d-block">Description</small>
                        {{ $data->description ?: 'N/A' }}
                    </div>
                    @if($data->attachment)
                    <div class="col-md-12">
                        <a href="{{ asset('storage/' . $data->attachment) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-paperclip me-1"></i> View Attachment
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- STATUS & BALANCE --}}
    <div class="col-lg-4">
        <div class="arbif-card h-100">
            <div class="arbif-card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Approval</span><span class="badge bg-{{ $appClass }}">{{ $data->AppStatus }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span>Payment</span><span class="badge bg-{{ $payClass }}">{{ $data->PaymentStatus }}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between"><span>Total</span><strong>{{ $data->currency }} {{ number_format($data->total_amount, 2) }}</strong></div>
                <div class="d-flex justify-content-between"><span>Paid</span><strong class="text-success">{{ number_format($data->amount_paid, 2) }}</strong></div>
                <div class="d-flex justify-content-between"><span>Balance</span><strong class="text-danger">{{ number_format($balance, 2) }}</strong></div>
                <hr>
                @if($data->approver)
                    <small class="text-muted d-block">{{ $data->AppStatus }} by</small>
                    <strong>{{ $data->approver->name }}</strong>
                    <small class="d-block text-muted">{{ $data->approved_at ? \Carbon\Carbon::parse($data->approved_at)->format('d M Y H:i') : '' }}</small>
                    @if($data->descriptions)
                        <small class="d-block mt-1"><em>"{{ $data->descriptions }}"</em></small>
                    @endif
                @else
                    <small class="text-muted">Awaiting approval decision.</small>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ITEMS --}}
<div class="arbif-card mb-4">
    <div class="arbif-card-body">
        <h6 class="fw-bold mb-3"><i class="fas fa-list me-1"></i> Expense Items</h6>
        <div class="arbif-table-wrap">
            <table class="arbif-table">
                <thead>
                    <tr>
                        <th>#</th><th>Item</th><th>Product</th><th>Supplier</th>
                        <th class="text-end">Qty</th><th class="text-end">Unit Cost</th><th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data->items as $i => $item)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $item->item_name }}</td>
                        <td>{{ $item->product->product_name ?? '-' }}</td>
                        <td>{{ $item->supplier_name ?? '-' }}</td>
                        <td class="text-end">{{ rtrim(rtrim(number_format($item->quantity, 3), '0'), '.') }}</td>
                        <td class="text-end">{{ number_format($item->unit_cost, 2) }}</td>
                        <td class="text-end">{{ number_format($item->total_cost, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center">No items</td></tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6" class="text-end fw-bold">GRAND TOTAL</td>
                        <td class="text-end fw-bold">{{ $data->currency }} {{ number_format($data->total_amount, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

{{-- PAYMENTS --}}
<div class="arbif-card">
    <div class="arbif-card-body">
        <h6 class="fw-bold mb-3"><i class="fas fa-money-check-alt me-1"></i> Payment History</h6>
        <div class="arbif-table-wrap">
            <table class="arbif-table">
                <thead>
                    <tr>
                        <th>#</th><th>Payment No.</th><th>Date</th><th>Method</th><th>Reference</th>
                        <th class="text-end">Amount</th><th>Paid By</th><th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data->payments as $i => $pay)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td><span class="arbif-badge arbif-badge-navy">{{ $pay->payment_number }}</span></td>
                        <td>{{ \Carbon\Carbon::parse($pay->payment_date)->format('d M Y H:i') }}</td>
                        <td>{{ $pay->payment_method }}</td>
                        <td>{{ $pay->reference_number ?? '-' }}</td>
                        <td class="text-end">{{ number_format($pay->amount_paid, 2) }}</td>
                        <td>{{ $pay->payer->name ?? 'N/A' }}</td>
                        <td>{{ Str::limit($pay->descriptions, 40) ?: '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center">No payments recorded yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- APPROVE / REJECT MODAL --}}
@if($data->AppStatus === 'Pending')
<div class="modal fade arbif-modal" id="approveModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon"><i class="fas fa-check-circle"></i></div>
                <h5 class="modal-title fw-bold">Approve / Reject Expense</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('approveExpense', encrypt($data->id)) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Decision <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="">-- Select --</option>
                            <option value="Approved">Approve</option>
                            <option value="Rejected">Reject</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Remarks</label>
                        <textarea name="descriptions" rows="3" class="form-control" placeholder="Reason / comments..."></textarea>
                    </div>
                    <div class="modal-footer px-0 pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="arbif-btn-submit">Submit Decision</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

{{-- PAYMENT MODAL --}}
@if($data->AppStatus === 'Approved' && $data->PaymentStatus !== 'Paid')
<div class="modal fade arbif-modal" id="payModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon"><i class="fas fa-money-bill-wave"></i></div>
                <h5 class="modal-title fw-bold">Record Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info py-2">
                    Outstanding balance: <strong>{{ $data->currency }} {{ number_format($balance, 2) }}</strong>
                </div>
                <form method="POST" action="{{ route('processExpensePayment', encrypt($data->id)) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Amount <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0.01" max="{{ $balance }}" name="amount_paid" class="form-control" value="{{ old('amount_paid', $balance) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                        <select name="payment_method" class="form-select" required>
                            <option value="">-- Select --</option>
                            @foreach(['Cash', 'Bank Transfer', 'Mobile Money', 'Cheque'] as $m)
                                <option value="{{ $m }}" {{ old('payment_method') == $m ? 'selected' : '' }}>{{ $m }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reference Number</label>
                        <input type="text" name="reference_number" class="form-control" value="{{ old('reference_number') }}" placeholder="Transaction / cheque no.">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="descriptions" rows="2" class="form-control">{{ old('descriptions') }}</textarea>
                    </div>
                    <div class="modal-footer px-0 pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="arbif-btn-submit">Disburse Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@endsection