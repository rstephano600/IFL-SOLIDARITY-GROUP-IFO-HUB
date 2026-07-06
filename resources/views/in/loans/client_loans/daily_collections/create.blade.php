@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Add Daily Collection</h4>
        </div>

        <div class="card-body">
            <form id="collectionForm" method="POST" action="{{ route('daily_collections.store') }}">
                @csrf

                {{-- Hidden Loan and Group Center Fields --}}
                @if(isset($loan))
                    <input type="hidden" name="client_loan_id" value="{{ $loan->id }}">
                    <input type="hidden" name="group_center_id" value="{{ $loan->group_center_id }}">
                    <div class="alert alert-info">
                        Recording collection for <strong>{{ $loan->loan_number }}</strong>
                        (Client: {{ $loan->client->first_name }} {{ $loan->client->last_name }})
                    </div>
                @else
                    {{-- Fallback if accessed manually --}}
                    <div class="mb-3">
                        <label for="client_loan_id" class="form-label">Loan</label>
                        <select name="client_loan_id" id="client_loan_id" class="form-select" required>
                            <option value="">Select Loan</option>
                            @foreach($loans as $loan)
                                <option value="{{ $loan->id }}">{{ $loan->loan_number }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="group_center_id" class="form-label">Group Center</label>
                        <select name="group_center_id" id="group_center_id" class="form-select" required>
                            <option value="">Select Center</option>
                            @foreach($groupCenters as $center)
                                <option value="{{ $center->id }}">{{ $center->center_name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="mb-3">
                    <label for="date_of_payment" class="form-label">Date of Payment</label>
                    <input type="date" name="date_of_payment" id="date_of_payment" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="amount_paid" class="form-label">Amount Paid</label>
                    <input type="number" step="0.01" name="amount_paid" id="amount_paid" class="form-control" value="0" required>
                </div>

                <div class="mb-3">
                    <label for="penalty_fee" class="form-label">Penalty Fee</label>
                    <input type="number" step="0.01" name="penalty_fee" id="penalty_fee" class="form-control" value="0">
                </div>

                <div class="mb-3">
                    <label for="total_preclosure" class="form-label">Total Preclosure</label>
                    <input type="number" step="0.01" name="total_preclosure" id="total_preclosure" class="form-control" value="0">
                </div>

                <div class="mb-3">
                    <label for="payment_method" class="form-label">Payment Method</label>
                    <select name="payment_method" id="payment_method" class="form-select">
                        <option value="cash">Cash</option>
                        <option value="mobile_money">Mobile Money</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="cheque">Cheque</option>
                        <option value="card">Card</option>
                        <option value="direct_debit">Direct Debit</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('daily_collections.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('collectionForm').addEventListener('submit', function () {
    ['amount_paid', 'penalty_fee', 'total_preclosure'].forEach(name => {
        const input = this.querySelector(`[name="${name}"]`);
        if (!input.value) input.value = 0;
    });
});
</script>
@endsection
