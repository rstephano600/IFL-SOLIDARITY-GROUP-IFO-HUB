@extends('layouts.workingside')

@section('title', 'Register Supplier Award')
@section('page-title', 'Register Supplier Award')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-award"></i>
        </div>
        Register Supplier Award
    </h3>

    <div>
        <a href="{{ route('supplierAward') }}" class="btn btn-secondary px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to List
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body p-4">
        <form method="POST" action="{{ route('storesupplierAward') }}">
            @csrf

            <h6 class="fw-bold text-primary mb-3"><i class="fas fa-info-circle me-1"></i> Award Details & Associations</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Branch <span class="text-danger">*</span></label>
                    <select name="branch_id" class="form-select" data-searchable data-placeholder="Search ..." required>
                        <option value="">-- Select Branch --</option>
                        @foreach($branchies as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Awarded Supplier <span class="text-danger">*</span></label>
                    <select name="supplier_id" class="form-select" data-searchable data-placeholder="Search ..." required>
                        <option value="">-- Select Supplier --</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->SupplierName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Associated Purchase Request</label>
                    <select name="purchase_request_id" class="form-select"data-searchable data-placeholder="Search ..." >
                        <option value="">-- Select Approved Request (Optional) --</option>
                        @foreach($purchaseRequests as $pr)
                            <option value="{{ $pr->id }}" {{ old('purchase_request_id') == $pr->id ? 'selected' : '' }}>
                                {{ $pr->request_no }} - {{ $pr->purpose ?? 'PR' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Associated Tender</label>
                    <select name="tender_id" class="form-select" data-searchable data-placeholder="Search ...">
                        <option value="">-- Select Tender (Optional) --</option>
                        @foreach($tenders as $tender)
                            <option value="{{ $tender->id }}" {{ old('tender_id') == $tender->id ? 'selected' : '' }}>
                                {{ $tender->tender_no ?? 'Tender #' . $tender->id }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Supplier Quotation</label>
                    <select name="supplier_quotation_id" class="form-select" data-searchable data-placeholder="Search ..." >
                        <option value="">-- Select Quotation (Optional) --</option>
                        @foreach($quotations as $quotation)
                            <option value="{{ $quotation->id }}" {{ old('supplier_quotation_id') == $quotation->id ? 'selected' : '' }}>
                                {{ $quotation->quotation_no ?? 'Quotation #' . $quotation->id }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Award Date <span class="text-danger">*</span></label>
                    <input type="date" name="award_date" class="form-control" value="{{ old('award_date', date('Y-m-d')) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Award Amount <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="award_amount" class="form-control" value="{{ old('award_amount', 0) }}" min="0" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Currency <span class="text-danger">*</span></label>
                    <select name="currency_code" class="form-select" required>
                        <option value="TZS" {{ old('currency_code', 'TZS') == 'TZS' ? 'selected' : '' }}>TZS</option>
                        <option value="USD" {{ old('currency_code') == 'USD' ? 'selected' : '' }}>USD</option>
                        <option value="EUR" {{ old('currency_code') == 'EUR' ? 'selected' : '' }}>EUR</option>
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label">Award Comments / Remarks</label>
                    <textarea name="award_comment" class="form-control" rows="3" placeholder="Provide reason or evaluation summary for awarding this supplier...">{{ old('award_comment') }}</textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('supplierAward') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="arbif-btn-submit">Save Award</button>
            </div>
        </form>
    </div>
</div>

@endsection