@extends('layouts.workingside')

@section('title', 'Expenses')
@section('page-title', 'Expenses')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-receipt"></i></div>
        Expense Requisitions
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('deletedExpenses') }}" class="btn btn-outline-danger d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-trash-alt me-2"></i> Deleted Expenses
        </a>
        <a href="{{ route('createExpense') }}" class="arbif-btn-submit text-decoration-none">
            <i class="fas fa-plus me-1"></i> New Expense
        </a>
    </div>
</div>

{{-- FILTER BAR --}}
<div class="arbif-card mb-4">
    <div class="arbif-card-body p-3">
        <form method="GET" action="{{ route('expenses') }}" class="row g-2 align-items-center">
            <div class="col-md-2">
                <select name="branch_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- All Branches --</option>
                    @foreach($branchies as $branch)
                        <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                            {{ $branch->branch_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <select name="expense_category_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- All Categories --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('expense_category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <select name="AppStatus" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Approval --</option>
                    @foreach(['Pending', 'Approved', 'Rejected'] as $s)
                        <option value="{{ $s }}" {{ request('AppStatus') == $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <select name="PaymentStatus" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Payment --</option>
                    @foreach(['Unpaid', 'Partially Paid', 'Paid'] as $s)
                        <option value="{{ $s }}" {{ request('PaymentStatus') == $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Number, title, reference...">
            </div>

            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Filter</button>
                @if(request()->anyFilled(['branch_id', 'expense_category_id', 'AppStatus', 'PaymentStatus', 'search']))
                    <a href="{{ route('expenses') }}" class="btn btn-secondary text-white">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="expensesTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Expense No.</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Branch</th>
                        <th>Date</th>
                        <th class="text-end">Total</th>
                        <th class="text-end">Paid</th>
                        <th>Approval</th>
                        <th>Payment</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
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
                        <td class="text-end">{{ $item->currency }} {{ number_format($item->total_amount, 2) }}</td>
                        <td class="text-end">{{ number_format($item->amount_paid, 2) }}</td>
                        <td>
                            @php $appClass = ['Approved' => 'success', 'Rejected' => 'danger', 'Pending' => 'warning text-dark'][$item->AppStatus] ?? 'secondary'; @endphp
                            <span class="badge bg-{{ $appClass }}">{{ $item->AppStatus }}</span>
                        </td>
                        <td>
                            @php $payClass = ['Paid' => 'success', 'Partially Paid' => 'info text-dark', 'Unpaid' => 'secondary'][$item->PaymentStatus] ?? 'secondary'; @endphp
                            <span class="badge bg-{{ $payClass }}">{{ $item->PaymentStatus }}</span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('viewExpense', encrypt($item->id)) }}" class="arbif-btn-view">
                                    <i class="fas fa-eye"></i> View
                                </a>

                                @if($item->AppStatus !== 'Approved' && $item->PaymentStatus !== 'Paid')
                                    <a href="{{ route('editExpense', encrypt($item->id)) }}" class="arbif-btn-edit">
                                        <i class="fas fa-pencil"></i> Edit
                                    </a>
                                @endif

                                @can('view-generally-delete-of-any-item')
                                    <form action="{{ route('destroyExpense', encrypt($item->id)) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this expense?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="arbif-btn-delete border-0 bg-transparent">
                                            <i class="fas fa-trash"></i> Del
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center">No Expenses Found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $data->links() }}
        </div>
    </div>
</div>

@endsection