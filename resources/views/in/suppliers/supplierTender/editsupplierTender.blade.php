@extends('layouts.workingside')

@section('title', 'Edit Supplier Tender')
@section('page-title', 'Edit Supplier Tender')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-edit"></i>
        </div>
        Edit Supplier Tender: {{ $data->tender_no }}
    </h3>

    <div>
        <a href="{{ route('supplierTender') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
            <i class="fas fa-arrow-left"></i> Back to Tenders
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <form method="POST" action="{{ route('updatesupplierTender', encrypt($data->id)) }}">
            @csrf
            @method('PUT')

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label">Assigned Branch <span class="text-danger">*</span></label>
                    <select name="branch_id" class="form-select @error('branch_id') is-invalid @enderror" data-searchable required>
                        <option value="">-- Select Branch --</option>
                        @foreach($branchies as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id', $data->branch_id) == $branch->id ? 'selected' : '' }}>
                                {{ $branch->branch_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('branch_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tender No <span class="text-danger">*</span></label>
                    <input type="text" name="tender_no" class="form-control @error('tender_no') is-invalid @enderror" value="{{ old('tender_no', $data->tender_no) }}" required>
                    @error('tender_no') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Purchase Request (PR)</label>
                    <select name="purchase_request_id" class="form-select @error('purchase_request_id') is-invalid @enderror" data-searchable>
                        <option value="">-- Select Purchase Request --</option>
                        @foreach($purchaseRequests as $pr)
                            <option value="{{ $pr->id }}" {{ old('purchase_request_id', $data->purchase_request_id) == $pr->id ? 'selected' : '' }}>
                                {{ $pr->pr_no ?? 'PR #'.$pr->id }}
                            </option>
                        @endforeach
                    </select>
                    @error('purchase_request_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-8">
                    <label class="form-label">Tender Title <span class="text-danger">*</span></label>
                    <input type="text" name="tender_title" class="form-control @error('tender_title') is-invalid @enderror" value="{{ old('tender_title', $data->tender_title) }}" required>
                    @error('tender_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tender Status <span class="text-danger">*</span></label>
                    <select name="tender_status" class="form-select @error('tender_status') is-invalid @enderror" required>
                        @foreach(['Draft', 'Published', 'Under Evaluation', 'Awarded', 'Cancelled'] as $status)
                            <option value="{{ $status }}" {{ old('tender_status', $data->tender_status) == $status ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                    @error('tender_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <label class="form-label">Tender Date <span class="text-danger">*</span></label>
                    <input type="date" name="tender_date" class="form-control @error('tender_date') is-invalid @enderror" value="{{ old('tender_date', \Carbon\Carbon::parse($data->tender_date)->format('Y-m-d')) }}" required>
                    @error('tender_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Closing Date <span class="text-danger">*</span></label>
                    <input type="date" name="closing_date" class="form-control @error('closing_date') is-invalid @enderror" value="{{ old('closing_date', \Carbon\Carbon::parse($data->closing_date)->format('Y-m-d')) }}" required>
                    @error('closing_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Currency</label>
                    <input type="text" name="currency_code" class="form-control @error('currency_code') is-invalid @enderror" value="{{ old('currency_code', $data->currency_code) }}">
                    @error('currency_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Estimated Budget</label>
                    <input type="number" step="0.01" name="estimated_budget" class="form-control @error('estimated_budget') is-invalid @enderror" value="{{ old('estimated_budget', $data->estimated_budget) }}">
                    @error('estimated_budget') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label">Evaluation Method</label>
                    <input type="text" name="evaluation_method" class="form-control @error('evaluation_method') is-invalid @enderror" value="{{ old('evaluation_method', $data->evaluation_method) }}">
                    @error('evaluation_method') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Awarded Supplier</label>
                    <select name="awarded_supplier_id" class="form-select @error('awarded_supplier_id') is-invalid @enderror" data-searchable>
                        <option value="">-- None / Pending --</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('awarded_supplier_id', $data->awarded_supplier_id) == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->supplier_name ?? $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('awarded_supplier_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Awarded At</label>
                    <input type="date" name="awarded_at" class="form-control @error('awarded_at') is-invalid @enderror" value="{{ old('awarded_at', $data->awarded_at ? \Carbon\Carbon::parse($data->awarded_at)->format('Y-m-d') : '') }}">
                    @error('awarded_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Tender Description</label>
                    <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $data->description) }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Award Comment / Decision Note</label>
                    <textarea name="award_comment" rows="4" class="form-control @error('award_comment') is-invalid @enderror">{{ old('award_comment', $data->award_comment) }}</textarea>
                    @error('award_comment') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('supplierTender') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="arbif-btn-submit">Update Tender</button>
            </div>
        </form>
    </div>
</div>

@endsection