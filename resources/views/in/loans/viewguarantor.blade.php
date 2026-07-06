@extends('layouts.workingside')

@section('title','View Guarantor')

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
                    Guarantor Profile
                </h4>

            </div>

            <table class="table table-bordered">

                <tr>
                    <th width="30%">Guarantor Number</th>
                    <td>{{ $data->guarantor_number }}</td>
                </tr>

                <tr>
                    <th>Full Name</th>
                    <td>{{ $data->full_name }}</td>
                </tr>

                <tr>
                    <th>Gender</th>
                    <td>{{ $data->gender }}</td>
                </tr>

                <tr>
                    <th>Phone Number</th>
                    <td>{{ $data->phone_number }}</td>
                </tr>

                <tr>
                    <th>Alternative Phone</th>
                    <td>{{ $data->alternative_phone }}</td>
                </tr>

                <tr>
                    <th>NIDA Number</th>
                    <td>{{ $data->nida_number }}</td>
                </tr>

                <tr>
                    <th>Email</th>
                    <td>{{ $data->email }}</td>
                </tr>

                <tr>
                    <th>Occupation</th>
                    <td>{{ $data->occupation }}</td>
                </tr>

                <tr>
                    <th>Relationship</th>
                    <td>{{ $data->relationship_with_client }}</td>
                </tr>

                <tr>
                    <th>Address</th>
                    <td>{{ $data->physical_address }}</td>
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
        display:none !important;
    }

}
</style>
@endpush