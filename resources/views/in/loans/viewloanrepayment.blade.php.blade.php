@extends('layouts.workingside')

@section('title','View Loan Repayment')

@section('content')

<div class="arbif-card">

    <div class="arbif-card-body">

        <div class="text-end no-print mb-3">

            <button onclick="window.print()"
                    class="arbif-btn-submit">

                Print Receipt

            </button>

        </div>

        <div id="printArea">

            <div class="text-center mb-4">

                <img src="{{ asset('images/arbif.png') }}"
                     style="height:100px;">

                <h3 class="mt-3">
                    AIR-BIF MICROFINANCE
                </h3>

                <h4>
                    Loan Repayment Receipt
                </h4>

            </div>

            <table class="table table-bordered">

                <tr>
                    <th>Loan Number</th>
                    <td>{{ optional($data->loan)->loan_number }}</td>
                </tr>

                <tr>
                    <th>Client</th>
                    <td>{{ optional($data->client)->FullName }}</td>
                </tr>

                <tr>
                    <th>Payment Date</th>
                    <td>{{ $data->payment_date }}</td>
                </tr>

                <tr>
                    <th>Amount Paid</th>
                    <td>{{ number_format($data->amount_paid,2) }}</td>
                </tr>

                <tr>
                    <th>Principal Paid</th>
                    <td>{{ number_format($data->principal_paid,2) }}</td>
                </tr>

                <tr>
                    <th>Interest Paid</th>
                    <td>{{ number_format($data->interest_paid,2) }}</td>
                </tr>

                <tr>
                    <th>Penalty Paid</th>
                    <td>{{ number_format($data->penalty_paid,2) }}</td>
                </tr>

                <tr>
                    <th>Payment Method</th>
                    <td>{{ $data->payment_method }}</td>
                </tr>

                <tr>
                    <th>Reference Number</th>
                    <td>{{ $data->reference_number }}</td>
                </tr>

                <tr>
                    <th>Remarks</th>
                    <td>{{ $data->remarks }}</td>
                </tr>

            </table>

        </div>

    </div>

</div>

@endsection