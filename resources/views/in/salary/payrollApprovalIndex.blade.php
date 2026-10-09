@extends('layouts.workingside')
@section('title', 'Payroll Approvals')
@section('page-title', 'Payroll Approvals')

@section('content')

<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-stamp"></i></div>Payroll Approval Queue</h3>
    <a href="{{ route('salarypay') }}" class="arbif-btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Payroll
    </a>
</div>

{{-- ── PENDING APPROVAL ── --}}
<div class="arbif-card mb-3">
    <div class="arbif-card-body">
        <h5 class="arbif-section-title">Awaiting Your Decision — {{ $pending->count() }}</h5>

        <div class="arbif-table-wrap">
            <table class="arbif-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Batch Code</th>
                        <th>Payroll Month</th>
                        <th>Submitted By</th>
                        <th>Submitted At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pending as $index => $batch)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><span class="arbif-badge arbif-badge-navy">{{ $batch->BatchCode }}</span></td>
                        <td>{{ \Carbon\Carbon::parse($batch->PayrollMonth . '-01')->format('F Y') }}</td>
                        <td>{{ $batch->submittedBy->FirstName ?? '—' }} {{ $batch->submittedBy->LastName ?? '' }}</td>
                        <td>{{ optional($batch->SubmittedAt)->format('d M Y, H:i') ?? '—' }}</td>
                        <td>
                            <a href="{{ route('payrollBatchReview', $batch->BatchCode) }}" class="arbif-btn-submit">
                                <i class="fas fa-search"></i> Review
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="arbif-table-empty text-center py-4">
                            <i class="fas fa-check-circle d-block mb-2 fs-3 text-muted"></i>
                            Nothing waiting for approval right now.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ── RECENT HISTORY ── --}}
<div class="arbif-card">
    <div class="arbif-card-body">
        <h5 class="arbif-section-title">Recent Batch History</h5>

        <div class="arbif-table-wrap">
            <table class="arbif-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Batch Code</th>
                        <th>Payroll Month</th>
                        <th>Status</th>
                        <th>Submitted By</th>
                        <th>Decided By</th>
                        <th>Decided At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($history as $index => $batch)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><span class="arbif-badge arbif-badge-navy">{{ $batch->BatchCode }}</span></td>
                        <td>{{ \Carbon\Carbon::parse($batch->PayrollMonth . '-01')->format('F Y') }}</td>
                        <td>
                            @if($batch->BatchStatus == 'Approved')
                                <span class="arbif-badge arbif-badge-success">Approved</span>
                            @elseif($batch->RejectionReason)
                                <span class="arbif-badge arbif-badge-danger">Rejected — Pending Rework</span>
                            @else
                                <span class="arbif-badge arbif-badge-warning">{{ $batch->BatchStatus }}</span>
                            @endif
                        </td>
                        <td>{{ $batch->submittedBy->FirstName ?? '—' }}</td>
                        <td>
                            @if($batch->BatchStatus == 'Approved')
                                {{ $batch->approvedBy->FirstName ?? '—' }}
                            @elseif($batch->RejectionReason)
                                {{ $batch->rejectedBy->FirstName ?? '—' }}
                            @else
                                —
                            @endif
                        </td>
                        <td>{{ optional($batch->ApprovedAt ?? $batch->RejectedAt)->format('d M Y, H:i') ?? '—' }}</td>
                        <td>
                            <a href="{{ route('payrollBatchReview', $batch->BatchCode) }}" class="arbif-btn-view">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="arbif-table-empty text-center py-4">
                            <i class="fas fa-inbox d-block mb-2 fs-3 text-muted"></i>
                            No payroll history yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection