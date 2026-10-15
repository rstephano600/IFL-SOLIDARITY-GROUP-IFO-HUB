@extends('layouts.workingside')

@section('title', 'Sales Payments History')
@section('page-title', 'Sales Payments History')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-file-invoice-dollar"></i>
        </div>
        Sales Payments Log
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('deletedSalePayments') }}" class="btn btn-outline-danger d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-trash-alt me-2"></i> Deleted Payments
        </a>
        <a href="{{ route('pendingSalesPayments') }}" class="arbif-btn-submit text-decoration-none">
            <i class="fas fa-clock me-1"></i> Pay Pending Sales
        </a>
    </div>
</div>

{{-- FILTER BAR --}}
<div class="arbif-card mb-4">
    <div class="arbif-card-body p-3">
        <form method="GET" action="{{ route('salesPayments') }}" class="row g-2 align-items-center">
            <div class="col-md-4">
                <select name="customer_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- All Customers --</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                            {{ $customer->customer_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}" placeholder="From Date">
            </div>

            <div class="col-md-3">
                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}" placeholder="To Date">
            </div>

            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter me-1"></i> Filter</button>
                @if(request()->anyFilled(['customer_id', 'from_date', 'to_date']))
                    <a href="{{ route('salesPayments') }}" class="btn btn-secondary text-white">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="paymentsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Payment No.</th>
                        <th>Sale No.</th>
                        <th>Customer</th>
                        <th>Payment Date</th>
                        <th>Amount Paid (TZS)</th>
                        <th>Transaction Ref</th>
                        <th>Received By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $data->firstItem() + $index }}</td>
                        <td>
                            <a href="{{ route('viewSalePayment', encrypt($item->id)) }}" class="fw-bold text-decoration-none">
                                {{ $item->payment_no }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('viewSale', encrypt($item->sale_id)) }}" class="fw-bold text-primary text-decoration-none">
                                {{ $item->sale->sale_no ?? 'N/A' }}
                            </a>
                        </td>
                        <td>{{ $item->sale->customer->customer_name ?? 'Walk-In Customer' }}</td>
                        <td>{{ $item->payment_date ? \Carbon\Carbon::parse($item->payment_date)->format('d M, Y') : 'N/A' }}</td>
                        <td class="fw-bold text-success fs-6">{{ number_format($item->amount, 2) }}</td>
                        <td>{{ $item->reference_transaction ?? 'Cash' }}</td>
                        <td>{{ $item->receiver->name ?? 'System' }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('viewSalePayment', encrypt($item->id)) }}" class="arbif-btn-view">
                                    <i class="fas fa-eye"></i> View
                                </a>

                                @can('view-generally-delete-of-any-item')
                                    <a href="{{ route('destroySalePayment', encrypt($item->id)) }}" class="arbif-btn-delete" onclick="return confirm('Are you sure you want to soft delete this payment record?')">
                                        <i class="fas fa-trash"></i> Del
                                    </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">No Payment Records Found</td>
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