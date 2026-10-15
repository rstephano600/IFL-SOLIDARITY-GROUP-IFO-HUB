@extends('layouts.workingside')

@section('title', 'Deleted Expenses')
@section('page-title', 'Deleted Expenses')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-trash-alt"></i></div>
        Deleted Expenses
    </h3>
    <a href="{{ route('expenses') }}" class="btn btn-secondary text-white">
        <i class="fas fa-arrow-left me-1"></i> Back to Expenses
    </a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="deletedExpensesTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Expense No.</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Branch</th>
                        <th class="text-end">Total</th>
                        <th>Created By</th>
                        <th>Deleted On</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><span class="arbif-badge arbif-badge-navy">{{ $item->expense_number }}</span></td>
                        <td>{{ Str::limit($item->expense_title, 35) }}</td>
                        <td>{{ $item->category->name ?? 'N/A' }}</td>
                        <td>{{ $item->branch->branch_name ?? 'N/A' }}</td>
                        <td class="text-end">{{ $item->currency }} {{ number_format($item->total_amount, 2) }}</td>
                        <td>{{ $item->creator->name ?? 'N/A' }}</td>
                        <td>{{ $item->updated_at?->format('d M Y H:i') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('viewExpense', encrypt($item->id)) }}" class="arbif-btn-view">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <form action="{{ route('restoreExpense', encrypt($item->id)) }}" method="POST" class="d-inline" onsubmit="return confirm('Restore this expense?');">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="fas fa-undo"></i> Restore
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center">No Deleted Expenses Found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection