<div class="row">
    {{-- 1. CLIENT SELECTION (col-md-4) --}}
    <div class="col-md-4 mb-3">
        <label for="client_id" class="form-label">Client (Borrower) <span class="text-danger">*</span></label>
        <select class="form-select @error('client_id') is-invalid @enderror" id="client_id" name="client_id" required>
            <option value="">Select Client</option>
            {{-- Assuming Client model has first_name, last_name, and business_name --}}
            @foreach ($clients as $client)
                <option value="{{ $client->id }}" {{ old('client_id', $loan->client_id) == $client->id ? 'selected' : '' }}>
                    {{ $client->first_name . ' ' . $client->last_name }} : {{ $client->business_name }}
                </option>
            @endforeach
        </select>
        @error('client_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- 2. LOAN CATEGORY SELECTION (col-md-4) --}}
    <div class="col-md-4 mb-3">
        <label for="loan_category_id" class="form-label">Loan Category <span class="text-danger">*</span></label>
        <select class="form-select @error('loan_category_id') is-invalid @enderror" id="loan_category_id" name="loan_category_id" required>
            <option value="">Select Category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" 
                        data-rate="{{ $category->interest_rate }}" 
                        data-term="{{ $category->max_term_months }}"
                        data-principal="{{ $category->principal_amount }}"
                        data-frequency="{{ $category->repayment_frequency }}"
                        {{ old('loan_category_id', $loan->loan_category_id) == $category->id ? 'selected' : '' }}>
                    {{ $category->name }} - {{ $category->principal_amount }}TZS -{{ $category->total_repayable_amount }}- ({{ $category->interest_rate }}%)
                </option>
            @endforeach
        </select>
        @error('loan_category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- 3. GROUP CENTER SELECTION (col-md-4) --}}
    <div class="col-md-4 mb-3">
        <label for="group_center_id" class="form-label">Group/Center Assigned</label>
        <select class="form-select @error('group_center_id') is-invalid @enderror" id="group_center_id" name="group_center_id">
            <option value="">N/A (Individual Loan)</option>
            {{-- Assuming $centers is passed from the controller --}}
            @foreach ($centers as $center)
                <option value="{{ $center->id }}" {{ old('group_center_id', $loan->group_center_id) == $center->id ? 'selected' : '' }}>
                    {{ $center->center_name }} ({{ $center->center_code }})
                </option>
            @endforeach
        </select>
        @error('group_center_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<hr>
<h5 class="mb-3">Financial Terms (Inherited/Customized)</h5>

{{-- The Controller only accepts principal amount and term months from the user --}}
<div class="row">
    <div class="col-md-3 mb-3">
        <label for="principal_amount" class="form-label">Principal Amount <span class="text-danger">*</span></label>
        <input type="number" step="0.01" class="form-control @error('principal_amount') is-invalid @enderror" id="principal_amount" name="principal_amount" 
                value="{{ old('principal_amount', $loan->principal_amount ?? '') }}" required min="1">
        <small class="text-muted">Actual amount client receives.</small>
        @error('principal_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    
    {{-- INTEREST RATE (READ-ONLY) --}}
    <div class="col-md-3 mb-3">
        <label for="interest_rate" class="form-label">Interest Rate (%) (Category Default)</label>
        {{-- READONLY: Controller fetches this from category, not user input --}}
        <input type="number" step="0.01" class="form-control" id="interest_rate" 
                value="{{ old('interest_rate', $loan->interest_rate ?? '') }}" readonly>
        <small class="text-muted">Set by selected Loan Category.</small>
    </div>

    <div class="col-md-3 mb-3">
        <label for="term_months" class="form-label">Term (Months) <span class="text-danger">*</span></label>
        <input type="number" class="form-control @error('term_months') is-invalid @enderror" id="term_months" name="term_months" 
                value="{{ old('term_months', $loan->term_months ?? '') }}" required min="1">
        @error('term_months')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    
    {{-- REPAYMENT FREQUENCY (DISABLED) --}}
    <div class="col-md-3 mb-3">
        <label for="repayment_frequency" class="form-label">Repayment Frequency (Category Default)</label>
        {{-- DISABLED: Controller fetches this from category, not user input. Removed name attribute. --}}
        <select class="form-select" id="repayment_frequency" disabled>
            @php $frequencies = ['daily', 'weekly', 'bi_weekly', 'monthly', 'quarterly']; @endphp
            @foreach ($frequencies as $freq)
                <option value="{{ $freq }}" {{ old('repayment_frequency', $loan->repayment_frequency) === $freq ? 'selected' : '' }}>
                    {{ ucwords(str_replace('_', ' ', $freq)) }}
                </option>
            @endforeach
        </select>
        <small class="text-muted">Frequency is set by selected Loan Category.</small>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label for="disbursement_date" class="form-label">Disbursement Date <span class="text-danger">*</span></label>
        <input type="date" class="form-control @error('disbursement_date') is-invalid @enderror" id="disbursement_date" name="disbursement_date" 
                value="{{ old('disbursement_date', $loan->disbursement_date ? $loan->disbursement_date->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
        @error('disbursement_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    
    @if ($loan->exists)
        <div class="col-md-4 mb-3">
            <label for="status" class="form-label">Loan Status <span class="text-danger">*</span></label>
            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                @php $statuses = ['pending', 'approved', 'active', 'completed', 'defaulted', 'closed']; @endphp
                @foreach ($statuses as $s)
                    <option value="{{ $s }}" {{ old('status', $loan->status) === $s ? 'selected' : '' }}>
                        {{ ucwords($s) }}
                    </option>
                @endforeach
            </select>
            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4 mb-3">
            <label for="closure_reason" class="form-label">Closure Reason (If Closed)</label>
            <input type="text" class="form-control @error('closure_reason') is-invalid @enderror" id="closure_reason" name="closure_reason" 
                    value="{{ old('closure_reason', $loan->closure_reason) }}">
            @error('closure_reason')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    @endif
</div>

{{-- JavaScript to pre-fill loan terms based on selected category --}}
<script>
    document.getElementById('loan_category_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        // Only apply defaults if the fields are empty or if it's a new loan
        if (!'{{ $loan->exists }}') { 
            // Update the readonly interest rate input for display
            document.getElementById('interest_rate').value = selectedOption.getAttribute('data-rate') || '';
            
            // Update the term (which is still user-editable)
            document.getElementById('term_months').value = selectedOption.getAttribute('data-term') || '';
            
            // Update the principal (which is still user-editable)
            document.getElementById('principal_amount').value = selectedOption.getAttribute('data-principal') || '';
            
            // Update the disabled repayment frequency select for display
            document.getElementById('repayment_frequency').value = selectedOption.getAttribute('data-frequency') || 'monthly';
        }
        
        // Even if editing an existing loan, if the category changes, we should update the non-editable fields
        if ('{{ $loan->exists }}') {
            document.getElementById('interest_rate').value = selectedOption.getAttribute('data-rate') || '';
            document.getElementById('repayment_frequency').value = selectedOption.getAttribute('data-frequency') || 'monthly';
        }
    });
    
    // Trigger on load for new loans to pre-fill if a default is already selected via old() or initial load
    document.addEventListener('DOMContentLoaded', function() {
        if (document.getElementById('loan_category_id').value && !'{{ $loan->exists }}') {
             document.getElementById('loan_category_id').dispatchEvent(new Event('change'));
        }
    });
</script>
