@extends('layouts.workingside')

@section('title', 'View Loan Penalty')
@section('page-title', 'Loan Penalty Information')

@section('content')

<div class="arbif-card">

    <div class="arbif-card-body">

        <div class="text-end no-print mb-3">

            <a href="{{ route('loanpenalties') }}"
               class="arbif-btn-cancel">

                <i class="fas fa-arrow-left"></i>
                Back

            </a>

            <button onclick="window.print()"
                    class="arbif-btn-submit">

                <i class="fas fa-print"></i>
                Print

            </button>

        </div>

        <div id="printArea">

            <div class="text-center mb-4">

                <img src="{{ asset('images/arbif.png') }}"
                     alt="ARBIF Logo"
                     style="height:100px;">

                <h3 class="mt-3">
                    AIR-BIF MICROFINANCE
                </h3>

                <h4>
                    Loan Penalty Information
                </h4>

            </div>


            <table class="table table-bordered">

                <tr>
                    <th width="35%">Loan Number</th>
                    <td>
                        {{ optional($data->loan)->loan_number }}
                    </td>
                </tr>

                <tr>
                    <th>Client Name</th>
                    <td>
                        {{ optional($data->client)->FullName }}
                    </td>
                </tr>

                <tr>
                    <th>Penalty Category</th>
                    <td>
                        {{ optional($data->penaltyCategory)->name }}
                    </td>
                </tr>

                <tr>
                    <th>Penalty Date</th>
                    <td>
                        {{ \Carbon\Carbon::parse($data->penalty_date)->format('d M Y') }}
                    </td>
                </tr>

                <tr>
                    <th>Overdue Days</th>
                    <td>
                        {{ $data->overdue_days }}
                    </td>
                </tr>

                <tr>
                    <th>Penalty Rate (%)</th>
                    <td>
                        {{ number_format($data->penalty_rate,2) }}
                    </td>
                </tr>

                <tr>
                    <th>Penalty Amount</th>
                    <td>
                        {{ number_format($data->penalty_amount,2) }}
                    </td>
                </tr>

                <tr>
                    <th>Payment Status</th>
                    <td>

                        @if($data->payment_status == 'PAID')

                            <span class="badge bg-success">

                                PAID

                            </span>

                        @else

                            <span class="badge bg-danger">

                                NOT PAID

                            </span>

                        @endif

                    </td>
                </tr>

                <tr>
                    <th>Paid At</th>
                    <td>
                        {{ $data->paid_at ?? 'Not Yet Paid' }}
                    </td>
                </tr>

                <tr>
                    <th>Remarks</th>
                    <td>
                        {!! nl2br(e($data->remarks)) !!}
                    </td>
                </tr>

                <tr>
                    <th>Created At</th>
                    <td>
                        {{ $data->created_at }}
                    </td>
                </tr>

            </table>


            <div class="row mt-5">

                <div class="col-md-6">

                    <strong>Prepared By:</strong>

                    <br>

                    {{ Auth()->user()->name }}

                </div>

                <div class="col-md-6 text-end">

                    <strong>Generated On:</strong>

                    <br>

                    {{ now()->format('d M Y H:i A') }}

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

    .arbif-card {

        border: none !important;
        box-shadow: none !important;

    }

}

.table th {

    background: #f8f9fa;

}

</style>

@endpush