
<div class="mb-3">
    <label>Client</label>
    <select name="client_id" class="form-select" required>
        <option value="">-- Select Client --</option>
        @foreach($clients as $client)
            <option value="{{ $client->id }}" @selected(old('client_id', $clientLoan->client_id ?? '') == $client->id)>
                {{ $client->first_name }} {{ $client->last_name }}
            </option>
        @endforeach
    </select>
    @error('client_id') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label>Group Center</label>
    <select name="group_center_id" class="form-select">
        <option value="">-- Optional --</option>
        @foreach($groupCenters as $center)
            <option value="{{ $center->id }}" @selected(old('group_center_id', $clientLoan->group_center_id ?? '') == $center->id)>
                {{ $center->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label>Loan Number</label>
    <input type="text" name="loan_number" class="form-control" value="{{ old('loan_number', $clientLoan->loan_number ?? '') }}" required>
</div>

<div class="mb-3">
    <label>Amount Requested</label>
    <input type="number" name="amount_requested" step="0.01" class="form-control" value="{{ old('amount_requested', $clientLoan->amount_requested ?? '') }}" required>
</div>

<div class="mb-3">
    <label>Payable Frequency</label>
    <input type="number" name="payable_frequency" step="0.01" class="form-control" value="{{ old('payable_frequency', $clientLoan->payable_frequency ?? '') }}" required>
</div>

<div class="mb-3">
    <label>Repayment Frequency</label>
    <select name="repayment_frequency" class="form-select" required>
        @foreach(['daily','weekly','bi_weekly','monthly','yearly','quarterly'] as $freq)
            <option value="{{ $freq }}" @selected(old('repayment_frequency', $clientLoan->repayment_frequency ?? '') == $freq)>
                {{ ucfirst(str_replace('_',' ', $freq)) }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label>Interest Rate (%)</label>
    <input type="number" name="interest_rate" step="0.01" class="form-control" value="{{ old('interest_rate', $clientLoan->interest_rate ?? '') }}">
</div>

<div class="mb-3">
    <label>Start Date</label>
    <input type="date" name="start_date" class="form-control" value="{{ old('start_date', optional($clientLoan->start_date)->format('Y-m-d')) }}">
</div>

<div class="mb-3">
    <label>End Date</label>
    <input type="date" name="end_date" class="form-control" value="{{ old('end_date', optional($clientLoan->end_date)->format('Y-m-d')) }}">
</div>

<div class="mb-3">
    <label>Status</label>
    <select name="status" class="form-select" required>
        @foreach(['pending','approved','disbursed','completed','defaulted'] as $status)
            <option value="{{ $status }}" @selected(old('status', $clientLoan->status ?? '') == $status)>{{ ucfirst($status) }}</option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label>Remarks</label>
    <textarea name="remarks" class="form-control">{{ old('remarks', $clientLoan->remarks ?? '') }}</textarea>
</div>