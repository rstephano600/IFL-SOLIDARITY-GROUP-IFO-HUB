@extends('layouts.workingside')

@section('title', 'Pending Sales Payments')
@section('page-title', 'Pending Sales Payments')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-hand-holding-usd"></i>
        </div>
        Pending & Partial Sales Balances
    </h3>

    <div>
        <a href="{{ route('salesPayments') }}" class="btn btn-secondary px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> All Payments History
        </a>
    </div>
</div>

{{-- SEARCH & FILTERS --}}
<div class="arbif-card mb-4">
    <div class="arbif-card-body p-3">
        <form method="GET" action="{{ route('pendingSalesPayments') }}" class="row g-2">
            <div class="col-md-7">
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search by Sale No, Reference, or Customer Name...">
            </div>
            <div class="col-md-3">
                <select name="payment_status" class="form-select" onchange="this.form.submit()">
                    <option value="">-- All Pending Statuses --</option>
                    <option value="UNPAID" {{ request('payment_status') == 'UNPAID' ? 'selected' : '' }}>UNPAID</option>
                    <option value="PARTIAL" {{ request('payment_status') == 'PARTIAL' ? 'selected' : '' }}>PARTIAL</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Filter</button>
                @if(request()->anyFilled(['search', 'payment_status']))
                    <a href="{{ route('pendingSalesPayments') }}" class="btn btn-secondary text-white">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Sale No.</th>
                        <th>Customer</th>
                        <th>Sale Date</th>
                        <th>Total (TZS)</th>
                        <th>Paid (TZS)</th>
                        <th>Balance (TZS)</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $data->firstItem() + $index }}</td>
                        <td>
                            <a href="{{ route('viewSale', encrypt($item->id)) }}" class="fw-bold text-decoration-none">
                                {{ $item->sale_no }}
                            </a>
                        </td>
                        <td><strong>{{ $item->customer->customer_name ?? 'Walk-In Customer' }}</strong></td>
                        <td>{{ $item->sale_date ? \Carbon\Carbon::parse($item->sale_date)->format('d M, Y') : 'N/A' }}</td>
                        <td class="fw-bold">{{ number_format($item->total_amount, 2) }}</td>
                        <td class="text-success">{{ number_format($item->paid_amount, 2) }}</td>
                        <td class="fw-bold text-danger fs-6">{{ number_format($item->balance_amount, 2) }}</td>
                        <td>
                            <span class="badge {{ $item->payment_status == 'UNPAID' ? 'bg-danger' : 'bg-warning text-dark' }}">
                                {{ $item->payment_status }}
                            </span>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-success fw-bold px-3" data-bs-toggle="modal" data-bs-target="#payModal{{ $item->id }}">
                                <i class="fas fa-money-bill-wave me-1"></i> Pay
                            </button>
                        </td>
                    </tr>

                    {{-- INLINE QUICK PAYMENT MODAL --}}
                    <div class="modal fade arbif-modal" id="payModal{{ $item->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <div class="modal-icon">
                                        <i class="fas fa-wallet"></i>
                                    </div>
                                    <h5 class="modal-title">Record Payment ({{ $item->sale_no }})</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="{{ route('storeSalePayment', encrypt($item->id)) }}">
                                        @csrf

                                        <div class="mb-3">
                                            <label class="form-label">Outstanding Balance</label>
                                            <input type="text" class="form-control fw-bold text-danger fs-6" value="TZS {{ number_format($item->balance_amount, 2) }}" readonly>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Payment Date <span class="text-danger">*</span></label>
                                            <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Payment Amount (TZS) <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" name="amount" class="form-control" max="{{ $item->balance_amount }}" value="{{ $item->balance_amount }}" min="0.01" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Transaction Ref / Reference No.</label>
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
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">No pending or partially paid sales found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $data->links() }}
        </div>
    </div>
</div>

@endsection