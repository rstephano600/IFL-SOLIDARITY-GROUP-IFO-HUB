@extends('layouts.workingside')

@section('title', 'Customer Profile')
@section('page-title', 'Customer Profile')

@section('content')

<div class="arbif-page-header mb-4">
    <h3>
        <div class="page-icon">
            <i class="fas fa-user-tag"></i>
        </div>
        Customer: {{ $customer->customer_name }}
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('createCustomerInvoice', ['encryptedCustomerId' => encrypt($customer->id), 'month' => $selectedMonth]) }}" class="arbif-btn-submit text-decoration-none">
            <i class="fas fa-file-invoice me-1"></i> Generate Monthly Invoice
        </a>
        <a href="{{ route('salesCustomers') }}" class="btn btn-secondary px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back
        </a>
    </div>
</div>

{{-- MONTHLY FILTER & SUMMARY CARDS --}}
<div class="row g-3 mb-4">
    <div class="col-md-12">
        <div class="arbif-card p-3">
            <form method="GET" action="{{ route('viewsaleCustomer', encrypt($customer->id)) }}" class="row g-2 align-items-center">
                <div class="col-md-4">
                    <label class="form-label fw-bold mb-0">Select Billing Month:</label>
                    <input type="month" name="month" class="form-control" value="{{ $selectedMonth }}" onchange="this.form.submit()">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary mt-4 w-100"><i class="fas fa-filter me-1"></i> Filter Month</button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-4">
        <div class="arbif-card p-3 bg-light">
            <small class="text-muted d-block fw-bold">Period Total Sales</small>
            <strong class="fs-5 text-primary">TZS {{ number_format($totalSales, 2) }}</strong>
        </div>
    </div>
    <div class="col-md-4">
        <div class="arbif-card p-3 bg-light">
            <small class="text-muted d-block fw-bold">Period Total Paid</small>
            <strong class="fs-5 text-success">TZS {{ number_format($totalPaid, 2) }}</strong>
        </div>
    </div>
    <div class="col-md-4">
        <div class="arbif-card p-3 bg-light border-start border-4 border-danger">
            <small class="text-muted d-block fw-bold">Period Pending Balance</small>
            <strong class="fs-5 text-danger">TZS {{ number_format($totalPending, 2) }}</strong>
        </div>
    </div>
</div>

<div class="arbif-card mb-4">
    <div class="arbif-card-body p-4">
        <h6 class="fw-bold text-primary mb-3"><i class="fas fa-receipt me-1"></i> Sales History for Period ({{ \Carbon\Carbon::parse($selectedMonth . '-01')->format('F Y') }})</h6>

        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Sale No.</th>
                        <th>Date</th>
                        <th>Total Amount (TZS)</th>
                        <th>Paid (TZS)</th>
                        <th>Balance (TZS)</th>
                        <th>Status</th>
                        <th>Invoiced / Not Invoiced</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                    <tr>
                        <td><strong>{{ $sale->sale_no }}</strong></td>
                        <td>{{ \Carbon\Carbon::parse($sale->sale_date)->format('d M, Y') }}</td>
                        <td class="fw-bold">{{ number_format($sale->total_amount, 2) }}</td>
                        <td class="text-success">{{ number_format($sale->paid_amount, 2) }}</td>
                        <td class="text-danger fw-bold">{{ number_format($sale->balance_amount, 2) }}</td>
                        <td><span class="badge {{ $sale->payment_status == 'PAID' ? 'bg-success' : 'bg-danger' }}">{{ $sale->payment_status }}</span></td>
                        <td>
                            @if($sale->is_invoiced)
                                <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Invoiced</span>
                            @else
                                <span class="badge bg-secondary"><i class="fas fa-clock me-1"></i> Not Invoiced</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('viewSale', encrypt($sale->id)) }}" class="btn btn-sm btn-outline-primary">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No sales found for this month.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- CUSTOMER INVOICES --}}
<div class="arbif-card">
    <div class="arbif-card-body p-4">
        <h6 class="fw-bold text-primary mb-3"><i class="fas fa-file-invoice me-1"></i> Generated Invoices</h6>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Bill No.</th>
                        <th>Date</th>
                        <th>Total Amount</th>
                        <th>Paid Amount</th>
                        <th>Balance Amount</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $inv)
                    <tr>
                        <td><strong>{{ $inv->bill_no }}</strong></td>
                        <td>{{ $inv->bill_date ? $inv->bill_date->format('d M, Y') : 'N/A' }}</td>
                        <td class="fw-bold">TZS {{ number_format($inv->total_amount, 2) }}</td>
                        <td class="text-success">TZS {{ number_format($inv->paid_amount, 2) }}</td>
                        <td class="text-danger fw-bold">TZS {{ number_format($inv->balance_amount, 2) }}</td>
                        <td>
                            <a href="{{ route('viewCustomerInvoice', encrypt($inv->id)) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye me-1"></i> View / Pay
                            </a>
                            <a href="{{ route('printCustomerInvoice', encrypt($inv->id)) }}" target="_blank" class="btn btn-sm btn-outline-dark me-1">
                                <i class="fas fa-print me-1"></i> Print
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No invoices generated yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection