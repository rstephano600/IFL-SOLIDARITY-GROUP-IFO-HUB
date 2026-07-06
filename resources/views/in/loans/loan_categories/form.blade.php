@extends('layouts.app')

@section('title', isset($loanCategory) ? 'Edit Loan Category' : 'Create New Loan Category')
@section('page-title', isset($loanCategory) ? 'Edit Category' : 'New Category')

@section('content')
<div class="container-fluid py-4">

    {{-- ================================================================= --}}
    {{-- HEADER --}}
    {{-- ================================================================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <h2 class="h3 mb-0 text-dark">
            <i class="bi bi-tags-fill me-2 text-info"></i> 
            {{ isset($loanCategory) ? 'Edit Loan Category: ' . $loanCategory->name : 'Define New Loan Category' }}
        </h2>
        <a href="{{ route('loan_categories.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left-circle me-2"></i> Back to Categories
        </a>
    </div>

    {{-- ================================================================= --}}
    {{-- FORM CARD --}}
    {{-- ================================================================= --}}
    <div class="card shadow-lg border-0">
        <div class="card-body p-4">

            {{-- Determine Form Action and Method --}}
            <form action="{{ isset($loanCategory) ? route('loan_categories.update', $loanCategory->id) : route('loan_categories.store') }}" 
                  method="POST" 
                  class="row g-4 needs-validation" 
                  novalidate>
                @csrf
                @if(isset($loanCategory))
                    @method('PUT')
                @endif
                
                {{-- Global Error/Success Feedback (Laravel standard practice) --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Section Header: Basic Details --}}
                <div class="col-12">
                    <h5 class="text-primary fw-bold border-bottom pb-2"><i class="bi bi-info-circle me-1"></i> General Information</h5>
                </div>

                {{-- Name --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Category Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name', $loanCategory->name ?? '') }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Currency --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Currency <span class="text-danger">*</span></label>
                    <input type="text" name="currency" class="form-control @error('currency') is-invalid @enderror" 
                           value="{{ old('currency', $loanCategory->currency ?? 'TZS') }}" required>
                    @error('currency') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Section Header: Financial Parameters --}}
                <div class="col-12 mt-5">
                    <h5 class="text-primary fw-bold border-bottom pb-2"><i class="bi bi-cash me-1"></i> Financial Parameters</h5>
                </div>
                
                {{-- Amount Disbursed (Max Loan Amount) --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Max Amount Disbursable <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="amount_disbursed" class="form-control @error('amount_disbursed') is-invalid @enderror" 
                           value="{{ old('amount_disbursed', $loanCategory->amount_disbursed ?? '') }}" placeholder="E.g., 500000" required>
                    @error('amount_disbursed') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Principal Due (Often confusing, maybe Maximum Principal to repay) --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Principal Due (Optional)</label>
                    <input type="number" step="0.01" name="principal_due" class="form-control @error('principal_due') is-invalid @enderror" 
                           value="{{ old('principal_due', $loanCategory->principal_due ?? '') }}" placeholder="E.g., 550000 (Incl. interest/fees if required)">
                    @error('principal_due') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                {{-- Interest Rate (%) --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Interest Rate (%) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="interest_rate" class="form-control @error('interest_rate') is-invalid @enderror" 
                           value="{{ old('interest_rate', $loanCategory->interest_rate ?? 20) }}" required>
                    @error('interest_rate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Section Header: Fees --}}
                <div class="col-12 mt-5">
                    <h5 class="text-primary fw-bold border-bottom pb-2"><i class="bi bi-percent me-1"></i> Associated Fees (Amounts)</h5>
                </div>
                
                {{-- Insurance Fee --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Insurance Fee</label>
                    <input type="number" step="0.01" name="insurance_fee" class="form-control @error('insurance_fee') is-invalid @enderror" 
                           value="{{ old('insurance_fee', $loanCategory->insurance_fee ?? 0) }}">
                    @error('insurance_fee') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Officer Visit Fee --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Officer Visit Fee</label>
                    <input type="number" step="0.01" name="officer_visit_fee" class="form-control @error('officer_visit_fee') is-invalid @enderror" 
                           value="{{ old('officer_visit_fee', $loanCategory->officer_visit_fee ?? 0) }}">
                    @error('officer_visit_fee') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                {{-- Empty column for alignment --}}
                <div class="col-md-4"></div>

                {{-- Section Header: Term & Repayment --}}
                <div class="col-12 mt-5">
                    <h5 class="text-primary fw-bold border-bottom pb-2"><i class="bi bi-calendar-check me-1"></i> Term & Repayment Schedule</h5>
                </div>

                {{-- Repayment Frequency --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Repayment Frequency <span class="text-danger">*</span></label>
                    <select name="repayment_frequency" class="form-select @error('repayment_frequency') is-invalid @enderror" required>
                        <option value="" disabled>Select Frequency</option>
                        @foreach(['daily','weekly','bi_weekly','monthly','quarterly'] as $f)
                            <option value="{{ $f }}" {{ old('repayment_frequency', $loanCategory->repayment_frequency ?? '')==$f ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_',' ', $f)) }}
                            </option>
                        @endforeach
                    </select>
                    @error('repayment_frequency') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                {{-- Max Term (Days) --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Max Term (Days)</label>
                    <input type="number" name="max_term_days" class="form-control @error('max_term_days') is-invalid @enderror" 
                           value="{{ old('max_term_days', $loanCategory->max_term_days ?? '') }}" placeholder="E.g., 30 (for 1 month)">
                    @error('max_term_days') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Max Term (Months) --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Max Term (Months)</label>
                    <input type="number" name="max_term_months" class="form-control @error('max_term_months') is-invalid @enderror" 
                           value="{{ old('max_term_months', $loanCategory->max_term_months ?? '') }}" placeholder="E.g., 6">
                    @error('max_term_months') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>


                {{-- Section Header: Descriptions & Toggles --}}
                <div class="col-12 mt-5">
                    <h5 class="text-primary fw-bold border-bottom pb-2"><i class="bi bi-text-left me-1"></i> Documentation & Status</h5>
                </div>

                {{-- Conditions --}}
                <div class="col-12">
                    <label class="form-label fw-semibold">Conditions</label>
                    <textarea name="conditions" rows="2" class="form-control @error('conditions') is-invalid @enderror" 
                              placeholder="Describe prerequisites for loan approval...">{{ old('conditions', $loanCategory->conditions ?? '') }}</textarea>
                    @error('conditions') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Descriptions --}}
                <div class="col-12">
                    <label class="form-label fw-semibold">Descriptions</label>
                    <textarea name="descriptions" rows="3" class="form-control @error('descriptions') is-invalid @enderror" 
                              placeholder="Detailed description of the loan category...">{{ old('descriptions', $loanCategory->descriptions ?? '') }}</textarea>
                    @error('descriptions') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Checkbox Toggles --}}
                <div class="col-12 d-flex gap-5 pt-3">
                    
                    {{-- Is Active --}}
                    <div class="form-check form-switch fs-5">
                        <input type="hidden" name="is_active" value="0">
                        <input class="form-check-input" type="checkbox" role="switch" value="1" id="isActiveSwitch" name="is_active"
                            {{ old('is_active', $loanCategory->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold text-dark" for="isActiveSwitch">
                            Active
                        </label>
                    </div>

                    {{-- For New Clients --}}
                    <div class="form-check form-switch fs-5">
                        <input type="hidden" name="is_new_client" value="0">
                        <input class="form-check-input" type="checkbox" role="switch" value="1" id="isNewClientSwitch" name="is_new_client"
                            {{ old('is_new_client', $loanCategory->is_new_client ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold text-dark" for="isNewClientSwitch">
                            Available for New Clients
                        </label>
                    </div>
                </div>

                {{-- ================================================================= --}}
                {{-- SUBMIT BUTTON --}}
                {{-- ================================================================= --}}
                <div class="col-12 mt-5 pt-3 border-top">
                    <button type="submit" class="btn btn-success btn-lg px-5 shadow-sm">
                        <i class="bi bi-save me-2"></i> 
                        {{ isset($loanCategory) ? 'Update Category' : 'Create Category' }}
                    </button>
                    <a href="{{ route('loan_categories.index') }}" class="btn btn-outline-secondary btn-lg ms-3">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection