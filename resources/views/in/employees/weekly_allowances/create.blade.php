@extends('layouts.app')
@section('title', 'Add Weekly Allowance')

@section('content')
<div class="container py-4">
    <div class="card shadow border-0">
        <div class="card-header bg-success text-white">
            <h4>Add Weekly Allowance</h4>
        </div>

        <div class="card-body">
            <form action="{{ route('employee_weekly_allowances.store') }}" method="POST" enctype="multipart/form-data" id="allowanceForm">
                @csrf
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Employee</label>
                        <select id="employee_id" name="employee_id" class="form-select" required></select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Week Start</label>
                        <input type="date" name="week_start" class="form-control" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Week End</label>
                        <input type="date" name="week_end" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Allowance Type</label>
                        <input type="text" name="allowance_type" class="form-control" placeholder="e.g., Transport" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Amount (TZS)</label>
                        <input type="number" step="0.01" name="amount" id="amount" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Currency</label>
                        <input type="text" name="currency" class="form-control" value="TZS" readonly>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Optional notes..."></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Attachment (optional)</label>
                        <input type="file" name="attachment" class="form-control">
                    </div>

                    <div class="col-md-6 text-end align-self-end">
                        <h5>Total Preview: <span id="totalPreview" class="text-success fw-bold">0.00 TZS</span></h5>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-success">Save Allowance</button>
                    <a href="{{ route('employee_weekly_allowances.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Select2 and total preview --}}
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

    // Auto update total preview
    $('#amount').on('input', function() {
        const val = parseFloat($(this).val()) || 0;
        $('#totalPreview').text(val.toLocaleString() + ' TZS');
    });
});
</script>
@endpush
@endsection
