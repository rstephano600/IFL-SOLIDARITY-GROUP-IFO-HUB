@extends('layouts.configside')
@section('title', 'Edit Share Type')
@section('page-title', 'Edit Share Type Profile')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-pencil"></i></div>
        Edit Share Type: <span class="text-navy ms-1">{{ $shareType->TypeName }}</span>
    </h3>
    <a href="{{ route('sharetypes') }}" class="arbif-btn-cancel text-decoration-none">
        <i class="fas fa-arrow-left me-1"></i> Back to Portfolio
    </a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body p-4">
        <form method="POST" id="dataFormFill" action="{{ route('updatesharetypes', [encrypt($shareType->id)]) }}" enctype="multipart/form-data">
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

            <!-- SECTION 1: IDENTITY & PRICING -->
            <h5 class="text-navy mb-3 pb-2 border-bottom fw-bold">
                <i class="fas fa-tag me-2"></i> 1. Share Type Details
            </h5>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label" for="TypeCode">Type Code <span class="text-danger">*</span></label>
                    <input type="text" id="TypeCode" name="TypeCode" class="form-control" placeholder="e.g. ST-001" value="{{ old('TypeCode', $shareType->TypeCode) }}" required>
                </div>

                <div class="col-md-8">
                    <label class="form-label" for="TypeName">Type Name <span class="text-danger">*</span></label>
                    <input type="text" id="TypeName" name="TypeName" class="form-control" placeholder="e.g. Ordinary Shares" value="{{ old('TypeName', $shareType->TypeName) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="NominalValue">Nominal Value <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" id="NominalValue" name="NominalValue" class="form-control" placeholder="0.00" value="{{ old('NominalValue', $shareType->NominalValue) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="Status">Status <span class="text-danger">*</span></label>
                    <select class="form-select" name="Status" id="Status" required>
                        <option value="Active" {{ old('Status', $shareType->Status) === 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ old('Status', $shareType->Status) === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <div class="form-check form-switch mt-4 pt-2">
                        <input class="form-check-input" type="checkbox" id="DividendEligible" name="DividendEligible" value="1" {{ old('DividendEligible', $shareType->DividendEligible) ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="DividendEligible">Eligible for Dividends</label>
                    </div>
                </div>

                <div class="col-md-12">
                    <label class="form-label" for="Description">Description</label>
                    <textarea id="Description" name="Description" class="form-control" rows="3" placeholder="Brief details about this share type...">{{ old('Description', $shareType->Description) }}</textarea>
                </div>
            </div>

            <!-- SECTION 2: ORGANIZATIONAL ASSIGNMENT -->
            <h5 class="text-navy mb-3 pb-2 border-bottom fw-bold">
                <i class="fas fa-building me-2"></i> 2. Organizational Scope
            </h5>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Company Entity <span class="text-danger">*</span></label>
                    <select style="width: 100%" name="company_id" data-searchable data-placeholder="Select Company..." required>
                        <option></option>
                        @foreach($companies as $comp)
                            <option value="{{ $comp->id }}" {{ old('company_id', $shareType->company_id) == $comp->id ? 'selected' : '' }}>
                                {{ $comp->company_code ?? '' }} - {{ $comp->company_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Operational Branch <span class="text-danger">*</span></label>
                    <select style="width: 100%" name="branch_id" data-searchable data-placeholder="Select Branch..." required>
                        <option></option>
                        @foreach($branches as $br)
                            <option value="{{ $br->id }}" {{ old('branch_id', $shareType->branch_id) == $br->id ? 'selected' : '' }}>
                                {{ $br->branch_code ?? '' }} - {{ $br->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- FOOTER ACTIONS -->
            <div class="d-flex justify-content-end gap-2 border-top pt-3">
                <a href="{{ route('sharetypes') }}" class="arbif-btn-cancel text-decoration-none">
                    <i class="bi bi-x"></i> Cancel
                </a>
                <button onclick="confirmSubmit()" type="submit" class="arbif-btn-submit">
                    <i class="bi bi-check2"></i> <span id="submitBtnText">Update Share Type</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection