@extends('layouts.workingside')
@section('title', 'Payroll Management')
@section('page-title', 'Payroll Management')

@section('content')

<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-money-check-alt"></i></div>Payroll Management — {{ \Carbon\Carbon::parse($month . '-01')->format('F Y') }}</h3>

    <div class="d-flex align-items-center gap-2">
        @if($activeBatch)
            <span class="arbif-badge arbif-badge-warning">
                <i class="fas fa-clock"></i> Batch {{ $activeBatch->BatchCode }} — {{ $activeBatch->BatchStatus }}
            </span>
        @endif
        <a href="{{ route('payrollApprovalIndex') }}" class="arbif-btn-secondary">
            <i class="fas fa-stamp"></i> Approval Queue
        </a>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- ── BATCH CONTROLS ── --}}
<div class="arbif-card mb-3">
    <div class="arbif-card-body">
        @if(!$activeBatch)
            {{-- No draft batch yet: show the Generate form --}}
            <h5 class="arbif-section-title">Generate Payroll</h5>
            <form method="POST" action="{{ route('generatePayrollByMonth') }}" class="row g-3 align-items-end">
                @csrf
                <div class="col-md-4">
                    <label class="form-label">Payroll Month <span class="text-danger">*</span></label>
                    <input type="month" name="PayrollMonth" class="form-control" value="{{ old('PayrollMonth', $month) }}" required>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="arbif-btn-submit" onclick="return confirm('Generate payroll for the selected month? This will pull all active salary setups and pending deductions.');">
                        <i class="fas fa-cogs"></i> Generate Payroll
                    </button>
                </div>
            </form>
        @elseif($activeBatch->BatchStatus == 'PendingApproval')
            {{-- Batch already sent for approval: no further edits from here --}}
            <div class="alert alert-warning mb-0 d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-hourglass-half"></i>
                    This batch is awaiting approval — submitted {{ optional($activeBatch->SubmittedAt)->format('d M Y, H:i') }}.
                    No further edits are possible until it's approved or rejected.
                </div>
                <a href="{{ route('payrollBatchReview', $activeBatch->BatchCode) }}" class="arbif-btn-submit text-nowrap">
                    <i class="fas fa-eye"></i> View Submission
                </a>
            </div>
        @else
            {{-- Draft batch exists: show Rollback + Change Month + Submit controls --}}
            @if($activeBatch->RejectionReason)
                <div class="alert alert-danger mb-3">
                    <strong>This batch was rejected</strong> by {{ $activeBatch->rejectedBy->FirstName ?? 'an approver' }}
                    on {{ optional($activeBatch->RejectedAt)->format('d M Y, H:i') }}:
                    <div class="mt-1">{{ $activeBatch->RejectionReason }}</div>
                    <div class="mt-1 text-muted">Make your corrections below, then resubmit for approval.</div>
                </div>
            @endif

            <div class="row g-3">
                <div class="col-md-6">
                    <h5 class="arbif-section-title">Change Payroll Month</h5>
                    <form method="POST" action="{{ route('changeGeneratedPayrollMonth') }}" class="d-flex gap-2">
                        @csrf
                        <input type="hidden" name="BatchCode" value="{{ $activeBatch->BatchCode }}">
                        <input type="month" name="PayrollMonth" class="form-control" value="{{ $activeBatch->PayrollMonth }}" required>
                        <button type="submit" class="arbif-btn-secondary text-nowrap">
                            <i class="fas fa-exchange-alt"></i> Update Month
                        </button>
                    </form>
                </div>

                <div class="col-md-6">
                    <h5 class="arbif-section-title">Rollback Batch</h5>
                    <form method="POST" action="{{ route('rollbackGeneratedPayroll') }}"
                          onsubmit="return confirm('Roll back this entire generated batch? All draft rows and their source deductions/overtime will be restored to their pre-generation state.');">
                        @csrf
                        <input type="hidden" name="BatchCode" value="{{ $activeBatch->BatchCode }}">
                        <button type="submit" class="arbif-btn-delete">
                            <i class="fas fa-undo"></i> Rollback This Batch
                        </button>
                    </form>
                </div>
            </div>

            <div class="mt-3 text-end">
                <form method="POST" action="{{ route('submitPayrollForApproval') }}"
                      onsubmit="return confirm('Submit this batch for approval? You won\'t be able to edit it further while it\'s pending.');">
                    @csrf
                    <input type="hidden" name="BatchCode" value="{{ $activeBatch->BatchCode }}">
                    <button type="submit" class="arbif-btn-submit">
                        <i class="fas fa-paper-plane"></i> Submit for Approval
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>

{{-- ── ACTIVE / DRAFT PAYROLL TABLE ── --}}
<div class="arbif-card mb-3">
    <div class="arbif-card-body">

        <div class="arbif-table-wrap">
            <table class="arbif-table" id="payrollTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th>Department</th>
                        <th>Gross</th>
                        <th>Allowance</th>
                        <th>Overtime</th>
                        <th>NSSF (Emp)</th>
                        <th>PAYE</th>
                        <th>Heslb</th>
                        <th>Loan</th>
                        <th>Advance</th>
                        <th>Net Pay</th>
                        <th>Condition</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($temp as $index => $item)
                    <tr data-department="{{ $item->employee->department ?? '' }}">
                        <td>{{ $index + 1 }}</td>
                        <td>
                            {{ $item->employee->user->FirstName ?? '' }}
                            {{ $item->employee->user->LastName ?? '' }}
                            <br><span class="arbif-badge arbif-badge-navy">{{ $item->employee->EmployeeID ?? 'N/A' }}</span>
                        </td>
                        <td>{{ $item->employee->department ?? '—' }}</td>
                        <td>{{ number_format((float) $item->AmountPaid, 2) }}</td>
                        <td>{{ number_format((float) $item->Allowance, 2) }}</td>
                        <td>{{ number_format((float) $item->Overtime, 2) }}</td>
                        <td>{{ number_format((float) $item->EmpNssf, 2) }}</td>
                        <td>{{ number_format((float) $item->Paye, 2) }}</td>
                        <td>{{ number_format((float) $item->Heslb, 2) }}</td>
                        <td>{{ number_format((float) $item->Bcabd, 2) }}</td>
                        <td>{{ number_format((float) $item->Advance, 2) }}</td>
                        <td><strong>{{ number_format((float) $item->NetPay, 2) }}</strong></td>
                        <td>
                            @if($item->Condition == 'None')
                                <span class="arbif-badge arbif-badge-success">{{ $item->Condition }}</span>
                            @else
                                <span class="arbif-badge arbif-badge-warning">{{ $item->Condition }}</span>
                            @endif
                        </td>
                        <td class="d-flex flex-wrap gap-1">
                            <a href="{{ route('editsalary', [encrypt($item->Emply_id)]) }}" class="arbif-btn-edit" title="Edit Gross">
                                <i class="fas fa-coins"></i>
                            </a>
                            <a href="{{ route('editnetsalary', [encrypt($item->Emply_id)]) }}" class="arbif-btn-edit" title="Edit Net Pay">
                                <i class="fas fa-hand-holding-usd"></i>
                            </a>
                            <a href="{{ route('editsalarydetails', [encrypt($item->Emply_id)]) }}" class="arbif-btn-secondary" title="Edit Details">
                                <i class="fas fa-sliders-h"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="14" class="arbif-table-empty text-center py-4">
                            <i class="fas fa-inbox d-block mb-2 fs-3 text-muted"></i>
                            No draft payroll generated yet for this month.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($temp->isNotEmpty())
                <tfoot>
                    <tr class="fw-bold">
                        <td colspan="3">Total</td>
                        <td>{{ number_format((float) $temp->sum('AmountPaid'), 2) }}</td>
                        <td>{{ number_format((float) $temp->sum('Allowance'), 2) }}</td>
                        <td>{{ number_format((float) $temp->sum('Overtime'), 2) }}</td>
                        <td>{{ number_format((float) $temp->sum('EmpNssf'), 2) }}</td>
                        <td>{{ number_format((float) $temp->sum('Paye'), 2) }}</td>
                        <td>{{ number_format((float) $temp->sum('Heslb'), 2) }}</td>
                        <td>{{ number_format((float) $temp->sum('Bcabd'), 2) }}</td>
                        <td>{{ number_format((float) $temp->sum('Advance'), 2) }}</td>
                        <td>{{ number_format((float) $temp->sum('NetPay'), 2) }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>

{{-- ── PREVIOUS MONTH — FINALIZED PAYROLL ── --}}
<div class="arbif-card">
    <div class="arbif-card-body">
        <h5 class="arbif-section-title">
            Previous Month Summary
            @if($prslr)
                — {{ \Carbon\Carbon::parse($prslr->PaidMonth . '-01')->format('F Y') }}
            @endif
        </h5>

        <div class="arbif-table-wrap">
            <table class="arbif-table" id="previousPayrollTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th>Gross</th>
                        <th>Net Pay</th>
                        <th>Date Paid</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($prslr1 as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            {{ $item->employee->user->FirstName ?? '' }}
                            {{ $item->employee->user->LastName ?? '' }}
                        </td>
                        <td>{{ number_format((float) $item->AmountPaid, 2) }}</td>
                        <td>{{ number_format((float) $item->NetPay, 2) }}</td>
                        <td>{{ optional($item->DatePaid)->format('d M Y') ?? '—' }}</td>
                        <td><span class="arbif-badge arbif-badge-success">{{ $item->Status }}</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="arbif-table-empty text-center py-4">
                            <i class="fas fa-inbox d-block mb-2 fs-3 text-muted"></i>
                            No finalized payroll found for the previous month.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.getElementById('departmentFilter')?.addEventListener('change', function () {
        const selected = this.value;
        document.querySelectorAll('#payrollTable tbody tr[data-department]').forEach(row => {
            row.style.display = (!selected || row.dataset.department === selected) ? '' : 'none';
        });
    });
</script>
@endpush