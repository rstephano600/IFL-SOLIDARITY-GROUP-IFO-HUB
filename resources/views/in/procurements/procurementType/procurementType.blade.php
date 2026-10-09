@extends('layouts.configside')

@section('title', 'Procurement Types')
@section('page-title', 'Procurement Types')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-boxes-packing"></i>
        </div>
        Procurement Types
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('deletedprocurementType') }}" class="btn btn-outline-danger d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-trash-alt me-2"></i> Deleted Types
        </a>
        <button class="arbif-btn-submit" data-bs-toggle="modal" data-bs-target="#addProcurementTypeModal">
            <i class="fas fa-plus me-1"></i> Add Procurement Type
        </button>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="procurementTypeTable">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Code</th>
                        <th>Procurement Name</th>
                        <th>Branch</th>
                        <th>Impact Flags</th>
                        <th>Workflow Summary</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $item)
                    <tr>
                        <td>
                            <span class="badge bg-light text-dark border">{{ $item->display_order }}</span>
                        </td>
                        <td>
                            <span class="arbif-badge arbif-badge-navy">
                                {{ $item->procurement_code }}
                            </span>
                        </td>
                        <td><strong>{{ $item->procurement_name }}</strong></td>
                        <td>{{ $item->branch->branch_name ?? 'Global / Central' }}</td>
                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                @if($item->creates_stock) <span class="badge bg-success"><i class="fas fa-warehouse me-1"></i>Stock</span> @endif
                                @if($item->creates_expense) <span class="badge bg-warning text-dark"><i class="fas fa-receipt me-1"></i>Expense</span> @endif
                                @if($item->creates_asset) <span class="badge bg-info text-dark"><i class="fas fa-building me-1"></i>Asset</span> @endif
                            </div>
                        </td>
                        <td>
                            <small class="text-muted">
                                @if($item->isQuickPurchase())
                                    <span class="badge bg-secondary">Direct Purchase</span>
                                @else
                                    <span class="badge bg-primary">Full Workflow</span>
                                @endif
                            </small>
                        </td>
                        <td>
                            <a href="{{ route('viewprocurementType', encrypt($item->id)) }}" class="arbif-btn-view">
                                <i class="fas fa-eye"></i> view
                            </a>

                            <a href="{{ route('editprocurementType', encrypt($item->id)) }}" class="arbif-btn-edit">
                                <i class="fas fa-pencil"></i> edit
                            </a>

                            @can('view-generally-delete-of-any-item')
                            <a href="{{ route('destroyprocurementType', encrypt($item->id)) }}" class="arbif-btn-delete" onclick="return confirm('Are you sure you want to remove this procurement type?')">
                                <i class="fas fa-trash"></i> del
                            </a>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No Procurement Types Found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- REGISTER PROCUREMENT TYPE MODAL --}}
<div class="modal fade arbif-modal" id="addProcurementTypeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon">
                    <i class="fas fa-boxes-packing"></i>
                </div>
                <h5 class="modal-title fw-bold">Register Procurement Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('storeprocurementType') }}">
                    @csrf

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Assigned Branch <span class="text-danger">*</span></label>
                            <select name="branch_id" class="form-select" data-searchable data-placeholder="Search ..." required>
                                <option value="">-- Select Branch --</option>
                                @foreach($branchies as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Procurement Code <span class="text-danger">*</span></label>
                            <input type="text" name="procurement_code" class="form-control" placeholder="e.g. PROC-GOODS" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Display Order</label>
                            <input type="number" name="display_order" class="form-control" value="1" min="1">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Procurement Name <span class="text-danger">*</span></label>
                        <input type="text" name="procurement_name" class="form-control" placeholder="e.g. GOODS & INVENTORY PROCUREMENT" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="2" class="form-control" placeholder="Brief overview of this procurement type..."></textarea>
                    </div>

                    {{-- WORKFLOW CONFIGURATION FLAGS --}}
                    <h6 class="fw-bold border-bottom pb-2 mb-3 text-primary"><i class="fas fa-list-check me-2"></i>Workflow Requirements</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="requires_request" id="req_req" checked>
                                <label class="form-check-label" for="req_req">Requires Requisition/Request</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="requires_quotation" id="req_quot">
                                <label class="form-check-label" for="req_quot">Requires Supplier Quotations</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="requires_quotation_evaluation" id="req_eval">
                                <label class="form-check-label" for="req_eval">Requires Quotation Evaluation</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="requires_purchase_order" id="req_po">
                                <label class="form-check-label" for="req_po">Requires Purchase Order (PO)</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="requires_receiving" id="req_rec" checked>
                                <label class="form-check-label" for="req_rec">Requires Receiving Note (GRN)</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="requires_inspection" id="req_insp">
                                <label class="form-check-label" for="req_insp">Requires Quality Inspection</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="requires_invoice" id="req_inv" checked>
                                <label class="form-check-label" for="req_inv">Requires Supplier Invoice</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="requires_payment_approval" id="req_pay" checked>
                                <label class="form-check-label" for="req_pay">Requires Payment Approval</label>
                            </div>
                        </div>
                    </div>

                    {{-- IMPACT & GOVERNANCE FLAGS --}}
                    <h6 class="fw-bold border-bottom pb-2 mb-3 text-primary"><i class="fas fa-chart-pie me-2"></i>Impact & Governance Controls</h6>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="creates_stock" id="c_stock">
                                <label class="form-check-label" for="c_stock">Creates Stock/Inventory</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="creates_expense" id="c_expense">
                                <label class="form-check-label" for="c_expense">Creates Direct Expense</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="creates_asset" id="c_asset">
                                <label class="form-check-label" for="c_asset">Creates Capital Asset</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="requires_budget" id="req_bud" checked>
                                <label class="form-check-label" for="req_bud">Requires Budget Validation</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="requires_approval" id="req_app" checked>
                                <label class="form-check-label" for="req_app">Requires Management Approval</label>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer px-0 pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="arbif-btn-submit">Save Procurement Type</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    new DataTable('#procurementTypeTable', {
        perPage: 10,
        searchable: true,
        sortable: true
    });
</script>
@endpush