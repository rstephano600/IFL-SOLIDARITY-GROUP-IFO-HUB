@extends('layouts.app')
@section('title', 'Weekly Allowance Details')

@section('content')
<div class="container py-4">
    <div class="card shadow border-0">
        <div class="card-header bg-info text-white">
            <h4>Weekly Allowance Details</h4>
        </div>

        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-md-4">Employee</dt>
                <dd class="col-md-8">{{ $employeeWeeklyAllowance->employee->first_name }} {{ $employeeWeeklyAllowance->employee->last_name }}</dd>

                <dt class="col-md-4">Allowance Type</dt>
                <dd class="col-md-8">{{ ucfirst($employeeWeeklyAllowance->allowance_type) }}</dd>

                <dt class="col-md-4">Amount</dt>
                <dd class="col-md-8 fw-bold text-success">{{ number_format($employeeWeeklyAllowance->amount, 2) }} {{ $employeeWeeklyAllowance->currency }}</dd>

                <dt class="col-md-4">Week Period</dt>
                <dd class="col-md-8">{{ $employeeWeeklyAllowance->week_start }} — {{ $employeeWeeklyAllowance->week_end }}</dd>

                <dt class="col-md-4">Status</dt>
                <dd class="col-md-8">
                    <span class="badge bg-{{ $employeeWeeklyAllowance->status == 'paid' ? 'success' : ($employeeWeeklyAllowance->status == 'pending' ? 'warning' : 'danger') }}">
                        {{ ucfirst($employeeWeeklyAllowance->status) }}
                    </span>
                </dd>

                <dt class="col-md-4">Payment Date</dt>
                <dd class="col-md-8">{{ $employeeWeeklyAllowance->payment_date ?? 'Not paid yet' }}</dd>

                <dt class="col-md-4">Description</dt>
                <dd class="col-md-8">{{ $employeeWeeklyAllowance->description ?? '—' }}</dd>

                <dt class="col-md-4">Attachment</dt>
                <dd class="col-md-8">
                    @if($employeeWeeklyAllowance->attachment)
                        <a href="{{ asset('storage/'.$employeeWeeklyAllowance->attachment) }}" target="_blank">View File</a>
                    @else
                        None
                    @endif
                </dd>

                <dt class="col-md-4">Created By</dt>
                <dd class="col-md-8">{{ $employeeWeeklyAllowance->creator->name ?? 'System' }}</dd>
            </dl>

            <div class="text-end mt-4">
                <a href="{{ route('employee_weekly_allowances.edit', $employeeWeeklyAllowance->id) }}" class="btn btn-warning text-white">Edit</a>
                <a href="{{ route('employee_weekly_allowances.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>
    </div>
</div>
@endsection
