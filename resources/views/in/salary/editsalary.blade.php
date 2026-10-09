@extends('layouts.workingside')
@section('title', 'Edit Gross Salary')
@section('page-title', 'Edit Gross Salary')

@section('content')

<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-coins"></i></div>Edit Gross Salary</h3>
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

            <form method="POST" action="{{ route('tempsalaryreg') }}">
                @csrf
                <input type="hidden" name="Salary_id" value="{{ $salary->id }}">
                <input type="hidden" name="Emply_id" value="{{ $salary->Emply_id }}">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Current Net Pay (reference)</label>
                        <input type="text" class="form-control" value="{{ number_format((float) $salary->NetPay, 2) }}" disabled>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Gross Salary (Amount Paid) <span class="text-danger">*</span></label>
                        <input type="number" step="0.000001" name="AmountPaid" class="form-control"
                               value="{{ old('AmountPaid', $salary->AmountPaid) }}" required>
                        <small class="text-muted">NSSF, PAYE, SDL, NSSF Employer's and WCF will be recalculated from this figure.</small>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Reason / Condition <span class="text-danger">*</span></label>
                        <input type="text" name="Condition" class="form-control"
                               value="{{ old('Condition', $salary->Condition) }}"
                               placeholder="e.g. Salary revision, correction, bonus adjustment" required>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('salarypay') }}" class="arbif-btn-secondary">Cancel</a>
                    <button type="submit" class="arbif-btn-submit">
                        <i class="fas fa-save"></i> Update Gross Salary
                    </button>
                </div>
            </form>
        @endif
    </div>
</div>

@endsection