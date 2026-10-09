@extends('layouts.workingside')

@section('title', 'Pending Supplier Invoices to Pay')
@section('page-title', 'Invoices Awaiting Payment')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-hand-holding-dollar"></i>
        </div>
        Verified Invoices Awaiting Payment
    </h3>

    <div>
        <a href="{{ route('supplierPayment') }}" class="btn btn-secondary px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to Payments
        </a>
    </div>
</div>

<div class="arbif-card mb-4">
    <div class="arbif-card-body p-3">
        <form method="GET" action="{{ route('unPaidSupplierInvoices') }}" class="row g-2">
            <div class="col-md-9">
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search by Sys Inv No, Supplier Inv No, or Supplier Name...">
            </div>
            <div class="col-md-3 d-flex gap-1">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Search</button>
                @if(request()->filled('search'))
                    <a href="{{ route('unPaidSupplierInvoices') }}" class="btn btn-secondary text-white">Reset</a>
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
                        <th>Sys Inv #</th>
                        <th>Supplier Inv #</th>
                        <th>Supplier</th>
                        <th>Due Date</th>
                        <th>Total Amount</th>
                        <th>Paid Amount</th>
                        <th>Outstanding Balance</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $inv)
                    <tr>
                        <td>{{ $data->firstItem() + $index }}</td>
                        <td><strong class="text-primary">{{ $inv->invoice_no }}</strong></td>
                        <td><strong>{{ $inv->supplier_invoice_no }}</strong></td>
                        <td>{{ $inv->supplier->SupplierName ?? 'N/A' }}</td>
                        <td>{{ $inv->due_date ? $inv->due_date->format('d M, Y') : 'N/A' }}</td>
                        <td class="fw-bold">{{ number_format($inv->total_amount, 2) }} {{ $inv->currency_code }}</td>
                        <td class="text-success">{{ number_format($inv->paid_amount, 2) }} {{ $inv->currency_code }}</td>
                        <td class="fw-bold text-danger">{{ number_format($inv->outstanding_amount, 2) }} {{ $inv->currency_code }}</td>
                        <td>
                            <a href="{{ route('createsupplierPayment', encrypt($inv->id)) }}" class="btn btn-sm btn-success fw-bold">
                                <i class="fas fa-dollar-sign me-1"></i> Process Payment
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">No outstanding verified invoices found!</td>
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