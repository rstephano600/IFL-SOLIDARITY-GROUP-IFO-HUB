@extends('layouts.workingside')

@section('title', 'Deleted Payments')
@section('page-title', 'Deleted Payments')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-trash-alt"></i></div>
        Deleted Payments
    </h3>
    <a href="{{ route('expensePayments') }}" class="btn btn-secondary text-white">
        <i class="fas fa-arrow-left me-1"></i> Back to Payments
    </a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="deletedPaymentsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Payment No.</th>
                        <th>Expense</th>
                        <th>Branch</th>
                        <th>Method</th>
                        <th class="text-end">Amount</th>
                        <th>Paid By</th>
                        <th>Deleted On</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><span class="arbif-badge arbif-badge-navy">{{ $item->payment_number }}</span></td>
                        <td>
                            {{ $item->expense->expense_number ?? 'N/A' }}
                            <small class="d-block text-muted">{{ Str::limit($item->expense->expense_title ?? '', 30) }}</small>
                        </td>
                        <td>{{ $item->branch->branch_name ?? 'N/A' }}</td>
                        <td>{{ $item->payment_method }}</td>
                        <td class="text-end">{{ $item->expense->currency ?? '' }} {{ number_format($item->amount_paid, 2) }}</td>
                        <td>{{ $item->payer->name ?? 'N/A' }}</td>
                        <td>{{ $item->updated_at?->format('d M Y H:i') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('viewExpensePayment', encrypt($item->id)) }}" class="arbif-btn-view"><i class="fas fa-eye"></i> View</a>
                                <form action="{{ route('restoreExpensePayment', encrypt($item->id)) }}" method="POST" class="d-inline" onsubmit="return confirm('Restore this payment? The expense balance will be updated.');">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success"><i class="fas fa-undo"></i> Restore</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center">No Deleted Payments Found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection