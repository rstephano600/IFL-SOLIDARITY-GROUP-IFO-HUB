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
                
                {{-- ===================================================== --}}
                {{-- BANK INFORMATION --}}
                {{-- ===================================================== --}}

                <div class="col-md-12 mt-4">
                    <h5 class="border-bottom pb-2">
                        <i class="fa fa-university"></i> Bank Account Information
                    </h5>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="bank_name">Bank Name</label>
                    <input type="text" id="bank_name" name="bank_name" class="form-control"
                        placeholder="Enter bank name" value="{{ old('bank_name', $branch->bank_name) }}" maxlength="150">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="bank_branch">Bank Branch</label>
                    <input type="text" id="bank_branch" name="bank_branch" class="form-control"
                        placeholder="Enter bank branch" value="{{ old('bank_branch', $branch->bank_branch) }}" maxlength="150">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="bank_account_name">Account Name</label>
                    <input type="text" id="bank_account_name" name="bank_account_name" class="form-control"
                        placeholder="Registered account name" value="{{ old('bank_account_name', $branch->bank_account_name) }}" maxlength="200">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="bank_account_number">Bank Account Number</label>
                    <input type="text" id="bank_account_number" name="bank_account_number" class="form-control"
                        placeholder="Enter bank account number" value="{{ old('bank_account_number', $branch->bank_account_number) }}" maxlength="100">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="bank_swift_code">SWIFT / BIC Code</label>
                    <input type="text" id="bank_swift_code" name="bank_swift_code" class="form-control"
                        placeholder="Enter SWIFT/BIC code if applicable" value="{{ old('bank_swift_code', $branch->bank_swift_code) }}" maxlength="50">
                </div>

                {{-- ===================================================== --}}
                {{-- MOBILE MONEY INFORMATION --}}
                {{-- ===================================================== --}}

                <div class="col-md-12 mt-4">
                    <h5 class="border-bottom pb-2">
                        <i class="fa fa-mobile"></i> Mobile Money Information
                    </h5>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="mobile_money_provider">Mobile Money Provider</label>
                    <select id="mobile_money_provider" name="mobile_money_provider" class="form-control">
                        <option value="">Select Provider</option>
                        @foreach(['M-Pesa', 'Mixx by Yas', 'Airtel Money', 'HaloPesa', 'Other'] as $provider)
                            <option value="{{ $provider }}" {{ old('mobile_money_provider', $branch->mobile_money_provider) == $provider ? 'selected' : '' }}>
                                {{ $provider }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="mobile_money_number">Mobile Money Number</label>
                    <input type="text" id="mobile_money_number" name="mobile_money_number" class="form-control"
                        placeholder="e.g. +255..." value="{{ old('mobile_money_number', $branch->mobile_money_number) }}" maxlength="30">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="mobile_money_account_name">Mobile Money Account Name</label>
                    <input type="text" id="mobile_money_account_name" name="mobile_money_account_name" class="form-control"
                        placeholder="Registered account name" value="{{ old('mobile_money_account_name', $branch->mobile_money_account_name) }}" maxlength="200">
                </div>

                {{-- ===================================================== --}}
                {{-- PAYMENT SETTINGS --}}
                {{-- ===================================================== --}}

                <div class="col-md-12 mt-4">
                    <h5 class="border-bottom pb-2">
                        <i class="fa fa-money"></i> Payment Settings
                    </h5>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="default_payment_method">Default Payment Method</label>
                    <select id="default_payment_method" name="default_payment_method" class="form-control">
                        @foreach(['CASH', 'BANK_TRANSFER', 'CHEQUE', 'MOBILE_MONEY'] as $method)
                            <option value="{{ $method }}" {{ old('default_payment_method', $branch->default_payment_method ?? 'CASH') == $method ? 'selected' : '' }}>
                                {{ ucwords(strtolower(str_replace('_', ' ', $method))) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="default_currency">Default Currency</label>
                    <select id="default_currency" name="default_currency" class="form-control">
                        @foreach(['TZS', 'USD', 'KES', 'UGX'] as $currency)
                            <option value="{{ $currency }}" {{ old('default_currency', $branch->default_currency ?? 'TZS') == $currency ? 'selected' : '' }}>
                                {{ $currency }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="payment_reference_prefix">Payment Reference Prefix</label>
                    <input type="text" id="payment_reference_prefix" name="payment_reference_prefix" class="form-control"
                        placeholder="e.g. IFLSG-DAR" value="{{ old('payment_reference_prefix', $branch->payment_reference_prefix) }}" maxlength="50">
                </div>

                {{-- ===================================================== --}}
                {{-- FINANCE CONTACT INFORMATION --}}
                {{-- ===================================================== --}}

                <div class="col-md-12 mt-4">
                    <h5 class="border-bottom pb-2">
                        <i class="fa fa-address-card"></i> Finance Contact Information
                    </h5>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="finance_contact_person">Finance Contact Person</label>
                    <input type="text" id="finance_contact_person" name="finance_contact_person" class="form-control"
                        placeholder="Finance officer name" value="{{ old('finance_contact_person', $branch->finance_contact_person) }}" maxlength="150">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="finance_phone">Finance Phone</label>
                    <input type="text" id="finance_phone" name="finance_phone" class="form-control"
                        placeholder="Finance contact number" value="{{ old('finance_phone', $branch->finance_phone) }}" maxlength="30">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="finance_email">Finance Email</label>
                    <input type="email" id="finance_email" name="finance_email" class="form-control"
                        placeholder="finance@company.com" value="{{ old('finance_email', $branch->finance_email) }}" maxlength="150">
                </div>

                {{-- ===================================================== --}}
                {{-- TAX INFORMATION --}}
                {{-- ===================================================== --}}

                <div class="col-md-12 mt-4">
                    <h5 class="border-bottom pb-2">
                        <i class="fa fa-file-text"></i> Tax Information
                    </h5>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="tin_number">TIN Number</label>
                    <input type="text" id="tin_number" name="tin_number" class="form-control"
                        placeholder="Enter TIN number" value="{{ old('tin_number', $branch->tin_number) }}" maxlength="50">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="vrn_number">VRN Number</label>
                    <input type="text" id="vrn_number" name="vrn_number" class="form-control"
                        placeholder="Enter VRN number if registered" value="{{ old('vrn_number', $branch->vrn_number) }}" maxlength="50">
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