@extends('layouts.workingside')

@section('title', 'View Expense Voucher')

@section('page-title', 'View Expense Voucher')

@section('content')

<div class="arbif-page-header d-flex justify-content-between align-items-center mb-4">
    <h3>
        <div class="page-icon">
            <i class="fas fa-file-invoice-dollar"></i>
        </div>
        Voucher Details: {{ $data->expense_number ?? 'N/A' }}
    </h3>
    
    <div>
        <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm me-2">
            <i class="fas fa-arrow-left"></i> Back
        </a>

        {{-- Conditional Approval Operations --}}
        @if($data->AppStatus == 'Pending')
            <form action="{{ route('approveexpense', encrypt($data->id)) }}" method="POST" class="d-inline" onsubmit="return confirm('Approve this expense voucher?');">
                @csrf
                <button type="submit" class="btn btn-success btn-sm me-2">
                    <i class="fas fa-check-circle"></i> Approve
                </button>
            </form>

            <form action="{{ route('rejectexpense', encrypt($data->id)) }}" method="POST" class="d-inline" onsubmit="return confirm('Reject this expense voucher?');">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm me-2">
                    <i class="fas fa-times-circle"></i> Reject
                </button>
            </form>
        @endif

        {{-- Conditional Payment Operations --}}
        @if($data->AppStatus == 'Approved' && $data->PaymentStatus == 'Pending')
            <form action="{{ route('payexpense', encrypt($data->id)) }}" method="POST" class="d-inline" onsubmit="return confirm('Mark this approved expense as PAID/SETTLED?');">
                @csrf
                <button type="submit" class="btn btn-info btn-sm text-white">
                    <i class="fas fa-money-bill-wave"></i> Disburse Payment
                </button>
            </form>
        @endif
    </div>
</div>

{{-- GRID METADATA SUMMARY CARDS --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="arbif-card p-3 h-100">
            <small class="text-muted uppercase font-weight-bold d-block mb-1">Financial Intent</small>
            <h5>{{ $data->expense_title }}</h5>
            <span class="badge bg-light text-dark border">{{ optional($data->category)->name ?? 'Uncategorized' }}</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="arbif-card p-3 h-100">
            <small class="text-muted uppercase font-weight-bold d-block mb-1">Gross Allocation</small>
            <h4 class="text-primary font-weight-bold mt-1">{{ $data->currency }} {{ number_format($data->total_amount, 2) }}</h4>
            <small class="text-muted">Dated: {{ \Carbon\Carbon::parse($data->expense_date)->format('d M Y') }}</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="arbif-card p-3 h-100">
            <small class="text-muted uppercase font-weight-bold d-block mb-1">Workflow Tracking</small>
            <div class="mb-1">
                Approval: 
                @if($data->AppStatus == 'Pending')
                    <span class="badge bg-warning text-dark">Pending</span>
                @elseif($data->AppStatus == 'Approved')
                    <span class="badge bg-success">Approved</span>
                @else
                    <span class="badge bg-danger">{{ $data->AppStatus }}</span>
                @endif
            </div>
            <div>
                Payment: 
                <span class="badge {{ $data->PaymentStatus == 'Paid' ? 'bg-success' : 'bg-secondary' }}">
                    {{ $data->PaymentStatus }}
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="arbif-card p-3 h-100">
            <small class="text-muted uppercase font-weight-bold d-block mb-1">Auditing Registry</small>
            <small class="d-block text-muted">Audit: <code>{{ $data->AuditingStatus }}</code></small>
            <small class="d-block text-muted">Report: <code>{{ $data->ReportStatus }}</code></small>
        </div>
    </div>
</div>

{{-- VOUCHER LINE ITEMS BREAKDOWN --}}
<div class="arbif-card mb-4">
    <div class="arbif-card-body">
        <h5 class="arbif-section-title mb-3"><i class="fas fa-list-ol me-1"></i> Itemized Line Breakdowns</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th width="5%">#</th>
                        <th width="30%">Item Name</th>
                        <th width="25%">Line Context / Specs</th>
                        <th width="10%" class="text-center">Qty</th>
                        <th width="10%" class="text-center">Unit</th>
                        <th width="20%" class="text-end">Unit Cost</th>
                        <th width="20%" class="text-end">Gross Cost</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $item->item_name }}</strong></td>
                        <td><span class="text-muted small">{{ $item->description ?? 'N/A' }}</span></td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-center"><span class="badge bg-light text-dark border">{{ $item->unit ?? 'Pcs' }}</span></td>
                        <td class="text-end">{{ number_format($item->unit_cost, 2) }}</td>
                        <td class="text-end font-weight-bold">{{ number_format($item->total_cost, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-3 text-muted">No specific breakdown lines logged for this voucher. Displaying flat total summation instead.</td>
                    </tr>
                    @endforelse
                    <tr class="table-light">
                        <td colspan="6" class="text-end font-weight-bold"><strong>Total Calculated Balance:</strong></td>
                        <td class="text-end text-primary font-weight-bold"><strong>{{ $data->currency }} {{ number_format($data->total_amount, 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="arbif-card h-100">
            <div class="arbif-card-body">
                <h5 class="arbif-section-title mb-2">Voucher Scope Context</h5>
                <p class="text-dark p-3 bg-light rounded border mb-0" style="white-space: pre-wrap;">{{ $data->description ?? 'No historical context descriptions updated for this ledger asset node.' }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="arbif-card h-100">
            <div class="arbif-card-body">
                <h5 class="arbif-section-title mb-2">Operational Audit Footprints</h5>
                <table class="table table-sm table-bordered mb-0">
                    <tbody>
                        <tr>
                            <th width="40%" class="bg-light">Filed By</th>
                            <td>{{ optional($data->creator)->name ?? 'System Context' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Authorization Signature</th>
                            <td>
                                @if($data->approved_by)
                                    {{ optional($data->approver)->name }} <small class="text-muted">({{ \Carbon\Carbon::parse($data->approved_at)->format('d M Y H:i') }})</small>
                                @else
                                    <span class="text-warning">Awaiting Validation</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-light">Disbursement Signature</th>
                            <td>
                                @if($data->paid_by)
                                    {{ optional($data->payer)->name }} <small class="text-muted">({{ \Carbon\Carbon::parse($data->paid_at)->format('d M Y H:i') }})</small>
                                @else
                                    <span class="text-muted">Awaiting Disbursal</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection