@extends('layouts.workingside')
@section('title', 'Manage Weekly Allowance Batch')
@section('page-title', 'Manage Weekly Allowance Batch')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-edit"></i></div>
        Review Batch: Week {{ $data->WeekNumber }}, {{ \Carbon\Carbon::parse($data->AllowanceMonth)->format('F Y') }}
    </h3>
    <div class="d-flex gap-2">
        <a href="{{ route('weeklyallowanceinformations') }}" class="btn btn-secondary btn-sm d-flex align-items-center text-decoration-none">
            <i class="fas fa-arrow-left me-1"></i> Back to Registry
        </a>
    </div>
</div>

<!-- Batch Overview Metadata Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="arbif-card p-3 border-start border-primary border-4 bg-white shadow-sm">
            <small class="text-muted text-uppercase font-weight-bold d-block mb-1">Batch Period</small>
            <h5 class="mb-0 text-dark">Week {{ $data->WeekNumber }} ({{ $data->AllowanceYear }})</h5>
        </div>
    </div>
    <div class="col-md-3">
        <div class="arbif-card p-3 border-start border-success border-4 bg-white shadow-sm">
            <small class="text-muted text-uppercase font-weight-bold d-block mb-1">Total Headcount</small>
            <h5 class="mb-0 text-dark">{{ $datas->count() }} Employees</h5>
        </div>
    </div>
    <div class="col-md-3">
        <div class="arbif-card p-3 border-start border-info border-4 bg-white shadow-sm">
            <small class="text-muted text-uppercase font-weight-bold d-block mb-1">Cumulative Value</small>
            <h5 class="mb-0 text-success">{{ number_format($datas->sum('AllowanceAmount'), 2) }}</h5>
        </div>
    </div>
    <div class="col-md-3">
        <div class="arbif-card p-3 border-start border-warning border-4 bg-white shadow-sm">
            <small class="text-muted text-uppercase font-weight-bold d-block mb-1">Approval Matrix</small>
            <h5 class="mb-0">
                <span class="arbif-badge arbif-badge-warning">{{ $data->ApprovalStatus }}</span>
            </h5>
        </div>
    </div>
</div>

<!-- Editable Batch Ledger Form -->
<form method="POST" action="{{ route('updateweeklyallowance') }}">
    @csrf
    @method('POST')

    <div class="arbif-card">
        <div class="arbif-card-body p-0">
            <div class="arbif-table-wrap">
                <table class="arbif-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 5%">#</th>
                            <th style="width: 30%">Employee Information</th>
                            <th style="width: 20%">Editable Allowance Amount <span class="text-danger">*</span></th>
                            <th style="width: 35%">Internal Operational Comment / Adjustments</th>
                            <th style="width: 10%">System Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($datas as $index => $item)
                        <tr>
                            <td>
                                {{ $index + 1 }}
                                <!-- Hidden element targeting array loops back to controller bulk transaction safely -->
                                <input type="hidden" name="ids[]" value="{{ $item->id }}">
                            </td>
                            <td>
                                <strong class="d-block text-dark">
                                    {{ $item->employee->user->FirstName ?? '' }} 
                                    {{ $item->employee->user->LastName ?? '' }}
                                </strong>
                                <small class="text-muted font-monospace">{{ $item->employee->EmployeeID ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light text-secondary">Tsh</span>
                                    <input type="number" 
                                           name="AllowanceAmount[]" 
                                           class="form-control form-control-sm font-weight-bold text-end" 
                                           step="0.01" 
                                           min="0" 
                                           value="{{ old('AllowanceAmount.' . $index, $item->AllowanceAmount) }}" 
                                           required>
                                </div>
                            </td>
                            <td>
                                <input type="text" 
                                       name="AllowanceComment[]" 
                                       class="form-control form-control-sm" 
                                       placeholder="Provide reason for adjustments..." 
                                       value="{{ old('AllowanceComment.' . $index, $item->AllowanceComment) }}">
                            </td>
                            <td>
                                <small class="arbif-badge arbif-badge-navy d-inline-block">{{ $item->Conditions }}</small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="arbif-table-empty">
                                <i class="fas fa-exclamation-triangle d-block mb-2 fs-3 text-warning"></i> No active record entries verified within this batch allocation.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($datas->isNotEmpty())
        <div class="arbif-card-footer card-footer d-flex justify-content-between align-items-center bg-light p-3 border-top">
            <!-- Dangerous Action: Soft Delete Entire Batch Sequence -->
            <button type="button" 
                    class="btn btn-outline-danger btn-sm" 
                    onclick="confirmBatchDeletion('{{ Crypt::encrypt($data->id) }}')">
                <i class="fas fa-trash-alt me-1"></i> Terminate Entire Batch
            </button>

            <!-- Save Form Action -->
            <div class="d-flex gap-2">
                <a href="{{ route('weeklyallowanceinformations') }}" class="arbif-btn-cancel text-decoration-none py-2 px-3 bg-secondary text-white rounded d-inline-flex align-items-center small">
                    <i class="fas fa-times me-1"></i> Discard
                </a>
                <button type="submit" class="arbif-btn-submit py-2 px-3 rounded small border-0 text-white btn-primary">
                    <i class="fas fa-save me-1"></i> Apply Changes To Ledger
                </button>
            </div>
        </div>
        @endif
    </div>
</form>

<!-- Dedicated Hidden Form for Bulk Erasure Interceptions -->
<form id="delete-batch-form" method="POST" style="display: none;">
    @csrf
    @method('POST')
</form>

<!-- Standard JS Alert Interceptor Logic ensuring cascading confirmations -->
<script>
function confirmBatchDeletion(encryptedId) {
    if (confirm("Are you absolutely sure you want to drop this entire allowance batch? This action resets the Status parameters for all employees inside this period block.")) {
        const structuralForm = document.getElementById('delete-batch-form');
        structuralForm.action = "{{ url('in/weeklyallowance/delete') }}/" + encryptedId; 
        structuralForm.submit();
    }
}
</script>
@endsection