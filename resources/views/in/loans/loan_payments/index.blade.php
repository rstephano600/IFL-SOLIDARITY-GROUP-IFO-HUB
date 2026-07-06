@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Loan Payments</h2>
        <a href="{{ route('loan_payments.create') }}" class="btn btn-primary">Add Payment</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped align-middle">
        <thead>
            <tr>
                <th>#</th>
                <th>Receipt</th>
                <th>Client</th>
                <th>Loan</th>
                <th>Group Centre</th>
                <th>Amount</th>
                <th>Payment Date</th>
                <th>Method</th>
                <th>Status</th>
                <th width="120">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($payments as $payment)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $payment->receipt_number }}</td>
                    <td>{{ $payment->client->first_name ?? '' }} {{ $payment->client->last_name ?? '' }}</td>
                    <td>{{ $payment->loan->loan_number ?? '' }}</td>
                    <td>{{ $payment->groupCentre->name ?? '-' }}</td>
                    <td>{{ number_format($payment->amount, 2) }}</td>
                    <td>{{ $payment->payment_date }}</td>
                    <td>{{ ucfirst($payment->payment_method ?? '-') }}</td>
                    <td>
                        <span class="badge bg-{{ $payment->status == 'confirmed' ? 'success' : ($payment->status == 'pending' ? 'warning' : 'danger') }}">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('loan_payments.show', $payment->id) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('loan_payments.edit', $payment->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('loan_payments.destroy', $payment->id) }}" method="POST" style="display:inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this payment?')">Del</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="10" class="text-center">No payments recorded yet.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $payments->links() }}
</div>
@endsection
