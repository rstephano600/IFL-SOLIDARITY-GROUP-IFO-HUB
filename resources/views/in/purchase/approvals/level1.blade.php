@extends('layouts.workingside')

@section('title', 'Purchase Request Approval - Level ' . $level)
@section('page-title', 'Purchase Request Approval - Level ' . $level)

@section('content')

<div class="arbif-page-header d-flex justify-content-between align-items-center mb-3">
    <h3>
        <div class="page-icon">
            <i class="fas fa-clipboard-check"></i>
        </div>
        Purchase Request Approval - Level {{ $level }}
    </h3>

    <div>
        <span class="badge bg-primary fs-6 px-3 py-2">
            {{ $data->total() }} Request(s)
        </span>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body p-4">
        {{-- Search / filter information --}}
        <div class="alert alert-info d-flex align-items-center mb-4">
            <i class="fas fa-info-circle fa-lg me-2"></i>
            <div>
                Review the purchase requests assigned to your current approval level.
                Check the requested items, quantities, estimated costs, and justification before approving or rejecting a request.
            </div>
        </div>

    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="purchaseRequestTable">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Request Details</th>
                        <th>Procurement Type</th>
                        <th>Requester</th>
                        <th>Requested Items</th>
                        <th class="text-end">Estimated Total</th>
                        <th>Current Step</th>
                        <th style="min-width: 260px;">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($data as $requestItem)
                        <tr>
                            <td>{{ $data->firstItem() + $loop->index }}</td>

                            <td>
                                <strong>{{ $requestItem->request_no }}</strong>
                                <br>
                                <small class="text-muted">
                                    Request date: 
                                    {{ $requestItem->request_date ? \Carbon\Carbon::parse($requestItem->request_date)->format('d M Y') : 'N/A' }}
                                </small>
                                <br>
                                <span class="badge bg-warning text-dark mt-1">
                                    {{ $requestItem->priority ?? 'Normal' }}
                                </span>
                                @if ($requestItem->required_date)
                                    <br>
                                    <small class="text-muted">
                                        Required: {{ \Carbon\Carbon::parse($requestItem->required_date)->format('d M Y') }}
                                    </small>
                                @endif
                            </td>

                            <td>
                                {{ $requestItem->procurementType->procurement_name 
                                    ?? $requestItem->procurementType->name 
                                    ?? 'N/A' }}
                            </td>

                            <td>
                                {{ $requestItem->requester->name 
                                    ?? $requestItem->requester->username 
                                    ?? 'N/A' }}
                            </td>

                            <td>
                                <button type="button" 
                                    class="btn btn-sm btn-outline-primary mb-2" 
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#items-{{ $requestItem->id }}" 
                                    aria-expanded="false">
                                    <i class="fas fa-eye me-1"></i> View {{ $requestItem->items->count() }} Item(s)
                                </button>

                                <div class="collapse" id="items-{{ $requestItem->id }}">
                                    <div class="card card-body p-2 border shadow-sm">
                                        <table class="table table-sm table-bordered mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Item</th>
                                                    <th class="text-center">Quantity</th>
                                                    <th class="text-end">Unit Price</th>
                                                    <th class="text-end">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($requestItem->items as $item)
                                                    <tr>
                                                        <td>
                                                            {{ $item->product->product_name 
                                                                ?? $item->product->name 
                                                                ?? 'N/A' }}
                                                        </td>
                                                        <td class="text-center">
                                                            {{ number_format((float) $item->requested_quantity, 2) }}
                                                        </td>
                                                        <td class="text-end">
                                                            {{ number_format((float) $item->estimated_unit_price, 2) }}
                                                        </td>
                                                        <td class="text-end">
                                                            {{ number_format((float) $item->estimated_total, 2) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

                                        @if ($requestItem->purpose)
                                            <div class="mt-2 small">
                                                <strong>Purpose:</strong> {{ $requestItem->purpose }}
                                            </div>
                                        @endif

                                        @if ($requestItem->justification)
                                            <div class="mt-1 small">
                                                <strong>Justification:</strong> {{ $requestItem->justification }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="text-end fw-bold">
                                {{ $requestItem->currency_code ?? 'TZS' }}
                                {{ number_format((float) $requestItem->estimated_total, 2) }}
                            </td>

                            <td>
                                <span class="badge bg-info text-dark">
                                    {{ $requestItem->currentStep->step_name ?? 'N/A' }}
                                </span>
                            </td>

                            <td>
                                {{-- APPROVE FORM --}}
                                @can('approve-purchase-request-level-' . $level)
                                    <form method="POST" 
                                        action="{{ route('approvepurchaseRequestLevel' . $level, encrypt($requestItem->id)) }}" 
                                        enctype="multipart/form-data" 
                                        class="mb-3">
                                        @csrf

                                        <label class="form-label small fw-bold mb-1">Approval Comment</label>
                                        <textarea name="comment" 
                                            class="form-control form-control-sm mb-2" 
                                            rows="2" 
                                            maxlength="5000" 
                                            placeholder="Enter approval comment (optional)"></textarea>

                                        <label class="form-label small fw-bold mb-1">Supporting Document (Optional)</label>
                                        <input type="file" 
                                            name="attachment" 
                                            class="form-control form-control-sm mb-2" 
                                            accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">

                                        <button type="submit" 
                                            class="btn btn-success btn-sm w-100" 
                                            onclick="return confirm('Are you sure you want to approve this purchase request?')">
                                            <i class="fas fa-check me-1"></i> Approve Request
                                        </button>
                                    </form>
                                @endcan

                                {{-- REJECT FORM --}}
                                @can('reject-purchase-request-level-' . $level)
                                    <form method="POST" 
                                        action="{{ route('rejectpurchaseRequestLevel' . $level, encrypt($requestItem->id)) }}">
                                        @csrf

                                        <label class="form-label small fw-bold mb-1">Rejection Reason</label>
                                        <textarea name="rejection_reason" 
                                            class="form-control form-control-sm mb-2" 
                                            rows="2" 
                                            minlength="3" 
                                            maxlength="5000" 
                                            required 
                                            placeholder="Explain why this request is being rejected"></textarea>

                                        <button type="submit" 
                                            class="btn btn-danger btn-sm w-100" 
                                            onclick="return confirm('Are you sure you want to reject this purchase request?')">
                                            <i class="fas fa-times me-1"></i> Reject Request
                                        </button>
                                    </form>
                                @endcan

                                @cannot('approve-purchase-request-level-' . $level)
                                    @cannot('reject-purchase-request-level-' . $level)
                                        <span class="text-muted small">
                                            <i class="fas fa-lock me-1"></i> View only
                                        </span>
                                    @endcannot
                                @endcannot
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <h5>No Pending Approval Requests</h5>
                                <p class="text-muted mb-0">
                                    There are currently no purchase requests awaiting approval at Level {{ $level }}.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pt-2 border-top">
            <small class="text-muted">
                Showing {{ $data->firstItem() ?? 0 }} to {{ $data->lastItem() ?? 0 }} of {{ $data->total() }} requests
            </small>

            <div>
                {{ $data->links() }}
            </div>
        </div>
    </div>
</div>

@endsection