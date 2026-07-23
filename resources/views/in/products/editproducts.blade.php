@extends('layouts.configside')
@section('title', 'Modify Product Details')
@section('page-title', 'Modify Product Profile')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-edit"></i></div>
        Edit Product: {{ $product->product_name }}
    </h3>
    <a href="{{ url()->previous() }}" class="arbif-btn-cancel text-decoration-none d-inline-flex align-items-center">
        <i class="fas fa-arrow-left me-1"></i> Cancel & Return
    </a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <form method="POST" id="dataFormFill" action="{{ route('updateproducts', [encrypt($product->id)]) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- SECTION 1: IDENTITY & CLASSIFICATION -->
            <h5 class="text-navy mb-3 pb-1 border-bottom fw-bold"><i class="fas fa-tag me-2"></i> 1. Core Identity & Category</h5>
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label" for="product_code">Product Code <span class="text-danger">*</span></label>
                    <input type="text" id="product_code" name="product_code" class="form-control" value="{{ old('product_code', $product->product_code) }}" required>
                </div>

                <div class="col-md-5">
                    <label class="form-label" for="product_name">Product Name <span class="text-danger">*</span></label>
                    <input type="text" id="product_name" name="product_name" class="form-control" value="{{ old('product_name', $product->product_name) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Product Category <span class="text-danger">*</span></label>
                    <select style="width: 100%" name="product_category_id" data-searchable data-placeholder="Select Category..." required>
                        <option></option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('product_category_id', $product->product_category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->category_code }} - {{ $cat->category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Unit of Measure (UOM) <span class="text-danger">*</span></label>
                    <select style="width: 100%" name="unit_of_measure_id" data-searchable data-placeholder="Select UOM..." required>
                        <option></option>
                        @foreach($unitsOfMeasure as $uom)
                            <option value="{{ $uom->id }}" {{ old('unit_of_measure_id', $product->unit_of_measure_id) == $uom->id ? 'selected' : '' }}>
                                {{ $uom->UnitName }} {{ isset($uom->symbol) ? "({$uom->symbol})" : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-8">
                    <label class="form-label" for="description">Description / Specifications</label>
                    <textarea id="description" name="description" class="form-control" rows="1">{{ old('description', $product->description) }}</textarea>
                </div>
            </div>

            <!-- SECTION 2: PRICING STRUCTURE -->
            <h5 class="text-navy mb-3 pb-1 border-bottom fw-bold"><i class="fas fa-coins me-2"></i> 2. Pricing & Taxation</h5>
            <div class="row g-3 mb-4">
                <div class="col-md-2-4 col-sm-6">
                    <label class="form-label" for="cost_price">Cost Price <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" id="cost_price" name="cost_price" class="form-control" value="{{ old('cost_price', $product->cost_price) }}" required>
                </div>
                <div class="col-md-2-4 col-sm-6">
                    <label class="form-label" for="selling_price">Selling Price <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" id="selling_price" name="selling_price" class="form-control" value="{{ old('selling_price', $product->selling_price) }}" required>
                </div>
                <div class="col-md-2-4 col-sm-6">
                    <label class="form-label" for="minimum_price">Min Selling Price</label>
                    <input type="number" step="0.01" id="minimum_price" name="minimum_price" class="form-control" value="{{ old('minimum_price', $product->minimum_price) }}">
                </div>
                <div class="col-md-2-4 col-sm-6">
                    <label class="form-label" for="maximum_price">Max Selling Price</label>
                    <input type="number" step="0.01" id="maximum_price" name="maximum_price" class="form-control" value="{{ old('maximum_price', $product->maximum_price) }}">
                </div>
                <div class="col-md-2-4 col-sm-6">
                    <label class="form-label" for="tax_rate">Tax Rate (%)</label>
                    <input type="number" step="0.01" id="tax_rate" name="tax_rate" class="form-control" value="{{ old('tax_rate', $product->tax_rate) }}">
                </div>
            </div>

            <!-- SECTION 3: ACCOUNTING MAPPING -->
            <h5 class="text-navy mb-3 pb-1 border-bottom fw-bold"><i class="fas fa-calculator me-2"></i> 3. GL Accounting Controls</h5>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Income GL Account <span class="text-danger">*</span></label>
                    <select style="width: 100%" name="income_gl_account_id" data-searchable data-placeholder="Select Income Account..." required>
                        <option></option>
                        @foreach($glAccounts as $gl)
                            <option value="{{ $gl->id }}" {{ old('income_gl_account_id', $product->income_gl_account_id) == $gl->id ? 'selected' : '' }}>
                                {{ $gl->ThirdAccountCode ?? $gl->id }} - {{ $gl->ThirdAccountName }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Expense GL Account <span class="text-danger">*</span></label>
                    <select style="width: 100%" name="expense_gl_account_id" data-searchable data-placeholder="Select Expense Account..." required>
                        <option></option>
                        @foreach($glAccounts as $gl)
                            <option value="{{ $gl->id }}" {{ old('expense_gl_account_id', $product->expense_gl_account_id) == $gl->id ? 'selected' : '' }}>
                                {{ $gl->ThirdAccountCode ?? $gl->id }} - {{ $gl->ThirdAccountName }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Inventory GL Account</label>
                    <select style="width: 100%" name="inventory_gl_account_id" data-searchable data-placeholder="Select Inventory Account...">
                        <option></option>
                        @foreach($glAccounts as $gl)
                            <option value="{{ $gl->id }}" {{ old('inventory_gl_account_id', $product->inventory_gl_account_id) == $gl->id ? 'selected' : '' }}>
                                {{ $gl->ThirdAccountCode ?? $gl->id }} - {{ $gl->ThirdAccountName }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- SECTION 4: SCOPE & FLAGS -->
            <h5 class="text-navy mb-3 pb-1 border-bottom fw-bold"><i class="fas fa-sliders-h me-2"></i> 4. Scope Allocation & Configuration</h5>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Business Code <span class="text-danger">*</span></label>
                    <select style="width: 100%" name="CompanyBusinessCode_id" data-searchable data-placeholder="Select Business Code..." required>
                        <option></option>
                        @foreach($businessCodes as $code)
                            <option value="{{ $code->id }}" {{ old('CompanyBusinessCode_id', $product->CompanyBusinessCode_id) == $code->id ? 'selected' : '' }}>
                                ({{ $code->business_code}}) {{ $code->business_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Company Entity <span class="text-danger">*</span></label>
                    <select style="width: 100%" name="company_id" data-searchable data-placeholder="Select Company..." required>
                        <option></option>
                        @foreach($companies as $comp)
                            <option value="{{ $comp->id }}" {{ old('company_id', $product->company_id) == $comp->id ? 'selected' : '' }}>
                                {{ $comp->company_code }} - {{ $comp->company_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Operational Branch <span class="text-danger">*</span></label>
                    <select style="width: 100%" name="branch_id" data-searchable data-placeholder="Select Branch..." required>
                        <option></option>
                        @foreach($branches as $br)
                            <option value="{{ $br->id }}" {{ old('branch_id', $product->branch_id) == $br->id ? 'selected' : '' }}>
                                {{ $br->branch_code }} - {{ $br->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Switches -->
                <div class="col-md-3">
                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" type="checkbox" id="is_stock_item" name="is_stock_item" value="1" {{ old('is_stock_item', $product->is_stock_item) ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="is_stock_item">Is Tracked Stock Item</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" type="checkbox" id="allow_discount" name="allow_discount" value="1" {{ old('allow_discount', $product->allow_discount) ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="allow_discount">Allow Sales Discount</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" type="checkbox" id="requires_member" name="requires_member" value="1" {{ old('requires_member', $product->requires_member) ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="requires_member">Requires Member</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" type="checkbox" id="requires_approval" name="requires_approval" value="1" {{ old('requires_approval', $product->requires_approval) ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="requires_approval">Requires Approval</label>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-end gap-2 border-top pt-3" style="margin-top: 30px;">
                <a href="{{ url()->previous() }}" class="arbif-btn-cancel text-decoration-none">
                    <i class="bi bi-x"></i> Cancel
                </a>
                <button onclick="confirmSubmit()" type="submit" class="arbif-btn-submit">
                    <i class="bi bi-check2"></i> <span id="submitBtnText">Commit Product Updates</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection