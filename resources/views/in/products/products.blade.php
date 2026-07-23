@extends('layouts.configside')
@section('title', 'Product Catalogue')
@section('page-title', 'Products Management')

@section('content')
<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-box-open"></i></div>Product Catalogue Portfolio</h3>
    <button class="arbif-btn-submit" data-bs-toggle="modal" data-bs-target="#addProductModal">
        <i class="fas fa-plus-circle me-1"></i> Add New Product
    </button>
</div>

<!-- Main Datatable Index Panel -->
<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="productsTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Product Code</th>
                        <th class="sortable">Product Name</th>
                        <th class="sortable">Category</th>
                        <th class="sortable">Cost Price</th>
                        <th class="sortable">Selling Price</th>
                        <th class="sortable">UOM</th>
                        <th class="sortable">Type</th>
                        <th class="sortable">Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><span class="arbif-badge arbif-badge-navy">{{ $item->product_code }}</span></td>
                        <td>
                            <div class="fw-bold text-dark">{{ $item->product_name }}</div>
                            <small class="text-muted">{{ $item->company->company_name ?? '—' }}</small>
                        </td>
                        <td>{{ $item->category->category_name ?? '—' }}</td>
                        <td>{{ number_format($item->cost_price, 2) }}</td>
                        <td><strong class="text-navy">{{ number_format($item->selling_price, 2) }}</strong></td>
                        <td><span class="badge bg-light text-dark border">{{ $item->unit->UnitName ?? $item->unit->symbol ?? '—' }}</span></td>
                        <td>
                            @if($item->is_stock_item)
                                <span class="badge bg-info text-dark"><i class="fas fa-cubes me-1"></i> Stock</span>
                            @else
                                <span class="badge bg-secondary"><i class="fas fa-concierge-bell me-1"></i> Service</span>
                            @endif
                        </td>
                        <td>
                            <span class="arbif-badge {{ $item->Status === 'Active' ? 'bg-success' : 'bg-danger' }} text-white">
                                {{ $item->Status ?? 'Active' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('viewproducts', [encrypt($item->id)]) }}" class="arbif-btn-edit">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a href="{{ route('editproducts', [encrypt($item->id)]) }}" class="arbif-btn-edit">
                                <i class="fas fa-pencil"></i> Edit
                            </a>
                            <a onclick="confirmDelete()" href="{{ route('destroyproducts', [encrypt($item->id)]) }}" class="arbif-btn-delete">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="arbif-table-empty">
                            <i class="bi bi-inbox"></i>
                            No active products exist in the catalogue.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="arbif-pagination">
            <span class="arbif-pagination-info" data-table-info="productsTable"></span>
            <div class="arbif-pagination-pages" data-table-pages="productsTable"></div>
        </div>
    </div>
</div>

<!-- Product Creation Modal -->
<div class="modal fade arbif-modal" id="addProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon"><i class="fas fa-box"></i></div>
                <h5 class="modal-title">Create New Product Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <form method="POST" id="dataFormFill" action="{{ route('storeproducts') }}" enctype="multipart/form-data">
                    @csrf

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
                    <h5 class="text-navy mb-3 pb-1 border-bottom fw-bold">
                        <i class="fas fa-tag me-2"></i> 1. Basic Identity & Classification
                    </h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label class="form-label" for="product_code">Product Code <span class="text-danger">*</span></label>
                            <input type="text" id="product_code" name="product_code" class="form-control" placeholder="e.g. PRD-001" value="{{ old('product_code') }}" required>
                        </div>

                        <div class="col-md-5">
                            <label class="form-label" for="product_name">Product Name <span class="text-danger">*</span></label>
                            <input type="text" id="product_name" name="product_name" class="form-control" placeholder="e.g. Wireless Router AC1200" value="{{ old('product_name') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Product Category <span class="text-danger">*</span></label>
                            <select style="width: 100%" name="product_category_id" data-searchable data-placeholder="Select Category..." required>
                                <option></option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('product_category_id') == $cat->id ? 'selected' : '' }}>
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
                                    <option value="{{ $uom->id }}" {{ old('unit_of_measure_id') == $uom->id ? 'selected' : '' }}>
                                        {{ $uom->UnitName }} {{ isset($uom->symbol) ? "({$uom->symbol})" : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label" for="description">Description & Specifications</label>
                            <textarea id="description" name="description" class="form-control" rows="1" placeholder="Product details...">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <!-- SECTION 2: PRICING & TAXATION -->
                    <h5 class="text-navy mb-3 pb-1 border-bottom fw-bold">
                        <i class="fas fa-coins me-2"></i> 2. Pricing Structures & Tax Rate
                    </h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-2-4 col-sm-6">
                            <label class="form-label" for="cost_price">Cost Price <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" id="cost_price" name="cost_price" class="form-control" placeholder="0.00" value="{{ old('cost_price', '0.00') }}" required>
                        </div>
                        <div class="col-md-2-4 col-sm-6">
                            <label class="form-label" for="selling_price">Selling Price <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" id="selling_price" name="selling_price" class="form-control" placeholder="0.00" value="{{ old('selling_price', '0.00') }}" required>
                        </div>
                        <div class="col-md-2-4 col-sm-6">
                            <label class="form-label" for="minimum_price">Min Selling Price</label>
                            <input type="number" step="0.01" id="minimum_price" name="minimum_price" class="form-control" placeholder="0.00" value="{{ old('minimum_price', '0.00') }}">
                        </div>
                        <div class="col-md-2-4 col-sm-6">
                            <label class="form-label" for="maximum_price">Max Selling Price</label>
                            <input type="number" step="0.01" id="maximum_price" name="maximum_price" class="form-control" placeholder="0.00" value="{{ old('maximum_price', '0.00') }}">
                        </div>
                        <div class="col-md-2-4 col-sm-6">
                            <label class="form-label" for="tax_rate">Tax Rate (%)</label>
                            <input type="number" step="0.01" id="tax_rate" name="tax_rate" class="form-control" placeholder="18.00" value="{{ old('tax_rate', '0.00') }}">
                        </div>
                    </div>

                    <!-- SECTION 3: FINANCIAL & GENERAL LEDGER MAPPING -->
                    <h5 class="text-navy mb-3 pb-1 border-bottom fw-bold">
                        <i class="fas fa-calculator me-2"></i> 3. GL Account Accounting Mapping
                    </h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Income GL Account <span class="text-danger"></span></label>
                            <select style="width: 100%" name="income_gl_account_id" data-searchable data-placeholder="Select Income Account..." required>
                                <option></option>
                                @foreach($glAccounts as $gl)
                                    <option value="{{ $gl->id }}" {{ old('income_gl_account_id') == $gl->id ? 'selected' : '' }}>
                                        {{ $gl->ThirdAccountCode ?? $gl->id }} - {{ $gl->ThirdAccountName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Expense GL Account <span class="text-danger"></span></label>
                            <select style="width: 100%" name="expense_gl_account_id" data-searchable data-placeholder="Select Expense Account..." required>
                                <option></option>
                                @foreach($glAccounts as $gl)
                                    <option value="{{ $gl->id }}" {{ old('expense_gl_account_id') == $gl->id ? 'selected' : '' }}>
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
                                    <option value="{{ $gl->id }}" {{ old('inventory_gl_account_id') == $gl->id ? 'selected' : '' }}>
                                        {{ $gl->ThirdAccountCode ?? $gl->id }} - {{ $gl->ThirdAccountName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- SECTION 4: ORGANIZATIONAL SCOPE & BEHAVIOR CONTROL -->
                    <h5 class="text-navy mb-3 pb-1 border-bottom fw-bold">
                        <i class="fas fa-sliders-h me-2"></i> 4. Scope & Behaviour Settings
                    </h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Business Code <span class="text-danger">*</span></label>
                            <select style="width: 100%" name="CompanyBusinessCode_id" data-searchable data-placeholder="Select Business Code..." required>
                                <option></option>
                                @foreach($businessCodes as $code)
                                    <option value="{{ $code->id }}" {{ old('CompanyBusinessCode_id') == $code->id ? 'selected' : '' }}>
                                        ({{ $code->business_code }} ){{ $code->business_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Company Entity <span class="text-danger">*</span></label>
                            <select style="width: 100%" name="company_id" data-searchable data-placeholder="Select Company..." required>
                                <option></option>
                                @foreach($companies as $comp)
                                    <option value="{{ $comp->id }}" {{ old('company_id') == $comp->id ? 'selected' : '' }}>
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
                                    <option value="{{ $br->id }}" {{ old('branch_id') == $br->id ? 'selected' : '' }}>
                                        {{ $br->branch_code }} - {{ $br->branch_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Flags -->
                        <div class="col-md-3">
                            <div class="form-check form-switch mt-3">
                                <input class="form-check-input" type="checkbox" id="is_stock_item" name="is_stock_item" value="1" {{ old('is_stock_item', 1) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="is_stock_item">Is Tracked Stock Item</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check form-switch mt-3">
                                <input class="form-check-input" type="checkbox" id="allow_discount" name="allow_discount" value="1" {{ old('allow_discount', 1) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="allow_discount">Allow Sales Discount</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check form-switch mt-3">
                                <input class="form-check-input" type="checkbox" id="requires_member" name="requires_member" value="1" {{ old('requires_member') ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="requires_member">Requires Member Assignment</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check form-switch mt-3">
                                <input class="form-check-input" type="checkbox" id="requires_approval" name="requires_approval" value="1" {{ old('requires_approval') ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="requires_approval">Requires Manager Approval</label>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer shadow-sm bg-light rounded-bottom p-3" style="margin-top: 30px;">
                        <button type="button" class="arbif-btn-cancel" data-bs-dismiss="modal">
                            <i class="bi bi-x"></i> Cancel
                        </button>
                        <button onclick="confirmSubmit()" type="submit" class="arbif-btn-submit">
                            <i class="bi bi-check2"></i> <span id="submitBtnText">Commit Product Profile</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection