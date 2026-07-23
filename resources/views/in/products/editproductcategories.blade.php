@extends('layouts.configside')
@section('title', 'Modify Product Category')
@section('page-title', 'Modify Product Category Profile')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-edit"></i></div>
        Edit Category: {{ $productCategory->category_name }}
    </h3>
    <a href="{{ url()->previous() }}" class="arbif-btn-cancel text-decoration-none d-inline-flex align-items-center">
        <i class="fas fa-arrow-left me-1"></i> Cancel & Return
    </a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <form method="POST" id="dataFormFill" action="{{ route('updateproductcategories', [encrypt($productCategory->id)]) }}" enctype="multipart/form-data">
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

            <!-- SECTION 1: CORE CATEGORY IDENTIFIERS -->
            <h5 class="text-navy mb-3 pb-1 border-bottom fw-bold"><i class="fas fa-tag me-2"></i> 1. Core Category Information</h5>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label" for="category_code">Category Code <span class="text-danger">*</span></label>
                    <input type="text" id="category_code" name="category_code" class="form-control" value="{{ old('category_code', $productCategory->category_code) }}" required>
                </div>

                <div class="col-md-5">
                    <label class="form-label" for="category_name">Category Display Name <span class="text-danger">*</span></label>
                    <input type="text" id="category_name" name="category_name" class="form-control" value="{{ old('category_name', $productCategory->category_name) }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="display_order">Display Sequence Order</label>
                    <input type="number" id="display_order" name="display_order" class="form-control" value="{{ old('display_order', $productCategory->display_order) }}" min="0">
                </div>

                <div class="col-md-12">
                    <label class="form-label" for="description">Category Overview & Scope Description</label>
                    <textarea id="description" name="description" class="form-control" rows="3">{{ old('description', $productCategory->description) }}</textarea>
                </div>
            </div>

            <!-- SECTION 2: ORGANIZATIONAL SCOPE & BUSINESS LINE -->
            <h5 class="text-navy mb-3 pb-1 border-bottom fw-bold"><i class="fas fa-sitemap me-2"></i> 2. Organizational Matrix Scope</h5>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Company Business Code <span class="text-danger">*</span></label>
                    <select style="width: 100%" name="CompanyBusinessCode_id" data-searchable data-placeholder="Select Business Code..." required>
                        <option></option>
                        @foreach($businessCodes as $code)
                            <option value="{{ $code->id }}" {{ old('CompanyBusinessCode_id', $productCategory->CompanyBusinessCode_id) == $code->id ? 'selected' : '' }}>
                                {{ $code->code_name ?? $code->business_code }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Corporate Body Placement <span class="text-danger">*</span></label>
                    <select style="width: 100%" name="company_id" data-searchable data-placeholder="Select Corporate Scope..." required>
                        <option></option>
                        @foreach($companies as $comp)
                            <option value="{{ $comp->id }}" {{ old('company_id', $productCategory->company_id) == $comp->id ? 'selected' : '' }}>
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
                            <option value="{{ $br->id }}" {{ old('branch_id', $productCategory->branch_id) == $br->id ? 'selected' : '' }}>
                                {{ $br->branch_code }} - {{ $br->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-end gap-2 border-top pt-3" style="margin-top: 30px;">
                <a href="{{ url()->previous() }}" class="arbif-btn-cancel text-decoration-none">
                    <i class="bi bi-x"></i> Cancel
                </a>
                <button onclick="confirmSubmit()" type="submit" class="arbif-btn-submit">
                    <i class="bi bi-check2"></i> <span id="submitBtnText">Commit Category Updates</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection