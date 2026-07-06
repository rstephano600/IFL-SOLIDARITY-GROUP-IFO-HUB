@extends('layouts.app')
@section('title', 'Weekly Allowances')

@section('content')
<div class="container py-4">
    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white">
            <h4>Weekly Allowances</h4>
        </div>

        <div class="card-body">
            <form method="GET" class="row g-2 mb-3">
                <div class="col-md-3">
                    <input type="text" name="employee" class="form-control" placeholder="Search employee..." value="{{ request('employee') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
                        <option value="paid" {{ request('status')=='paid'?'selected':'' }}>Paid</option>
                        <option value="cancelled" {{ request('status')=='cancelled'?'selected':'' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-3">
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>
                <div class="col-md-1 text-end">
                    <button class="btn btn-primary w-100">Filter</button>
                </div>
            </form>

            <div class="d-flex justify-content-between align-items-center mb-2">
                <a href="{{ route('employee_weekly_allowances.create') }}" class="btn btn-success">+ Add New</a>
                <h5>Total: <span class="text-success fw-bold">{{ number_format($total, 2) }} TZS</span></h5>
            </div>

            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Week</th>
                        <th>Status</th>
                        <th>Payment Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($allowances as $allowance)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $allowance->employee->first_name }} {{ $allowance->employee->last_name }}</td>
                            <td>{{ ucfirst($allowance->allowance_type) }}</td>
                            <td>{{ number_format($allowance->amount, 2) }}</td>
                            <td>{{ $allowance->week_start }} - {{ $allowance->week_end }}</td>
                            <td>
                                <span class="badge bg-{{ $allowance->status == 'paid' ? 'success' : ($allowance->status == 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($allowance->status) }}
                                </span>
                            </td>
                            <td>{{ $allowance->payment_date ?? '-' }}</td>
                            <td>
                                <a href="{{ route('employee_weekly_allowances.show', $allowance->id) }}" class="btn btn-info btn-sm">View</a>
                                <a href="{{ route('employee_weekly_allowances.edit', $allowance->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted">No records found.</td></tr>
                    @endforelse
                </tbody>
            </table>

            {{ $allowances->links() }}
        </div>
    </div>
</div>
@endsection
