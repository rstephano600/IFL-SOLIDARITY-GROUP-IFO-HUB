@extends('layouts.workingside')

@section('title', 'Payment Details')
@section('page-title', 'Payment Details')

@section('content')

@php
    $exp = $data->expense;
    $balance = $exp ? round((float) $exp->total_amount - (float) $exp->amount_paid, 2) : 0;
    $payClass = ['Paid' => 'success', 'Partially Paid' => 'info text-dark', 'Unpaid' => 'secondary'][$exp->PaymentStatus ?? ''] ?? 'secondary';
@endphp

<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-file-invoice-dollar"></i></div>
        {{ $data->payment_number }}
        @if($data->Status === 'Deleted')
            <span class="badge bg-danger ms-2">Deleted</span>
        @endif
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('expensePayments') }}" class="btn btn-secondary text-white"><i class="fas fa-arrow-left me-1"></i> Payments</a>
        @if($exp)
            <a href="{{ route('viewExpense', encrypt($exp->id)) }}" class="btn btn-primary"><i class="fas fa-receipt me-1"></i> View Expense</a>
        @endif

        @if($data->Status === 'Active')
            @can('view-generally-delete-of-any-item')
                <form action="{{ route('destroyExpensePayment', encrypt($data->id)) }}" method="POST" onsubmit="return confirm('Delete this payment? The expense balance will be updated.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger"><i class="fas fa-trash me-1"></i> Delete</button>
                </form>
            @endcan
        @else
            <form action="{{ route('restoreExpensePayment', encrypt($data->id)) }}" method="POST" onsubmit="return confirm('Restore this payment?');">
                @csrf
                <button type="submit" class="btn btn-success"><i class="fas fa-undo me-1"></i> Restore</button>
            </form>
        @endif
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="arbif-card h-100">
            <div class="arbif-card-body">
                <h6 class="fw-bold mb-3"><i class="fas fa-info-circle me-1"></i> Payment Information</h6>
                <div class="row g-3">
                    <div class="col-md-6"><small class="text-muted d-block">Amount Paid</small><strong class="fs-5 text-success">{{ $exp->currency ?? '' }} {{ number_format($data->amount_paid, 2) }}</strong></div>
                    <div class="col-md-6"><small class="text-muted d-block">Payment Date</small><strong>{{ \Carbon\Carbon::parse($data->payment_date)->format('d M Y H:i') }}</strong></div>
                    <div class="col-md-6"><small class="text-muted d-block">Method</small><strong>{{ $data->payment_method }}</strong></div>
                    <div class="col-md-6"><small class="text-muted d-block">Reference Number</small><strong>{{ $data->reference_number ?? '-' }}</strong></div>
                    <div class="col-md-6"><small class="text-muted d-block">Paid By</small><strong>{{ $data->payer->name ?? 'N/A' }}</strong></div>
                    <div class="col-md-6"><small class="text-muted d-block">Branch</small><strong>{{ $data->branch->branch_name ?? 'N/A' }}</strong></div>
                    <div class="col-md-6"><small class="text-muted d-block">Auditing Status</small><span class="badge bg-secondary">{{ $data->AuditingStatus }}</span></div>
                    <div class="col-md-6"><small class="text-muted d-block">Report Status</small><span class="badge bg-secondary">{{ $data->ReportStatus }}</span></div>
                    <div class="col-12"><small class="text-muted d-block">Notes</small>{{ $data->descriptions ?: 'N/A' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="arbif-card h-100">
            <div class="arbif-card-body">
                <h6 class="fw-bold mb-3"><i class="fas fa-receipt me-1"></i> Related Expense</h6>
                @if($exp)
                    <div class="mb-2"><span class="arbif-badge arbif-badge-navy">{{ $exp->expense_number }}</span></div>
                    <strong>{{ $exp->expense_title }}</strong>
                    <small class="d-block text-muted mb-3">{{ $exp->category->name ?? 'N/A' }} - {{ $exp->branch->branch_name ?? 'N/A' }}</small>
                    <div class="d-flex justify-content-between"><span>Total</span><strong>{{ $exp->currency }} {{ number_format($exp->total_amount, 2) }}</strong></div>
                    <div class="d-flex justify-content-between"><span>Paid to date</span><strong class="text-success">{{ number_format($exp->amount_paid, 2) }}</strong></div>
                    <div class="d-flex justify-content-between"><span>Balance</span><strong class="text-danger">{{ number_format($balance, 2) }}</strong></div>
                    <div class="d-flex justify-content-between mt-2"><span>Payment Status</span><span class="badge bg-{{ $payClass }}">{{ $exp->PaymentStatus }}</span></div>
                @else
                    <small class="text-muted">Expense not found.</small>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection