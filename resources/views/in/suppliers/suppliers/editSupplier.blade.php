@extends('layouts.workingside')

@section('title', 'Edit Supplier')
@section('page-title', 'Edit Supplier')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-pencil-alt"></i>
        </div>
        Edit Supplier Details
    </h3>

    <div>
        <a href="{{ route('suppliers') }}" class="btn btn-secondary d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to Suppliers
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body p-4">
        <form method="POST" action="{{ route('updateSupplier', encrypt($data->id)) }}">
            @csrf

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Assigned Branch <span class="text-danger">*</span></label>
                    <select name="branch_id" class="form-select" data-searchable data-placeholder="Search ..." required>
                        <option value="">-- Select Branch --</option>
                        @foreach($branchies as $branch)
                            <option value="{{ $branch->id }}" {{ $data->branch_id == $branch->id ? 'selected' : '' }}>
                                {{ $branch->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Supplier Category <span class="text-danger">*</span></label>
                    <select name="SupplierCategory_id" class="form-select" required>
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $data->SupplierCategory_id == $category->id ? 'selected' : '' }}>
                                {{ $category->CategoryName }} ({{ $category->SupplierGroup }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Supplier / Company Name <span class="text-danger">*</span></label>
                    <input type="text" name="SupplierName" class="form-control" value="{{ old('SupplierName', $data->SupplierName) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Supplier Code</label>
                    <input type="text" name="SupplierCode" class="form-control" value="{{ old('SupplierCode', $data->SupplierCode) }}" placeholder="e.g. EXT-001">
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="Phone" class="form-control" value="{{ old('Phone', $data->Phone) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Alternative Phone</label>
                    <input type="text" name="AlternativePhone" class="form-control" value="{{ old('AlternativePhone', $data->AlternativePhone) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="Email" class="form-control" value="{{ old('Email', $data->Email) }}">
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Contact Person</label>
                    <input type="text" name="ContactPerson" class="form-control" value="{{ old('ContactPerson', $data->ContactPerson) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">City / Location</label>
                    <input type="text" name="City" class="form-control" value="{{ old('City', $data->City) }}">
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Region</label>
                    <input type="text" name="Region" class="form-control" value="{{ old('Region', $data->Region) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Country</label>
                    <input type="text" name="Country" class="form-control" value="{{ old('Country', $data->Country ?? 'Tanzania') }}">
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">TIN Number</label>
                    <input type="text" name="TinNumber" class="form-control" value="{{ old('TinNumber', $data->TinNumber) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">VAT Number</label>
                    <input type="text" name="VatNumber" class="form-control" value="{{ old('VatNumber', $data->VatNumber) }}">
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label">Credit Limit</label>
                    <input type="number" step="0.01" min="0" name="CreditLimit" class="form-control" value="{{ old('CreditLimit', $data->CreditLimit) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Opening Balance</label>
                    <input type="number" step="0.01" min="0" name="OpeningBalance" class="form-control" value="{{ old('OpeningBalance', $data->OpeningBalance) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Payment Terms</label>
                    <input type="text" name="PaymentTerms" class="form-control" value="{{ old('PaymentTerms', $data->PaymentTerms) }}" placeholder="e.g. Net 30 Days">
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Physical Address</label>
                <textarea name="Address" rows="3" class="form-control">{{ old('Address', $data->Address) }}</textarea>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('suppliers') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="arbif-btn-submit">Update Supplier</button>
            </div>
        </form>
    </div>
</div>

@endsection