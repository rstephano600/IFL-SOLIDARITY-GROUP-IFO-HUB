@extends('layouts.workingside')

@section('title', 'Workflow Audit History')
@section('page-title', 'Workflow Audit Trail')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-history"></i>
        </div>
        Audit History ({{ $purchaseRequest->request_no }})
    </h3>

    <div>
        <a href="{{ route('viewpurchaseRequest', encrypt($purchaseRequest->id)) }}" class="btn btn-secondary px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to Profile
        </a>
    </div>
</div>

<div class="arbif-card mb-4">
    <div class="arbif-card-body p-4">
        <div class="row align-items-center">
            <div class="col-md-4">
                <strong>Procurement Type:</strong> {{ $purchaseRequest->procurementType->procurement_name ?? 'N/A' }}
            </div>
            <div class="col-md-4">
                <strong>Requester:</strong> {{ $purchaseRequest->requester->name ?? 'N/A' }}
            </div>
            <div class="col-md-4">
                <strong>Total Amount:</strong> {{ number_format($purchaseRequest->estimated_total, 2) }} TZS
            </div>
        </div>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <h6 class="fw-bold mb-3"><i class="fas fa-list-ol me-2 text-primary"></i>Sequential Workflow Log</h6>
        <div class="arbif-table-wrap">
            <table class="arbif-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Step Name</th>
                        <th>Action</th>
                        <th>Acted By</th>
                        <th>Assigned To</th>
                        <th>Started At</th>
                        <th>Acted At</th>
                        <th>Status</th>
                        <th>Comment / Attachment</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($workflows as $index => $wf)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $wf->workflowStep->step_name ?? 'Initialization' }}</strong>
                        </td>
                        <td>
                            <span class="badge bg-dark">{{ $wf->action }}</span>
                        </td>
                        <td>{{ $wf->actedBy->name ?? 'N/A' }}</td>
                        <td>{{ $wf->assignedTo->name ?? 'Unassigned' }}</td>
                        <td>{{ $wf->started_at ? $wf->started_at->format('d M, Y H:i A') : '--' }}</td>
                        <td>{{ $wf->acted_at ? $wf->acted_at->format('d M, Y H:i A') : '--' }}</td>
                        <td>
                            @php
                                $statusBadges = [
                                    'Pending' => 'bg-warning text-dark',
                                    'Completed' => 'bg-success',
                                    'Rejected' => 'bg-danger',
                                    'Cancelled' => 'bg-secondary'
                                ];
                            @endphp
                            <span class="badge {{ $statusBadges[$wf->purchase_status] ?? 'bg-secondary' }}">
                                {{ $wf->purchase_status }}
                            </span>
                        </td>
                        <td>
                            <div>{{ $wf->comment ?? 'N/A' }}</div>
                            @if($wf->attachment_path)
                                <a href="{{ asset('storage/' . $wf->attachment_path) }}" target="_blank" class="small text-primary">
                                    <i class="fas fa-paperclip me-1"></i> View Attachment
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">No Workflow Trail Found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection