@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Loan Payment</h2>
    <a href="{{ route('loan_payments.index') }}" class="btn btn-secondary mb-3">Back</a>

    <form action="{{ route('loan_payments.update', $loanPayment->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Loan</label>
                <select name="loan_id" class="form-select" disabled>
                    <option value="{{ $loanPayment->loan_id }}">{{ $loanPayment->loan->loan_number }}</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label>Client</label>
                <select name="client_id" class="form-select" disabled>
                    <option value="{{ $loanPayment->client_id }}">{{ $loanPayment->client->first_name }} {{ $loanPayment->client->last_name }}</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label>Group Centre</label>
                <select name="group_center_id" class="form-select">
                    <option value="">-- Select Centre --</option>
                    @foreach($centres as $centre)
                        <option value="{{ $centre->id }}" {{ $loanPayment->group_centre_id == $centre->id ? 'selected' : '' }}>
                            {{ $centre->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label>Payment Date</label>
                <input type="date" name="payment_date" class="form-control" value="{{ $loanPayment->payment_date }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Amount</label>
                <input type="number" step="0.01" name="amount" class="form-control" value="{{ $loanPayment->amount }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Payment Method</label>
                <select name="payment_method" class="form-select">
                    <option value="">-- Select Method --</option>
                    @foreach(['cash', 'bank_transfer', 'mobile_money', 'cheque'] as $method)
                        <option value="{{ $method }}" {{ $loanPayment->payment_method == $method ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $method)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label>Reference Number</label>
                <input type="text" name="reference_number" class="form-control" value="{{ $loanPayment->reference_number }}">
            </div>

            <div class="col-md-12 mb-3">
                <label>Remarks</label>
                <textarea name="remarks" class="form-control">{{ $loanPayment->remarks }}</textarea>
            </div>
        </div>

        <button type="submit" class="btn btn-success">Update Payment</button>
    </form>
</div>
@endsection
