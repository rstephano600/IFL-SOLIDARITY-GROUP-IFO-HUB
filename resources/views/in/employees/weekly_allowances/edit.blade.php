@extends('layouts.app')
@section('title', 'Edit Weekly Allowance')

@section('content')
<div class="container py-4">
    <div class="card shadow border-0">
        <div class="card-header bg-warning text-white">
            <h4>Edit Weekly Allowance</h4>
        </div>

        <div class="card-body">
            <form action="{{ route('employee_weekly_allowances.update', $employeeWeeklyAllowance->id) }}" 
                  method="POST" enctype="multipart/form-data" id="allowanceForm">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Employee</label>
                        <select id="employee_id" name="employee_id" class="form-select" required>
                            <option value="{{ $employeeWeeklyAllowance->employee_id }}" selected>
                                {{ $employeeWeeklyAllowance->employee->first_name }} {{ $employeeWeeklyAllowance->employee->last_name }}
                            </option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Week Start</label>
                        <input type="date" name="week_start" class="form-control"
                            value="{{ $employeeWeeklyAllowance->week_start->format('Y-m-d') }}" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Week End</label>
                        <input type="date" name="week_end" class="form-control"
                            value="{{ $employeeWeeklyAllowance->week_end->format('Y-m-d') }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Allowance Type</label>
                        <input type="text" name="allowance_type" class="form-control" 
                               value="{{ $employeeWeeklyAllowance->allowance_type }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Amount (TZS)</label>
                        <input type="number" step="0.01" name="amount" id="amount" 
                               value="{{ $employeeWeeklyAllowance->amount }}" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Currency</label>
                        <input type="text" name="currency" class="form-control" 
                               value="{{ $employeeWeeklyAllowance->currency }}" readonly>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="2">{{ $employeeWeeklyAllowance->description }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Attachment (optional)</label>
                        <input type="file" name="attachment" class="form-control">
                        @if($employeeWeeklyAllowance->attachment)
                            <a href="{{ asset('storage/'.$employeeWeeklyAllowance->attachment) }}" target="_blank" class="d-block mt-1">View Current File</a>
                        @endif
                    </div>

                    <div class="col-md-6 text-end align-self-end">
                        <h5>Total Preview: <span id="totalPreview" class="text-success fw-bold">{{ number_format($employeeWeeklyAllowance->amount, 2) }} TZS</span></h5>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-warning text-white">Update Allowance</button>
                    <a href="{{ route('employee_weekly_allowances.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#employee_id').select2({
        placeholder: 'Search for employee...',
        ajax: {
            url: '{{ route('search.employees') }}',
            dataType: 'json',
            delay: 250,
            data: params => ({ term: params.term }),
            processResults: data => ({ results: data.results }),
            cache: true
        }
    });

    $('#amount').on('input', function() {
        const val = parseFloat($(this).val()) || 0;
        $('#totalPreview').text(val.toLocaleString() + ' TZS');
    });
});
</script>
@endpush
@endsection
