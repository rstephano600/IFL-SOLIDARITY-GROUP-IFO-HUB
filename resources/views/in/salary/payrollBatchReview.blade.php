@extends('layouts.workingside')
@section('title', 'Review Payroll Batch')
@section('page-title', 'Review Payroll Batch')

@section('content')

<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-file-invoice-dollar"></i></div>Batch {{ $batch->BatchCode }}</h3>
    <a href="{{ route('payrollApprovalIndex') }}" class="arbif-btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Queue
    </a>
</div>

{{-- ── BATCH SUMMARY ── --}}
<div class="arbif-card mb-3">
    <div class="arbif-card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <strong>Payroll Month</strong>
                <div>{{ \Carbon\Carbon::parse($batch->PayrollMonth . '-01')->format('F Y') }}</div>
            </div>
            <div class="col-md-3">
                <strong>Status</strong>
                <div>
                    @if($batch->BatchStatus == 'Approved')
                        <span class="arbif-badge arbif-badge-success">Approved</span>
                    @elseif($batch->BatchStatus == 'PendingApproval')
                        <span class="arbif-badge arbif-badge-warning">Pending Approval</span>
                    @else
                        <span class="arbif-badge arbif-badge-navy">{{ $batch->BatchStatus }}</span>
                    @endif
                </div>
            </div>
            <div class="col-md-3">
                <strong>Submitted By</strong>
                <div>
                    {{ $batch->submittedBy->FirstName ?? '—' }} {{ $batch->submittedBy->LastName ?? '' }}
                    <br><small class="text-muted">{{ optional($batch->SubmittedAt)->format('d M Y, H:i') ?? '—' }}</small>
                </div>
            </div>
            <div class="col-md-3">
                <strong>Total Net Pay</strong>
                <div class="fs-5">{{ number_format((float) $rows->sum('NetPay'), 2) }}</div>
            </div>
        </div>

        @if($batch->RejectionReason)
            <div class="alert alert-danger mt-3 mb-0">
                <strong>Previously rejected</strong> by {{ $batch->rejectedBy->FirstName ?? 'N/A' }}
                on {{ optional($batch->RejectedAt)->format('d M Y, H:i') }}:
                <div class="mt-1">{{ $batch->RejectionReason }}</div>
            </div>
        @endif
    </div>
</div>

{{-- ── EMPLOYEE ROWS ── --}}
<div class="arbif-card mb-3">
    <div class="arbif-card-body">
        <h5 class="arbif-section-title">Employees — {{ $rows->count() }}</h5>

        <div class="arbif-table-wrap">
            <table class="arbif-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th>Gross</th>
                        <th>Allowance</th>
                        <th>Overtime</th>
                        <th>NSSF (Emp)</th>
                        <th>PAYE</th>
                        <th>Deductions</th>
                        <th>Net Pay</th>
                        <th>Condition</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $index => $row)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            {{ $row->employee->user->FirstName ?? '' }}
                            {{ $row->employee->user->LastName ?? '' }}
                            <br><span class="arbif-badge arbif-badge-navy">{{ $row->employee->EmployeeID ?? 'N/A' }}</span>
                        </td>
                        <td>{{ number_format((float) $row->AmountPaid, 2) }}</td>
                        <td>{{ number_format((float) $row->Allowance, 2) }}</td>
                        <td>{{ number_format((float) $row->Overtime, 2) }}</td>
                        <td>{{ number_format((float) $row->EmpNssf, 2) }}</td>
                        <td>{{ number_format((float) $row->Paye, 2) }}</td>
                        <td>{{ number_format((float) ($row->Heslb + $row->Bcabd + $row->Advance), 2) }}</td>
                        <td><strong>{{ number_format((float) $row->NetPay, 2) }}</strong></td>
                        <td>{{ $row->Condition }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="arbif-table-empty text-center py-4">No rows found for this batch.</td>
                    </tr>
                    @endforelse
                </tbody>
                @if($rows->isNotEmpty())
                <tfoot>
                    <tr class="fw-bold">
                        <td colspan="2">Total</td>
                        <td>{{ number_format((float) $rows->sum('AmountPaid'), 2) }}</td>
                        <td>{{ number_format((float) $rows->sum('Allowance'), 2) }}</td>
                        <td>{{ number_format((float) $rows->sum('Overtime'), 2) }}</td>
                        <td>{{ number_format((float) $rows->sum('EmpNssf'), 2) }}</td>
                        <td>{{ number_format((float) $rows->sum('Paye'), 2) }}</td>
                        <td>{{ number_format((float) ($rows->sum('Heslb') + $rows->sum('Bcabd') + $rows->sum('Advance')), 2) }}</td>
                        <td>{{ number_format((float) $rows->sum('NetPay'), 2) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>

{{-- ── DECISION ACTIONS ── --}}
@if($batch->BatchStatus == 'PendingApproval')
<div class="arbif-card">
    <div class="arbif-card-body d-flex flex-wrap gap-2 justify-content-end">
        <button type="button" class="arbif-btn-delete" data-bs-toggle="modal" data-bs-target="#rejectModal">
            <i class="fas fa-times"></i> Reject
        </button>

        <form method="POST" action="{{ route('approvePayroll') }}"
              onsubmit="return confirm('Approve this payroll batch? Rows will be moved to final payroll records and cannot be edited afterward.');">
            @csrf
            <input type="hidden" name="BatchCode" value="{{ $batch->BatchCode }}">
            <button type="submit" class="arbif-btn-submit">
                <i class="fas fa-check"></i> Approve &amp; Submit to Payroll
            </button>
        </form>
    </div>
</div>

{{-- ── REJECT MODAL ── --}}
<div class="modal fade arbif-modal" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon"><i class="fas fa-times-circle"></i></div>
                <h5 class="modal-title">Reject Batch {{ $batch->BatchCode }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('rejectPayroll') }}">
                    @csrf
                    <input type="hidden" name="BatchCode" value="{{ $batch->BatchCode }}">

                    <label class="form-label">Reason for rejection <span class="text-danger">*</span></label>
                    <textarea name="RejectionReason" class="form-control" rows="3" required
                              placeholder="e.g. Overtime figures need review for the Sales department"></textarea>

                    <div class="modal-footer mt-3 px-0 pb-0">
                        <button type="button" class="arbif-btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="arbif-btn-delete">
                            <i class="fas fa-times"></i> Confirm Rejection
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@endsection