@extends('layouts.configside')
@section('title', 'Edit Company Information')
@section('page-title', 'Edit Company Information')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-pencil"></i></div>
        Edit Company: {{ $company->company_name }}
    </h3>
    <a href="{{ url()->previous() }}" class="arbif-btn-cancel text-decoration-none d-inline-flex align-items-center">
        <i class="fas fa-arrow-left me-1"></i> Cancel & Return
    </a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <form method="POST" id="dataFormFill" action="{{ route('updatecompaniesinformations', [encrypt($company->id)]) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label" for="company_code">Company Code <span class="text-danger">*</span></label>
                    <input type="text" id="company_code" name="company_code" class="form-control" placeholder="e.g. CMP-001" value="{{ old('company_code', $company->company_code) }}" maxlength="30" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="company_name">Company Name <span class="text-danger">*</span></label>
                    <input type="text" id="company_name" name="company_name" class="form-control" placeholder="e.g. Acme Holdings Ltd" value="{{ old('company_name', $company->company_name) }}" maxlength="200" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="company_type">Company Type <span class="text-danger">*</span></label>
                    <input type="text" id="company_type" name="company_type" class="form-control" placeholder="e.g. Subsidiary, LLC, HQ" value="{{ old('company_type', $company->company_type) }}" maxlength="200" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Parent Company</label>
                    <select style="width: 100%" name="parent_company_id" data-searchable data-placeholder="Select Parent Company (Optional)...">
                        <option></option>
                        @foreach($parentCompanies as $parentOption)
                            <option value="{{ $parentOption->id }}" {{ old('parent_company_id', $company->parent_company_id) == $parentOption->id ? 'selected' : '' }}>
                                {{ $parentOption->company_code }} - {{ $parentOption->company_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="established_date">Established Date</label>
                    <input type="date" id="established_date" name="established_date" class="form-control" value="{{ old('established_date', $company->established_date ? \Carbon\Carbon::parse($company->established_date)->format('Y-m-d') : '') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="e.g. info@company.com" value="{{ old('email', $company->email) }}" maxlength="150">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" class="form-control" placeholder="e.g. +255..." value="{{ old('phone', $company->phone) }}" maxlength="30">
                </div>

                <div class="col-md-8">
                    <label class="form-label" for="address">Physical Address</label>
                    <input type="text" id="address" name="address" class="form-control" placeholder="e.g. 123 Business Parkway" value="{{ old('address', $company->address) }}" maxlength="255">
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="region">Region</label>
                    <input type="text" id="region" name="region" class="form-control" placeholder="Region" value="{{ old('region', $company->region) }}" maxlength="100">
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="district">District</label>
                    <input type="text" id="district" name="district" class="form-control" placeholder="District" value="{{ old('district', $company->district) }}" maxlength="100">
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="ward">Ward</label>
                    <input type="text" id="ward" name="ward" class="form-control" placeholder="Ward" value="{{ old('ward', $company->ward) }}" maxlength="100">
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="village">Village/Street</label>
                    <input type="text" id="village" name="village" class="form-control" placeholder="Village" value="{{ old('village', $company->village) }}" maxlength="100">
                </div>

                <div class="col-md-12">
                    <label class="form-label" for="description">Company Description</label>
                    <textarea id="description" name="description" class="form-control" rows="4" placeholder="Describe the corporate activities or purpose...">{{ old('description', $company->description) }}</textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 border-top pt-3" style="margin-top: 30px;">
                <a href="{{ url()->previous() }}" class="arbif-btn-cancel text-decoration-none">
                    <i class="bi bi-x"></i> Cancel
                </a>
                <button onclick="confirmSubmit()" type="submit" class="arbif-btn-submit">
                    <i class="bi bi-check2"></i> <span id="submitBtnText">Save Operational Changes</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection