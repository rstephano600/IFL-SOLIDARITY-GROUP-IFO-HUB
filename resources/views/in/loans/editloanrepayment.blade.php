@extends('layouts.workingside')

@section('title', 'Edit Loan Repayment')
@section('page-title', 'Edit Loan Repayment')

@section('content')

{{-- ══════════════════════════════════════════════
     PAGE HEADER
══════════════════════════════════════════════ --}}
<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-edit"></i>
        </div>
        Edit Loan Repayment
    </h3>
</div>

<div class="d-flex gap-2 mb-3">
    <a href="{{ route('loansrepayments') }}" class="arbif-btn-cancel">
        <i class="fas fa-arrow-left"></i>
        Back to Repayments
    </a>
</div>

{{-- ══════════════════════════════════════════════
     LOAN CONTEXT — read-only info panel
══════════════════════════════════════════════ --}}
<div class="arbif-card mb-3">
    <div class="arbif-card-body">
        <div class="row g-3">

            <div class="col-md-4">
                <label class="form-label text-muted">Loan Number</label>
                <p class="fw-bold mb-0">
                    {{ optional($data->loan)->loan_number ?? 'N/A' }}
                </p>
            </div>

            <div class="col-md-4">
                <label class="form-label text-muted">Client</label>
                <p class="fw-bold mb-0">
                    {{ optional($data->client)->client->name ?? 'N/A' }}
                </p>
            </div>

            <div class="col-md-4">
                <label class="form-label text-muted">Outstanding Balance</label>
                <p class="fw-bold mb-0 text-danger">
                    TZS {{ number_format(optional($data->loan)->outstanding_balance ?? 0, 2) }}
                </p>
            </div>

        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════
     EDIT FORM
══════════════════════════════════════════════ --}}
<div class="arbif-card">
    <div class="arbif-card-body">

        <form method="POST"
              action="{{ route('updateloanrepayment', encrypt($data->id)) }}">
            @csrf
            @method('PUT') {{-- ✅ Required for Laravel PUT route matching --}}

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Payment Date</label>

                    {{-- ✅ Carbon format to avoid datetime bleeding into date input --}}
                    <input type="date"
                           name="payment_date"
                           value="{{ \Carbon\Carbon::parse($data->payment_date)->format('Y-m-d') }}"
                           class="form-control"
                           required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Amount Paid</label>
                    <input type="number"
                           step="0.01"
                           min="1"
                           name="amount_paid"
                           value="{{ $data->amount_paid }}"
                           class="form-control"
                           required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Payment Method</label>

                    {{-- ✅ Select instead of free-text input, mirrors store form --}}
                    <select name="payment_method" class="form-control">
                        <option value="Cash"
                            {{ $data->payment_method === 'Cash' ? 'selected' : '' }}>
                            Cash
                        </option>
                        <option value="Bank"
                            {{ $data->payment_method === 'Bank' ? 'selected' : '' }}>
                            Bank
                        </option>
                        <option value="Mobile Money"
                            {{ $data->payment_method === 'Mobile Money' ? 'selected' : '' }}>
                            Mobile Money
                        </option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Reference Number</label>
                    <input type="text"
                           name="reference_number"
                           value="{{ $data->reference_number }}"
                           class="form-control">
                </div>

                <div class="col-md-12">
                    <label class="form-label">Remarks</label>
                    <textarea name="remarks"
                              rows="4"
                              class="form-control">{{ $data->remarks }}</textarea>
                </div>

            </div>

            <div class="mt-4 d-flex gap-2">

                <button type="submit" class="arbif-btn-submit">
                    <i class="fas fa-save"></i>
                    Update Repayment
                </button>

                <a href="{{ route('loansrepayments') }}" class="arbif-btn-cancel">
                    Cancel
                </a>

            </div>

        </form>

    </div>
</div>

@endsection