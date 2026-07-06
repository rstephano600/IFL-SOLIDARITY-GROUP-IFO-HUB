<table>
    <thead>
        <tr>
            <th colspan="8" style="text-align:center; font-weight:bold; font-size:16px;">
                Collections Summary Report
            </th>
        </tr>
        <tr>
            <th>#</th>
            <th>Center</th>
            <th>Group</th>
            <th>Client</th>
            <th>Loan Number</th>
            <th>Paid Date</th>
            <th>Total Paid</th>
            <th>Breakdown (P / I / Penalty)</th>
        </tr>
    </thead>
    <tbody>
        @php $i = 1; @endphp

        @foreach($collections->groupBy('loan.group.groupCenter.name') as $centerName => $centerCollections)
            <tr>
                <td colspan="8" style="font-weight:bold; background:#d9edf7;">
                    Center: {{ $centerName ?? 'N/A' }}
                </td>
            </tr>

            @foreach($centerCollections->groupBy('loan.group.group_name') as $groupName => $groupCollections)
                <tr>
                    <td colspan="8" style="font-weight:bold; background:#f5f5f5;">
                        Group: {{ $groupName ?? 'N/A' }}
                    </td>
                </tr>

                @foreach($groupCollections as $collection)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $collection->loan->group->groupCenter->name ?? 'N/A' }}</td>
                        <td>{{ $collection->loan->group->group_name ?? 'N/A' }}</td>
                        <td>{{ $collection->loan->client->first_name ?? '' }} {{ $collection->loan->client->last_name ?? '' }}</td>
                        <td>{{ $collection->loan->loan_number }}</td>
                        <td>{{ $collection->paid_date }}</td>
                        <td>{{ number_format($collection->principal_paid + $collection->interest_paid + $collection->penalty_paid, 2) }}</td>
                        <td>
                            {{ number_format($collection->principal_paid, 2) }} /
                            {{ number_format($collection->interest_paid, 2) }} /
                            {{ number_format($collection->penalty_paid, 2) }}
                        </td>
                    </tr>
                @endforeach

                {{-- Totals per group --}}
                @php
                    $groupTotal = $groupCollections->sum(fn($c) => $c->principal_paid + $c->interest_paid + $c->penalty_paid);
                @endphp
                <tr style="background:#e6ffe6; font-weight:bold;">
                    <td colspan="6" style="text-align:right;">Group Total:</td>
                    <td colspan="2">{{ number_format($groupTotal, 2) }}</td>
                </tr>
            @endforeach

            {{-- Totals per center --}}
            @php
                $centerTotal = $centerCollections->sum(fn($c) => $c->principal_paid + $c->interest_paid + $c->penalty_paid);
            @endphp
            <tr style="background:#c8e6c9; font-weight:bold;">
                <td colspan="6" style="text-align:right;">Center Total:</td>
                <td colspan="2">{{ number_format($centerTotal, 2) }}</td>
            </tr>
        @endforeach

        {{-- Grand Total --}}
        @php
            $grandTotal = $collections->sum(fn($c) => $c->principal_paid + $c->interest_paid + $c->penalty_paid);
        @endphp
        <tr style="background:#b2fab4; font-weight:bold;">
            <td colspan="6" style="text-align:right;">Grand Total:</td>
            <td colspan="2">{{ number_format($grandTotal, 2) }}</td>
        </tr>
    </tbody>
</table>
