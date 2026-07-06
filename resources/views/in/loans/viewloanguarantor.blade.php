@extends('layouts.workingside')

@section('title','View Loan Guarantor')

@section('content')

<div class="arbif-card">

    <div class="arbif-card-body">

        <div class="text-end no-print mb-3">

            <button onclick="window.print()"
                    class="arbif-btn-submit">

                Print

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
                    Loan Guarantor Information
                </h4>

            </div>

            <table class="table table-bordered">

                <tr>
                    <th width="35%">Loan Number</th>
                    <td>{{ optional($data->loan)->loan_number }}</td>
                </tr>

                <tr>
                    <th>Client</th>
                    <td>{{ optional($data->client)->FullName }}</td>
                </tr>

                <tr>
                    <th>Guarantor Name</th>
                    <td>{{ optional($data->guarantor)->full_name }}</td>
                </tr>

                <tr>
                    <th>Phone Number</th>
                    <td>{{ optional($data->guarantor)->phone_number }}</td>
                </tr>

                <tr>
                    <th>NIDA Number</th>
                    <td>{{ optional($data->guarantor)->nida_number }}</td>
                </tr>

                <tr>
                    <th>Guarantee Amount</th>
                    <td>{{ number_format($data->guarantee_amount,2) }}</td>
                </tr>

                <tr>
                    <th>Relationship Type</th>
                    <td>{{ $data->relationship_type }}</td>
                </tr>

                <tr>
                    <th>Occupation</th>
                    <td>{{ optional($data->guarantor)->occupation }}</td>
                </tr>

                <tr>
                    <th>Address</th>
                    <td>{{ optional($data->guarantor)->physical_address }}</td>
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

@push('styles')

<style>

@media print {

    .no-print,
    .sidebar,
    .navbar,
    footer {

        display: none !important;

    }

}

</style>

@endpush