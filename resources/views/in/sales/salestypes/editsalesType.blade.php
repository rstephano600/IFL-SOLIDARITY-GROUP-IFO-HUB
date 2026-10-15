@extends('layouts.configside')

@section('title', 'Edit Sales Type')
@section('page-title', 'Edit Sales Type')

@section('content')

<div class="arbif-page-header mb-4">
    <h3>
        <div class="page-icon">
            <i class="fas fa-pencil-alt"></i>
        </div>
        Edit Sales Type: {{ $data->type_name }}
    </h3>

    <div>
        <a href="{{ route('salesType') }}" class="btn btn-secondary px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to List
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body p-4">
        <form method="POST" action="{{ route('updatesalesType', encrypt($data->id)) }}">
            @csrf

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Assigned Branch <span class="text-danger">*</span></label>
                    <select name="branch_id" class="form-select" data-searchable data-placeholder="Search ..." required>
                        <option value="">-- Select Branch --</option>
                        @foreach($branchies as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id', $data->branch_id) == $branch->id ? 'selected' : '' }}>
                                {{ $branch->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Type Code <span class="text-danger">*</span></label>
                    <input type="text" name="type_code" class="form-control" value="{{ old('type_code', $data->type_code) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Type Name <span class="text-danger">*</span></label>
                    <input type="text" name="type_name" class="form-control" value="{{ old('type_name', $data->type_name) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Default Payment Terms <span class="text-danger">*</span></label>
                    <select name="default_payment_terms" class="form-select" data-searchable data-placeholder="Search ..." required>
                        <option value="IMMEDIATE" {{ old('default_payment_terms', $data->default_payment_terms) == 'IMMEDIATE' ? 'selected' : '' }}>IMMEDIATE</option>
                        <option value="CREDIT" {{ old('default_payment_terms', $data->default_payment_terms) == 'CREDIT' ? 'selected' : '' }}>CREDIT</option>
                        <option value="COMPANY_ACCOUNT" {{ old('default_payment_terms', $data->default_payment_terms) == 'COMPANY_ACCOUNT' ? 'selected' : '' }}>COMPANY ACCOUNT</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Display Order</label>
                    <input type="number" name="display_order" class="form-control" value="{{ old('display_order', $data->display_order) }}" min="0">
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" name="requires_customer" value="1" id="edit_req_cust" {{ old('requires_customer', $data->requires_customer) ? 'checked' : '' }}>
                        <label class="form-check-label" for="edit_req_cust">Requires Customer</label>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" name="requires_employee" value="1" id="edit_req_emp" {{ old('requires_employee', $data->requires_employee) ? 'checked' : '' }}>
                        <label class="form-check-label" for="edit_req_emp">Requires Employee</label>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" name="allows_discount" value="1" id="edit_allow_disc" {{ old('allows_discount', $data->allows_discount) ? 'checked' : '' }}>
                        <label class="form-check-label" for="edit_allow_disc">Allows Discount</label>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Description</label>
                <textarea name="description" rows="4" class="form-control">{{ old('description', $data->description) }}</textarea>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('salesType') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="arbif-btn-submit">Update Sales Type</button>
            </div>
        </form>
    </div>
</div>

@endsection