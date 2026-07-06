@extends('layouts.app')

@section('title', 'Create New Client Loan')
@section('page-title', 'New Loan Application')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0 text-primary">New Client Loan Application <i class="bi bi-file-earmark-plus"></i></h2>
    </div>

    {{-- Form Card --}}
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Provide Loan Details</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('client_loans.store') }}">
                @csrf

                {{-- Section: Client & Group Info --}}
                <h6 class="text-secondary border-bottom pb-1 mb-3 pt-2">1. Client and Group Information</h6>
                <div class="row g-3 mb-4">
                    {{-- Client Selection (Updated for Select2) --}}
                    <div class="col-md-6">
                        <label for="client_id" class="form-label fw-semibold">Client <span class="text-danger">*</span></label>
                        {{-- Added 'select2-enabled' class for JS targeting --}}
                        <select id="client_id" name="client_id" class="form-select select2-enabled @error('client_id') is-invalid @enderror" required>
                            <option value="">— Search and Select Client —</option>
                            {{-- Assuming $clients is passed to the view --}}
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->first_name }} {{ $client->last_name }} (ID: {{ $client->id }})
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Group Center Selection (Updated for Select2) --}}
                    <div class="col-md-6">
                        <label for="group_center_id" class="form-label fw-semibold">Group Center (Optional)</label>
                        {{-- Added 'select2-enabled' class for JS targeting --}}
                        <select id="group_center_id" name="group_center_id" class="form-select select2-enabled @error('group_center_id') is-invalid @enderror">
                            <option value="">None (Individual Loan) — Search Center</option>
                            {{-- Assuming $groupCenters is passed to the view --}}
                            @foreach($groupCenters as $gc)
                                <option value="{{ $gc->id }}" {{ old('group_center_id') == $gc->id ? 'selected' : '' }}>
                                    {{ $gc->center_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('group_center_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Section: Loan Amounts and Terms --}}
                <h6 class="text-secondary border-bottom pb-1 mb-3 pt-2">2. Loan Request and Terms</h6>
                <div class="row g-3 mb-4">
                    {{-- Amount Requested --}}
                    <div class="col-md-4">
                        <label for="amount_requested" class="form-label fw-semibold">Amount Requested ($) <span class="text-danger">*</span></label>
                        <input type="number" id="amount_requested" name="amount_requested" step="0.01" class="form-control @error('amount_requested') is-invalid @enderror" value="{{ old('amount_requested') }}" required>
                        @error('amount_requested')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Payable Frequency (Duration/Term) --}}
                    <div class="col-md-4">
                        <label for="payable_frequency" class="form-label fw-semibold">Loan Term (e.g., Months) <span class="text-danger">*</span></label>
                        <input type="number" id="payable_frequency" name="payable_frequency" class="form-control @error('payable_frequency') is-invalid @enderror" value="{{ old('payable_frequency') }}" required placeholder="e.g., 12 months, 52 weeks">
                        <div class="form-text">The total duration of the loan.</div>
                        @error('payable_frequency')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Repayment Frequency --}}
                    <div class="col-md-4">
                        <label for="repayment_frequency" class="form-label fw-semibold">Repayment Interval <span class="text-danger">*</span></label>
                        <select id="repayment_frequency" name="repayment_frequency" class="form-select @error('repayment_frequency') is-invalid @enderror" required>
                            <option value="">— Select Interval —</option>
                            @foreach(['daily','weekly','bi_weekly','monthly','quarterly','yearly'] as $freq)
                                <option value="{{ $freq }}" {{ old('repayment_frequency') == $freq ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_',' ',$freq)) }}
                                </option>
                            @endforeach
                        </select>
                        @error('repayment_frequency')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Remarks --}}
                    <div class="col-12">
                        <label for="remarks" class="form-label fw-semibold">Remarks / Notes</label>
                        <textarea id="remarks" name="remarks" class="form-control @error('remarks') is-invalid @enderror" rows="2">{{ old('remarks') }}</textarea>
                        @error('remarks')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Hidden Fields (Auto-filled by system/controller) --}}
                <input type="hidden" name="status" value="pending">

                {{-- Form Actions --}}
                <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary btn-lg shadow-sm me-2 d-flex align-items-center">
                        <i class="bi bi-save me-2"></i> Save Loan Application
                    </button>
                    <a href="{{ route('client_loans.index') }}" class="btn btn-secondary btn-lg">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

{{-- JAVASCRIPT SECTION FOR SELECT2 --}}
@section('scripts')
    {{-- Ensure you load Select2 CSS/JS in your main layout file or include them here --}}
    <script>
        $(document).ready(function() {
            // Target elements with the 'select2-enabled' class
            $('.select2-enabled').select2({
                theme: 'bootstrap-5', // Use Bootstrap 5 theme if available
                placeholder: 'Select an option or type to search...',
                allowClear: true,     // Allows clearing the selection
                width: '100%'         // Makes it full width
            });
        });
    </script>
@endsection