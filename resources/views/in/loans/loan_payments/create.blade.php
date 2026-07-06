@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Loan Payment</h2>
    <a href="{{ route('loan_payments.index') }}" class="btn btn-secondary mb-3">Back</a>

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> Check the form below for errors.
            <ul>
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('loan_payments.store') }}" method="POST">
        @csrf

        <div class="row">
<div class="col-md-6 mb-3">
    <label>Loan</label>
    <select name="loan_id" id="loan_id" class="form-select" required>
        <option value="">-- Select Loan --</option>
        @foreach($loans as $loan)
            <option value="{{ $loan->id }}" data-client-id="{{ $loan->client_id }}">
                {{ $loan->loan_number }} - ({{ $loan->client->first_name }} {{ $loan->client->last_name }})
            </option>
        @endforeach
    </select>
</div>

{{-- Hidden client input (auto-filled) --}}
<input type="hidden" name="client_id" id="client_id">

            <div class="col-md-6 mb-3">
                <label>Group Centre</label>
                <select name="group_center_id" class="form-select">
                    <option value="">-- Select Centre --</option>
                    @foreach($centres as $centre)
                        <option value="{{ $centre->id }}">{{ $centre->center_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label>Payment Date</label>
                <input type="date" name="payment_date" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Amount</label>
                <input type="number" step="0.01" name="amount" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Payment Method</label>
                <select name="payment_method" class="form-select">
                    <option value="">-- Select Method --</option>
                    <option value="cash">Cash</option>
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="mobile_money">Mobile Money</option>
                    <option value="cheque">Cheque</option>
                </select>
            </div>

            <div class="col-md-12 mb-3">
                <label>Remarks</label>
                <textarea name="remarks" class="form-control"></textarea>
            </div>
        </div>

        <button type="submit" class="btn btn-success">Save Payment</button>
    </form>
</div>

<script>

document.addEventListener('DOMContentLoaded', function () {
    const loanSelect = document.getElementById('loan_id');
    const clientInput = document.getElementById('client_id');

    loanSelect.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const clientId = selectedOption.getAttribute('data-client-id');

        clientInput.value = clientId ? clientId : '';
    });
});
</script>

@endsection
