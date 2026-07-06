@extends('layouts.workingside')

@section('title', 'View Loan Category')

@section('page-title', 'Loan Category Details')

@section('content')

<div class="container-fluid">

    <div class="arbif-card">

        <div class="arbif-card-body">

            {{-- PRINT ACTIONS --}}
            <div class="d-flex justify-content-between align-items-center mb-4 no-print">

                <h4 class="mb-0">
                    <i class="fas fa-file-invoice-dollar"></i>
                    Loan Category Information
                </h4>

                <div>

                    <button onclick="window.print()"
                            class="arbif-btn-submit">

                        <i class="fas fa-print"></i>
                        Print

                    </button>

                    <a href="{{ url()->previous() }}"
                       class="arbif-btn-cancel">

                        <i class="fas fa-arrow-left"></i>
                        Back

                    </a>

                </div>

            </div>


            {{-- PRINTABLE AREA --}}
            <div id="printArea">

                {{-- HEADER --}}
                <div class="text-center mb-4">

                    <img src="{{ asset('images/arbif.png') }}"
                         alt="ARBIF Logo"
                         style="height:100px;">

                    <h2 class="mt-3 mb-1">
                        AIR-BIF MICROFINANCE
                    </h2>

                    <h4 class="mb-0">
                        Loan Category Profile
                    </h4>

                </div>


                {{-- CATEGORY TITLE --}}
                <div class="text-center mb-4">

                    <h3 class="fw-bold text-primary">
                        {{ $data->name ?? 'N/A' }}
                    </h3>

                    <span class="badge bg-success">
                        {{ $data->repayment_frequency ?? 'N/A' }}
                    </span>

                </div>


                {{-- LOAN SUMMARY --}}
                <div class="row">

                    <div class="col-md-6">

                        <table class="table table-bordered">

                            <tr>
                                <th width="45%">
                                    Amount Disbursed
                                </th>

                                <td>
                                    {{ number_format($data->amount_disbursed ?? 0, 2) }}
                                    {{ $data->currency ?? '' }}
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    Interest Rate
                                </th>

                                <td>
                                    {{ $data->interest_rate ?? 0 }}%
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    Interest Amount
                                </th>

                                <td>
                                    {{ number_format($data->interest_amount ?? 0, 2) }}
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    Repayable Amount
                                </th>

                                <td>
                                    <strong>
                                        {{ number_format($data->repayable_amount ?? 0, 2) }}
                                    </strong>
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    Principal Due
                                </th>

                                <td>
                                    {{ number_format($data->principal_due ?? 0, 2) }}
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    Interest Due
                                </th>

                                <td>
                                    {{ number_format($data->interest_due ?? 0, 2) }}
                                </td>
                            </tr>

                        </table>

                    </div>


                    <div class="col-md-6">

                        <table class="table table-bordered">

                            <tr>
                                <th width="45%">
                                    Total Days Due
                                </th>

                                <td>
                                    {{ $data->total_days_due ?? 0 }} Days
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    Max Term Days
                                </th>

                                <td>
                                    {{ $data->max_term_days ?? 0 }}
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    Max Term Months
                                </th>

                                <td>
                                    {{ $data->max_term_months ?? 0 }}
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    Insurance Fee
                                </th>

                                <td>
                                    {{ number_format($data->insurance_fee ?? 0, 2) }}
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    Officer Visit Fee
                                </th>

                                <td>
                                    {{ number_format($data->officer_visit_fee ?? 0, 2) }}
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    New Client
                                </th>

                                <td>

                                    @if($data->is_new_client)

                                        <span class="badge bg-success">
                                            YES
                                        </span>

                                    @else

                                        <span class="badge bg-danger">
                                            NO
                                        </span>

                                    @endif

                                </td>
                            </tr>

                        </table>

                    </div>

                </div>


                {{-- CONDITIONS --}}
                <div class="mt-4">

                    <h5 class="border-bottom pb-2">
                        Loan Conditions
                    </h5>

                    <div class="p-3 border rounded bg-light">

                        {!! nl2br(e($data->conditions ?? 'No conditions available')) !!}

                    </div>

                </div>


                {{-- DESCRIPTION --}}
                <div class="mt-4">

                    <h5 class="border-bottom pb-2">
                        Description
                    </h5>

                    <div class="p-3 border rounded bg-light">

                        {!! nl2br(e($data->descriptions ?? 'No description available')) !!}

                    </div>

                </div>


                {{-- FOOTER --}}
                <div class="mt-5">

                    <div class="row">

                        <div class="col-md-6">

                            <p>
                                <strong>Prepared By:</strong><br>

                                {{ Auth()->user()->name }}
                            </p>

                        </div>

                        <div class="col-md-6 text-end">

                            <p>
                                <strong>Date:</strong><br>

                                {{ now()->format('d M Y H:i A') }}
                            </p>

                        </div>

                    </div>

                </div>

            </div>

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

        body {
            background: #fff !important;
        }

        .arbif-card {
            border: none !important;
            box-shadow: none !important;
        }

        #printArea {
            width: 100%;
        }

    }

    table th {
        background: #f8f9fa;
    }

</style>

@endpush