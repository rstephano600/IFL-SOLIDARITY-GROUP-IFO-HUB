@extends('layouts.workingside')

@section('title', 'Quotation Evaluation')
@section('page-title', 'Quotation Evaluation')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-clipboard-check"></i>
        </div>
        Quotation Evaluation
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('deletedquotationEvaluation') }}" class="btn btn-outline-danger d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-trash-alt me-2"></i> Deleted Evaluations
        </a>
        <button class="arbif-btn-submit" data-bs-toggle="modal" data-bs-target="#addFormModal">
            <i class="fas fa-plus"></i> Add Evaluation
        </button>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="quotationEvaluationTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Evaluation No</th>
                        <th>Quotation No</th>
                        <th>Supplier</th>
                        <th>Branch</th>
                        <th>Total Score</th>
                        <th>Status</th>
                        <th>Evaluated By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <span class="arbif-badge arbif-badge-navy">
                                {{ $item->evaluation_no }}
                            </span>
                        </td>
                        <td>{{ $item->quotation->quotation_no ?? 'N/A' }}</td>
                        <td>{{ $item->quotation->supplier->SupplierName ?? 'N/A' }}</td>
                        <td>{{ $item->branch->branch_name ?? $item->branch->name ?? 'Global / Central' }}</td>
                        <td>
                            <strong>{{ number_format($item->total_score, 2) }}%</strong>
                        </td>
                        <td>
                            @if($item->evaluation_status == 'Approved')
                                <span class="badge bg-success">Approved</span>
                            @elseif($item->evaluation_status == 'Rejected')
                                <span class="badge bg-danger">Rejected</span>
                            @else
                                <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </td>
                        <td>{{ $item->evaluator->name ?? 'N/A' }}</td>
                        <td>
                            <a href="{{ route('viewquotationEvaluation', encrypt($item->id)) }}" class="arbif-btn-view">
                                <i class="fas fa-eye"></i> view
                            </a>

                            <a href="{{ route('editquotationEvaluation', encrypt($item->id)) }}" class="arbif-btn-edit">
                                <i class="fas fa-pencil"></i> edit
                            </a>
                            
                            @can('view-generally-delete-of-any-item')
                            <a href="{{ route('destroyquotationEvaluation', encrypt($item->id)) }}" class="arbif-btn-delete" onclick="return confirm('Are you sure you want to remove this evaluation?')">
                                <i class="fas fa-trash"></i> del
                            </a>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">No Quotation Evaluations Found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- REGISTER QUOTATION EVALUATION MODAL --}}
<div class="modal fade arbif-modal" id="addFormModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <h5 class="modal-title">Register Quotation Evaluation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('storequotationEvaluation') }}">
                    @csrf

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Evaluation No <span class="text-danger">*</span></label>
                            <input type="text" name="evaluation_no" class="form-control" placeholder="e.g. EVAL-2026-001" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Supplier Quotation <span class="text-danger">*</span></label>
                            <select name="supplier_quotation_id" class="form-select" data-searchable data-placeholder="Search quotation..." required>
                                <option value="">-- Select Quotation --</option>
                                @foreach($supplierQuotations as $quotation)
                                    <option value="{{ $quotation->id }}">
                                        {{ $quotation->quotation_no }} - {{ $quotation->supplier->SupplierName ?? 'N/A' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Assigned Branch <span class="text-danger">*</span></label>
                            <select name="branch_id" class="form-select" data-searchable data-placeholder="Search branch..." required>
                                <option value="">-- Select Branch --</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->branch_name ?? $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Evaluation Status</label>
                            <select name="evaluation_status" class="form-select">
                                <option value="Pending">Pending</option>
                                <option value="Approved">Approved</option>
                                <option value="Rejected">Rejected</option>
                            </select>
                        </div>
                    </div>

                    <hr>
                    <h6 class="fw-bold mb-3">Evaluation Scores (0 - 100)</h6>

                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Technical Score <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" max="100" name="technical_score" class="form-control create-score" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Price Score <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" max="100" name="price_score" class="form-control create-score" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Delivery Score <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" max="100" name="delivery_score" class="form-control create-score" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Quality Score <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" max="100" name="quality_score" class="form-control create-score" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Recommendation</label>
                            <textarea name="recommendation" rows="3" class="form-control" placeholder="Enter evaluation recommendation..."></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Comments / Remarks</label>
                            <textarea name="evaluation_comment" rows="3" class="form-control" placeholder="Enter additional comments..."></textarea>
                        </div>
                    </div>

                    <div class="modal-footer px-0 pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="arbif-btn-submit">Save Evaluation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    new DataTable('#quotationEvaluationTable', {
        perPage: 10,
        searchable: true,
        sortable: true
    });
</script>
@endpush