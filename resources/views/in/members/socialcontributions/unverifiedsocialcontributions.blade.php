@extends('layouts.workingside')
@section('title', 'Unverified Social Contributions')
@section('page-title', 'Unverified Social Contributions')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-shield-alt"></i></div>
        Unverified Social Contributions
    </h3>
    <a href="{{ route('socialcontributions') }}" class="arbif-btn-submit text-decoration-none">
        <i class="fas fa-list me-1"></i> All Contributions
    </a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="unverifiedContributionsTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Ref No / Date</th>
                        <th class="sortable">Member Details</th>
                        <th class="sortable">Schedule / Type</th>
                        <th class="sortable">Amount</th>
                        <th class="sortable">Entered By</th>
                        <th class="sortable">Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contributions as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <span class="arbif-badge arbif-badge-navy">{{ $item->ContributionRefNo ?? $item->ref_no ?? $item->id }}</span>
                            <div class="small text-muted mt-1">
                                {{ $item->created_at ? $item->created_at->format('d M, Y H:i') : '—' }}
                            </div>
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
                            <div class="fw-bold text-dark">{{ $item->user->name ?? '—' }}</div>
                            <small class="text-muted">{{ $item->company->company_name ?? '' }}</small>
                        </td>
                        <td>
                            <span class="arbif-badge bg-warning text-dark">
                                <i class="fas fa-clock me-1"></i> {{ $item->verification_status }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('viewsocialcontributions', [encrypt($item->id)]) }}" class="arbif-btn-edit text-decoration-none">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <button class="arbif-btn-submit text-white" style="background-color: #0d6efd; border-color: #0d6efd;" data-bs-toggle="modal" data-bs-target="#verifyModal{{ $item->id }}">
                                    <i class="fas fa-check-double me-1"></i> Verify
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Verification Action Modal -->
                    <div class="modal fade arbif-modal" id="verifyModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <div class="modal-icon"><i class="fas fa-shield-alt"></i></div>
                                    <h5 class="modal-title">Verify Social Contribution</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form method="POST" action="{{ route('verifysocialcontribution', [encrypt($item->id)]) }}">
                                    @csrf
                                    <div class="modal-body text-start">
                                        {{-- Transaction Overview Summary --}}
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
                                                <span class="text-muted small">Schedule:</span>
                                                <strong class="text-dark small">{{ $item->schedule->schedule_name ?? $item->schedule->title ?? '—' }}</strong>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted small">Contribution Amount:</span>
                                                <strong class="text-navy small">{{ number_format($item->AmountPaid ?? $item->amount ?? 0, 2) }}</strong>
                                            </div>
                                        </div>

                                        <div class="row g-3">
                                            {{-- Verification Action Selection --}}
                                            <div class="col-md-12">
                                                <label class="form-label fw-bold" for="verification_status_{{ $item->id }}">
                                                    Verification Action <span class="text-danger">*</span>
                                                </label>
                                                <select name="verification_status" id="verification_status_{{ $item->id }}" class="form-select" required>
                                                    <option value="" disabled selected>-- Select Decision --</option>
                                                    <option value="Verified"> Verify Contribution</option>
                                                    <option value="Rejected"> Reject Contribution</option>
                                                </select>
                                            </div>

                                            {{-- Verification Remarks --}}
                                            <div class="col-md-12">
                                                <label class="form-label fw-bold" for="verification_remarks_{{ $item->id }}">
                                                    Verification Remarks / Notes
                                                </label>
                                                <textarea id="verification_remarks_{{ $item->id }}" name="verification_remarks" class="form-control" rows="3" placeholder="Add verification remarks or reasons for rejection..."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light p-3">
                                        <button type="button" class="arbif-btn-cancel" data-bs-dismiss="modal">
                                            <i class="bi bi-x me-1"></i> Cancel
                                        </button>
                                        <button type="submit" class="arbif-btn-submit">
                                            <i class="bi bi-check-circle me-1"></i> Submit Verification
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
                            No social contributions pending verification.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="arbif-pagination">
            <span class="arbif-pagination-info" data-table-info="unverifiedContributionsTable"></span>
            <div class="arbif-pagination-pages" data-table-pages="unverifiedContributionsTable"></div>
        </div>
    </div>
</div>
@endsection