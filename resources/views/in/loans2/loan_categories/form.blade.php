{{-- Assumes $loanCategory is passed from create or edit --}}
<script>
    function toggleCalculationFields() {
        // Check if the user wants the system to calculate
        const shouldCalculate = document.getElementById('calculate_amounts').checked;
        const installmentField = document.getElementById('installment_amount');
        const totalRepayableField = document.getElementById('total_repayable_amount');
        const isEdit = installmentField.hasAttribute('data-initial-value'); // Check if we are in 'edit' mode

        // If calculation is checked, disable and remove 'required' visually
        if (shouldCalculate) {
            installmentField.disabled = true;
            totalRepayableField.disabled = true;
            
            // Remove values and the required indicator visually
            installmentField.value = '';
            totalRepayableField.value = '';
            
            document.getElementById('installment_label_star').style.display = 'none';
            document.getElementById('total_repayable_label_star').style.display = 'none';

        } else {
            // If user wants to input manually, re-enable fields and restore required indicator
            installmentField.disabled = false;
            totalRepayableField.disabled = false;

            document.getElementById('installment_label_star').style.display = 'inline';
            document.getElementById('total_repayable_label_star').style.display = 'inline';
            
            // In Edit mode, restore original value when switching back to manual entry
            if (isEdit) {
                 installmentField.value = installmentField.getAttribute('data-initial-value');
                 totalRepayableField.value = totalRepayableField.getAttribute('data-initial-value');
            }
        }
    }
    
    // Run on page load to set initial state
    document.addEventListener('DOMContentLoaded', toggleCalculationFields);
</script>


<div class="row">
    <div class="col-md-6 mb-3">
        <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $loanCategory->name) }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="currency" class="form-label">Currency <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('currency') is-invalid @enderror" id="currency" name="currency" value="{{ old('currency', $loanCategory->currency ?? 'USD') }}" required maxlength="10">
        @error('currency')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label for="interest_rate" class="form-label">Interest Rate (%) <span class="text-danger">*</span></label>
        <input type="number" step="0.01" class="form-control @error('interest_rate') is-invalid @enderror" id="interest_rate" name="interest_rate" value="{{ old('interest_rate', $loanCategory->interest_rate) }}" required min="0">
        @error('interest_rate')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4 mb-3">
        <label for="repayment_frequency" class="form-label">Repayment Frequency <span class="text-danger">*</span></label>
        <select class="form-select @error('repayment_frequency') is-invalid @enderror" id="repayment_frequency" name="repayment_frequency" required>
            <option value="">Select Frequency</option>
            @php
                $frequencies = ['daily', 'weekly', 'bi_weekly', 'monthly', 'quarterly'];
                $selectedFreq = old('repayment_frequency', $loanCategory->repayment_frequency);
            @endphp
            @foreach ($frequencies as $freq)
                <option value="{{ $freq }}" {{ $selectedFreq === $freq ? 'selected' : '' }}>
                    {{ ucwords(str_replace('_', ' ', $freq)) }}
                </option>
            @endforeach
        </select>
        @error('repayment_frequency')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    
    <div class="col-md-4 mb-3">
        <label for="max_term_months" class="form-label">Max Term (Months) <span class="text-danger">*</span></label>
        <input type="number" class="form-control @error('max_term_months') is-invalid @enderror" id="max_term_months" name="max_term_months" value="{{ old('max_term_months', $loanCategory->max_term_months) }}" required min="1">
        @error('max_term_months')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label for="min_amount" class="form-label">Minimum Loan Amount <span class="text-danger">*</span></label>
        <input type="number" step="0.01" class="form-control @error('min_amount') is-invalid @enderror" id="min_amount" name="min_amount" value="{{ old('min_amount', $loanCategory->min_amount) }}" required min="0">
        @error('min_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="max_amount" class="form-label">Maximum Loan Amount <span class="text-danger">*</span></label>
        <input type="number" step="0.01" class="form-control @error('max_amount') is-invalid @enderror" id="max_amount" name="max_amount" value="{{ old('max_amount', $loanCategory->max_amount) }}" required min="0">
        <small class="form-text text-muted">Must be greater than or equal to minimum amount.</small>
        @error('max_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="principal_amount" class="form-label">Default Principal Amount <span class="text-danger">*</span></label>
        <input type="number" step="0.01" class="form-control @error('principal_amount') is-invalid @enderror" id="principal_amount" name="principal_amount" value="{{ old('principal_amount', $loanCategory->principal_amount) }}" required min="0">
        @error('principal_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<hr>
<div class="form-check form-switch mb-4">
    <input class="form-check-input" type="checkbox" role="switch" id="calculate_amounts" name="calculate_amounts" value="1" 
           onchange="toggleCalculationFields()" {{ old('calculate_amounts', empty($loanCategory->installment_amount) && empty($loanCategory->total_repayable_amount)) ? 'checked' : '' }}>
    <label class="form-check-label h5" for="calculate_amounts">System Calculation Mode: Calculate Installment & Total Repayable</label>
    <small class="text-muted d-block">Check this box to automatically calculate the installment and total repayable amounts based on the principal, rate, term, and frequency above. Uncheck to enter manually.</small>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="installment_amount" class="form-label">
            Default Installment Amount 
            <span class="text-danger" id="installment_label_star">*</span>
        </label>
        <input type="number" step="0.01" class="form-control @error('installment_amount') is-invalid @enderror" id="installment_amount" name="installment_amount" 
               value="{{ old('installment_amount', $loanCategory->installment_amount) }}" 
               min="0"
               data-initial-value="{{ $loanCategory->installment_amount }}" {{-- Store initial value for edit mode --}}>
        @error('installment_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="total_repayable_amount" class="form-label">
            Default Total Repayable 
            <span class="text-danger" id="total_repayable_label_star">*</span>
        </label>
        <input type="number" step="0.01" class="form-control @error('total_repayable_amount') is-invalid @enderror" id="total_repayable_amount" name="total_repayable_amount" 
               value="{{ old('total_repayable_amount', $loanCategory->total_repayable_amount) }}" 
               min="0"
               data-initial-value="{{ $loanCategory->total_repayable_amount }}" {{-- Store initial value for edit mode --}}>
        @error('total_repayable_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4 mb-3">
        <label for="max_term_days" class="form-label">Max Term (Days) <small class="text-muted">(Optional)</small></label>
        <input type="number" class="form-control @error('max_term_days') is-invalid @enderror" id="max_term_days" name="max_term_days" value="{{ old('max_term_days', $loanCategory->max_term_days) }}" min="1">
        @error('max_term_days')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="mb-3">
    <label for="conditions" class="form-label">Conditions/Description <span class="text-danger">*</span></label>
    <textarea class="form-control @error('conditions') is-invalid @enderror" id="conditions" name="conditions" rows="3" required>{{ old('conditions', $loanCategory->conditions) }}</textarea>
    @error('conditions')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="form-check form-switch mb-3">
    <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" 
           {{ old('is_active', $loanCategory->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">Category is Active</label>
    @error('is_active')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>