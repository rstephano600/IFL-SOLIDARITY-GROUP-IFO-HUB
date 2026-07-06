@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Payment Details</h2>
    <a href="{{ route('loan_payments.index') }}" class="btn btn-secondary mb-3">Back</a>

    <div class="card">
        <div class="card-body">
            <h5>Receipt: <strong>{{ $loanPayment->receipt_number }}</strong></h5>
            <p><strong>Client:</strong> {{ $loanPayment->client->first_name }} {{ $loanPayment->client->last_name }}</p>
            <p><strong>Loan:</strong> {{ $loanPayment->loan->loan_number }}</p>
            <p><strong>Group Centre:</strong> {{ $loanPayment->groupCentre->name ?? '-' }}</p>
            <p><strong>Amount Paid:</strong> {{ number_format($loanPayment->amount, 2) }}</p>
            <p><strong>Payment Date:</strong> {{ $loanPayment->payment_date }}</p>
            <p><strong>Payment Method:</strong> {{ ucfirst($loanPayment->payment_method ?? '-') }}</p>
            <p><strong>Reference:</strong> {{ $loanPayment->reference_number ?? '-' }}</p>
            <p><strong>Remarks:</strong> {{ $loanPayment->remarks ?? '-' }}</p>
            <p><strong>Status:</strong> 
                <span class="badge bg-{{ $loanPayment->status == 'confirmed' ? 'success' : ($loanPayment->status == 'pending' ? 'warning' : 'danger') }}">
                    {{ ucfirst($loanPayment->status) }}
                </span>
            </p>
            <p><strong>Recorded By:</strong> User #{{ $loanPayment->created_by }}</p>
        </div>
    </div>
</div>
@endsection
