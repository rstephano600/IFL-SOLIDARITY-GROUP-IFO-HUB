@extends('layouts.workingside')

@section('title', 'Expenses Pending Payment')
@section('page-title', 'Expenses Pending Payment')

@section('content')

@php $outstanding = (float) $summary->total - (float) $summary->paid; @endphp

<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-hand-holding-usd"></i></div>
        Expenses Pending Payment
    </h3>
    <div class="d-flex gap-2">
        <a href="{{ route('expensePayments') }}" class="btn btn-outline-primary d-flex align-items-center">
            <i class="fas fa-money-check-alt me-2"></i> Payments
        </a>
        <a href="{{ route('expenses') }}" class="btn btn-secondary text-white d-flex align-items-center">
            <i class="fas fa-receipt me-2"></i> All Expenses
        </a>
    </div>
</div>

{{-- SUMMARY --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="arbif-card"><div class="arbif-card-body text-center">
            <small class="text-muted d-block">Expenses Awaiting Payment</small>
            <strong class="fs-4">{{ number_format($summary->cnt) }}</strong>
        </div></div>
    </div>
    <div class="col-md-4">
        <div class="arbif-card"><div class="arbif-card-body text-center">
            <small class="text-muted d-block">Total Approved Amount</small>
            <strong class="fs-4">{{ number_format($summary->total, 2) }}</strong>
        </div></div>
    </div>
    <div class="col-md-4">
        <div class="arbif-card"><div class="arbif-card-body text-center">
            <small class="text-muted d-block">Total Outstanding</small>
            <strong class="fs-4 text-danger">{{ number_format($outstanding, 2) }}</strong>
        </div></div>
    </div>
</div>

{{-- FILTER BAR --}}
<div class="arbif-card mb-4">
    <div class="arbif-card-body p-3">
        <form method="GET" action="{{ route('unpaidExpenses') }}" class="row g-2 align-items-center">
            <div class="col-md-2">
                <select name="branch_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- All Branches --</option>
                    @foreach($branchies as $branch)
                        <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->branch_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="expense_category_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- All Categories --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('expense_category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="PaymentStatus" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Unpaid & Partial --</option>
                    <option value="Unpaid" {{ request('PaymentStatus') == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                    <option value="Partially Paid" {{ request('PaymentStatus') == 'Partially Paid' ? 'selected' : '' }}>Partially Paid</option>
                </select>
            </div>
            <div class="col-md-2"><input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" title="From"></div>
            <div class="col-md-2"><input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" title="To"></div>
            <div class="col-md-2"><input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Number, title..."></div>
            <div class="col-12 d-flex gap-1 justify-content-end">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search me-1"></i> Filter</button>
                @if(request()->anyFilled(['branch_id', 'expense_category_id', 'PaymentStatus', 'date_from', 'date_to', 'search']))
                    <a href="{{ route('unpaidExpenses') }}" class="btn btn-secondary text-white">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="unpaidExpensesTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Expense No.</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Branch</th>
                        <th>Date</th>
                        <th>Approved By</th>
                        <th class="text-end">Total</th>
                        <th class="text-end">Paid</th>
                        <th class="text-end">Balance</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    @php $balance = (float) $item->total_amount - (float) $item->amount_paid; @endphp
                    <tr>
                        <td>{{ $data->firstItem() + $index }}</td>
                        <td><span class="arbif-badge arbif-badge-navy">{{ $item->expense_number }}</span></td>
                        <td>
                            <a href="{{ route('viewExpense', encrypt($item->id)) }}" class="fw-bold text-decoration-none">
                                {{ Str::limit($item->expense_title, 35) }}
                            </a>
                        </td>
                        <td>{{ $item->category->name ?? 'N/A' }}</td>
                        <td>{{ $item->branch->branch_name ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->expense_date)->format('d M Y') }}</td>
                        <td>{{ $item->approver->name ?? 'N/A' }}</td>
                        <td class="text-end">{{ $item->currency }} {{ number_format($item->total_amount, 2) }}</td>
                        <td class="text-end">{{ number_format($item->amount_paid, 2) }}</td>
                        <td class="text-end fw-bold text-danger">{{ number_format($balance, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $item->PaymentStatus === 'Partially Paid' ? 'info text-dark' : 'secondary' }}">
                                {{ $item->PaymentStatus }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('viewExpense', encrypt($item->id)) }}" class="arbif-btn-view"><i class="fas fa-eye"></i> View</a>
                                <a href="{{ route('payExpense', encrypt($item->id)) }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-money-bill-wave me-1"></i> Pay
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="12" class="text-center">No expenses are waiting for payment</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">{{ $data->links() }}</div>
    </div>
</div>

@endsection