@extends('layouts.workingside')

@section('title', 'Loan Penalties')
@section('page-title', 'Loan Penalties Management')

@section('content')

<div class="arbif-page-header">

    <h3>
        <div class="page-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        Loan Penalties
    </h3>


</div>


<div class="arbif-card">

    <div class="arbif-card-body">

        <div class="arbif-table-wrap">

            <table class="arbif-table" id="loanPenaltyTable">

                <thead>

                    <tr>

                        <th class="sortable">#</th>
                        <th class="sortable">Loan Number</th>
                        <th class="sortable">Client</th>
                        <th class="sortable">Penalty Category</th>
                        <th class="sortable">Penalty Date</th>
                        <th class="sortable">Overdue Days</th>
                        <!-- <th class="sortable">Rate (%)</th> -->
                        <th class="sortable">Penalty Amount</th>
                        <th class="sortable">Status</th>
                        <th>Actions</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($data as $index => $item)

                    <tr>

                        <td>{{ $index + 1 }}</td>

                        <td>
                            {{ optional($item->loan)->loan_number }}
                        </td>

                        <td>
                            {{ optional($item->client)->client->name }}
                        </td>

                        <td>
                            {{ optional($item->penaltyCategory)->name }}
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($item->penalty_date)->format('d M Y') }}
                        </td>

                        <td>
                            {{ $item->overdue_days }}
                        </td>

                        <!-- <td>
                            {{ number_format($item->penalty_rate,2) }}
                        </td> -->

                        <td>
                            {{ number_format($item->penalty_amount,2) }}
                        </td>

                        <td>

                            @if($item->payment_status == 'PAID')

                                <span class="badge bg-success">
                                    PAID
                                </span>

                            @else

                                <span class="badge bg-danger">
                                    NOT PAID
                                </span>

                            @endif

                        </td>

                        <td>

                            @if($item->payment_status != 'PAID')

                                <a href="{{ route('payloanpenalty', encrypt($item->id)) }}"
                                   class="btn btn-success btn-sm"
                                   onclick="return confirm('Mark this penalty as PAID?')">

                                    Mark as Paid

                                </a>

                            @endif

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="10" class="text-center">
                            No Loan Penalties Found
                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


@endsection
