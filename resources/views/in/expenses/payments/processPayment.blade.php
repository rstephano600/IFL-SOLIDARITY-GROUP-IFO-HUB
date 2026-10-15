@extends('layouts.workingside')

@section('title', 'Process Payment')
@section('page-title', 'Process Expense Payment')

@section('content')

@php
    $balance = round((float) $data->total_amount - (float) $data->amount_paid, 2);
    $payClass = ['Paid' => 'success', 'Partially Paid' => 'info text-dark', 'Unpaid' => 'secondary'][$data->PaymentStatus] ?? 'secondary';
@endphp

<div class="arbif-page-header">
    <h3>
        <div class="page-icon"><i class="fas fa-money-bill-wave"></i></div>
        Process Payment - {{ $data->expense_number }}
    </h3>
    <a href="{{ route('viewExpense', encrypt($data->id)) }}" class="btn btn-secondary text-white">
        <i class="fas fa-arrow-left me-1"></i> Back to Expense
    </a>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row g-3">
    {{-- EXPENSE SUMMARY --}}
    <div class="col-lg-5">
        <div class="arbif-card mb-3">
            <div class="arbif-card-body">
                <h6 class="fw-bold mb-3">{{ $data->expense_title }}</h6>
                <div class="d-flex justify-content-between mb-1"><span class="text-muted">Category</span><strong>{{ $data->category->name ?? 'N/A' }}</strong></div>
                <div class="d-flex justify-content-between mb-1"><span class="text-muted">Branch</span><strong>{{ $data->branch->branch_name ?? 'N/A' }}</strong></div>
                <div class="d-flex justify-content-between mb-1"><span class="text-muted">Expense Date</span><strong>{{ \Carbon\Carbon::parse($data->expense_date)->format('d M Y') }}</strong></div>
                <div class="d-flex justify-content-between mb-1"><span class="text-muted">Payment Status</span><span class="badge bg-{{ $payClass }}">{{ $data->PaymentStatus }}</span></div>
                <hr>
                <div class="d-flex justify-content-between"><span>Total</span><strong>{{ $data->currency }} {{ number_format($data->total_amount, 2) }}</strong></div>
                <div class="d-flex justify-content-between"><span>Already Paid</span><strong class="text-success">{{ number_format($data->amount_paid, 2) }}</strong></div>
                <div class="d-flex justify-content-between"><span>Outstanding</span><strong class="text-danger">{{ number_format($balance, 2) }}</strong></div>
            </div>
        </div>

        <div class="arbif-card">
            <div class="arbif-card-body">
                <h6 class="fw-bold mb-3"><i class="fas fa-history me-1"></i> Previous Payments</h6>
                @php $previous = $data->payments->where('Status', 'Active'); @endphp
                @forelse($previous as $pay)
                    <div class="d-flex justify-content-between border-bottom py-1">
                        <div>
                            <span class="arbif-badge arbif-badge-navy">{{ $pay->payment_number }}</span>
                            <small class="d-block text-muted">{{ \Carbon\Carbon::parse($pay->payment_date)->format('d M Y') }} - {{ $pay->payment_method }}</small>
                        </div>
                        <strong>{{ number_format($pay->amount_paid, 2) }}</strong>
                    </div>
                @empty
                    <small class="text-muted">No payments recorded yet.</small>
                @endforelse
            </div>
        </div>
    </div>

    {{-- PAYMENT FORM --}}
    <div class="col-lg-7">
        <div class="arbif-card">
            <div class="arbif-card-body">
                <h6 class="fw-bold mb-3"><i class="fas fa-file-invoice-dollar me-1"></i> Payment Details</h6>

                <form method="POST" action="{{ route('processExpensePayment', encrypt($data->id)) }}" id="paymentForm">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Amount to Pay ({{ $data->currency }}) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" step="0.01" min="0.01" max="{{ $balance }}" name="amount_paid" id="amountPaid"
                                   class="form-control" value="{{ old('amount_paid', $balance) }}" required>
                            <button type="button" class="btn btn-outline-primary" id="payFull">Full Balance</button>
                            <button type="button" class="btn btn-outline-secondary" id="payHalf">50%</button>
                        </div>
                        <small class="d-block mt-1">
                            Remaining after this payment: <strong id="remainingAfter">0.00</strong>
                            <span class="text-danger d-none" id="overWarn"> - exceeds outstanding balance!</span>
                        </small>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                            <select name="payment_method" class="form-select" required>
                                <option value="">-- Select --</option>
                                @foreach(['Cash', 'Bank Transfer', 'Mobile Money', 'Cheque'] as $m)
                                    <option value="{{ $m }}" {{ old('payment_method') == $m ? 'selected' : '' }}>{{ $m }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Reference Number</label>
                            <input type="text" name="reference_number" class="form-control" value="{{ old('reference_number') }}" placeholder="Transaction / cheque no.">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="descriptions" rows="3" class="form-control" placeholder="Optional remarks...">{{ old('descriptions') }}</textarea>
                    </div>

                    <div class="text-end">
                        <a href="{{ route('viewExpense', encrypt($data->id)) }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="arbif-btn-submit" id="submitPay">
                            <i class="fas fa-check me-1"></i> Disburse Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const balance = {{ $balance }};
    const input   = document.getElementById('amountPaid');
    const remain  = document.getElementById('remainingAfter');
    const warn    = document.getElementById('overWarn');
    const submit  = document.getElementById('submitPay');
    const fmt = n => n.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});

    function update() {
        const amt = parseFloat(input.value) || 0;
        const left = Math.round((balance - amt) * 100) / 100;
        remain.textContent = fmt(Math.max(left, 0));
        warn.classList.toggle('d-none', left >= 0);
        submit.disabled = left < 0 || amt <= 0;
    }

    input.addEventListener('input', update);
    document.getElementById('payFull').addEventListener('click', () => { input.value = balance.toFixed(2); update(); });
    document.getElementById('payHalf').addEventListener('click', () => { input.value = (balance / 2).toFixed(2); update(); });

    document.getElementById('paymentForm').addEventListener('submit', function (e) {
        if (!confirm('Confirm disbursement of ' + fmt(parseFloat(input.value) || 0) + ' {{ $data->currency }}?')) e.preventDefault();
    });

    update();
});
</script>
@endpush

@endsection