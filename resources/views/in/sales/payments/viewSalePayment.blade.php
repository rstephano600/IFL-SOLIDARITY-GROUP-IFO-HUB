@extends('layouts.workingside')

@section('title', 'Payment Receipt Details')
@section('page-title', 'Payment Receipt Details')

@section('content')

<div class="arbif-page-header mb-4">
    <h3>
        <div class="page-icon">
            <i class="fas fa-receipt"></i>
        </div>
        Payment Receipt: {{ $data->payment_no }}
    </h3>

    <div>
        <a href="{{ route('salesPayments') }}" class="btn btn-secondary px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to History
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="arbif-card">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-info-circle me-1"></i> Transaction Breakdown</h6>

                <table class="table table-borderless align-middle">
                    <tr>
                        <th style="width: 30%;" class="text-muted">Payment Number:</th>
                        <td class="fw-bold fs-6">{{ $data->payment_no }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Reference Code:</th>
                        <td><span class="arbif-badge arbif-badge-navy">{{ $data->ReferenceNo }}</span></td>
                    </tr>
                    <tr>
                        <th class="text-muted">Associated Sale:</th>
                        <td>
                            <a href="{{ route('viewSale', encrypt($data->sale_id)) }}" class="fw-bold text-decoration-none">
                                {{ $data->sale->sale_no ?? 'N/A' }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted">Customer Name:</th>
                        <td><strong>{{ $data->sale->customer->customer_name ?? 'Walk-In Customer' }}</strong></td>
                    </tr>
                    <tr>
                        <th class="text-muted">Payment Date:</th>
                        <td>{{ $data->payment_date ? \Carbon\Carbon::parse($data->payment_date)->format('d M, Y H:i') : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Amount Paid:</th>
                        <td class="fw-bold text-success fs-5">TZS {{ number_format($data->amount, 2) }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Transaction Reference:</th>
                        <td>{{ $data->reference_transaction ?? 'Cash' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Received By:</th>
                        <td>{{ $data->receiver->name ?? 'System' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Notes:</th>
                        <td>{{ $data->notes ?? 'No additional notes recorded.' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="arbif-card mb-4">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-wallet me-1"></i> Current Sale Status</h6>

                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Total Sale Bill:</span>
                        <span class="fw-bold">TZS {{ number_format($data->sale->total_amount ?? 0, 2) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Total Paid to Date:</span>
                        <span class="fw-bold text-success">TZS {{ number_format($data->sale->paid_amount ?? 0, 2) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Remaining Balance:</span>
                        <span class="fw-bold text-danger">TZS {{ number_format($data->sale->balance_amount ?? 0, 2) }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection