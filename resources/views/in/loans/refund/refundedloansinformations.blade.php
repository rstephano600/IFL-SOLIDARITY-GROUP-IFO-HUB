@extends('layouts.workingside')

@section('title', 'Refunded Loans Archive')

@section('page-title', 'Refunded Loans Archive')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-archive"></i>
        </div>
        Refunded Loans Archive
    </h3>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="refundedArchiveTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Refund Number</th>
                        <th class="sortable">Loan Number</th>
                        <th class="sortable">Client Details</th>
                        <th class="sortable">Settled Amount</th>
                        <th class="sortable">Processed By</th>
                        <th class="sortable">Authorized By</th>
                        <th class="sortable">State</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong class="text-success">{{ $item->refund_number ?? 'N/A' }}</strong>
                            <br><small class="text-muted">{{ $item->refund_date ? $item->refund_date->format('d M Y') : 'N/A' }}</small>
                        </td>
                        <td>
                            <span>{{ optional($item->loan)->loan_number ?? 'N/A' }}</span>
                        </td>
                        <td>
                            {{ optional($item->client)->name ?? 'N/A' }}
                            @if($item->groupCenter)
                                <br><small class="text-muted">Center: {{ $item->groupCenter->center_name }}</small>
                            @endif
                        </td>
                        <td>
                            <strong class="text-dark">{{ number_format($item->refunded_amount ?? 0, 2) }}</strong>
                            <br><small class="text-muted">Gross: {{ number_format($item->total_refund ?? 0, 2) }}</small>
                        </td>
                        <td>
                            {{ optional($item->user)->name ?? 'System' }}
                        </td>
                        <td>
                            <span class="text-semibold text-success">
                                <i class="fas fa-user-check me-1"></i> {{ optional($item->approver)->name ?? 'Authorized' }}
                            </span>
                        </td>
                        <td>
                            <span class="arbif-badge arbif-badge-success">
                                <i class="fas fa-check-double me-1"></i> Refunded
                            </span>
                        </td>
                        <td>
                            {{-- View Details using the view route we built --}}
                            <a href="{{ route('viewloanrefund', encrypt($item->id)) }}" 
                               class="arbif-btn-view">
                                <i class="fas fa-eye"></i> View Profile
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <span class="text-muted">No historical refunded loan records found inside the ledger archive.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection