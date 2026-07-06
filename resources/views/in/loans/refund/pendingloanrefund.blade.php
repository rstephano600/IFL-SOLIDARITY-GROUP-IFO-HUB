@extends('layouts.workingside')

@section('title', 'Pending Loan Refunds')

@section('page-title', 'Pending Loan Refunds')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-reply-all"></i>
        </div>
        Pending Loan Refunds
    </h3>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="loanRefundTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Refund Number</th>
                        <th class="sortable">Loan Number</th>
                        <th class="sortable">Client</th>
                        <th class="sortable">Refund Date</th>
                        <th class="sortable">Requested Refund</th>
                        <th class="sortable">Total Refund</th>
                        <th class="sortable">Approval Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $item->refund_number ?? 'N/A' }}</strong>
                        </td>
                        <td>
                            <span>{{ optional($item->loan)->loan_number ?? 'N/A' }}</span>
                        </td>
                        <td>
                            {{-- Matches the profile name accessor on your Client model --}}
                            {{ optional($item->client)->name ?? 'N/A' }}
                            @if($item->group)
                                <br><small class="text-muted">Group: {{ $item->group->group_name }}</small>
                            @endif
                        </td>
                        <td>
                            {{ $item->refund_date ? $item->refund_date->format('d M Y') : 'N/A' }}
                        </td>
                        <td>
                            {{ number_format($item->requested_refund ?? 0, 2) }}
                        </td>
                        <td>
                            <strong>{{ number_format($item->total_refund ?? 0, 2) }}</strong>
                        </td>
                        <td>
                            <span class="arbif-badge arbif-badge-warning">
                                Pending
                            </span>
                        </td>
                        <td>
                            {{-- View Details --}}
                            <a href="{{ route('viewloanrefund', encrypt($item->id)) }}" 
                               class="arbif-btn-view">
                                <i class="fas fa-eye"></i> View
                            </a>
                            
                            <!-- {{-- Quick Approval Form --}}
                            <form action="{{ route('approveloanrefund', encrypt($item->id)) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to APPROVE this loan refund?');">
                                @csrf
                                <button type="submit" class="arbif-btn-submit bg-success border-success btn-sm d-inline-flex align-items-center py-1 px-2" style="font-size: 0.85rem;">
                                    <i class="fas fa-check-circle me-1"></i> Approve
                                </button>
                            </form>

                            {{-- Quick Rejection Form --}}
                            <form action="{{ route('rejectloanrefund', encrypt($item->id)) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to REJECT this loan refund?');">
                                @csrf
                                <button type="submit" class="arbif-btn-delete bg-danger border-danger btn-sm d-inline-flex align-items-center py-1 px-2" style="font-size: 0.85rem;">
                                    <i class="fas fa-times-circle me-1"></i> Reject
                                </button>
                            </form> -->
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">
                            No Pending Loan Refunds Found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection