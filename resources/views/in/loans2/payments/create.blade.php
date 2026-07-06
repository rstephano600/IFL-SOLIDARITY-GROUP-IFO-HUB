@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Record New Payment for Loan: **{{ $loan->loan_number }}**</h1>
        <a href="{{ route('loans.show', $loan) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Loan Details
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            Payment Details
        </div>
        <div class="card-body">
            
            {{-- Loan Financial Summary --}}
            <div class="alert alert-info d-flex justify-content-between">
                <div>
                    <strong>Total Loan Outstanding:</strong> {{ number_format($loan->total_outstanding, 2) }} TZS
                </div>
                <div>
                    <strong>Total Installments Overdue/Pending:</strong> {{ number_format($totalDue, 2) }} TZS
                </div>
            </div>

            <form action="{{ route('payments.store', $loan) }}" method="POST">
                @csrf

                <div class="row">
                    {{-- Payment Date --}}
                    <div class="col-md-6 mb-3">
                        <label for="payment_date" class="form-label">Payment Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('payment_date') is-invalid @enderror" id="payment_date" name="payment_date" 
                               value="{{ old('payment_date', now()->format('Y-m-d')) }}" required max="{{ now()->format('Y-m-d') }}">
                        @error('payment_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Payment Amount --}}
                    <div class="col-md-6 mb-3">
                        <label for="payment_amount" class="form-label">Amount Received (TZS) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control @error('payment_amount') is-invalid @enderror" id="payment_amount" name="payment_amount" 
                               value="{{ old('payment_amount', $totalDue > 0 ? number_format($totalDue, 2, '.', '') : '') }}" required min="0.01">
                        <small class="text-muted">Enter the exact amount received from the client.</small>
                        @error('payment_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row">
                    {{-- Payment Method --}}
                    <div class="col-md-6 mb-3">
                        <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                        <select class="form-select @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method" required>
                            <option value="">Select Method</option>
                            @php $methods = ['cash', 'mobile_money', 'bank_transfer', 'cheque', 'direct_debit', 'card', 'other']; @endphp
                            @foreach ($methods as $method)
                                <option value="{{ $method }}" {{ old('payment_method') === $method ? 'selected' : '' }}>
                                    {{ ucwords(str_replace('_', ' ', $method)) }}
                                </option>
                            @endforeach
                        </select>
                        @error('payment_method')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Transaction Reference --}}
                    <div class="col-md-6 mb-3">
                        <label for="transaction_reference" class="form-label">Transaction Reference / Cheque No.</label>
                        <input type="text" class="form-control @error('transaction_reference') is-invalid @enderror" id="transaction_reference" name="transaction_reference" 
                               value="{{ old('transaction_reference') }}">
                        <small class="text-muted">E.g., M-Pesa code, Bank Transaction ID.</small>
                        @error('transaction_reference')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Remarks --}}
                <div class="mb-3">
                    <label for="remarks" class="form-label">Remarks / Notes</label>
                    <textarea class="form-control @error('remarks') is-invalid @enderror" id="remarks" name="remarks" rows="3">{{ old('remarks') }}</textarea>
                    @error('remarks')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn btn-success mt-3">
                    <i class="fas fa-check-circle"></i> Record and Allocate Payment
                </button>
            </form>
        </div>
    </div>
</div>
@endsection