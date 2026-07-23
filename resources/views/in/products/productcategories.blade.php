@extends('layouts.configside')
@section('title', 'Product Categories Portfolio')
@section('page-title', 'Product Categories Management')

@section('content')
<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-boxes"></i></div>Product Categories Portfolio</h3>
    <button class="arbif-btn-submit" data-bs-toggle="modal" data-bs-target="#addCategoryFormModal">
        <i class="fas fa-plus-circle me-1"></i> Add Product Category
    </button>
</div>

<!-- Main Datatable Index Panel -->
<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="productCategoriesTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Category Code</th>
                        <th class="sortable">Category Name</th>
                        <th class="sortable">Business Line</th>
                        <th class="sortable">Company Entity</th>
                        <th class="sortable">Branch Base</th>
                        <th class="sortable">Display Order</th>
                        <th class="sortable">Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productCategories as $index => $category)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><span class="arbif-badge arbif-badge-navy">{{ $category->category_code }}</span></td>
                        <td>
                            <div class="fw-bold text-dark">{{ $category->category_name }}</div>
                            @if($category->description)
                                <small class="text-muted d-block text-truncate" style="max-width: 200px;">
                                    {{ $category->description }}
                                </small>
                            @endif
                        </td>
                        <td>{{ $category->business->business_name ?? '—' }}</td>
                        <td>{{ $category->company->company_name ?? '—' }}</td>
                        <td>{{ $category->branch->branch_name ?? '—' }}</td>
                        <td><span class="badge bg-secondary">{{ $category->display_order ?? 0 }}</span></td>
                        <td>
                            <span class="arbif-badge {{ $category->Status === 'Active' ? 'bg-success' : 'bg-danger' }} text-white">
                                {{ $category->Status ?? 'Active' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('viewproductcategories', [encrypt($category->id)]) }}" class="arbif-btn-edit">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a href="{{ route('editproductcategories', [encrypt($category->id)]) }}" class="arbif-btn-edit">
                                <i class="fas fa-pencil"></i> Edit
                            </a>
                            <a onclick="confirmDelete()" href="{{ route('destroyproductcategories', [encrypt($category->id)]) }}" class="arbif-btn-delete">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="arbif-table-empty">
                            <i class="bi bi-inbox"></i>
                            No active product categories exist in the portfolio.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="arbif-pagination">
            <span class="arbif-pagination-info" data-table-info="productCategoriesTable"></span>
            <div class="arbif-pagination-pages" data-table-pages="productCategoriesTable"></div>
        </div>
    </div>
</div>

<!-- Category Onboarding Modal -->
<div class="modal fade arbif-modal" id="addCategoryFormModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon"><i class="fas fa-folder-plus"></i></div>
                <h5 class="modal-title">Create Product Category Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <form method="POST" id="dataFormFill" action="{{ route('storeproductcategories') }}" enctype="multipart/form-data">
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

                    <!-- SECTION 1: CORE CATEGORY IDENTITY -->
                    <h5 class="text-navy mb-3 pb-1 border-bottom fw-bold">
                        <i class="fas fa-tag me-2"></i> 1. Core Category Information
                    </h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label" for="category_code">Category Code <span class="text-danger">*</span></label>
                            <input type="text" id="category_code" name="category_code" class="form-control" placeholder="e.g. CAT-001" value="{{ old('category_code') }}" required>
                        </div>

                        <div class="col-md-5">
                            <label class="form-label" for="category_name">Category Display Name <span class="text-danger">*</span></label>
                            <input type="text" id="category_name" name="category_name" class="form-control" placeholder="e.g. Electronics & Hardware" value="{{ old('category_name') }}" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" for="display_order">Display Sequence Order</label>
                            <input type="number" id="display_order" name="display_order" class="form-control" placeholder="0" value="{{ old('display_order', 0) }}" min="0">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label" for="description">Category Overview & Scope Description</label>
                            <textarea id="description" name="description" class="form-control" rows="3" placeholder="Brief details about products belonging to this category...">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <!-- SECTION 2: ORGANIZATIONAL & BUSINESS SCOPE -->
                    <h5 class="text-navy mb-3 pb-1 border-bottom fw-bold">
                        <i class="fas fa-sitemap me-2"></i> 2. Organizational Matrix & Scope
                    </h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Company Business Code <span class="text-danger">*</span></label>
                            <select style="width: 100%" name="CompanyBusinessCode_id" data-searchable data-placeholder="Select Business Code..." required>
                                <option></option>
                                @foreach($businessCodes as $code)
                                    <option value="{{ $code->id }}" {{ old('CompanyBusinessCode_id') == $code->id ? 'selected' : '' }}>
                                        {{ $code->code_name ?? $code->business_code }} - {{ $code->business_name  }} 
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Corporate Body Placement <span class="text-danger">*</span></label>
                            <select style="width: 100%" name="company_id" data-searchable data-placeholder="Select Corporate Scope..." required>
                                <option></option>
                                @foreach($companies as $comp)
                                    <option value="{{ $comp->id }}" {{ old('company_id') == $comp->id ? 'selected' : '' }}>
                                        {{ $comp->company_code }} - {{ $comp->company_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Operational Branch Base <span class="text-danger">*</span></label>
                            <select style="width: 100%" name="branch_id" data-searchable data-placeholder="Select Branch Anchor..." required>
                                <option></option>
                                @foreach($branches as $br)
                                    <option value="{{ $br->id }}" {{ old('branch_id') == $br->id ? 'selected' : '' }}>
                                        {{ $br->branch_code }} - {{ $br->branch_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer shadow-sm bg-light rounded-bottom p-3" style="margin-top: 30px;">
                        <button type="button" class="arbif-btn-cancel" data-bs-dismiss="modal">
                            <i class="bi bi-x"></i> Cancel
                        </button>
                        <button onclick="confirmSubmit()" type="submit" class="arbif-btn-submit">
                            <i class="bi bi-check2"></i> <span id="submitBtnText">Commit Category Profile</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection