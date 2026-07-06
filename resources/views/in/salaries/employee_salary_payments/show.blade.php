@extends('layouts.app')
@section('title', 'View Salary Payment')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-info text-white">
            <h4 class="mb-0"><i class="bi bi-eye me-2"></i> Payment Details</h4>
        </div>

        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th>Employee</th>
                    <td>{{ $employeeSalaryPayment->employee->first_name }} {{ $employeeSalaryPayment->employee->last_name }}</td>
                </tr>
                <tr>
                    <th>Salary Record</th>
                    <td>#{{ $employeeSalaryPayment->employee_salary_id }}</td>
                </tr>
                <tr>
                    <th>Payment Date</th>
                    <td>{{ $employeeSalaryPayment->payment_date }}</td>
                </tr>
                <tr>
                    <th>Amount</th>
                    <td>{{ number_format($employeeSalaryPayment->amount_paid, 2) }} {{ $employeeSalaryPayment->currency }}</td>
                </tr>
                <tr>
                    <th>Method</th>
                    <td>{{ $employeeSalaryPayment->payment_method }}</td>
                </tr>
                <tr>
                    <th>Reference</th>
                    <td>{{ $employeeSalaryPayment->reference_number ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td><span class="badge bg-{{ $employeeSalaryPayment->status == 'confirmed' ? 'success' : ($employeeSalaryPayment->status == 'pending' ? 'warning' : 'danger') }}">{{ ucfirst($employeeSalaryPayment->status) }}</span></td>
                </tr>
                <tr>
                    <th>Notes</th>
                    <td>{{ $employeeSalaryPayment->notes ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Attachment</th>
                    <td>
                        @if($employeeSalaryPayment->attachment)
                            <a href="{{ asset('storage/'.$employeeSalaryPayment->attachment) }}" target="_blank" class="text-primary">View File</a>
                        @else
                            <em>No attachment</em>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Created By</th>
                    <td>{{ $employeeSalaryPayment->creator?->name }}</td>
                </tr>
                <tr>
                    <th>Updated By</th>
                    <td>{{ $employeeSalaryPayment->updater?->name ?? '-' }}</td>
                </tr>
                   <tr>
    <th>Employee Acknowledged</th>
    <td>
        @if($employeeSalaryPayment->employee_acknowledged)
            <span class="badge bg-success">Signed on {{ $employeeSalaryPayment->employee_signed_at }}</span>
            <br>
            @if($employeeSalaryPayment->employee_signature)
                <img src="{{ $employeeSalaryPayment->employee_signature }}" alt="Signature" height="80">
            @endif
        @else
            <span class="badge bg-danger">Not yet signed</span>
        @endif
    </td>
</tr>

            </table>

            <div class="mt-3 text-end">
                <a href="{{ route('employee_salary_payments.edit', $employeeSalaryPayment->id) }}" class="btn btn-warning text-white">Edit</a>
                <a href="{{ route('employee_salary_payments.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>
    </div>
</div>
@endsection
