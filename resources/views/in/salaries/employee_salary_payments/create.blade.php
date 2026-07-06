@extends('layouts.app')
@section('title', 'Add Employee Salary Payment')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0"><i class="bi bi-cash-stack me-2"></i> Add Employee Salary Payment</h4>
        </div>

        <div class="card-body">
            <form action="{{ route('employee_salary_payments.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">

                    {{-- Employee (AJAX Select2) --}}
                    <!-- <div class="col-md-6">
                        <label class="form-label fw-bold">Employee</label>
                        <select name="employee_id" id="employee_id" class="form-control select2-employee" required></select>
                        @error('employee_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div> -->

                    {{-- Salary Record --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Salary Record</label>
                        <select name="employee_salary_id" class="form-control" required>
                            <option value="">-- Select Salary Record --</option>
                            @foreach($salaryRecords as $record)
                                <option value="{{ $record->id }}">
                                    {{ $record->employee->first_name }} {{ $record->employee->last_name }} - {{ number_format($record->net_amount_due, 2) }}
                                </option>
                            @endforeach
                        </select>
                        @error('employee_salary_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- Payment Date --}}
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Payment Date</label>
                        <input type="date" name="payment_date" class="form-control" required>
                        @error('payment_date') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- Amount --}}
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Amount Paid</label>
                        <input type="number" name="amount_paid" step="0.01" class="form-control" required>
                        @error('amount_paid') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- Currency --}}
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Currency</label>
                        <input type="text" name="currency" value="TZS" class="form-control" required>
                        @error('currency') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- Payment Method --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Payment Method</label>
                        <input type="text" name="payment_method" class="form-control" placeholder="Cash, Bank, Mobile Money">
                        @error('payment_method') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- Reference Number --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Reference Number</label>
                        <input type="text" name="reference_number" class="form-control">
                        @error('reference_number') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- File Attachment --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Attachment (Optional)</label>
                        <input type="file" name="attachment" class="form-control" accept=".pdf,.jpg,.jpeg,.png" id="attachment">
                        <div id="file-preview" class="mt-2"></div>
                        @error('attachment') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- Status --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Status</label>
                        <select name="status" class="form-control" required>
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                        @error('status') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- Notes --}}
                    <div class="col-md-12">
                        <label class="form-label fw-bold">Notes</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                        @error('notes') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <a href="{{ route('employee_salary_payments.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-success">Save Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
{{-- ✅ Select2 AJAX --}}
<script>
$(document).ready(function () {
    $('#employee_id').select2({
        placeholder: 'Search employee...',
        ajax: {
            url: '{{ route("employees.search") }}', // create this route for AJAX
            dataType: 'json',
            delay: 250,
            data: params => ({ q: params.term }),
            processResults: data => ({ results: data }),
            cache: true
        },
        minimumInputLength: 1
    });

    // Preview uploaded file
    $('#attachment').on('change', function() {
        let file = this.files[0];
        if (!file) return;
        $('#file-preview').html(`<small class="text-success"><i class="bi bi-paperclip"></i> ${file.name}</small>`);
    });
});
</script>
@endsection
