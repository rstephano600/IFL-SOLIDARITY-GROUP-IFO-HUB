@extends('layouts.workingside')

@section('content')

<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>

            <div class="d-flex align-items-center gap-2">

                <h4 class="fw-bold mb-0">
                    Purchase Request
                </h4>

                @php

                    $statusClass = match($purchaseRequest->Status) {

                        'Draft' => 'secondary',

                        'Submitted' => 'primary',

                        'In Progress' => 'warning',

                        'Completed' => 'success',

                        'Rejected' => 'danger',

                        default => 'dark',

                    };

                @endphp

                <span class="badge bg-{{ $statusClass }}">

                    {{ $purchaseRequest->Status }}

                </span>

            </div>

            <p class="text-muted mb-0 mt-1">

                {{ $purchaseRequest->request_no }}

            </p>

        </div>


        <div class="d-flex gap-2">

            <a href="{{ route('getpurchaserequests') }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left me-1"></i>
                Back

            </a>


            @if($purchaseRequest->Status === 'Draft')

                <a href="{{ route('editpurchaserequest', encrypt($purchaseRequest->id)) }}"
                   class="btn btn-warning">

                    <i class="bi bi-pencil me-1"></i>
                    Edit

                </a>


                <form method="POST"
                      action="{{ route('submitpurchaserequest', encrypt($purchaseRequest->id)) }}">

                    @csrf

                    <button type="submit"
                            class="btn btn-primary"
                            onclick="return confirm('Submit this purchase request?')">

                        <i class="bi bi-send me-1"></i>
                        Submit

                    </button>

                </form>

            @endif

        </div>

    </div>


    {{-- REQUEST INFORMATION --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white">

            <strong>
                <i class="bi bi-info-circle me-1"></i>
                Request Information
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Request Number
                    </small>

                    <strong>
                        {{ $purchaseRequest->request_no }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Request Date
                    </small>

                    <strong>
                        {{ optional($purchaseRequest->request_date)->format('d/m/Y') }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Required Date
                    </small>

                    <strong>

                        @if($purchaseRequest->required_date)

                            {{ optional($purchaseRequest->required_date)->format('d/m/Y') }}

                        @else

                            <span class="text-muted">
                                Not specified
                            </span>

                        @endif

                    </strong>

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Priority
                    </small>

                    @php

                        $priorityClass = match($purchaseRequest->priority) {

                            'Urgent' => 'danger',
                            'High' => 'warning',
                            'Low' => 'secondary',
                            default => 'primary',

                        };

                    @endphp

                    <span class="badge bg-{{ $priorityClass }}">

                        {{ $purchaseRequest->priority }}

                    </span>

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Requester
                    </small>

                    <strong>

                        {{ $purchaseRequest->requester->name
                            ?? $purchaseRequest->requester->username
                            ?? 'N/A' }}

                    </strong>

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Procurement Type
                    </small>

                    <strong>

                        {{ $purchaseRequest->procurementType->procurement_name ?? 'N/A' }}

                    </strong>

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Currency
                    </small>

                    <strong>
                        {{ $purchaseRequest->currency_code }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Estimated Total
                    </small>

                    <strong class="text-primary">

                        {{ number_format($purchaseRequest->estimated_total, 2) }}

                    </strong>

                </div>


                <div class="col-12">

                    <small class="text-muted d-block">
                        Purpose
                    </small>

                    <div>

                        {{ $purchaseRequest->purpose ?? 'No purpose specified.' }}

                    </div>

                </div>


                <div class="col-12">

                    <small class="text-muted d-block">
                        Justification
                    </small>

                    <div>

                        {{ $purchaseRequest->justification ?? 'No justification provided.' }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- CURRENT STEP --}}
    @if($purchaseRequest->currentStep)

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-primary text-white">

                <strong>
                    <i class="bi bi-arrow-right-circle me-1"></i>
                    Current Workflow Step
                </strong>

            </div>


            <div class="card-body">

                <div class="row align-items-center">

                    <div class="col-md-1 text-center">

                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto"
                             style="width:50px;height:50px;">

                            {{ $purchaseRequest->currentStep->step_order }}

                        </div>

                    </div>


                    <div class="col-md-5">

                        <small class="text-muted">
                            Current Step
                        </small>

                        <h5 class="mb-0">

                            {{ $purchaseRequest->currentStep->step_name }}

                        </h5>

                    </div>


                    <div class="col-md-3">

                        <small class="text-muted">
                            Responsible Role
                        </small>

                        <div>

                            <span class="badge bg-light text-dark border">

                                {{ $purchaseRequest->currentStep->assigned_role ?? 'Not assigned' }}

                            </span>

                        </div>

                    </div>


                    <div class="col-md-3 text-md-end">

                        @if(
                            in_array(
                                $purchaseRequest->Status,
                                ['Submitted', 'In Progress']
                            )
                        )

                            <button type="button"
                                    class="btn btn-success"
                                    data-bs-toggle="modal"
                                    data-bs-target="#approveModal">

                                <i class="bi bi-check-circle me-1"></i>
                                Approve

                            </button>

                            <button type="button"
                                    class="btn btn-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#rejectModal">

                                <i class="bi bi-x-circle me-1"></i>
                                Reject

                            </button>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    @endif


    {{-- ITEMS --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white">

            <strong>
                <i class="bi bi-box-seam me-1"></i>
                Requested Items
            </strong>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th width="50">
                                #
                            </th>

                            <th>
                                Product
                            </th>

                            <th>
                                Description
                            </th>

                            <th class="text-end">
                                Quantity
                            </th>

                            <th>
                                UOM
                            </th>

                            <th class="text-end">
                                Unit Price
                            </th>

                            <th class="text-end">
                                Total
                            </th>

                            <th>
                                Status
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($purchaseRequest->items as $key => $item)

                            <tr>

                                <td>
                                    {{ $key + 1 }}
                                </td>

                                <td>

                                    <strong>
                                        {{ $item->product->product_name ?? 'N/A' }}
                                    </strong>

                                    <br>

                                    <small class="text-muted">

                                        {{ $item->product->product_code ?? '' }}

                                    </small>

                                </td>


                                <td>

                                    {{ $item->description ?? '-' }}

                                </td>


                                <td class="text-end">

                                    {{ number_format($item->requested_quantity, 3) }}

                                </td>


                                <td>

                                    {{ $item->unitOfMeasure->UnitName ?? 'N/A' }}

                                </td>


                                <td class="text-end">

                                    {{ number_format($item->estimated_unit_price, 2) }}

                                </td>


                                <td class="text-end fw-bold">

                                    {{ number_format($item->estimated_total, 2) }}

                                </td>


                                <td>

                                    <span class="badge bg-secondary">

                                        {{ $item->item_status }}

                                    </span>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="8"
                                    class="text-center py-4">

                                    No items found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>


                    <tfoot>

                        <tr>

                            <th colspan="6"
                                class="text-end">

                                Estimated Total:

                            </th>

                            <th class="text-end text-primary fs-5">

                                {{ number_format($purchaseRequest->estimated_total, 2) }}

                            </th>

                            <th>

                                {{ $purchaseRequest->currency_code }}

                            </th>

                        </tr>

                    </tfoot>

                </table>

            </div>

        </div>

    </div>


    {{-- WORKFLOW HISTORY --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white">

            <strong>
                <i class="bi bi-clock-history me-1"></i>
                Procurement Workflow History
            </strong>

        </div>


        <div class="card-body">

            @forelse($purchaseRequest->workflows as $workflow)

                <div class="d-flex mb-4">

                    <div class="me-3">

                        @php

                            $workflowColor = match($workflow->status) {

                                'Completed' => 'success',

                                'Rejected' => 'danger',

                                'Pending' => 'warning',

                                default => 'secondary',

                            };

                        @endphp

                        <div class="rounded-circle bg-{{ $workflowColor }} text-white d-flex align-items-center justify-content-center"
                             style="width:42px;height:42px;">

                            @if($workflow->status === 'Completed')

                                <i class="bi bi-check"></i>

                            @elseif($workflow->status === 'Rejected')

                                <i class="bi bi-x"></i>

                            @else

                                <i class="bi bi-hourglass-split"></i>

                            @endif

                        </div>

                    </div>


                    <div class="flex-grow-1 border-bottom pb-3">

                        <div class="d-flex justify-content-between">

                            <div>

                                <h6 class="mb-1">

                                    {{ $workflow->workflowStep->step_order ?? '' }}.
                                    {{ $workflow->workflowStep->step_name ?? 'Workflow Step' }}

                                </h6>

                                <small class="text-muted">

                                    Role:
                                    {{ $workflow->workflowStep->assigned_role ?? 'N/A' }}

                                </small>

                            </div>


                            <span class="badge bg-{{ $workflowColor }}">

                                {{ $workflow->status }}

                            </span>

                        </div>


                        @if($workflow->comment)

                            <div class="mt-2">

                                <strong>
                                    Comment:
                                </strong>

                                {{ $workflow->comment }}

                            </div>

                        @endif


                        @if($workflow->rejection_reason)

                            <div class="alert alert-danger mt-2 mb-0">

                                <strong>
                                    Rejection Reason:
                                </strong>

                                {{ $workflow->rejection_reason }}

                            </div>

                        @endif


                        <div class="mt-2 text-muted small">

                            @if($workflow->started_at)

                                Started:
                                {{ $workflow->started_at->format('d/m/Y H:i') }}

                            @endif


                            @if($workflow->completed_at)

                                &nbsp; | &nbsp;

                                Completed:
                                {{ $workflow->completed_at->format('d/m/Y H:i') }}

                            @endif


                            @if($workflow->actedBy)

                                &nbsp; | &nbsp;

                                By:
                                {{ $workflow->actedBy->name
                                    ?? $workflow->actedBy->username
                                    ?? 'N/A' }}

                            @endif

                        </div>

                    </div>

                </div>

            @empty

                <div class="text-center text-muted py-4">

                    No workflow history available.

                </div>

            @endforelse

        </div>

    </div>

</div>


{{-- APPROVE MODAL --}}
<div class="modal fade"
     id="approveModal"
     tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form method="POST"
                  action="{{ route('approvepurchaserequest', encrypt($purchaseRequest->id)) }}">

                @csrf

                <div class="modal-header">

                    <h5 class="modal-title">

                        <i class="bi bi-check-circle text-success me-2"></i>

                        Approve Purchase Request

                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>

                </div>


                <div class="modal-body">

                    <div class="alert alert-info">

                        You are approving:

                        <strong>
                            {{ $purchaseRequest->currentStep->step_name ?? '' }}
                        </strong>

                    </div>


                    <label class="form-label">
                        Comment
                    </label>

                    <textarea name="comment"
                              class="form-control"
                              rows="4"
                              placeholder="Optional approval comment..."></textarea>

                </div>


                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button type="submit"
                            class="btn btn-success">

                        <i class="bi bi-check-circle me-1"></i>
                        Approve

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- REJECT MODAL --}}
<div class="modal fade"
     id="rejectModal"
     tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form method="POST"
                  action="{{ route('rejectpurchaserequest', encrypt($purchaseRequest->id)) }}">

                @csrf

                <div class="modal-header">

                    <h5 class="modal-title">

                        <i class="bi bi-x-circle text-danger me-2"></i>

                        Reject Purchase Request

                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>

                </div>


                <div class="modal-body">

                    <div class="alert alert-warning">

                        Please provide a reason for rejecting this request.

                    </div>


                    <label class="form-label">
                        Rejection Reason
                        <span class="text-danger">*</span>
                    </label>

                    <textarea name="rejection_reason"
                              class="form-control"
                              rows="5"
                              required
                              placeholder="Enter rejection reason..."></textarea>

                </div>


                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button type="submit"
                            class="btn btn-danger">

                        <i class="bi bi-x-circle me-1"></i>
                        Reject Request

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection