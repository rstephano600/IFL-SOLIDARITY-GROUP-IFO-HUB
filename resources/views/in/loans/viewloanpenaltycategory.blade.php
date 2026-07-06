@extends('layouts.workingside')

@section('title','View Loan Penalty Category')

@section('content')

<div class="arbif-card">

    <div class="arbif-card-body">

        <div class="text-end mb-3 no-print">

            <button onclick="window.print()"
                    class="arbif-btn-submit">

                <i class="fas fa-print"></i>
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
                    Loan Penalty Category Profile
                </h4>

            </div>


            <table class="table table-bordered">

                <tr>

                    <th width="30%">
                        Category Name
                    </th>

                    <td>
                        {{ $data->name }}
                    </td>

                </tr>

                <tr>

                    <th>
                        Conditions
                    </th>

                    <td>

                        {!! nl2br(e($data->conditions)) !!}

                    </td>

                </tr>

                <tr>

                    <th>
                        Description
                    </th>

                    <td>

                        {!! nl2br(e($data->descriptions)) !!}

                    </td>

                </tr>

                <tr>

                    <th>
                        Created Date
                    </th>

                    <td>

                        {{ $data->created_at->format('d M Y H:i A') }}

                    </td>

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

    .arbif-card {
        border:none !important;
        box-shadow:none !important;
    }

}

</style>

@endpush