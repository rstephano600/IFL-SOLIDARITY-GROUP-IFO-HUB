@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Repayment Schedule for Loan #{{ $loan->loan_number }}</h3>

    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <table class="table table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Due Day</th>
                        <th>Principal Due</th>
                        <th>Interest Due</th>
                        <th>Status</th>
                        <th>Days Left</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schedules as $schedule)
                        <tr>
                            <td>{{ $schedule->installment_number }}</td>
                            <td>Day {{ $schedule->due_day_number }}</td>
                            <td>{{ number_format($schedule->principal_due, 2) }}</td>
                            <td>{{ number_format($schedule->interest_due, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $schedule->status === 'paid' ? 'success' : ($schedule->status === 'overdue' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($schedule->status) }}
                                </span>
                            </td>
                            <td>{{ $schedule->days_left }}</td>
                            <td>
                                @if($schedule->status !== 'paid')
                                    <form action="{{ route('repayments.pay', $schedule->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-sm btn-primary">Pay</button>
                                    </form>
                                @else
                                    <span class="text-success fw-bold">✔ Paid</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3 text-end">
        <a href="{{ route('loan-approvals.index') }}" class="btn btn-secondary">Back</a>
    </div>
</div>
@endsection
