@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">#{{ $repaymentSchedule->installment_number }} Installment Details</h1>
        <div>
            <a href="{{ route('schedules.index', $repaymentSchedule->loan) }}" class="btn btn-secondary me-2">
                <i class="fas fa-list"></i> Full Schedule
            </a>
            <a href="{{ route('loans.show', $repaymentSchedule->loan) }}" class="btn btn-info">
                <i class="fas fa-file-invoice"></i> Back to Loan
            </a>
        </div>
    </div>

    {{-- Installment Summary --}}
    <div class="card shadow mb-4">
        <div class="card-header bg-warning text-dark">
            Installment Summary (Due: {{ $repaymentSchedule->due_date->format('Y-m-d') }})
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3"><strong>Status:</strong> <span class="badge bg-{{ ['paid' => 'success', 'partial' => 'warning', 'overdue' => 'danger', 'pending' => 'info'][$repaymentSchedule->status] ?? 'secondary' }}">{{ ucwords($repaymentSchedule->status) }}</span></div>
                <div class="col-md-3"><strong>Loan:</strong> <a href="{{ route('loans.show', $repaymentSchedule->loan) }}">{{ $repaymentSchedule->loan->loan_number }}</a></div>
                <div class="col-md-3"><strong>Client:</strong> {{ $repaymentSchedule->loan->client->first_name ?? 'N/A' }}</div>
                <div class="col-md-3"><strong>Days Late:</strong> {{ $repaymentSchedule->days_late }}</div>
            </div>
            <hr>
            <div class="row text-center fw-bold">
                <div class="col-md-3">Due: {{ number_format($repaymentSchedule->total_due, 2) }}</div>
                <div class="col-md-3 text-success">Paid: {{ number_format($repaymentSchedule->amount_paid, 2) }}</div>
                <div class="col-md-3 text-danger">Remaining: {{ number_format($repaymentSchedule->total_due - $repaymentSchedule->amount_paid, 2) }}</div>
            </div>
        </div>
    </div>
    
    {{-- Payments Applied to this Installment --}}
    <div class="card shadow mb-4">
        <div class="card-header bg-success text-white">
            Payments Applied
        </div>
        <div class="card-body">
            @if ($repaymentSchedule->payments->isEmpty())
                <p class="alert alert-info">No payments have been recorded or applied to this installment yet.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-striped small">
                        <thead>
                            <tr>
                                <th>Payment Ref.</th>
                                <th>Date Paid</th>
                                <th>Method</th>
                                <th class="text-end">Applied Amount</th>
                                <th class="text-end">Principal</th>
                                <th class="text-end">Interest</th>
                                <th class="text-end">Penalty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($repaymentSchedule->payments as $payment)
                                <tr>
                                    <td>{{ $payment->payment_number }}</td>
                                    <td>{{ $payment->payment_date }}</td>
                                    <td>{{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                    <td class="text-end fw-bold">{{ number_format($payment->pivot->amount_applied, 2) }}</td>
                                    <td class="text-end">{{ number_format($payment->pivot->principal_applied, 2) }}</td>
                                    <td class="text-end">{{ number_format($payment->pivot->interest_applied, 2) }}</td>
                                    <td class="text-end">{{ number_format($payment->pivot->penalty_applied, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection