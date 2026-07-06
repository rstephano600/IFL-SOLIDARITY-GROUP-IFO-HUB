@extends('layouts.workingside')

@section('title', 'Pending Expense Authorizations')

@section('page-title', 'Pending Expense Authorizations')

@section('content')

<div class="arbif-page-header mb-4">
    <h3>
        <div class="page-icon">
            <i class="fas fa-hourglass-half"></i>
        </div>
        Pending Expense Authorizations
    </h3>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="pendingExpensesTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Voucher Code</th>
                        <th class="sortable">Expense Title</th>
                        <th class="sortable">Category</th>
                        <th class="sortable">Filing Date</th>
                        <th class="sortable">Total Allocation</th>
                        <th class="sortable">Filer</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong class="text-primary">{{ $item->expense_number ?? 'N/A' }}</strong></td>
                        <td>
                            <div><strong>{{ $item->expense_title }}</strong></div>
                            <small class="text-muted">{{ Str::limit($item->description, 45) }}</small>
                        </td>
                        <td><span class="badge bg-light text-dark border">{{ optional($item->category)->name ?? 'Uncategorized' }}</span></td>
                        <td>{{ \Carbon\Carbon::parse($item->expense_date)->format('d M Y') }}</td>
                        <td><strong class="text-dark">{{ $item->currency }} {{ number_format($item->total_amount, 2) }}</strong></td>
                        <td>{{ optional($item->creator)->name ?? 'N/A' }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('viewexpense', encrypt($item->id)) }}" class="arbif-btn-view py-1 px-2" style="font-size: 0.8rem;" title="Review Full Sheet">
                                    <i class="fas fa-search"></i> Review
                                </a>
                                
                                <form action="{{ route('approveexpense', encrypt($item->id)) }}" method="POST" class="d-inline" onsubmit="return confirm('Approve this expense voucher?');">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm py-1 px-2" style="font-size: 0.8rem;" title="Quick Approve">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>

                                <form action="{{ route('rejectexpense', encrypt($item->id)) }}" method="POST" class="d-inline" onsubmit="return confirm('Reject this expense voucher?');">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm py-1 px-2" style="font-size: 0.8rem;" title="Quick Reject">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="fas fa-check-circle fa-2x text-success opacity-25 d-block mb-2"></i>
                            No pending expense validation items found in the current workflow queue.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection