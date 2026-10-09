@extends('layouts.workingside')

@section('title', 'Purchase Request Details')
@section('page-title', 'Purchase Request Details')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-file-invoice"></i>
        </div>
        Purchase Request Profile ({{ $data->request_no }})
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('purchaseRequest') }}" class="btn btn-secondary px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back
        </a>

        <a href="{{ route('purchaseRequestWorkflow', encrypt($data->id)) }}" class="btn btn-outline-info px-3" style="border-radius: 4px;">
            <i class="fas fa-history me-1"></i> Audit Trail
        </a>

        @if(in_array($data->Status, ['Draft', 'Rejected']))
            <a href="{{ route('editpurchaseRequest', encrypt($data->id)) }}" class="arbif-btn-edit text-decoration-none">
                <i class="fas fa-pencil me-1"></i> Edit
            </a>

            <a href="{{ route('submitpurchaseRequest', encrypt($data->id)) }}" class="arbif-btn-submit text-decoration-none" onclick="return confirm('Submit this purchase request for approval?')">
                <i class="fas fa-paper-plane me-1"></i> Submit
            </a>
        @endif

        @if($data->Status == 'Rejected')
            <a href="{{ route('resubmitpurchaseRequest', encrypt($data->id)) }}" class="btn btn-warning text-dark px-3" style="border-radius: 4px;" onclick="return confirm('Resubmit this purchase request?')">
                <i class="fas fa-redo me-1"></i> Resubmit
            </a>
        @endif

        @if(in_array($data->Status, ['Draft', 'Pending Approval']))
            <a href="{{ route('cancelpurchaseRequest', encrypt($data->id)) }}" class="btn btn-outline-danger px-3" style="border-radius: 4px;" onclick="return confirm('Cancel this request?')">
                <i class="fas fa-ban me-1"></i> Cancel
            </a>
        @endif
    </div>
</div>

{{-- APPROVAL ACTION BANNER (IF CURRENT USER IS AUTHORIZED OR WORKFLOW PENDING) --}}
@if($data->Status == 'Pending Approval' && $data->currentStep)
<div class="alert alert-warning border-start border-4 border-warning shadow-sm mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h5 class="fw-bold mb-1"><i class="fas fa-exclamation-circle me-2"></i> Pending Step Action: {{ $data->currentStep->step_name }}</h5>
            <p class="mb-0 text-muted">
                Assigned Role: <strong>{{ $data->currentStep->assigned_role ?? 'Any Authorized Manager' }}</strong> | Step Code: <strong>{{ $data->currentStep->step_code }}</strong>
            </p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-success fw-bold px-4" data-bs-toggle="modal" data-bs-target="#approveModal">
                <i class="fas fa-check me-1"></i> Approve Step
            </button>
            <button class="btn btn-danger fw-bold px-4" data-bs-toggle="modal" data-bs-target="#rejectModal">
                <i class="fas fa-times me-1"></i> Reject Request
            </button>
        </div>
    </div>
</div>
@endif

<div class="row g-4 mb-4">
    <div class="col-md-8">
        <div class="arbif-card mb-4">
            <div class="arbif-card-body p-4">
                <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-3">
                    <div>
                        <h4 class="fw-bold text-primary mb-1">{{ $data->request_no }}</h4>
                        <span class="arbif-badge arbif-badge-navy">{{ $data->procurementType->procurement_name ?? 'N/A' }}</span>
                    </div>
                    <div>
                        @php
                            $statusClasses = [
                                'Draft' => 'bg-secondary',
                                'Pending Approval' => 'bg-warning text-dark',
                                'Approved' => 'bg-success',
                                'Rejected' => 'bg-danger',
                                'Cancelled' => 'bg-dark',
                            ];
                        @endphp
                        <span class="badge fs-6 {{ $statusClasses[$data->Status] ?? 'bg-secondary' }}">
                            {{ $data->Status }}
                        </span>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <strong>Requester:</strong>
                        <p class="text-muted mb-2">{{ $data->requester->name ?? 'N/A' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Branch:</strong>
                        <p class="text-muted mb-2">{{ $data->branch->branch_name ?? 'Central / Global' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Request Date:</strong>
                        <p class="text-muted mb-2">{{ $data->request_date ? $data->request_date->format('d M, Y') : 'N/A' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Required By Date:</strong>
                        <p class="text-muted mb-2">{{ $data->required_date ? $data->required_date->format('d M, Y') : 'N/A' }}</p>
                    </div>

                    <div class="col-md-6">
                        <strong>Priority:</strong>
                        <p class="mb-2">
                            <span class="badge {{ $data->priority == 'High' || $data->priority == 'Urgent' ? 'bg-danger' : 'bg-info text-dark' }}">
                                {{ $data->priority }}
                            </span>
                        </p>
                    </div>

                    <div class="col-md-6">
                        <strong>Budget Amount:</strong>
                        <p class="text-muted mb-2">{{ number_format($data->budget_amount, 2) }} TZS</p>
                    </div>

                    <div class="col-12">
                        <strong>Purpose:</strong>
                        <p class="text-muted mb-2">{{ $data->purpose ?? 'N/A' }}</p>
                    </div>

                    <div class="col-12">
                        <strong>Justification:</strong>
                        <p class="text-muted mb-0">{{ $data->justification ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ITEMS TABLE --}}
        <div class="arbif-card">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold mb-3 border-bottom pb-2"><i class="fas fa-boxes me-2 text-primary"></i>Purchase Line Items</h6>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Product Name</th>
                                <th>Unit</th>
                                <th>Req. Qty</th>
                                <th>Est. Price</th>
                                <th>Est. Total</th>
                                @if($data->isApproved())
                                    <th>Appr. Qty</th>
                                    <th>Appr. Total</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data->items as $idx => $item)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td>
                                    <strong>{{ $item->product->product_name ?? 'Custom Item' }}</strong>
                                    @if($item->description)
                                        <div class="small text-muted">{{ $item->description }}</div>
                                    @endif
                                </td>
                                <td>{{ $item->unitOfMeasure->UnitName ?? 'N/A' }}</td>
                                <td>{{ number_format($item->requested_quantity, 2) }}</td>
                                <td>{{ number_format($item->estimated_unit_price, 2) }}</td>
                                <td class="fw-bold">{{ number_format($item->estimated_total, 2) }}</td>
                                @if($data->isApproved())
                                    <td>{{ number_format($item->approved_quantity, 2) }}</td>
                                    <td class="fw-bold text-success">{{ number_format($item->approved_total, 2) }}</td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" class="text-end fw-bold">Est. Total Amount:</td>
                                <td colspan="{{ $data->isApproved() ? '3' : '1' }}" class="fw-bold text-primary">{{ number_format($data->estimated_total, 2) }} TZS</td>
                            </tr>
                            @if($data->isApproved())
                            <tr>
                                <td colspan="7" class="text-end fw-bold">Final Approved Total:</td>
                                <td class="fw-bold text-success fs-6">{{ number_format($data->approved_total, 2) }} TZS</td>
                            </tr>
                            @endif
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        {{-- SUMMARY SIDEBAR --}}
        <div class="arbif-card mb-4">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold mb-3 border-bottom pb-2">Approval Summary</h6>

                <div class="mb-3">
                    <small class="text-muted d-block">Current Workflow Step</small>
                    <span class="badge bg-secondary fs-6">{{ $data->currentStep->step_name ?? 'Completed / None' }}</span>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block">Submitted At</small>
                    <span class="fw-bold text-dark">{{ $data->submitted_at ? $data->submitted_at->format('d M, Y H:i A') : 'Not Submitted' }}</span>
                </div>

                @if($data->approved_at)
                <div class="mb-3">
                    <small class="text-muted d-block">Approved At</small>
                    <span class="fw-bold text-success">{{ $data->approved_at->format('d M, Y H:i A') }}</span>
                </div>
                @endif

                @if($data->rejected_at)
                <div class="mb-3">
                    <small class="text-muted d-block">Rejected At</small>
                    <span class="fw-bold text-danger">{{ $data->rejected_at->format('d M, Y H:i A') }}</span>
                    <p class="text-danger small mt-1 mb-0"><strong>Reason:</strong> {{ $data->rejection_reason }}</p>
                </div>
                @endif

                <div class="mb-3">
                    <small class="text-muted d-block">Created By</small>
                    <span class="fw-bold text-dark">{{ $data->creator->name ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        {{-- RECENT WORKFLOW HISTORY --}}
        <div class="arbif-card">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold mb-3 border-bottom pb-2">Workflow Activity</h6>
                <div class="timeline">
                    @forelse($workflows as $wf)
                        <div class="border-start border-2 ps-3 mb-3 position-relative">
                            <strong class="d-block text-dark">{{ $wf->workflowStep->step_name ?? $wf->action }}</strong>
                            <small class="text-muted d-block">By: {{ $wf->actedBy->name ?? 'System/User' }}</small>
                            <small class="text-muted d-block">{{ $wf->created_at->format('d M, Y H:i A') }}</small>
                            @if($wf->comment)
                                <p class="small text-secondary mb-0 mt-1"><em>"{{ $wf->comment }}"</em></p>
                            @endif
                        </div>
                    @empty
                        <p class="text-muted small mb-0">No workflow history recorded yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- APPROVAL MODAL --}}
@if($data->currentStep)
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('approvepurchaseRequest', encrypt($data->id)) }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold"><i class="fas fa-check-circle me-1"></i> Approve Step: {{ $data->currentStep->step_name }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Approval Remarks / Comment {{ $data->currentStep->requires_comment ? '*' : '' }}</label>
                        <textarea name="comment" class="form-control" rows="3" placeholder="Enter optional approval remarks..." {{ $data->currentStep->requires_comment ? 'required' : '' }}></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"> Attach Supporting Document (Optional) </label>
                        <input type="file" name="attachment" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" >
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
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('rejectpurchaseRequest', encrypt($data->id)) }}">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold"><i class="fas fa-times-circle me-1"></i> Reject Purchase Request</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea name="rejection_reason" class="form-control" rows="3" placeholder="State clear grounds for rejecting this request..." required></textarea>
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
@endif

@endsection