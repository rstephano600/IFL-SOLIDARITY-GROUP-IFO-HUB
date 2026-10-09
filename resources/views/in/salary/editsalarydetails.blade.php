@extends('layouts.workingside')
@section('title', 'Edit Salary Details')
@section('page-title', 'Edit Salary Details')

@section('content')

<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-sliders-h"></i></div>Edit Salary Details</h3>
    <a href="{{ route('salarypay') }}" class="arbif-btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Payroll
    </a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        @if(!$salary)
            <div class="arbif-table-empty text-center py-4">
                <i class="fas fa-exclamation-circle d-block mb-2 fs-3 text-muted"></i>
                No draft salary record found for this employee.
            </div>
        @else
            <div class="mb-3">
                <span class="arbif-badge arbif-badge-navy">{{ $salary->employee->EmployeeID ?? 'N/A' }}</span>
                <strong class="ms-2">
                    {{ $salary->employee->user->FirstName ?? '' }}
                    {{ $salary->employee->user->LastName ?? '' }}
                </strong>
                <span class="text-muted ms-2">— {{ $salary->PaidMonth }}</span>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('tempsalarydetailsreg') }}">
                @csrf
                <input type="hidden" name="Salary_id" value="{{ $salary->id }}">

                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Base Gross (Actual) <span class="text-danger">*</span></label>
                        <input type="number" step="0.000001" name="GrossAmmount" id="GrossAmmount" class="form-control"
                               value="{{ old('GrossAmmount', $salary->ActualGross) }}" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Allowance</label>
                        <input type="number" step="0.000001" name="Allowance" id="Allowance" class="form-control"
                               value="{{ old('Allowance', $salary->Allowance) }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Overtime</label>
                        <input type="number" step="0.000001" name="Overtime" id="Overtime" class="form-control"
                               value="{{ old('Overtime', $salary->Overtime) }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Absent Deduction</label>
                        <input type="number" step="0.000001" name="Absent" id="Absent" class="form-control"
                               value="{{ old('Absent', $salary->Absent) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Heslb (reference — not editable here)</label>
                        <input type="text" class="form-control" value="{{ number_format((float) $salary->Heslb, 2) }}" disabled>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Staff Loan / Bcabd (reference)</label>
                        <input type="text" class="form-control" value="{{ number_format((float) $salary->Bcabd, 2) }}" disabled>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Advance (reference)</label>
                        <input type="text" class="form-control" value="{{ number_format((float) $salary->Advance, 2) }}" disabled>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Reason / Condition <span class="text-danger">*</span></label>
                        <input type="text" name="Condition" class="form-control"
                               value="{{ old('Condition', $salary->Condition) }}"
                               placeholder="e.g. Allowance added, overtime correction" required>
                    </div>

                    <div class="col-12">
                        <div class="arbif-card">
                            <div class="arbif-card-body py-2">
                                <strong>Computed Gross (preview):</strong>
                                <span id="computedGross">{{ number_format((float) $salary->AmountPaid, 2) }}</span>
                                <small class="text-muted d-block">
                                    NSSF, PAYE, SDL, NSSF Employer's, WCF and Net Pay are recalculated on the server from this gross figure when you submit.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('salarypay') }}" class="arbif-btn-secondary">Cancel</a>
                    <button type="submit" class="arbif-btn-submit">
                        <i class="fas fa-save"></i> Update Salary Details
                    </button>
                </div>
            </form>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Client-side preview only — the authoritative figure is always recalculated server-side on submit.
    function recomputeGrossPreview() {
        const base      = parseFloat(document.getElementById('GrossAmmount')?.value) || 0;
        const allowance = parseFloat(document.getElementById('Allowance')?.value) || 0;
        const overtime  = parseFloat(document.getElementById('Overtime')?.value) || 0;
        const absent    = parseFloat(document.getElementById('Absent')?.value) || 0;

        const gross = Math.max(0, base + allowance + overtime - absent);
        document.getElementById('computedGross').textContent = gross.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    ['GrossAmmount', 'Allowance', 'Overtime', 'Absent'].forEach(id => {
        document.getElementById(id)?.addEventListener('input', recomputeGrossPreview);
    });
</script>
@endpush