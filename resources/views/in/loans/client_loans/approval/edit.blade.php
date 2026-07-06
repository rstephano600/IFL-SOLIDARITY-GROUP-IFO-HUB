@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5>Approve Loan #{{ $clientLoan->loan_number }}</h5>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('loans.approve.update', $clientLoan->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Client Name</label>
                    <input type="text" class="form-control" value="{{ $clientLoan->client->first_name }} {{ $clientLoan->client->last_name }}" disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label">Amount Reqeusted</label>
                    <input type="text" class="form-control" value="{{ $clientLoan->amount_requested }}" disabled>
                </div>

                <div class="mb-3">
                    <label for="amount_disbursed" class="form-label">Amount Disbursed</label>
                    <input type="number" step="0.01" name="amount_disbursed" id="amount_disbursed"
                        class="form-control @error('amount_disbursed') is-invalid @enderror"
                        value="{{ old('amount_disbursed', $clientLoan->amount_disbursed) }}">
                    @error('amount_disbursed') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="loan_fee" class="form-label">Loan Fee</label>
                    <input type="number" step="0.01" name="loan_fee" id="loan_fee"
                        class="form-control @error('loan_fee') is-invalid @enderror"
                        value="{{ old('loan_fee', $clientLoan->loan_fee) }}">
                    @error('loan_fee') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="other_fee" class="form-label">Other Fee</label>
                    <input type="number" step="0.01" name="other_fee" id="other_fee"
                        class="form-control @error('other_fee') is-invalid @enderror"
                        value="{{ old('other_fee', $clientLoan->other_fee) }}">
                    @error('other_fee') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="interest_rate" class="form-label">Interest Rate (%)</label>
                    <input type="number" step="0.01" name="interest_rate" id="interest_rate"
                        class="form-control @error('interest_rate') is-invalid @enderror"
                        value="{{ old('interest_rate', $clientLoan->interest_rate) }}">
                    @error('interest_rate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Approve Loan
                    </button>
                    <a href="{{ route('client_loans.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
