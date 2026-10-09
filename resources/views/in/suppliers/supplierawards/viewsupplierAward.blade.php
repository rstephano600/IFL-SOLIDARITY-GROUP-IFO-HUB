@extends('layouts.workingside')

@section('title', 'Supplier Award Profile')
@section('page-title', 'Supplier Award Profile')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-award"></i>
        </div>
        Award Profile ({{ $data->award_no }})
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('supplierAward') }}" class="btn btn-secondary px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back
        </a>

        @if($data->award_status !== 'Approved')
            <a href="{{ route('editsupplierAward', encrypt($data->id)) }}" class="arbif-btn-edit text-decoration-none">
                <i class="fas fa-pencil me-1"></i> Edit Award
            </a>

            <button class="btn btn-success px-3" style="border-radius: 4px;" data-bs-toggle="modal" data-bs-target="#approveAwardModal">
                <i class="fas fa-check me-1"></i> Approve Award
            </button>

            <button class="btn btn-danger px-3" style="border-radius: 4px;" data-bs-toggle="modal" data-bs-target="#rejectAwardModal">
                <i class="fas fa-times me-1"></i> Reject Award
            </button>
        @endif
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-8">
        <div class="arbif-card mb-4">
            <div class="arbif-card-body p-4">
                <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-3">
                    <div>
                        <h4 class="fw-bold text-primary mb-1">{{ $data->award_no }}</h4>
                        <span class="arbif-badge arbif-badge-navy">{{ $data->supplier->SupplierName ?? 'N/A' }}</span>
                    </div>
                    <div>
                        @php
                            $statusClasses = [
                                'Pending'  => 'bg-warning text-dark',
                                'Approved' => 'bg-success',
                                'Rejected' => 'bg-danger',
                            ];
                        @endphp
                        <span class="badge fs-6 {{ $statusClasses[$data->award_status] ?? 'bg-secondary' }}">
                            {{ $data->award_status }}
                        </span>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <strong>Award Amount:</strong>
                        <p class="text-success fw-bold fs-5 mb-2">{{ number_format($data->award_amount, 2) }} {{ $data->currency_code }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Award Date:</strong>
                        <p class="text-muted mb-2">{{ $data->award_date ? $data->award_date->format('d M, Y') : 'N/A' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Branch:</strong>
                        <p class="text-muted mb-2">{{ $data->branch->branch_name ?? 'Central / Global' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Company:</strong>
                        <p class="text-muted mb-2">{{ $data->company->company_name ?? 'N/A' }}</p>
                    </div>

                    <div class="col-md-4">
                        <strong>Purchase Request:</strong>
                        <p class="text-muted mb-2">{{ $data->purchaseRequest->request_no ?? 'N/A' }}</p>
                    </div>

                    <div class="col-md-4">
                        <strong>Tender Reference:</strong>
                        <p class="text-muted mb-2">{{ $data->tender->tender_no ?? 'N/A' }}</p>
                    </div>

                    <div class="col-md-4">
                        <strong>Quotation Reference:</strong>
                        <p class="text-muted mb-2">{{ $data->quotation->quotation_no ?? 'N/A' }}</p>
                    </div>

                    <div class="col-12">
                        <strong>Award Comment / Evaluation Notes:</strong>
                        <p class="text-muted mb-0">{{ $data->award_comment ?? 'No additional comments provided.' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- GENERATED PURCHASE ORDERS --}}
        <div class="arbif-card">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold mb-3 border-bottom pb-2"><i class="fas fa-file-invoice me-2 text-primary"></i>Generated Purchase Orders</h6>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>PO Number</th>
                                <th>Order Date</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data->purchaseOrders as $idx => $po)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td class="fw-bold">{{ $po->po_number ?? 'PO #' . $po->id }}</td>
                                <td>{{ $po->order_date ? $po->order_date->format('d M, Y') : 'N/A' }}</td>
                                <td>{{ number_format($po->total_amount ?? 0, 2) }}</td>
                                <td><span class="badge bg-info text-dark">{{ $po->Status ?? 'Active' }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No purchase orders generated for this award yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="arbif-card">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold mb-3 border-bottom pb-2">Audit & Metadata</h6>

                <div class="mb-3">
                    <small class="text-muted d-block">Approved By</small>
                    <span class="fw-bold text-dark">{{ $data->approver->name ?? 'Not Approved' }}</span>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block">Approved At</small>
                    <span class="text-muted">{{ $data->approved_at ? $data->approved_at->format('d M, Y H:i A') : 'N/A' }}</span>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block">Created By</small>
                    <span class="fw-bold text-dark">{{ $data->creator->name ?? 'N/A' }}</span>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block">Created At</small>
                    <span class="text-muted">{{ $data->created_at ? $data->created_at->format('d M, Y H:i A') : 'N/A' }}</span>
                </div>

                <div>
                    <small class="text-muted d-block">Last Updated By</small>
                    <span class="fw-bold text-dark">{{ $data->updater->name ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- APPROVAL MODAL --}}
<div class="modal fade" id="approveAwardModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('approvesupplierAward', encrypt($data->id)) }}">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold"><i class="fas fa-check-circle me-1"></i> Approve Supplier Award</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Approval Remarks</label>
                        <textarea name="award_comment" class="form-control" rows="3" placeholder="Optional approval note...">{{ $data->award_comment }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Confirm Approval</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- REJECTION MODAL --}}
<div class="modal fade" id="rejectAwardModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('rejectsupplierAward', encrypt($data->id)) }}">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold"><i class="fas fa-times-circle me-1"></i> Reject Supplier Award</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea name="award_comment" class="form-control" rows="3" placeholder="State clear reasons for rejection..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Confirm Rejection</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection