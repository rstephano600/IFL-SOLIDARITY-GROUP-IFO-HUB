<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Collections Summary Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        h2, h3, h4 {
            text-align: left;
            margin: 8px 0;
        }
        h2 {
            text-align: center;
            text-transform: uppercase;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tfoot td {
            font-weight: bold;
            background-color: #f9f9f9;
        }
        .center-header {
            background-color: #e9ecef;
            font-weight: bold;
            padding: 6px;
            margin-top: 15px;
        }
    </style>
</head>
<body>

    <h2>Collections Summary Report</h2>

    @php
        // Defensive: avoid null groupings (if relationships missing)
        $groupedCenters = $collections->groupBy(fn($c) => optional($c->loan->group->groupCenter)->center_name ?? 'Unassigned Center');
    @endphp

    @foreach($groupedCenters as $centerName => $centerCollections)
        <h3 class="center-header">Center: {{ $centerName }}</h3>

        @php
            $groupedGroups = $centerCollections->groupBy(fn($c) => optional($c->loan->group)->group_name ?? 'Unassigned Group');
        @endphp

        @foreach($groupedGroups as $groupName => $groupCollections)
            <h4>Group: {{ $groupName }}</h4>
            <table>

                <tfoot>
                    <tr>
                        <td colspan="6" style="text-align: right;">Group Total:</td>
                        <td>
                            {{ number_format($groupCollections->sum(fn($c) => $c->principal_paid + $c->penalty_paid), 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        @endforeach

        {{-- Center Total --}}
        <table>
            <tfoot>
                <tr>
                    <td colspan="6" style="text-align: right;">Center Total ({{ $centerName }}):</td>
                    <td>
                        {{ number_format($centerCollections->sum(fn($c) => $c->principal_paid + $c->penalty_paid), 2) }}
                    </td>
                </tr>
            </tfoot>
        </table>

    @endforeach

    {{-- Overall Total --}}
    <hr>
    <h3>Total Collections: 
        {{ number_format($collections->sum(fn($c) => $c->principal_paid + $c->penalty_paid), 2) }}
    </h3>

</body>
</html>
