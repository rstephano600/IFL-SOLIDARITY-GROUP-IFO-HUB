@extends('layouts.workingside')
@section('title', 'Generate Weekly Allowance')
@section('page-title', 'Generate Weekly Allowance')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-calculator"></i></div>
        Run Weekly Allowance Processing
    </h3>
    <div class="d-flex gap-2">
        <a href="{{ route('weeklyallowanceinformations') }}" class="btn btn-secondary btn-sm d-flex align-items-center text-decoration-none">
            <i class="fas fa-arrow-left me-1"></i> Back to Ledger
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="arbif-card">
            <div class="arbif-card-body">
                <div class="mb-4">
                    <h5 class="arbif-section-title">Batch Allowance Initialization</h5>
                    <p class="text-muted small">
                        Select a week number and month baseline. The engine targets active workers with an assigned custom weekly allowance value greater than 0.
                    </p>
                </div>

                <form method="POST" action="{{ route('storeweeklyallowance') }}">
                    @csrf

                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <label class="form-label font-weight-bold">Week Number <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-hashtag text-muted"></i></span>
                                <input type="number" 
                                       name="WeekNumber" 
                                       class="form-control @error('WeekNumber') is-invalid @enderror" 
                                       placeholder="e.g. 24"
                                       min="1" 
                                       max="53" 
                                       value="{{ old('WeekNumber', date('W')) }}" 
                                       required>
                                @error('WeekNumber')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-8 mb-4">
                            <label class="form-label font-weight-bold">Target Month Reference <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-calendar-alt text-muted"></i></span>
                                <input type="month" 
                                       name="AllowanceMonth" 
                                       class="form-control @error('AllowanceMonth') is-invalid @enderror" 
                                       value="{{ old('AllowanceMonth', date('Y-m')) }}" 
                                       required>
                                @error('AllowanceMonth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="arbif-card p-3 bg-light border-0 mb-4">
                        <div class="d-flex gap-3 align-items-start">
                            <i class="fas fa-exclamation-triangle text-warning fs-4 mt-1"></i>
                            <div class="small text-secondary">
                                <strong>Duplication Safety Layer:</strong> If allowance records have already been written for the assigned week numbers within this month block, execution will drop back safely to avoid duplicate balances.
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('weeklyallowanceinformations') }}" class="arbif-btn-cancel text-decoration-none">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                        <button type="submit" class="arbif-btn-submit">
                            <i class="fas fa-magic"></i> Compile Weekly Batches
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection