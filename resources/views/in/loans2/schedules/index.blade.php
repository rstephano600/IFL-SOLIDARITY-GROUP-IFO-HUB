@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">📅 Repayment Schedule for Loan: {{ $loan->loan_number }}</h1>
        <a href="{{ route('loans.show', $loan) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Loan Details
        </a>
    </div>

    @if ($schedule->isEmpty())
        <div class="alert alert-info">
            The amortization schedule has not yet been generated for this loan.
            (This usually happens right after loan approval.)
        </div>
    @else
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-calculator"></i> Amortization Plan ({{ $schedule->count() }} Installments)
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover small">
                        <thead>
                            <tr class="text-center">
                                <th>#</th>
                                <th>Due Date</th>
                                <th>Principal Due</th>
                                <th>Interest Due</th>
                                <th>Total Due</th>
                                <th>Amount Paid</th>
                                <th>Outstanding Principal</th>
                                <th>Status</th>
                                <th>Days Late</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($schedule as $item)
                                @php
                                    $statusClass = [
                                        'paid' => 'table-success',
                                        'partial' => 'table-warning',
                                        'overdue' => 'table-danger',
                                        'pending' => '',
                                    ][$item->status] ?? '';
                                @endphp
                                <tr class="{{ $statusClass }}">
                                    <td class="text-center">{{ $item->installment_number }}</td>
                                    <td>{{ $item->due_date->format('Y-m-d') }}</td>
                                    <td class="text-end">{{ number_format($item->principal_due, 2) }}</td>
                                    <td class="text-end">{{ number_format($item->interest_due, 2) }}</td>
                                    <td class="text-end fw-bold">{{ number_format($item->total_due, 2) }}</td>
                                    <td class="text-end text-success">{{ number_format($item->amount_paid, 2) }}</td>
                                    <td class="text-end">{{ number_format($item->principal_outstanding, 2) }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('schedules.show', $item) }}" class="badge bg-{{ ['paid' => 'success', 'partial' => 'warning', 'overdue' => 'danger', 'pending' => 'info'][$item->status] ?? 'secondary' }}">
                                            {{ ucwords($item->status) }}
                                        </a>
                                    </td>
                                    <td class="text-center">{{ $item->days_late }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-dark">
                                <td colspan="2" class="text-end fw-bold">TOTALS:</td>
                                <td class="text-end fw-bold">{{ number_format($schedule->sum('principal_due'), 2) }}</td>
                                <td class="text-end fw-bold">{{ number_format($schedule->sum('interest_due'), 2) }}</td>
                                <td class="text-end fw-bold">{{ number_format($schedule->sum('total_due'), 2) }}</td>
                                <td class="text-end fw-bold">{{ number_format($schedule->sum('amount_paid'), 2) }}</td>
                                <td colspan="3"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection