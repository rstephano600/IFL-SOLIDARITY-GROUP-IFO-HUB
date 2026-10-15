@extends('layouts.workingside')

@section('title', 'View Service Request')
@section('page-title', 'Service Request Profile & Approval')

@section('content')

<div class="arbif-page-header mb-4">
    <h3>
        <div class="page-icon">
            <i class="fas fa-file-alt"></i>
        </div>
        Service Request: {{ $data->request_no }}
    </h3>

    <div class="d-flex gap-2">
        @if(!in_array($data->request_status, ['Approved', 'Completed', 'Cancelled']))
            <a href="{{ route('editServiceRequest', encrypt($data->id)) }}" class="arbif-btn-edit">
                <i class="fas fa-pencil me-1"></i> Edit Header
            </a>
        @endif
        <a href="{{ route('serviceRequest') }}" class="btn btn-secondary px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to List
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-8">
        {{-- REQUEST INFORMATION CARD --}}
        <div class="arbif-card mb-4">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-info-circle me-1"></i> Request Details</h6>

                <table class="table table-borderless align-middle mb-0">
                    <tr>
                        <th style="width: 30%;" class="text-muted">System Reference:</th>
                        <td><span class="arbif-badge arbif-badge-navy">{{ $data->ReferenceNo }}</span></td>
                    </tr>
                    <tr>
                        <th class="text-muted">Title:</th>
                        <td class="fw-bold text-primary fs-6">{{ $data->request_title }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Requester:</th>
                        <td>{{ $data->requester->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Committee:</th>
                        <td>{{ $data->committee_name ?? $data->committee->committee_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Beneficiary:</th>
                        <td>{{ $data->beneficiary_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Request Date:</th>
                        <td>{{ $data->request_date ? $data->request_date->format('d M, Y') : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Required Date:</th>
                        <td>{{ $data->required_date ? $data->required_date->format('d M, Y') : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Priority:</th>
                        <td>
                            <span class="badge {{ $data->priority == 'High' || $data->priority == 'Urgent' ? 'bg-danger' : 'bg-info text-dark' }}">
                                {{ $data->priority }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted">Purpose / Overview:</th>
                        <td>{{ $data->purpose ?? 'No purpose details provided.' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- ITEMS BREAKDOWN --}}
        <div class="arbif-card mb-4">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-boxes me-1"></i> Requested Line Items</h6>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>Catalog / Product</th>
                                <th>Description</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data->items as $idx => $item)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $item->item_type }}</span></td>
                                <td>
                                    <strong>{{ $item->service->service_name ?? $item->product->product_name ?? 'N/A' }}</strong>
                                </td>
                                <td>{{ $item->item_description }}</td>
                                <td class="fw-bold">{{ $item->quantity }} {{ $item->unit_of_measure }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No items recorded.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ATTACHMENTS --}}
        <div class="arbif-card">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-paperclip me-1"></i> Attached Documents</h6>

                <div class="list-group">
                    @forelse($data->attachments as $att)
                        <a href="{{ Storage::url($att->file_path) }}" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-file-alt text-primary me-2"></i>
                                <strong>{{ $att->file_name }}</strong>
                                <small class="text-muted ms-2">({{ round($att->file_size / 1024, 2) }} KB)</small>
                            </div>
                            <span class="btn btn-sm btn-outline-primary"><i class="fas fa-download"></i> View File</span>
                        </a>
                    @empty
                        <div class="text-muted small">No supporting files attached.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        {{-- APPROVAL & WORKFLOW ACTIONS CARD --}}
        <div class="arbif-card mb-4">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-tasks me-1"></i> Approval Status & Actions</h6>

                <div class="mb-3">
                    <label class="form-label text-muted mb-1">Overall Status:</label>
                    <div><span class="badge bg-primary fs-6">{{ $data->request_status }}</span></div>
                </div>

                <hr>

                {{-- 1. COMMITTEE CHAIRMAN APPROVAL BOX --}}
                <div class="mb-4">
                    <h6 class="fw-bold fs-7 text-uppercase text-secondary">1. Committee Chairman Approval</h6>
                    <div class="small mb-1">Status: <strong>{{ $data->committee_chairman_approval_status }}</strong></div>
                    @if($data->committeeApprover)
                        <div class="small text-muted">Approved By: {{ $data->committeeApprover->name }}</div>
                        <div class="small text-muted">Date: {{ $data->committee_chairman_approved_at ? $data->committee_chairman_approved_at->format('d M, Y H:i') : '' }}</div>
                    @endif

                    @if($data->committee_chairman_approval_status === 'Pending')
                        <form method="POST" action="{{ route('committeeChairmanApproval', encrypt($data->id)) }}" class="mt-2">
                            @csrf
                            <textarea name="comment" class="form-control form-control-sm mb-2" placeholder="Approval / Rejection comment..."></textarea>
                            <div class="d-flex gap-2">
                                <button type="submit" name="status" value="Approved" class="btn btn-sm btn-success w-100">Approve</button>
                                <button type="submit" name="status" value="Rejected" class="btn btn-sm btn-danger w-100">Reject</button>
                            </div>
                        </form>
                    @endif
                </div>

                <hr>

                {{-- 2. GROUP CHAIRMAN APPROVAL BOX --}}
                <div>
                    <h6 class="fw-bold fs-7 text-uppercase text-secondary">2. Group Chairman Approval</h6>
                    <div class="small mb-1">Status: <strong>{{ $data->group_chairman_approval_status }}</strong></div>
                    @if($data->groupChairmanApprover)
                        <div class="small text-muted">Approved By: {{ $data->groupChairmanApprover->name }}</div>
                        <div class="small text-muted">Date: {{ $data->group_chairman_approved_at ? $data->group_chairman_approved_at->format('d M, Y H:i') : '' }}</div>
                    @endif

                    @if($data->committee_chairman_approval_status === 'Approved' && $data->group_chairman_approval_status === 'Pending')
                        <form method="POST" action="{{ route('groupChairmanApproval', encrypt($data->id)) }}" class="mt-2">
                            @csrf
                            <textarea name="comment" class="form-control form-control-sm mb-2" placeholder="Approval / Rejection comment..."></textarea>
                            <div class="d-flex gap-2">
                                <button type="submit" name="status" value="Approved" class="btn btn-sm btn-success w-100">Final Approve</button>
                                <button type="submit" name="status" value="Rejected" class="btn btn-sm btn-danger w-100">Reject</button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        {{-- METADATA CARD --}}
        <div class="arbif-card">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-building me-1"></i> System Information</h6>

                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Branch:</span>
                        <span>{{ $data->branch->branch_name ?? 'N/A' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Company:</span>
                        <span>{{ $data->company->company_name ?? 'N/A' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Created At:</span>
                        <span>{{ $data->created_at ? $data->created_at->format('d M, Y H:i') : 'N/A' }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection