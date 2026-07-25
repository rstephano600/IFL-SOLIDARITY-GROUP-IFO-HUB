@extends('layouts.workingside')
@section('title', 'Unverified Share Purchase Transactions')
@section('page-title', 'Pending Verification Transactions')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-shield-alt"></i></div>
        Unverified Share Purchase Transactions
    </h3>
    <a href="{{ route('sharepurchasetransactions') }}" class="arbif-btn-submit text-decoration-none">
        <i class="fas fa-list me-1"></i> All Transactions
    </a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="unverifiedTransactionsTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Transaction Ref</th>
                        <th class="sortable">Member</th>
                        <th class="sortable">Share Details</th>
                        <th class="sortable">Quantity</th>
                        <th class="sortable">Total Amount</th>
                        <th class="sortable">Initiated By</th>
                        <th class="sortable">Verification Status</th>
                        <th class="text-center">Actions</th>
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
                            <div class="fw-bold text-dark">{{ $tx->user->name ?? '—' }}</div>
                            <small class="text-muted">{{ $tx->created_at ? \Carbon\Carbon::parse($tx->created_at)->format('d M, Y H:i') : '' }}</small>
                        </td>
                        <td>
                            <span class="arbif-badge bg-warning text-dark">
                                <i class="fas fa-hourglass-half me-1"></i> {{ $tx->verification_status }}
                            </span>
                        </td>
                        <td class="text-center">
                            <button class="arbif-btn-submit text-white btn-sm" style="background-color: #0d6efd; border-color: #0d6efd;" data-bs-toggle="modal" data-bs-target="#verifyModal{{ $tx->id }}">
                                <i class="fas fa-check-shield me-1"></i> Process
                            </button>
                        </td>
                    </tr>

                    <!-- Verification Action Modal -->
                    <div class="modal fade arbif-modal" id="verifyModal{{ $tx->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <div class="modal-icon"><i class="fas fa-shield-alt"></i></div>
                                    <h5 class="modal-title">Process Transaction Verification</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form method="POST" action="{{ route('verifysharepurchasetransaction', [encrypt($tx->id)]) }}">
                                    @csrf
                                    <div class="modal-body text-start">
                                        {{-- Transaction Overview Card --}}
                                        <div class="bg-light p-3 rounded mb-3 border">
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="text-muted small">Transaction Ref:</span>
                                                <strong class="text-dark small">{{ $tx->TransactionRefNo ?? $tx->id }}</strong>
                                            </div>
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="text-muted small">Member:</span>
                                                <strong class="text-dark small">{{ $tx->member->full_name ?? $tx->member->member_name ?? 'Member' }}</strong>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted small">Total Amount:</span>
                                                <strong class="text-navy small">{{ number_format($tx->TotalAmount ?? $tx->amount ?? 0, 2) }}</strong>
                                            </div>
                                        </div>

                                        <div class="row g-3">
                                            {{-- Verification Action Selection (Matches 'required|in:Verified,Rejected') --}}
                                            <div class="col-md-12">
                                                <label class="form-label fw-bold" for="verification_status_{{ $tx->id }}">
                                                    Verification Action <span class="text-danger">*</span>
                                                </label>
                                                <select name="verification_status" id="verification_status_{{ $tx->id }}" class="form-select" required>
                                                    <option value="" disabled selected>-- Select Action --</option>
                                                    <option value="Verified"> Verify & Proceed to Approval</option>
                                                    <option value="Rejected"> Reject Transaction</option>
                                                </select>
                                            </div>

                                            {{-- Remarks Input --}}
                                            <div class="col-md-12">
                                                <label class="form-label fw-bold" for="verification_remarks_{{ $tx->id }}">
                                                    Verification Remarks / Notes
                                                </label>
                                                <textarea id="verification_remarks_{{ $tx->id }}" name="verification_remarks" class="form-control" rows="3" placeholder="Enter payment audit details or rejection reasons..."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light p-3">
                                        <button type="button" class="arbif-btn-cancel" data-bs-dismiss="modal">
                                            <i class="bi bi-x me-1"></i> Cancel
                                        </button>
                                        <button type="submit" class="arbif-btn-submit">
                                            <i class="bi bi-check-circle me-1"></i> Submit Decision
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="9" class="arbif-table-empty text-center py-4">
                            <i class="bi bi-inbox fs-3 d-block mb-2 text-muted"></i>
                            No unverified share purchase transactions found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="arbif-pagination">
            <span class="arbif-pagination-info" data-table-info="unverifiedTransactionsTable"></span>
            <div class="arbif-pagination-pages" data-table-pages="unverifiedTransactionsTable"></div>
        </div>
    </div>
</div>
@endsection