@extends('layouts.workingside')
@section('title', 'Pending Posting Social Contributions')
@section('page-title', 'Pending Posting Social Contributions')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-file-invoice-dollar"></i></div>
        Pending GL Posting Social Contributions
    </h3>
    <a href="{{ route('socialcontributions') }}" class="arbif-btn-submit text-decoration-none">
        <i class="fas fa-list me-1"></i> All Contributions
    </a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="pendingPostingContributionsTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Ref No</th>
                        <th class="sortable">Member Details</th>
                        <th class="sortable">Schedule / Branch</th>
                        <th class="sortable">Amount</th>
                        <th class="sortable">Approved By</th>
                        <th class="sortable">Posting Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contributions as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <span class="arbif-badge arbif-badge-navy">{{ $item->ContributionRefNo ?? $item->ref_no ?? $item->id }}</span>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $item->member->full_name ?? $item->member->member_name ?? '—' }}</div>
                            <small class="text-muted">{{ $item->member->member_code ?? '—' }}</small>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $item->schedule->schedule_name ?? $item->schedule->title ?? '—' }}</div>
                            <small class="text-muted">{{ $item->branch->branch_name ?? '—' }}</small>
                        </td>
                        <td>
                            <strong class="text-navy">{{ number_format($item->AmountPaid ?? $item->amount ?? 0, 2) }}</strong>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $item->approver->name ?? '—' }}</div>
                            <small class="text-muted">
                                {{ $item->approved_at ? \Carbon\Carbon::parse($item->approved_at)->format('d M, Y H:i') : '—' }}
                            </small>
                        </td>
                        <td>
                            <span class="arbif-badge bg-warning text-dark">
                                <i class="fas fa-clock me-1"></i> {{ $item->posting_status }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('viewsocialcontributions', [encrypt($item->id)]) }}" class="arbif-btn-edit text-decoration-none">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <button class="arbif-btn-submit text-white" style="background-color: #0d6efd; border-color: #0d6efd;" data-bs-toggle="modal" data-bs-target="#postModal{{ $item->id }}">
                                    <i class="fas fa-paper-plane me-1"></i> Post to GL
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Post to GL Modal -->
                    <div class="modal fade arbif-modal" id="postModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <div class="modal-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                                    <h5 class="modal-title">Post Social Contribution to General Ledger</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form method="POST" action="{{ route('postsocialcontribution', [encrypt($item->id)]) }}">
                                    @csrf
                                    <div class="modal-body text-start">
                                        {{-- Transaction Overview Card --}}
                                        <div class="bg-light p-3 rounded mb-3 border">
                                            <div class="row g-2">
                                                <div class="col-md-6">
                                                    <span class="text-muted small d-block">Reference No:</span>
                                                    <strong class="text-dark small">{{ $item->ContributionRefNo ?? $item->ref_no ?? $item->id }}</strong>
                                                </div>
                                                <div class="col-md-6">
                                                    <span class="text-muted small d-block">Member:</span>
                                                    <strong class="text-dark small">{{ $item->member->full_name ?? $item->member->member_name ?? '—' }}</strong>
                                                </div>
                                                <div class="col-md-6">
                                                    <span class="text-muted small d-block">Verified By:</span>
                                                    <strong class="text-dark small">{{ $item->verifier->name ?? '—' }}</strong>
                                                </div>
                                                <div class="col-md-6">
                                                    <span class="text-muted small d-block">Approved By:</span>
                                                    <strong class="text-dark small">{{ $item->approver->name ?? '—' }}</strong>
                                                </div>
                                                <div class="col-12 mt-2 pt-2 border-top d-flex justify-content-between">
                                                    <span class="fw-bold text-muted small">Total Posting Amount:</span>
                                                    <strong class="text-navy fs-6">{{ number_format($item->AmountPaid ?? $item->amount ?? 0, 2) }}</strong>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Accounting Accounts Setup --}}
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold" for="cash_account_code_{{ $item->id }}">
                                                    Cash / Bank Account Code <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" 
                                                       name="cash_account_code" 
                                                       id="cash_account_code_{{ $item->id }}" 
                                                       class="form-control" 
                                                       maxlength="30" 
                                                       placeholder="e.g. 1010-001" 
                                                       value="{{ old('cash_account_code', $item->cash_account_code) }}" 
                                                       required>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-bold" for="social_contribution_account_code_{{ $item->id }}">
                                                    Social Contribution Account Code <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" 
                                                       name="social_contribution_account_code" 
                                                       id="social_contribution_account_code_{{ $item->id }}" 
                                                       class="form-control" 
                                                       maxlength="30" 
                                                       placeholder="e.g. 4010-005" 
                                                       value="{{ old('social_contribution_account_code', $item->social_contribution_account_code) }}" 
                                                       required>
                                            </div>

                                            <div class="col-md-12">
                                                <label class="form-label fw-bold" for="posting_reference_{{ $item->id }}">
                                                    Posting Reference / Memo
                                                </label>
                                                <input type="text" 
                                                       name="posting_reference" 
                                                       id="posting_reference_{{ $item->id }}" 
                                                       class="form-control" 
                                                       maxlength="100" 
                                                       placeholder="Leave blank to use default ref ({{ $item->ContributionRefNo ?? 'Default Ref' }})" 
                                                       value="{{ old('posting_reference', $item->posting_reference ?? $item->ContributionRefNo) }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light p-3">
                                        <button type="button" class="arbif-btn-cancel" data-bs-dismiss="modal">
                                            <i class="bi bi-x me-1"></i> Cancel
                                        </button>
                                        <button type="submit" class="arbif-btn-submit">
                                            <i class="fas fa-check-circle me-1"></i> Post to GL
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="8" class="arbif-table-empty text-center py-4">
                            <i class="bi bi-inbox fs-3 d-block mb-2 text-muted"></i>
                            No social contributions pending GL posting.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="arbif-pagination">
            <span class="arbif-pagination-info" data-table-info="pendingPostingContributionsTable"></span>
            <div class="arbif-pagination-pages" data-table-pages="pendingPostingContributionsTable"></div>
        </div>
    </div>
</div>
@endsection