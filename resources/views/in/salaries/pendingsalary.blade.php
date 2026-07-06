@extends('layouts.workingside')
@section('title', 'Pending Salary Approvals')
@section('page-title', 'Pending Salary Ledger')

@section('content')
<div class="arbif-page-header mb-4">
    <h3>
        <div class="page-icon"><i class="fas fa-hourglass-half text-warning"></i></div>
        Pending Salary Approvals & Disbursals
    </h3>
    <div class="d-flex gap-2">
        <a href="{{ route('salaryinformations') }}" class="btn btn-secondary btn-sm d-flex align-items-center text-decoration-none">
            <i class="fas fa-list me-1"></i> View Salary Registry
        </a>
    </div>
</div>

@if($datas->isEmpty())
<div class="card shadow-sm border">
    <div class="card-body text-center py-5 text-muted">
        <i class="fas fa-check-circle d-block mb-3 fs-1 text-success"></i>
        <h5>No Pending Salary Batches Found</h5>
        <p class="small mb-0">All uploaded monthly payroll cycles have been processed or reviewed.</p>
    </div>
</div>
@else
<div class="accordion" id="pendingSalaryAccordion">
    @foreach($datas as $batchKey => $group)
        @php 
            $sampleRecord = $group->first();
            $encryptedId = Crypt::encrypt($sampleRecord->id);
            $totalNetPay = $group->sum('NetPay');
            $cleanCollapseId = 'collapse_' . Str::slug($batchKey);
            
            // Format batch name nicely (e.g., "2026-07" into "July 2026")
            $dateParts = explode('-', $batchKey);
            $formattedBatch = count($dateParts) == 2 ? \Carbon\Carbon::createFromDate($dateParts[0], $dateParts[1], 1)->format('F, Y') : $batchKey;
        @endphp

        <div class="card border shadow-sm mb-3 rounded overflow-hidden">
            <!-- Accordion Header Segment -->
            <div class="card-header bg-white p-3 d-flex flex-wrap align-items-center justify-content-between gap-3" id="heading_{{ $cleanCollapseId }}">
                <div class="d-flex align-items-center gap-3 cursor-pointer" data-bs-toggle="collapse" data-bs-target="#{{ $cleanCollapseId }}" aria-expanded="false">
                    <span class="btn btn-sm btn-light border p-1"><i class="fas fa-chevron-down"></i></span>
                    <div>
                        <h6 class="mb-0 text-dark font-weight-bold">Payroll Month: {{ $formattedBatch }}</h6>
                        <small class="text-muted">Contains {{ $group->count() }} compiled staff profiles</small>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-4">
                    <div class="text-end">
                        <small class="text-muted text-uppercase d-block" style="font-size: 10px;">Total Net Commitment</small>
                        <strong class="text-primary fs-5">{{ number_format($totalNetPay, 2) }}</strong>
                    </div>

                    <!-- Operations Matrix Selection Actions -->
                    <div class="d-flex gap-1">
                        <button type="button" class="btn btn-sm btn-success px-3" onclick="confirmAction('approve-salary-form-{{ $sampleRecord->id }}', 'approve this complete salary batch')">
                            <i class="fas fa-check-circle me-1"></i> Approve
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectSalaryModal{{ $sampleRecord->id }}">
                            <i class="fas fa-ban me-1"></i> Reject
                        </button>
                        <button type="button" class="btn btn-sm btn-primary px-3" data-bs-toggle="modal" data-bs-target="#paySalaryModal{{ $sampleRecord->id }}">
                            <i class="fas fa-wallet me-1"></i> Disburse Salaries
                        </button>
                    </div>
                </div>
            </div>

            <!-- Bulk Execution Underlying Form Redirect -->
            <form id="approve-salary-form-{{ $sampleRecord->id }}" action="{{ route('approvesalary', $encryptedId) }}" method="POST" class="d-none">@csrf</form>

            <!-- Expanded Summary Workspace Table -->
            <div id="{{ $cleanCollapseId }}" class="collapse" aria-labelledby="heading_{{ $cleanCollapseId }}" data-bs-parent="#pendingSalaryAccordion">
                <div class="card-body bg-light p-0 border-top">
                    <div class="table-responsive">
                        <table class="table table-hover bg-white mb-0 text-nowrap align-middle">
                            <thead class="bg-light text-secondary small text-uppercase">
                                <tr>
                                    <th class="ps-3 border-0">Employee</th>
                                    <th class="border-0">Employee ID</th>
                                    <th class="text-end border-0">Actual Gross</th>
                                    <th class="text-end border-0 text-danger">Total Deductions</th>
                                    <th class="text-end border-0 text-success pe-3">Net Pay</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($group as $item)
                                @php
                                    // Total calculations inside blade for representation metrics
                                    $deductions = ($item->Advance ?? 0) + ($item->OvtmAdvn ?? 0) + ($item->Heslb ?? 0) + 
                                                  ($item->Absent ?? 0) + ($item->Bcabd ?? 0) + ($item->EmpNssf ?? 0) + 
                                                  ($item->NssfPay ?? 0) + ($item->Paye ?? 0) + ($item->SdlPay ?? 0) + ($item->WcfPay ?? 0);
                                @endphp
                                <tr>
                                    <td class="ps-3">
                                        <div class="font-weight-bold text-dark">
                                            {{ $item->employee->user->FirstName ?? '' }} {{ $item->employee->user->LastName ?? '' }}
                                        </div>
                                    </td>
                                    <td><small class="text-muted font-monospace">{{ $item->employee->EmployeeID ?? 'N/A' }}</small></td>
                                    <td class="text-end">{{ number_format($item->ActualGross, 2) }}</td>
                                    <td class="text-end text-danger">-{{ number_format($deductions, 2) }}</td>
                                    <td class="text-end font-weight-bold text-success pe-3">{{ number_format($item->NetPay, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- REJECT SALARY MODAL -->
        <div class="modal fade" id="rejectSalaryModal{{ $sampleRecord->id }}" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('rejectsalary', $encryptedId) }}" method="POST">
                    @csrf
                    <div class="modal-content shadow-lg">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title"><i class="fas fa-times-circle me-2"></i> Reject Salary Batch</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p class="small text-secondary">Rejecting drops this batch back to review stages. Please note down calculations discrepancies or auditing compliance failure details below.</p>
                            <div class="mb-3">
                                <label class="form-label font-weight-bold small text-dark">Reason / Rejection Comments <span class="text-danger">*</span></label>
                                <textarea name="HrManagerComnt" rows="3" class="form-control" placeholder="Provide clear reasons for payroll adjustments..." required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-sm btn-danger px-3">Confirm Rejection</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- SALARY BANK DISBURSEMENT MODAL -->
        <div class="modal fade" id="paySalaryModal{{ $sampleRecord->id }}" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('paysalary', $encryptedId) }}" method="POST">
                    @csrf
                    <div class="modal-content shadow-lg">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title"><i class="fas fa-money-check-alt me-2"></i> Confirm Bank Settlement</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-info py-2 small border-0 mb-3">
                                Total net payroll value to disburse: <strong>{{ number_format($totalNetPay, 2) }}</strong>
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-weight-bold small text-dark">Payment Disbursal Method <span class="text-danger">*</span></label>
                                <select name="PayMode" class="form-select form-select-sm" required>
                                    <option value="" selected disabled>Select payout clearing account channel...</option>
                                    <option value="Bank Transfer">Bank Clearing House (EFT/TISS)</option>
                                    <option value="Cheque">Corporate Bank Cheque Issue</option>
                                    <option value="Cash">Cash Account Remittance</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-sm btn-success px-4">Execute Payment Release</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
</div>
@endif

<script>
function confirmAction(formId, processContext) {
    if (confirm("Are you absolute sure you want to " + processContext + "? This locks statuses across all records inside this group.")) {
        document.getElementById(formId).submit();
    }
}
</script>
@endsection