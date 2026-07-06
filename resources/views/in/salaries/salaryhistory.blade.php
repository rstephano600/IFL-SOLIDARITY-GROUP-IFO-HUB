@extends('layouts.workingside')
@section('title', 'Salary Historical Archive')
@section('page-title', 'Salary Historical Archive')

@section('content')
<div class="arbif-page-header mb-4">
    <h3>
        <div class="page-icon"><i class="fas fa-history text-success"></i></div>
        Settled Payroll Historical Logs
    </h3>
    <div class="d-flex gap-2">
        <a href="{{ route('salaryinformations') }}" class="btn btn-secondary btn-sm d-flex align-items-center text-decoration-none">
            <i class="fas fa-arrow-left me-1"></i> Return Registry
        </a>
    </div>
</div>

@if($datas->isEmpty())
<div class="card shadow-sm border text-center py-5">
    <div class="card-body text-muted">
        <i class="fas fa-archive fs-1 mb-3 text-secondary d-block"></i>
        <h5>No Cleared Salary Batches inside Archives</h5>
        <p class="small mb-0 text-secondary">Historical entries appear here once payment operations are successfully executed.</p>
    </div>
</div>
@else
<div class="accordion" id="historicalSalaryAccordion">
    @foreach($datas as $batchKey => $group)
        @php 
            $sampleRecord = $group->first();
            $totalNetPay = $group->sum('NetPay');
            $cleanCollapseId = 'history_collapse_' . Str::slug($batchKey);
            
            $dateParts = explode('-', $batchKey);
            $formattedBatch = count($dateParts) == 2 ? \Carbon\Carbon::createFromDate($dateParts[0], $dateParts[1], 1)->format('F, Y') : $batchKey;
        @endphp

        <div class="card border shadow-sm mb-3 rounded overflow-hidden">
            <!-- Header Summary Block -->
            <div class="card-header bg-white p-3 d-flex flex-wrap align-items-center justify-content-between gap-3" id="heading_{{ $cleanCollapseId }}">
                <div class="d-flex align-items-center gap-3 cursor-pointer" data-bs-toggle="collapse" data-bs-target="#{{ $cleanCollapseId }}" aria-expanded="false">
                    <span class="btn btn-sm btn-light border p-1"><i class="fas fa-chevron-down"></i></span>
                    <div>
                        <h6 class="mb-0 text-dark font-weight-bold">Batch Period: {{ $formattedBatch }}</h6>
                        <div class="d-flex gap-2 mt-1 align-items-center">
                            <span class="badge bg-success small py-1 px-2" style="font-size: 10px;"><i class="fas fa-check-double"></i> Fully Disbursed</span>
                            <small class="text-muted">Cleared On: {{ $sampleRecord->PaidDate ? \Carbon\Carbon::parse($sampleRecord->PaidDate)->format('d M Y') : 'N/A' }}</small>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-5">
                    <div class="text-end">
                        <small class="text-muted text-uppercase d-block" style="font-size: 10px;">Payment Method</small>
                        <span class="badge bg-secondary font-weight-bold" style="font-size: 11px;">{{ $sampleRecord->PayMode ?? 'Bank Transfer' }}</span>
                    </div>
                    <div class="text-end">
                        <small class="text-muted text-uppercase d-block" style="font-size: 10px;">Total Outflow Remitted</small>
                        <strong class="text-success fs-5">{{ number_format($totalNetPay, 2) }}</strong>
                    </div>
                </div>
            </div>

            <!-- Collapse Details Item Sheet -->
            <div id="{{ $cleanCollapseId }}" class="collapse" aria-labelledby="heading_{{ $cleanCollapseId }}" data-bs-parent="#historicalSalaryAccordion">
                <div class="card-body bg-light p-0 border-top">
                    <div class="table-responsive">
                        <table class="table table-hover bg-white mb-0 text-nowrap align-middle">
                            <thead class="bg-light text-secondary small text-uppercase">
                                <tr>
                                    <th class="ps-3 border-0">Staff Name</th>
                                    <th class="border-0">Reference Code</th>
                                    <th class="text-end border-0">Gross Base</th>
                                    <th class="text-end border-0 text-success pe-3">Net Cleared Remittance</th>
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
                                    <td class="text-end text-muted">{{ number_format($item->ActualGross, 2) }}</td>
                                    <td class="text-end font-weight-bold text-success pe-3">
                                        {{ number_format($item->NetPay, 2) }} <i class="fas fa-check text-success small ms-1"></i>
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