@extends('layouts.workingside')
@section('title', 'Generate Monthly Salary')
@section('page-title', 'Generate Monthly Salary')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-calculator"></i></div>
        Run Payroll Generation
    </h3>
    <div class="d-flex gap-2">
        <a href="{{ route('salaryinformations') }}" class="btn btn-secondary btn-sm d-flex align-items-center text-decoration-none">
            <i class="fas fa-arrow-left me-1"></i> Back to Ledger
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="arbif-card">
            <div class="arbif-card-body">
                <div class="mb-4">
                    <h5 class="arbif-section-title">Batch Payroll Processing</h5>
                    <p class="text-muted small">
                        Select a target month. The engine gathers parameters for active records to instantiate dynamic base lines automatically.
                    </p>
                </div>

                <form method="POST" action="{{ route('storesalary') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label font-weight-bold">Target Processing Month <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-calendar-alt text-muted"></i></span>
                            <input type="month" 
                                   name="PaidMonth" 
                                   class="form-control @error('PaidMonth') is-invalid @enderror" 
                                   value="{{ old('PaidMonth', date('Y-m')) }}" 
                                   required>
                            @error('PaidMonth')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="arbif-card p-3 bg-light border-0 mb-4">
                        <div class="d-flex gap-3 align-items-start">
                            <i class="fas fa-info-circle text-primary fs-4 mt-1"></i>
                            <div class="small text-secondary">
                                <strong>System Note:</strong> Duplicate checks are run automatically against confirmed batches. Ensure standard employee basic structures have baseline components configured before initialization.
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('salaryinformations') }}" class="arbif-btn-cancel text-decoration-none">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                        <button type="submit" class="arbif-btn-submit">
                            <i class="fas fa-cogs"></i> Initialize Calculation Engine
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection