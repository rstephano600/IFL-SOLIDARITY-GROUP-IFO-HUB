@extends('layouts.workingside')
@section('title', 'Pending Approval Social Contributions')
@section('page-title', 'Pending Approval Social Contributions')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-user-check"></i></div>
        Pending Approval Social Contributions
    </h3>
    <a href="{{ route('socialcontributions') }}" class="arbif-btn-submit text-decoration-none">
        <i class="fas fa-list me-1"></i> All Contributions
    </a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="pendingApprovalContributionsTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Ref No</th>
                        <th class="sortable">Member Details</th>
                        <th class="sortable">Schedule / Branch</th>
                        <th class="sortable">Amount</th>
                        <th class="sortable">Verified By</th>
                        <th class="sortable">Approval Status</th>
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
                            <div class="fw-bold text-dark">{{ $item->verifier->name ?? '—' }}</div>
                            <small class="text-muted">
                                {{ $item->verified_at ? \Carbon\Carbon::parse($item->verified_at)->format('d M, Y H:i') : '—' }}
                            </small>
                        </td>
                        <td>
                            <span class="arbif-badge bg-warning text-dark">
                                <i class="fas fa-hourglass-half me-1"></i> {{ $item->approval_status }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('viewsocialcontributions', [encrypt($item->id)]) }}" class="arbif-btn-edit text-decoration-none">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <button class="arbif-btn-submit text-white" style="background-color: #198754; border-color: #198754;" data-bs-toggle="modal" data-bs-target="#approveModal{{ $item->id }}">
                                    <i class="fas fa-user-check me-1"></i> Process
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Approval Action Modal -->
                    <div class="modal fade arbif-modal" id="approveModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <div class="modal-icon"><i class="fas fa-user-check"></i></div>
                                    <h5 class="modal-title">Approve / Reject Social Contribution</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form method="POST" action="{{ route('approvesocialcontribution', [encrypt($item->id)]) }}">
                                    @csrf
                                    <div class="modal-body text-start">
                                        {{-- Transaction Summary Card --}}
                                        <div class="bg-light p-3 rounded mb-3 border">
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="text-muted small">Reference No:</span>
                                                <strong class="text-dark small">{{ $item->ContributionRefNo ?? $item->ref_no ?? $item->id }}</strong>
                                            </div>
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="text-muted small">Member:</span>
                                                <strong class="text-dark small">{{ $item->member->full_name ?? $item->member->member_name ?? 'Member' }}</strong>
                                            </div>
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="text-muted small">Verified By:</span>
                                                <strong class="text-dark small">{{ $item->verifier->name ?? '—' }}</strong>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted small">Contribution Amount:</span>
                                                <strong class="text-navy small">{{ number_format($item->AmountPaid ?? $item->amount ?? 0, 2) }}</strong>
                                            </div>
                                        </div>

                                        <div class="row g-3">
                                            {{-- Approval Action Selection --}}
                                            <div class="col-md-12">
                                                <label class="form-label fw-bold" for="approval_status_{{ $item->id }}">
                                                    Approval Action <span class="text-danger">*</span>
                                                </label>
                                                <select name="approval_status" id="approval_status_{{ $item->id }}" class="form-select" required>
                                                    <option value="" disabled selected>-- Select Decision --</option>
                                                    <option value="Approved"> Approve Contribution</option>
                                                    <option value="Rejected"> Reject Contribution</option>
                                                </select>
                                            </div>

                                            {{-- Approval Remarks --}}
                                            <div class="col-md-12">
                                                <label class="form-label fw-bold" for="approval_remarks_{{ $item->id }}">
                                                    Approval Remarks / Notes
                                                </label>
                                                <textarea id="approval_remarks_{{ $item->id }}" name="approval_remarks" class="form-control" rows="3" placeholder="Add approval remarks or reasons for rejection..."></textarea>
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
                        <td colspan="8" class="arbif-table-empty text-center py-4">
                            <i class="bi bi-inbox fs-3 d-block mb-2 text-muted"></i>
                            No social contributions pending approval.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="arbif-pagination">
            <span class="arbif-pagination-info" data-table-info="pendingApprovalContributionsTable"></span>
            <div class="arbif-pagination-pages" data-table-pages="pendingApprovalContributionsTable"></div>
        </div>
    </div>
</div>
@endsection