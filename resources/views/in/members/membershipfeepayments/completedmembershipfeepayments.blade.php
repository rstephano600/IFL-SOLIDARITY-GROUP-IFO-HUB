@extends('layouts.workingside')
@section('title', 'Completed Membership Fee Payments')
@section('page-title', 'Completed Membership Fee Payments')

@section('content')
<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-check-circle"></i></div>
        Completed Membership Fee Payments
    </h3>
    <a href="{{ route('membershipfeepayments') }}" class="arbif-btn-submit text-decoration-none">
        <i class="fas fa-list me-1"></i> All Fee Payments
    </a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="completedFeePaymentsTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Ref No / Journal</th>
                        <th class="sortable">Member Details</th>
                        <th class="sortable">Branch / Company</th>
                        <th class="sortable">AmountPaid</th>
                        <th class="sortable">Posted By</th>
                        <th class="sortable">Posting Date</th>
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
                            <div class="fw-bold text-dark">{{ $item->branch->branch_name ?? '—' }}</div>
                            <small class="text-muted">{{ $item->company->company_name ?? '' }}</small>
                        </td>
                        <td>
                            <strong class="text-success">{{ number_format($item->AmountPaid ?? $item->AmountPaid ?? 0, 2) }}</strong>
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
                                <a href="{{ route('viewmembershipfeepayments', [encrypt($item->id)]) }}" class="arbif-btn-edit text-decoration-none">
                                    <i class="fas fa-eye me-1"></i> View
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="arbif-table-empty text-center py-4">
                            <i class="bi bi-inbox fs-3 d-block mb-2 text-muted"></i>
                            No completed membership fee payments found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="arbif-pagination">
            <span class="arbif-pagination-info" data-table-info="completedFeePaymentsTable"></span>
            <div class="arbif-pagination-pages" data-table-pages="completedFeePaymentsTable"></div>
        </div>
    </div>
</div>
@endsection