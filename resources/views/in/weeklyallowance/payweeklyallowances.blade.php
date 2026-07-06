@extends('layouts.workingside')
@section('title', 'Pending Weekly Allowances')
@section('page-title', 'Pending Weekly Allowances Ledger')

@section('content')
<div class="arbif-page-header mb-4">
    <h3>
        <div class="page-icon"><i class="fas fa-hourglass-half text-warning"></i></div>
        Pending  Disbursals
    </h3>
    <div class="d-flex gap-2">
        <a href="{{ route('weeklyallowanceinformations') }}" class="btn btn-secondary btn-sm d-flex align-items-center text-decoration-none">
            <i class="fas fa-list me-1"></i> View Main Ledger
        </a>
    </div>
</div>

@if($datas->isEmpty())
<div class="arbif-card">
    <div class="arbif-card-body text-center py-5 text-muted">
        <i class="fas fa-shield-alt d-block mb-3 fs-1 text-success"></i>
        <h5>All Clean! No Pending Allowances Found</h5>
        <p class="small mb-0">Every active allowance batch sequence has been fully processed or reviewed.</p>
    </div>
</div>
@else
<div class="accordion" id="pendingAllowancesAccordion">
    @foreach($datas as $batchKey => $group)
        @php 
            // Extract a proxy sample record to pass IDs down to the controller queries securely
            $sampleRecord = $group->first();
            $encryptedId = Crypt::encrypt($sampleRecord->id);
            $totalBatchAmount = $group->sum('AllowanceAmount');
            $cleanCollapseId = 'collapse_' . Str::slug($batchKey);
        @endphp

        <div class="card border shadow-sm mb-3 rounded overflow-hidden">
            <!-- Accordion Structural Heading Header Element -->
            <div class="card-header bg-white p-3 d-flex flex-wrap align-items-center justify-content-between gap-3" id="heading_{{ $cleanCollapseId }}">
                <div class="d-flex align-items-center gap-3 cursor-pointer" data-bs-toggle="collapse" data-bs-target="#{{ $cleanCollapseId }}" aria-expanded="false">
                    <span class="btn btn-sm btn-light border p-1"><i class="fas fa-chevron-down"></i></span>
                    <div>
                        <h6 class="mb-0 text-dark font-weight-bold">Batch: {{ str_replace('-', ' / ', $batchKey) }}</h6>
                        <small class="text-muted">Contains {{ $group->count() }} working staff profiles</small>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-4">
                    <div class="text-end">
                        <small class="text-muted text-uppercase d-block style-font" style="font-size: 10px;">Batch Value</small>
                        <strong class="text-primary fs-5">{{ number_format($totalBatchAmount, 2) }}</strong>
                    </div>

                    <!-- Authorization Decision Matrices -->
                    <div class="d-flex gap-1">
                        <button type="button" class="btn btn-sm btn-primary px-3" data-bs-toggle="modal" data-bs-target="#payModal{{ $sampleRecord->id }}">
                            <i class="fas fa-coins me-1"></i> Disburse Funds
                        </button>
                    </div>
                </div>
            </div>

        <!-- PAYMENT DISBURSEMENT MODAL -->
        <div class="modal fade" id="payModal{{ $sampleRecord->id }}" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('payweeklyallowance', $encryptedId) }}" method="POST">
                    @csrf
                    <div class="modal-content shadow-lg">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title"><i class="fas fa-money-check me-2"></i> Disburse Settlement Batch</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-info py-2 small border-0 mb-3">
                                Total batch value to disburse: <strong>{{ number_format($totalBatchAmount, 2) }}</strong>
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-weight-bold small text-dark">Payment Method <span class="text-danger">*</span></label>
                                <select name="PayMode" class="form-select form-select-sm" required>
                                    <option value="" selected disabled>Select standard disbursement method...</option>
                                    <option value="Bank Transfer">Direct Corporate Bank Transfer</option>
                                    <option value="Mobile Money">Mobile Wallet Settlement</option>
                                    <option value="Cash">Petty Cash Remittance</option>
                                    <option value="Cheque">Corporate Bank Cheque</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-sm btn-success px-4">Execute Disbursal</button>
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
    if (confirm("Are you certain you want to " + processContext + "? This automatically applies bulk updates across all grouped identities.")) {
        document.getElementById(formId).submit();
    }
}
</script>
@endsection