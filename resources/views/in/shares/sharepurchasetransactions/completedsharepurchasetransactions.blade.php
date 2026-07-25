@extends('layouts.workingside')
@section('title', 'Completed Share Purchase Transactions')
@section('page-title', 'Completed Transactions')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-check-double"></i></div>
        Completed Share Purchase Transactions Archive
    </h3>
    <a href="{{ route('sharepurchasetransactions') }}" class="arbif-btn-submit text-decoration-none">
        <i class="fas fa-list me-1"></i> All Transactions
    </a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="completedTransactionsTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Transaction Ref</th>
                        <th class="sortable">Member</th>
                        <th class="sortable">Share Type</th>
                        <th class="sortable">Quantity</th>
                        <th class="sortable">Total Value</th>
                        <th class="sortable">Certificate No</th>
                        <th class="sortable">Issued By</th>
                        <th class="sortable">Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $index => $tx)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><span class="arbif-badge arbif-badge-navy">{{ $tx->TransactionRefNo ?? $tx->id }}</span></td>
                        <td>
                            <div class="fw-bold text-dark">{{ $tx->member->full_name ?? $tx->member->member_name ?? '—' }}</div>
                            <small class="text-muted">{{ $tx->member->member_code ?? '—' }}</small>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $tx->shareType->TypeName ?? '—' }}</div>
                            <small class="text-muted">{{ $tx->shareOffering->offering_name ?? '—' }}</small>
                        </td>
                        <td><span class="fw-bold text-dark">{{ number_format($tx->SharesQuantity ?? $tx->quantity ?? 0) }}</span></td>
                        <td><strong class="text-navy">{{ number_format($tx->TotalAmount ?? $tx->amount ?? 0, 2) }}</strong></td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                <i class="fas fa-certificate text-warning me-1"></i> {{ $tx->certificate->certificate_number ?? $tx->certificate_number ?? '—' }}
                            </span>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $tx->certificateGenerator->name ?? '—' }}</div>
                        </td>
                        <td>
                            <span class="arbif-badge bg-success text-white">
                                <i class="fas fa-check-circle me-1"></i> {{ $tx->transaction_status }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('viewsharepurchasetransactions', [encrypt($tx->id)]) }}" class="arbif-btn-edit">
                                <i class="fas fa-eye"></i> View
                            </a>
                            @if($tx->certificate || isset($tx->certificate_number))
                            <a href="{{ route('downloadcertificatesharepurchasetransactions', [encrypt($tx->id)]) }}" class="arbif-btn-submit text-decoration-none" style="background-color: #6f42c1; border-color: #6f42c1;">
                                <i class="fas fa-download"></i> Cert PDF
                            </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="arbif-table-empty">
                            <i class="bi bi-inbox"></i>
                            No completed transactions found in archive.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="arbif-pagination">
            <span class="arbif-pagination-info" data-table-info="completedTransactionsTable"></span>
            <div class="arbif-pagination-pages" data-table-pages="completedTransactionsTable"></div>
        </div>
    </div>
</div>
@endsection