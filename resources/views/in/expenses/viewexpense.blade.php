@extends('layouts.workingside')

@section('content')

<div class="wrapper wrapper-content">

    <div class="ibox">

        <div class="ibox-title">

            <h5>Expense Details</h5>

        </div>

        <div class="ibox-content">

            <table class="table table-bordered">

                <tr>
                    <th>Expense Number</th>
                    <td>{{ $data->expense_number }}</td>
                </tr>

                <tr>
                    <th>Category</th>
                    <td>{{ $data->category->name ?? '' }}</td>
                </tr>

                <tr>
                    <th>Date</th>
                    <td>{{ $data->expense_date }}</td>
                </tr>

                <tr>
                    <th>Amount</th>
                    <td>{{ number_format($data->amount,2) }}</td>
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
                    <th>Description</th>
                    <td>{{ $data->descriptions }}</td>
                </tr>

                <tr>
                    <th>Approval Status</th>
                    <td>{{ $data->AppStatus }}</td>
                </tr>

                <tr>
                    <th>Attachment</th>
                    <td>

                        @if($data->attachment)

                        <a href="{{ asset('storage/'.$data->attachment) }}"
                           target="_blank">

                            View Attachment

                        </a>

                        @else

                        No Attachment

                        @endif

                    </td>

                </tr>

            </table>

        </div>

    </div>

</div>

@endsection