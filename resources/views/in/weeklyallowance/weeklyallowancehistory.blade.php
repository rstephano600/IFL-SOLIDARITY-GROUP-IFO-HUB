@extends('layouts.workingside')
@section('title', 'Weekly Allowance Historical Archive')
@section('page-title', 'Weekly Allowance Historical Archive')

@section('content')
<div class="arbif-page-header mb-4">
    <h3>
        <div class="page-icon"><i class="fas fa-history text-success"></i></div>
        Settled Allowance Historical Logs
    </h3>
    <div class="d-flex gap-2">
        <a href="{{ route('weeklyallowanceinformations') }}" class="btn btn-secondary btn-sm d-flex align-items-center text-decoration-none">
            <i class="fas fa-arrow-left me-1"></i> Main Registry
        </a>
    </div>
</div>

@if($datas->isEmpty())
<div class="arbif-card shadow-sm border text-center py-5">
    <div class="arbif-card-body text-muted">
        <i class="fas fa-archive fs-1 mb-3 text-secondary d-block"></i>
        <h5>No Cleared Batches in Archive</h5>
        <p class="small mb-0 text-secondary">Historical entries appear here once payment runs are completed.</p>
    </div>
</div>
@else
<div class="accordion" id="historicalAllowancesAccordion">
    @foreach($datas as $batchKey => $group)
        @php 
            $sampleRecord = $group->first();
            $totalBatchAmount = $group->sum('AllowanceAmount');
            $cleanCollapseId = 'history_collapse_' . Str::slug($batchKey);
        @endphp

        <div class="card border shadow-sm mb-3 rounded overflow-hidden">
            <!-- Header Summaries -->
            <div class="card-header bg-white p-3 d-flex flex-wrap align-items-center justify-content-between gap-3" id="heading_{{ $cleanCollapseId }}">
                <div class="d-flex align-items-center gap-3 cursor-pointer" data-bs-toggle="collapse" data-bs-target="#{{ $cleanCollapseId }}" aria-expanded="false">
                    <span class="btn btn-sm btn-light border p-1"><i class="fas fa-chevron-down"></i></span>
                    <div>
                        <h6 class="mb-0 text-dark font-weight-bold">Batch: {{ str_replace('-', ' / ', $batchKey) }}</h6>
                        <div class="d-flex gap-2 mt-1 align-items-center">
                            <span class="badge bg-success small py-1 px-2"><i class="fas fa-check"></i> Disbursed</span>
                            <small class="text-muted">Paid Date: {{ $sampleRecord->PaidDate ? $sampleRecord->PaidDate->format('d M Y') : 'N/A' }}</small>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-5">
                    <div class="text-end">
                        <small class="text-muted text-uppercase d-block" style="font-size: 10px;">Method used</small>
                        <span class="badge bg-secondary font-weight-bold">{{ $sampleRecord->PayMode ?? 'System Batch' }}</span>
                    </div>
                    <div class="text-end">
                        <small class="text-muted text-uppercase d-block" style="font-size: 10px;">Total Remitted</small>
                        <strong class="text-success fs-5">{{ number_format($totalBatchAmount, 2) }}</strong>
                    </div>
                </div>
            </div>

            <!-- Details Table Panel -->
            <div id="{{ $cleanCollapseId }}" class="collapse" aria-labelledby="heading_{{ $cleanCollapseId }}" data-bs-parent="#historicalAllowancesAccordion">
                <div class="card-body bg-light p-0 border-top">
                    <div class="table-responsive">
                        <table class="table table-hover bg-white mb-0 text-nowrap align-middle">
                            <thead class="bg-light text-secondary small text-uppercase">
                                <tr>
                                    <th class="ps-3 border-0">Employee Detail</th>
                                    <th class="border-0">Reference ID</th>
                                    <th class="text-end border-0">Approved Value</th>
                                    <th class="text-end border-0 pe-3">Remittance Ledger Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($group as $item)
                                <tr>
                                    <td class="ps-3">
                                        <div class="font-weight-bold text-dark">
                                            {{ $item->employee->user->FirstName ?? '' }} {{ $item->employee->user->LastName ?? '' }}
                                        </div>
                                    </td>
                                    <td><small class="text-muted font-monospace">{{ $item->employee->EmployeeID ?? 'N/A' }}</small></td>
                                    <td class="text-end font-weight-bold text-success">{{ number_format($item->AllowanceAmount, 2) }}</td>
                                    <td class="text-end pe-3">
                                        <span class="text-success small font-weight-bold"><i class="fas fa-receipt me-1"></i> Paid Confirmation Receipt</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endif
@endsection