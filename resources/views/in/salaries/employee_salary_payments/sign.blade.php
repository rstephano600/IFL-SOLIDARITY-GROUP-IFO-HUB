@extends('layouts.app')
@section('title', 'Sign Salary Payment')

@section('content')
<div class="container py-4">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="bi bi-pencil-square me-2"></i> Sign to Confirm Salary Payment</h4>
        </div>

        <div class="card-body">
            <h5>Salary Payment for: <strong>{{ $payment->employee->first_name }} {{ $payment->employee->last_name }}</strong></h5>
            <p><strong>Amount:</strong> {{ number_format($payment->amount_paid, 2) }} {{ $payment->currency }}</p>
            <p><strong>Payment Date:</strong> {{ $payment->payment_date }}</p>
            <p><strong>Status:</strong> {{ ucfirst($payment->status) }}</p>

            <form action="{{ route('employee_salary_payments.storeSignature', $payment->id) }}" method="POST" id="sign-form">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-bold">Your Signature</label>
                    <div class="border rounded bg-light">
                        <canvas id="signature-pad" width="500" height="200" class="border w-100"></canvas>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-secondary mt-2" id="clear-signature">Clear</button>
                    <input type="hidden" name="signature" id="signature-data">
                </div>

                <div class="text-end">
                    <a href="{{ route('employee_salary_payments.show', $payment->id) }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Submit Signature</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
const canvas = document.getElementById('signature-pad');
const signaturePad = new SignaturePad(canvas);

document.getElementById('clear-signature').addEventListener('click', function () {
    signaturePad.clear();
});

document.getElementById('sign-form').addEventListener('submit', function (e) {
    if (signaturePad.isEmpty()) {
        alert('Please provide your signature before submitting.');
        e.preventDefault();
    } else {
        document.getElementById('signature-data').value = signaturePad.toDataURL();
    }
});
</script>
@endsection
