@extends('layouts.workingside')

@section('title', 'Sales Transactions')
@section('page-title', 'Sales Transactions')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-receipt"></i>
        </div>
        Sales Transactions
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('deletedSales') }}" class="btn btn-outline-danger d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-trash-alt me-2"></i> Deleted Sales
        </a>
        <a href="{{ route('createSale') }}" class="arbif-btn-submit text-decoration-none">
            <i class="fas fa-plus me-1"></i> New Sale
        </a>
    </div>
</div>

{{-- FILTER BAR --}}
<div class="arbif-card mb-4">
    <div class="arbif-card-body p-3">
        <form method="GET" action="{{ route('sales') }}" class="row g-2 align-items-center">
            <div class="col-md-3">
                <select name="sales_type_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- All Sales Types --</option>
                    @foreach($salesTypes as $type)
                        <option value="{{ $type->id }}" {{ request('sales_type_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->type_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <select name="customer_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- All Customers --</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                            {{ $customer->customer_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <select name="payment_status" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Payment Status --</option>
                    <option value="UNPAID" {{ request('payment_status') == 'UNPAID' ? 'selected' : '' }}>UNPAID</option>
                    <option value="PARTIAL" {{ request('payment_status') == 'PARTIAL' ? 'selected' : '' }}>PARTIAL</option>
                    <option value="PAID" {{ request('payment_status') == 'PAID' ? 'selected' : '' }}>PAID</option>
                </select>
            </div>

            <div class="col-md-2">
                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}" placeholder="From Date">
            </div>

            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter me-1"></i> Filter</button>
                @if(request()->anyFilled(['sales_type_id', 'customer_id', 'payment_status', 'from_date', 'to_date']))
                    <a href="{{ route('sales') }}" class="btn btn-secondary text-white">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="salesTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Sale No.</th>
                        <th>Date</th>
                        <th>Sales Type</th>
                        <th>Customer</th>
                        <th>Total Amount (TZS)</th>
                        <th>Paid (TZS)</th>
                        <th>Balance (TZS)</th>
                        <th>Payment Status</th>
                        <th>Actions</th>
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
                        <td>{{ $item->sale_date ? \Carbon\Carbon::parse($item->sale_date)->format('d M, Y') : 'N/A' }}</td>
                        <td>{{ $item->salesType->type_name ?? 'N/A' }}</td>
                        <td>{{ $item->customer->customer_name ?? 'Walk-In Customer' }}</td>
                        <td class="fw-bold">{{ number_format($item->total_amount, 2) }}</td>
                        <td class="text-success">{{ number_format($item->paid_amount, 2) }}</td>
                        <td class="text-danger fw-bold">{{ number_format($item->balance_amount, 2) }}</td>
                        <td>
                            @php
                                $statusBadges = [
                                    'UNPAID'  => 'bg-danger',
                                    'PARTIAL' => 'bg-warning text-dark',
                                    'PAID'    => 'bg-success',
                                ];
                            @endphp
                            <span class="badge {{ $statusBadges[$item->payment_status] ?? 'bg-secondary' }}">
                                {{ $item->payment_status }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('viewSale', encrypt($item->id)) }}" class="arbif-btn-view">
                                    <i class="fas fa-eye"></i> View
                                </a>

                                @if($item->balance_amount > 0)
                                    <button type="button" class="btn btn-sm btn-outline-success px-2 py-1" data-bs-toggle="modal" data-bs-target="#paymentModal{{ $item->id }}">
                                        <i class="fas fa-money-bill-wave me-1"></i> Pay
                                    </button>
                                @endif

                                @can('view-generally-delete-of-any-item')
                                    @if($item->paid_amount == 0)
                                        <a href="{{ route('destroySale', encrypt($item->id)) }}" class="arbif-btn-delete" onclick="return confirm('Are you sure you want to delete this sale record?')">
                                            <i class="fas fa-trash"></i> Del
                                        </a>
                                    @endif
                                @endcan
                            </div>
                        </td>
                    </tr>

                    {{-- RECORD PAYMENT MODAL --}}
                    @if($item->balance_amount > 0)
                    <div class="modal fade arbif-modal" id="paymentModal{{ $item->id }}" tabindex="-1">
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
                                            <input type="number" step="0.01" name="amount" class="form-control" max="{{ $item->balance_amount }}" value="{{ $item->balance_amount }}" required>
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

                    @empty
                    <tr>
                        <td colspan="10" class="text-center">No Sales Transactions Found</td>
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