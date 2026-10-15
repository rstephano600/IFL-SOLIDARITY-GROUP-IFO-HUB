@extends('layouts.workingside')

@section('title', 'Create Monthly Invoice')
@section('page-title', 'Create Monthly Invoice')

@section('content')

<div class="arbif-page-header mb-4">
    <h3>
        <div class="page-icon">
            <i class="fas fa-file-invoice"></i>
        </div>
        Generate Invoice for {{ $customer->customer_name }}
    </h3>

    <div>
        <a href="{{ route('viewsaleCustomer', encrypt($customer->id)) }}" class="btn btn-secondary px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body p-4">
        <form method="POST" action="{{ route('storeCustomerInvoice', encrypt($customer->id)) }}">
            @csrf

            <h6 class="fw-bold text-primary mb-3"><i class="fas fa-info-circle me-1"></i> Invoice Period & Details</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Billing Date <span class="text-danger">*</span></label>
                    <input type="date" name="bill_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Due Date <span class="text-danger">*</span></label>
                    <input type="date" name="due_date" class="form-control" value="{{ date('Y-m-d', strtotime('+30 days')) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Period</label>
                    <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($selectedMonth . '-01')->format('F Y') }}" readonly>
                </div>

                <div class="col-12">
                    <label class="form-label">Invoice Notes</label>
                    <textarea name="notes" class="form-control" rows="2" placeholder="Notes or instructions for customer..."></textarea>
                </div>
            </div>

            <h6 class="fw-bold text-primary mb-3"><i class="fas fa-list me-1"></i> Select Sales to Billed</h6>
            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;" class="text-center">Select</th>
                            <th>Sale No.</th>
                            <th>Date</th>
                            <th>Total (TZS)</th>
                            <th>Paid (TZS)</th>
                            <th>Balance (TZS)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($unbilledSales as $sale)
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" name="sale_ids[]" value="{{ $sale->id }}" class="form-check-input" checked>
                            </td>
                            <td><strong>{{ $sale->sale_no }}</strong></td>
                            <td>{{ \Carbon\Carbon::parse($sale->sale_date)->format('d M, Y') }}</td>
                            <td>{{ number_format($sale->total_amount, 2) }}</td>
                            <td class="text-success">{{ number_format($sale->paid_amount, 2) }}</td>
                            <td class="fw-bold text-danger">{{ number_format($sale->balance_amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No pending sales available for billing this month.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($unbilledSales->count() > 0)
                <div class="d-flex justify-content-end gap-2">
                    <button type="submit" class="arbif-btn-submit">Generate & Post Invoice</button>
                </div>
            @endif
        </form>
    </div>
</div>

@endsection