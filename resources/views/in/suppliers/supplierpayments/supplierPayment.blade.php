@extends('layouts.workingside')

@section('title', 'Supplier Payments')
@section('page-title', 'Supplier Payments')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-money-check-dollar"></i>
        </div>
        Supplier Payments History
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('deletedsupplierPayment') }}" class="btn btn-outline-danger d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-trash-alt me-2"></i> Deleted Payments
        </a>
        <a href="{{ route('unPaidSupplierInvoices') }}" class="arbif-btn-submit text-decoration-none">
            <i class="fas fa-hand-holding-dollar me-1"></i> Pay Pending Invoices
        </a>
    </div>
</div>

{{-- FILTERS --}}
<div class="arbif-card mb-4">
    <div class="arbif-card-body p-3">
        <form method="GET" action="{{ route('supplierPayment') }}" class="row g-2 align-items-center">
            <div class="col-md-3">
                <select name="payment_status" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Payment Status --</option>
                    <option value="Completed" {{ request('payment_status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                    <option value="Pending" {{ request('payment_status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Cancelled" {{ request('payment_status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div class="col-md-3">
                <select name="supplier_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- All Suppliers --</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->SupplierName }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}" placeholder="From Date">
            </div>

            <div class="col-md-2">
                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}" placeholder="To Date">
            </div>

            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
                @if(request()->anyFilled(['payment_status', 'supplier_id', 'from_date', 'to_date']))
                    <a href="{{ route('supplierPayment') }}" class="btn btn-secondary text-white">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="supplierPaymentTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Payment Ref</th>
                        <th>Supplier</th>
                        <th>Invoice Ref</th>
                        <th>Payment Date</th>
                        <th>Method</th>
                        <th>Amount Paid</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $data->firstItem() + $index }}</td>
                        <td>
                            <a href="{{ route('viewsupplierPayment', encrypt($item->id)) }}" class="fw-bold text-decoration-none">
                                {{ $item->payment_no }}
                            </a>
                        </td>
                        <td><strong>{{ $item->supplier->SupplierName ?? 'N/A' }}</strong></td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ $item->supplierInvoice->invoice_no ?? 'N/A' }}
                            </span>
                        </td>
                        <td>{{ $item->payment_date ? $item->payment_date->format('d M, Y') : 'N/A' }}</td>
                        <td>{{ $item->payment_method }}</td>
                        <td class="fw-bold text-success">{{ number_format($item->payment_amount, 2) }} {{ $item->currency_code }}</td>
                        <td>
                            <span class="badge bg-success">{{ $item->payment_status }}</span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('viewsupplierPayment', encrypt($item->id)) }}" class="arbif-btn-view">
                                    <i class="fas fa-eye"></i> Receipt
                                </a>

                                @can('view-generally-delete-of-any-item')
                                    <a href="{{ route('destroysupplierPayment', encrypt($item->id)) }}" class="arbif-btn-delete" onclick="return confirm('Are you sure you want to soft delete this payment record?')">
                                        <i class="fas fa-trash"></i> Del
                                    </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">No Supplier Payment Records Found</td>
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