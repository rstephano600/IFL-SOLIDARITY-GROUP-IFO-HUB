@extends('layouts.workingside')

@section('title', 'Expense Payments')
@section('page-title', 'Expense Payments')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-money-check-alt"></i></div>
        Expense Payments
    </h3>
    <div class="d-flex gap-2">
        <a href="{{ route('deletedExpensePayments') }}" class="btn btn-outline-danger d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-trash-alt me-2"></i> Deleted Payments
        </a>
        <a href="{{ route('expenses') }}" class="btn btn-secondary text-white d-flex align-items-center">
            <i class="fas fa-receipt me-2"></i> Expenses
        </a>
    </div>
</div>

{{-- FILTER BAR --}}
<div class="arbif-card mb-4">
    <div class="arbif-card-body p-3">
        <form method="GET" action="{{ route('expensePayments') }}" class="row g-2 align-items-center">
            <div class="col-md-2">
                <select name="branch_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- All Branches --</option>
                    @foreach($branchies as $branch)
                        <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->branch_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="payment_method" class="form-select" onchange="this.form.submit()">
                    <option value="">-- All Methods --</option>
                    @foreach(['Cash', 'Bank Transfer', 'Mobile Money', 'Cheque'] as $m)
                        <option value="{{ $m }}" {{ request('payment_method') == $m ? 'selected' : '' }}>{{ $m }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2"><input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" title="From"></div>
            <div class="col-md-2"><input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" title="To"></div>
            <div class="col-md-2"><input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Payment / expense / ref..."></div>
            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Filter</button>
                @if(request()->anyFilled(['branch_id', 'payment_method', 'date_from', 'date_to', 'search']))
                    <a href="{{ route('expensePayments') }}" class="btn btn-secondary text-white">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted">{{ $data->total() }} payment(s)</span>
            <span>Total disbursed (filtered): <strong class="text-success">{{ number_format($totalAmount, 2) }}</strong></span>
        </div>

        <div class="arbif-table-wrap">
            <table class="arbif-table" id="paymentsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Payment No.</th>
                        <th>Expense</th>
                        <th>Branch</th>
                        <th>Date</th>
                        <th>Method</th>
                        <th>Reference</th>
                        <th class="text-end">Amount</th>
                        <th>Paid By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $data->firstItem() + $index }}</td>
                        <td><span class="arbif-badge arbif-badge-navy">{{ $item->payment_number }}</span></td>
                        <td>
                            @if($item->expense)
                                <a href="{{ route('viewExpense', encrypt($item->expense->id)) }}" class="text-decoration-none fw-bold">{{ $item->expense->expense_number }}</a>
                                <small class="d-block text-muted">{{ Str::limit($item->expense->expense_title, 30) }}</small>
                            @else N/A @endif
                        </td>
                        <td>{{ $item->branch->branch_name ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->payment_date)->format('d M Y H:i') }}</td>
                        <td>{{ $item->payment_method }}</td>
                        <td>{{ $item->reference_number ?? '-' }}</td>
                        <td class="text-end">{{ $item->expense->currency ?? '' }} {{ number_format($item->amount_paid, 2) }}</td>
                        <td>{{ $item->payer->name ?? 'N/A' }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('viewExpensePayment', encrypt($item->id)) }}" class="arbif-btn-view"><i class="fas fa-eye"></i> View</a>
                                @can('view-generally-delete-of-any-item')
                                    <form action="{{ route('destroyExpensePayment', encrypt($item->id)) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this payment? The expense balance will be updated.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="arbif-btn-delete border-0 bg-transparent"><i class="fas fa-trash"></i> Del</button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="10" class="text-center">No Payments Found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">{{ $data->links() }}</div>
    </div>
</div>

@endsection