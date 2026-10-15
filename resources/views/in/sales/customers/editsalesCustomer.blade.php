@extends('layouts.workingside')

@section('title', 'Edit Sales Customer')
@section('page-title', 'Edit Customer')

@section('content')

<div class="arbif-page-header mb-4">
    <h3>
        <div class="page-icon">
            <i class="fas fa-pencil-alt"></i>
        </div>
        Edit Customer: {{ $data->customer_name }}
    </h3>

    <div>
        <a href="{{ route('salesCustomer') }}" class="btn btn-secondary px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to List
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body p-4">
        <form method="POST" action="{{ route('updatesalesCustomer', encrypt($data->id)) }}">
            @csrf

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Assigned Branch <span class="text-danger">*</span></label>
                    <select name="branch_id" class="form-select" rdata-searchable data-placeholder="Search ..." required>
                        <option value="">-- Select Branch --</option>
                        @foreach($branchies as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id', $data->branch_id) == $branch->id ? 'selected' : '' }}>
                                {{ $branch->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Customer Type <span class="text-danger">*</span></label>
                    <select name="customer_type" id="edit_cust_type" class="form-select" data-searchable data-placeholder="Search ..." required>
                        <option value="INDIVIDUAL" {{ old('customer_type', $data->customer_type) == 'INDIVIDUAL' ? 'selected' : '' }}>INDIVIDUAL</option>
                        <option value="COMPANY" {{ old('customer_type', $data->customer_type) == 'COMPANY' ? 'selected' : '' }}>COMPANY / CORPORATE</option>
                        <option value="WALK_IN" {{ old('customer_type', $data->customer_type) == 'WALK_IN' ? 'selected' : '' }}>WALK-IN</option>
                    </select>
                </div>
            </div>

            <div class="row g-3 mb-3" id="edit_billing_company_div">
                <div class="col-md-12">
                    <label class="form-label">Linked Corporate Company <span class="text-danger">*</span></label>
                    <select name="billing_company_id" class="form-select"data-searchable data-placeholder="Search ..." >
                        <option value="">-- Select Billing Company --</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ old('billing_company_id', $data->billing_company_id) == $company->id ? 'selected' : '' }}>
                                {{ $company->company_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-8">
                    <label class="form-label">Customer Name / Title <span class="text-danger">*</span></label>
                    <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name', $data->customer_name) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Customer Code <span class="text-danger">*</span></label>
                    <input type="text" name="customer_code" class="form-control" value="{{ old('customer_code', $data->customer_code) }}" required>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label">Contact Person</label>
                    <input type="text" name="contact_person" class="form-control" value="{{ old('contact_person', $data->contact_person) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $data->phone) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $data->email) }}">
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Credit Limit (TZS)</label>
                    <input type="number" step="0.01" name="credit_limit" class="form-control" value="{{ old('credit_limit', $data->credit_limit) }}" min="0">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Payment Terms (Days)</label>
                    <input type="number" name="payment_terms_days" class="form-control" value="{{ old('payment_terms_days', $data->payment_terms_days) }}" min="0">
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Physical Address / Notes</label>
                <textarea name="address" rows="3" class="form-control">{{ old('address', $data->address) }}</textarea>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('salesCustomer') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="arbif-btn-submit">Update Customer</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        function toggleCompanyField() {
            if ($('#edit_cust_type').val() === 'COMPANY') {
                $('#edit_billing_company_div').slideDown();
            } else {
                $('#edit_billing_company_div').slideUp();
            }
        }

        $('#edit_cust_type').on('change', toggleCompanyField);
        toggleCompanyField();
    });
</script>
@endpush