@extends('layouts.workingside')
@section('title', 'Pending GL Posting Fee Payments')
@section('page-title', 'Pending GL Posting Membership Fee Payments')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-book font-20"></i></div>
        Pending GL Posting Fee Payments
    </h3>
    <a href="{{ route('membershipfeepayments') }}" class="arbif-btn-submit text-decoration-none">
        <i class="fas fa-list me-1"></i> All Fee Payments
    </a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="pendingPostingFeePaymentsTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Ref No</th>
                        <th class="sortable">Member Details</th>
                        <th class="sortable">Branch / Company</th>
                        <th class="sortable">Amount</th>
                        <th class="sortable">Audit Trail</th>
                        <th class="sortable">Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <span class="arbif-badge arbif-badge-navy">{{ $item->PaymentRefNo ?? $item->PaymentRefNo ?? $item->id }}</span>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $item->member->full_name ?? $item->member->member_name ?? '—' }}</div>
                            <small class="text-muted">{{ $item->member->member_code ?? '—' }}</small>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $item->branch->branch_name ?? '—' }}</div>
                            <small class="text-muted">{{ $item->company->company_name ?? '' }}</small>
                        </td>
                        <td>
                            <strong class="text-navy">{{ number_format($item->AmountPaid ?? $item->AmountPaid ?? 0, 2) }}</strong>
                        </td>
                        <td>
                            <div class="small">
                                <span class="text-muted">Verified:</span> <strong>{{ $item->verifier->name ?? '—' }}</strong><br>
                                <span class="text-muted">Approved:</span> <strong>{{ $item->approver->name ?? '—' }}</strong>
                            </div>
                        </td>
                        <td>
                            <span class="arbif-badge bg-info text-dark">
                                <i class="fas fa-hourglass-start me-1"></i> {{ $item->posting_status }} GL
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('viewmembershipfeepayments', [encrypt($item->id)]) }}" class="arbif-btn-edit text-decoration-none">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <button class="arbif-btn-submit text-white" style="background-color: #0d6efd; border-color: #0d6efd;" data-bs-toggle="modal" data-bs-target="#postGlModal{{ $item->id }}">
                                    <i class="fas fa-file-invoice-dollar me-1"></i> Post GL
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- GL Posting Modal -->
                    <div class="modal fade arbif-modal" id="postGlModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <div class="modal-icon"><i class="fas fa-book"></i></div>
                                    <h5 class="modal-title">Post Membership Fee to General Ledger</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form method="POST" action="{{ route('postmembershipfeepayment', [encrypt($item->id)]) }}">
                                    @csrf
                                    <div class="modal-body text-start">
                                        {{-- Payment Summary Banner --}}
                                        <div class="bg-light p-3 rounded mb-3 border">
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="text-muted small">Reference No:</span>
                                                <strong class="text-dark small">{{ $item->PaymentRefNo ?? $item->PaymentRefNo ?? $item->id }}</strong>
                                            </div>
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="text-muted small">Member:</span>
                                                <strong class="text-dark small">{{ $item->member->full_name ?? $item->member->member_name ?? 'Member' }}</strong>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted small">Posting Amount:</span>
                                                <strong class="text-navy small">{{ number_format($item->AmountPaid ?? $item->AmountPaid ?? 0, 2) }}</strong>
                                            </div>
                                        </div>

                                        <div class="row g-3">
                                            {{-- Cash / Bank Account Code --}}
                                            <div class="col-md-12">
                                                <label class="form-label fw-bold" for="cash_account_code_{{ $item->id }}">
                                                    Cash / Bank Account Code <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" id="cash_account_code_{{ $item->id }}" name="cash_account_code" class="form-control" maxlength="30" placeholder="e.g. 10100-001" required>
                                            </div>

                                            {{-- Fee Revenue Account Code --}}
                                            <div class="col-md-12">
                                                <label class="form-label fw-bold" for="fee_revenue_account_code_{{ $item->id }}">
                                                    Fee Revenue Account Code <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" id="fee_revenue_account_code_{{ $item->id }}" name="fee_revenue_account_code" class="form-control" maxlength="30" placeholder="e.g. 40100-002" required>
                                            </div>

                                            {{-- Posting Reference --}}
                                            <div class="col-md-12">
                                                <label class="form-label fw-bold" for="posting_reference_{{ $item->id }}">
                                                    Posting Reference / Voucher No.
                                                </label>
                                                <input type="text" id="posting_reference_{{ $item->id }}" name="posting_reference" class="form-control" maxlength="100" value="{{ $item->PaymentRefNo ?? $item->PaymentRefNo ?? '' }}" placeholder="Defaults to payment ref if left blank">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light p-3">
                                        <button type="button" class="arbif-btn-cancel" data-bs-dismiss="modal">
                                            <i class="bi bi-x me-1"></i> Cancel
                                        </button>
                                        <button type="submit" class="arbif-btn-submit">
                                            <i class="fas fa-check-circle me-1"></i> Post to Ledger
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
                            No membership fee payments pending GL posting.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="arbif-pagination">
            <span class="arbif-pagination-info" data-table-info="pendingPostingFeePaymentsTable"></span>
            <div class="arbif-pagination-pages" data-table-pages="pendingPostingFeePaymentsTable"></div>
        </div>
    </div>
</div>
@endsection