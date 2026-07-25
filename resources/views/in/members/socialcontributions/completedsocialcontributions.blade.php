@extends('layouts.workingside')
@section('title', 'Completed Social Contributions')
@section('page-title', 'Completed Social Contributions')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-check-circle"></i></div>
        Completed Social Contributions
    </h3>
    <a href="{{ route('socialcontributions') }}" class="arbif-btn-submit text-decoration-none">
        <i class="fas fa-list me-1"></i> All Contributions
    </a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="completedContributionsTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Ref No / Journal</th>
                        <th class="sortable">Member Details</th>
                        <th class="sortable">Schedule / Branch</th>
                        <th class="sortable">Amount</th>
                        <th class="sortable">Posted By</th>
                        <th class="sortable">Posting Date</th>
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
                            @if($item->journal_no)
                                <div class="small text-muted mt-1">
                                    <i class="fas fa-book me-1"></i>{{ $item->journal_no }}
                                </div>
                            @endif
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
                            <strong class="text-success">{{ number_format($item->AmountPaid ?? $item->amount ?? 0, 2) }}</strong>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $item->poster->name ?? '—' }}</div>
                            <small class="text-muted">{{ $item->company->company_name ?? '' }}</small>
                        </td>
                        <td>
                            <small class="text-dark fw-semibold">
                                {{ $item->posted_at ? \Carbon\Carbon::parse($item->posted_at)->format('d M, Y H:i') : '—' }}
                            </small>
                        </td>
                        <td>
                            <span class="arbif-badge bg-success text-white">
                                <i class="fas fa-check-double me-1"></i> Posted
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('viewsocialcontributions', [encrypt($item->id)]) }}" class="arbif-btn-edit text-decoration-none">
                                    <i class="fas fa-eye me-1"></i> View
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="arbif-table-empty text-center py-4">
                            <i class="bi bi-inbox fs-3 d-block mb-2 text-muted"></i>
                            No completed social contributions found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="arbif-pagination">
            <span class="arbif-pagination-info" data-table-info="completedContributionsTable"></span>
            <div class="arbif-pagination-pages" data-table-pages="completedContributionsTable"></div>
        </div>
    </div>
</div>
@endsection