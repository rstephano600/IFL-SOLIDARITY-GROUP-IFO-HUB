@extends('layouts.workingside')

@section('title', 'Expense Payment History')

@section('page-title', 'Expense Payment Transaction Logs')

@section('content')

<div class="arbif-page-header d-flex justify-content-between align-items-center mb-4">
    <h3>
        <div class="page-icon">
            <i class="fas fa-receipt"></i>
        </div>
        Disbursal & Payment Transaction Logs
    </h3>
    <div>
        <a href="{{ route('unpayedexpense') }}" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-money-check-alt me-1"></i> Open Disbursement Queue
        </a>
    </div>
</div>

{{-- TRANSACTION HISTORY MASTER CARD GRID --}}
<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="expensePaymentsHistoryTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Receipt No</th>
                        <th>Voucher Code</th>
                        <th>Parent Expense Title</th>
                        <th>Date Processed</th>
                        <th>Method</th>
                        <th>Reference / TX ID</th>
                        <th>Amount Paid</th>
                        <th>Disbursed By</th>
                        <th class="text-center">Voucher Details</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($data as $index => $payment)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <span class="badge bg-dark text-white font-monospace">{{ $payment->payment_number ?? 'N/A' }}</span>
                        </td>
                        <td>
                            <strong class="text-primary">{{ optional($payment->expense)->expense_code ?? 'N/A' }}</strong>
                        </td>
                        <td>
                            <div><strong>{{ optional($payment->expense)->expense_title ?? 'Unknown Expense' }}</strong></div>
                            @if($payment->descriptions)
                                <small class="text-muted d-block" style="font-size: 0.75rem;">
                                    <i class="fas fa-comment-alt me-1"></i> {{ Str::limit($payment->descriptions, 40) }}
                                </small>
                            @endif
                        </td>
                        <td>
                            {{ $payment->payment_date ? $payment->payment_date->format('d M Y') : 'N/A' }}
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                @switch(strtolower($payment->payment_method))
                                    @case('cash') <i class="fas fa-money-bill-wave text-success me-1"></i> @break
                                    @case('bank transfer') <i class="fas fa-university text-primary me-1"></i> @break
                                    @case('cheque') <i class="fas fa-money-check text-info me-1"></i> @break
                                    @case('mobile money') <i class="fas fa-mobile-alt text-warning me-1"></i> @break
                                    @default <i class="fas fa-credit-card text-secondary me-1"></i>
                                @endswitch
                                {{ ucfirst($payment->payment_method) }}
                            </span>
                        </td>
                        <td>
                            @if($payment->reference_number)
                                <code class="text-secondary font-weight-bold">{{ $payment->reference_number }}</code>
                            @else
                                <span class="text-muted small">---</span>
                            @endif
                        </td>
                        <td>
                            <strong class="text-success">
                                {{ optional($payment->expense)->currency ?? 'TZS' }} {{ number_format($payment->amount_paid, 2) }}
                            </strong>
                        </td>
                        <td>
                            <span class="small text-dark">{{ optional($payment->payer)->name ?? 'System' }}</span>
                        </td>
                        <td class="text-center">
                            @if($payment->expense_id)
                                <a href="{{ route('viewexpense', encrypt($payment->expense_id)) }}" class="arbif-btn-view py-1 px-2" style="font-size: 0.8rem;" title="View Parent Voucher Profiles">
                                    <i class="fas fa-eye"></i> View Profile
                                </a>
                            @else
                                <button class="btn btn-sm btn-light border" disabled><i class="fas fa-ban"></i> Dead Link</button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-4 text-muted">
                            <i class="fas fa-receipt fa-2x opacity-25 d-block mb-2"></i>
                            No individual financial transaction payout receipts located inside the accounting matrix ledger.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection