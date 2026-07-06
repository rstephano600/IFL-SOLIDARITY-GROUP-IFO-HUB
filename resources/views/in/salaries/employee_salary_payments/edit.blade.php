@extends('layouts.app')
@section('title', 'Edit Salary Payment')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-warning text-dark">
            <h4 class="mb-0"><i class="bi bi-pencil-square me-2"></i> Edit Salary Payment</h4>
        </div>

        <div class="card-body">
            <form action="{{ route('employee_salary_payments.update', $employeeSalaryPayment->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    {{-- Employee --}}

                    {{-- Salary Record --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Salary Record</label>
                        <select name="employee_salary_id" class="form-control" required>
                            @foreach($salaryRecords as $record)
                                <option value="{{ $record->id }}" {{ $employeeSalaryPayment->employee_salary_id == $record->id ? 'selected' : '' }}>
                                    {{ $record->employee->first_name }} {{ $record->employee->last_name }} - {{ number_format($record->net_amount_due, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Payment Date</label>
                        <input type="date" name="payment_date" value="{{ $employeeSalaryPayment->payment_date }}" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Amount Paid</label>
                        <input type="number" name="amount_paid" step="0.01" value="{{ $employeeSalaryPayment->amount_paid }}" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Currency</label>
                        <input type="text" name="currency" value="{{ $employeeSalaryPayment->currency }}" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Payment Method</label>
                        <input type="text" name="payment_method" value="{{ $employeeSalaryPayment->payment_method }}" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Reference Number</label>
                        <input type="text" name="reference_number" value="{{ $employeeSalaryPayment->reference_number }}" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Attachment</label>
                        <input type="file" name="attachment" class="form-control" accept=".pdf,.jpg,.jpeg,.png" id="attachment">
                        @if($employeeSalaryPayment->attachment)
                            <div class="mt-2">
                                <a href="{{ asset('storage/'.$employeeSalaryPayment->attachment) }}" target="_blank" class="text-success">
                                    <i class="bi bi-paperclip"></i> View Current File
                                </a>
                            </div>
                        @endif
                        <div id="file-preview" class="mt-2"></div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Status</label>
                        <select name="status" class="form-control">
                            <option value="pending" {{ $employeeSalaryPayment->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ $employeeSalaryPayment->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="cancelled" {{ $employeeSalaryPayment->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-bold">Notes</label>
                        <textarea name="notes" class="form-control" rows="3">{{ $employeeSalaryPayment->notes }}</textarea>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <a href="{{ route('employee_salary_payments.index') }}" class="btn btn-secondary">Back</a>
                    <button type="submit" class="btn btn-warning text-white">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
$(document).ready(function () {
    $('#employee_id').select2({
        placeholder: 'Search employee...',
        ajax: {
            url: '{{ route("employees.search") }}',
            dataType: 'json',
            delay: 250,
            data: params => ({ q: params.term }),
            processResults: data => ({ results: data }),
            cache: true
        },
        minimumInputLength: 1
    });

    $('#attachment').on('change', function() {
        let file = this.files[0];
        if (!file) return;
        $('#file-preview').html(`<small class="text-success"><i class="bi bi-paperclip"></i> ${file.name}</small>`);
    });
});
</script>
@endsection
@endsection
