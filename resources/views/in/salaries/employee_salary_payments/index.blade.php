@extends('layouts.app')
@section('title', 'Employee Salary Payments')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Employee Salary Payments</h4>
        </div>
        <div class="card-body">

            <form method="GET" class="row g-3 mb-3">
                <div class="col-md-4">
                    <select name="employee_id" class="form-control">
                        <option value="">-- Filter by Employee --</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->first_name }} {{ $emp->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-3">
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-outline-primary">Filter</button>
                </div>
            </form>

            <a href="{{ route('employee_salary_payments.create') }}" class="btn btn-success mb-3">+ Add Payment</a>

            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Attachment</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $p)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $p->employee?->first_name }} {{ $p->employee?->last_name }}</td>
                            <td>{{ number_format($p->amount_paid, 2) }} {{ $p->currency }}</td>
                            <td>{{ $p->payment_date }}</td>
                            <td>{{ $p->payment_method }}</td>
                            <td><span class="badge bg-{{ $p->status === 'confirmed' ? 'success' : ($p->status === 'pending' ? 'warning' : 'danger') }}">{{ ucfirst($p->status) }}</span></td>
                            <td>
                                @if($p->attachment)
                                    <a href="{{ asset('storage/'.$p->attachment) }}" target="_blank">View</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('employee_salary_payments.show', $p->id) }}" class="btn btn-sm btn-info">View</a>
                                <a href="{{ route('employee_salary_payments.edit', $p->id) }}" class="btn btn-sm btn-warning text-white">Edit</a>
                                <form method="POST" action="{{ route('employee_salary_payments.destroy', $p->id) }}" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this payment?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted">No payments found</td></tr>
                    @endforelse
                </tbody>
            </table>

            {{ $payments->links() }}
        </div>
    </div>
</div>
@endsection
