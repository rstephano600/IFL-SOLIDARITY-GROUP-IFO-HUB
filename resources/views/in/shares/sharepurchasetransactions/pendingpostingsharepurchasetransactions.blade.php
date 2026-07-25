@extends('layouts.workingside')
@section('title', 'Pending Posting Share Transactions')
@section('page-title', 'Pending Posting Transactions')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-file-invoice-dollar"></i></div>
        Pending Posting Share Transactions
    </h3>
    <a href="{{ route('sharepurchasetransactions') }}" class="arbif-btn-submit text-decoration-none">
        <i class="fas fa-list me-1"></i> All Transactions
    </a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="pendingPostingTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Transaction Ref</th>
                        <th class="sortable">Member</th>
                        <th class="sortable">Share Details</th>
                        <th class="sortable">Total Amount</th>
                        <th class="sortable">Verified By</th>
                        <th class="sortable">Approved By</th>
                        <th class="sortable">Posting Status</th>
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
                            <small class="text-muted">{{ number_format($tx->SharesQuantity ?? $tx->quantity ?? 0) }} Units</small>
                        </td>
                        <td><strong class="text-navy">{{ number_format($tx->TotalAmount ?? $tx->amount ?? 0, 2) }}</strong></td>
                        <td>{{ $tx->verifier->name ?? '—' }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ $tx->approver->name ?? '—' }}</div>
                            <small class="text-muted">{{ $tx->approved_at ? \Carbon\Carbon::parse($tx->approved_at)->format('d M, Y H:i') : '' }}</small>
                        </td>
                        <td>
                            <span class="arbif-badge bg-warning text-dark">
                                <i class="fas fa-spinner me-1"></i> {{ $tx->posting_status }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('viewsharepurchasetransactions', [encrypt($tx->id)]) }}" class="arbif-btn-edit">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <button class="arbif-btn-submit text-white" style="background-color: #0d6efd; border-color: #0d6efd;" data-bs-toggle="modal" data-bs-target="#postModal{{ $tx->id }}">
                                <i class="fas fa-paper-plane"></i> Post Ledger
                            </button>
                        </td>
                    </tr>

                    <!-- Posting Action Modal -->
                    <div class="modal fade arbif-modal" id="postModal{{ $tx->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <div class="modal-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                                    <h5 class="modal-title">Post Transaction to General Ledger</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form method="POST" action="{{ route('postsharepurchasetransaction', [encrypt($tx->id)]) }}" id="dataFormFill">
                                    @csrf
                                    <div class="modal-body">
                                        <p class="text-muted mb-3">Are you sure you want to commit and post transaction <strong>{{ $tx->TransactionRefNo ?? $tx->id }}</strong> to the accounting ledgers?</p>
                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <label class="form-label" for="posting_remarks{{ $tx->id }}">Posting Reference / Ledger Notes</label>
                                                <textarea id="posting_remarks{{ $tx->id }}" name="posting_remarks" class="form-control" rows="3" placeholder="Enter posting comments..."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light p-3">
                                        <button type="button" class="arbif-btn-cancel" data-bs-dismiss="modal">
                                            <i class="bi bi-x"></i> Cancel
                                        </button>
                                        <button onclick="confirmSubmit()" type="submit" class="arbif-btn-submit">
                                            <i class="bi bi-check2"></i> Execute Posting
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="9" class="arbif-table-empty">
                            <i class="bi bi-inbox"></i>
                            No transactions pending ledger posting.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="arbif-pagination">
            <span class="arbif-pagination-info" data-table-info="pendingPostingTable"></span>
            <div class="arbif-pagination-pages" data-table-pages="pendingPostingTable"></div>
        </div>
    </div>
</div>
@endsection