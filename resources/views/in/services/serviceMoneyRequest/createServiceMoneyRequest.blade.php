@extends('layouts.workingside')

@section('title', 'Prepare Service Money Request')
@section('page-title', 'Prepare Service Money Request')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
@endpush

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-hand-holding-dollar"></i>
        </div>
        Prepare Money Request
    </h3>

    <div>
        <a href="{{ route('serviceMoneyRequest') }}" class="btn btn-secondary px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to List
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body p-4">
        <form method="POST" action="{{ route('storeServiceMoneyRequest') }}">
            @csrf

            <h6 class="fw-bold text-primary mb-3"><i class="fas fa-info-circle me-1"></i> Request Details</h6>
            
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Approved Service Request <span class="text-danger">*</span></label>
                    <select name="service_request_id" id="service_request_id" class="form-select searchable-select" required>
                        <option value="">-- Select Approved Service Request --</option>
                        @foreach($serviceRequests as $sr)
                            <option value="{{ $sr->id }}" 
                                data-branch="{{ $sr->branch_id }}"
                                data-title="{{ $sr->request_title }}"
                                data-purpose="{{ $sr->purpose }}"
                                data-currency="{{ $sr->requested_currency }}"
                                {{ (old('service_request_id') == $sr->id || ($selectedServiceRequest && $selectedServiceRequest->id == $sr->id)) ? 'selected' : '' }}>
                                {{ $sr->request_no }} - {{ $sr->request_title }} ({{ $sr->branch->branch_name ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Branch <span class="text-danger">*</span></label>
                    <select name="branch_id" id="branch_id" class="form-select searchable-select" required>
                        <option value="">-- Select Branch --</option>
                        @foreach($branchies as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->branch_name }} [{{ $branch->company->company_name ?? '' }}][cite: 1]
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Money Request Date <span class="text-danger">*</span></label>
                    <input type="date" name="money_request_date" class="form-control" value="{{ old('money_request_date', date('Y-m-d')) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Payee Name <span class="text-danger">*</span></label>
                    <input type="text" name="payee_name" id="payee_name" class="form-control" value="{{ old('payee_name') }}" placeholder="Recipient/Vendor/Staff Name" required>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Currency <span class="text-danger">*</span></label>
                    <input type="text" name="currency_code" id="currency_code" class="form-control" value="{{ old('currency_code', 'TZS') }}" required>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Amount Requested <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="amount_requested" class="form-control" value="{{ old('amount_requested', '0.00') }}" min="0.01" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Payment Purpose / Notes <span class="text-danger">*</span></label>
                    <textarea name="payment_purpose" id="payment_purpose" class="form-control" rows="3" placeholder="State clear payment purpose or description..." required>{{ old('payment_purpose') }}</textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">Cashier Remarks (Optional)</label>
                    <textarea name="cashier_comment" class="form-control" rows="2" placeholder="Internal cashier preparation notes...">{{ old('cashier_comment') }}</textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('serviceMoneyRequest') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="arbif-btn-submit">Submit Money Request</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('.searchable-select').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });

        $('#service_request_id').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            const branchId = selectedOption.data('branch');
            const purpose = selectedOption.data('purpose');
            const currency = selectedOption.data('currency');

            if (branchId) {
                $('#branch_id').val(branchId).trigger('change');
            }
            if (purpose) {
                $('#payment_purpose').val(purpose);
            }
            if (currency) {
                $('#currency_code').val(currency);
            }
        });
    });
</script>
@endpush