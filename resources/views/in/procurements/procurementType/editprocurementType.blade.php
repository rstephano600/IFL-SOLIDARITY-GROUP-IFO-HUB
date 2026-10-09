@extends('layouts.configside')

@section('title', 'Edit Procurement Type')
@section('page-title', 'Edit Procurement Type')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-pencil-alt"></i>
        </div>
        Edit Procurement Type
    </h3>

    <div>
        <a href="{{ route('procurementType') }}" class="btn btn-secondary d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to List
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body p-4">
        <form method="POST" action="{{ route('updateprocurementType', encrypt($data->id)) }}">
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

                <div class="col-md-3">
                    <label class="form-label">Procurement Code <span class="text-danger">*</span></label>
                    <input type="text" name="procurement_code" class="form-control" value="{{ old('procurement_code', $data->procurement_code) }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Display Order</label>
                    <input type="number" name="display_order" class="form-control" value="{{ old('display_order', $data->display_order) }}" min="1">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Procurement Name <span class="text-danger">*</span></label>
                <input type="text" name="procurement_name" class="form-control" value="{{ old('procurement_name', $data->procurement_name) }}" required>
            </div>

            <div class="mb-4">
                <label class="form-label">Description</label>
                <textarea name="description" rows="3" class="form-control">{{ old('description', $data->description) }}</textarea>
            </div>

            {{-- WORKFLOW REQUIREMENTS --}}
            <h6 class="fw-bold border-bottom pb-2 mb-3 text-primary"><i class="fas fa-list-check me-2"></i>Workflow Requirements</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="requires_request" id="req_req" {{ $data->requires_request ? 'checked' : '' }}>
                        <label class="form-check-label" for="req_req">Requires Requisition/Request</label>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="requires_quotation" id="req_quot" {{ $data->requires_quotation ? 'checked' : '' }}>
                        <label class="form-check-label" for="req_quot">Requires Supplier Quotations</label>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="requires_quotation_evaluation" id="req_eval" {{ $data->requires_quotation_evaluation ? 'checked' : '' }}>
                        <label class="form-check-label" for="req_eval">Requires Quotation Evaluation</label>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="requires_purchase_order" id="req_po" {{ $data->requires_purchase_order ? 'checked' : '' }}>
                        <label class="form-check-label" for="req_po">Requires Purchase Order (PO)</label>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="requires_receiving" id="req_rec" {{ $data->requires_receiving ? 'checked' : '' }}>
                        <label class="form-check-label" for="req_rec">Requires Receiving Note (GRN)</label>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="requires_inspection" id="req_insp" {{ $data->requires_inspection ? 'checked' : '' }}>
                        <label class="form-check-label" for="req_insp">Requires Quality Inspection</label>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="requires_invoice" id="req_inv" {{ $data->requires_invoice ? 'checked' : '' }}>
                        <label class="form-check-label" for="req_inv">Requires Supplier Invoice</label>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="requires_payment_approval" id="req_pay" {{ $data->requires_payment_approval ? 'checked' : '' }}>
                        <label class="form-check-label" for="req_pay">Requires Payment Approval</label>
                    </div>
                </div>
            </div>

            {{-- IMPACT & GOVERNANCE --}}
            <h6 class="fw-bold border-bottom pb-2 mb-3 text-primary"><i class="fas fa-chart-pie me-2"></i>Impact & Governance Controls</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="creates_stock" id="c_stock" {{ $data->creates_stock ? 'checked' : '' }}>
                        <label class="form-check-label" for="c_stock">Creates Stock/Inventory</label>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="creates_expense" id="c_expense" {{ $data->creates_expense ? 'checked' : '' }}>
                        <label class="form-check-label" for="c_expense">Creates Direct Expense</label>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="creates_asset" id="c_asset" {{ $data->creates_asset ? 'checked' : '' }}>
                        <label class="form-check-label" for="c_asset">Creates Capital Asset</label>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="requires_budget" id="req_bud" {{ $data->requires_budget ? 'checked' : '' }}>
                        <label class="form-check-label" for="req_bud">Requires Budget Validation</label>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="requires_approval" id="req_app" {{ $data->requires_approval ? 'checked' : '' }}>
                        <label class="form-check-label" for="req_app">Requires Management Approval</label>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('procurementType') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="arbif-btn-submit">Update Procurement Type</button>
            </div>
        </form>
    </div>
</div>

@endsection