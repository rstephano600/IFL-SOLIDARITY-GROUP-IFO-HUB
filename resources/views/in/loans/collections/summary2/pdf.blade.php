<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Collection Summary - {{ now()->format('d/m/Y') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 15px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .summary-section {
            margin-bottom: 20px;
        }
        .summary-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .summary-row {
            display: table-row;
        }
        .summary-cell {
            display: table-cell;
            width: 25%;
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
            background-color: #f8f9fa;
        }
        .summary-cell h4 {
            margin: 0 0 5px 0;
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
        }
        .summary-cell .value {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table th {
            background-color: #333;
            color: white;
            padding: 8px 4px;
            text-align: left;
            font-size: 10px;
        }
        table td {
            padding: 6px 4px;
            border-bottom: 1px solid #ddd;
            font-size: 9px;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        tfoot {
            background-color: #e9ecef;
            font-weight: bold;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>COLLECTION SUMMARY REPORT</h1>
        <p>Generated on {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>Total Records: {{ $collections->count() }}</p>
    </div>

    <div class="summary-section">
        <h3>Summary Overview</h3>
        <div class="summary-grid">
            <div class="summary-row">
                <div class="summary-cell">
                    <h4>Principal</h4>
                    <div class="value">{{ number_format($summary['filtered']['principal'], 2) }}</div>
                </div>
                <div class="summary-cell">
                    <h4>Interest</h4>
                    <div class="value">{{ number_format($summary['filtered']['interest'], 2) }}</div>
                </div>
                <div class="summary-cell">
                    <h4>Penalty</h4>
                    <div class="value">{{ number_format($summary['filtered']['penalty'], 2) }}</div>
                </div>
                <div class="summary-cell">
                    <h4>Total</h4>
                    <div class="value">{{ number_format($summary['filtered']['total'], 2) }}</div>
                </div>
            </div>
        </div>
    </div>

    <h3>Collection Details</h3>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Inst#</th>
                <th>Loan#</th>
                <th>Client</th>
                <th>Group</th>
                <th>Center</th>
                <th>Officer</th>
                <th class="text-right">Principal</th>
                <th class="text-right">Interest</th>
                <th class="text-right">Penalty</th>
                <th class="text-right">Total</th>
                <th>Method</th>
            </tr>
        </thead>
        <tbody>
            @foreach($collections as $collection)
            <tr>
                <td>{{ $collection->paid_date ? $collection->paid_date : '' }}</td>
                <td class="text-center">{{ $collection->installment_number }}</td>
                <td>{{ $collection->loan->loan_number ?? 'N/A' }}</td>
                <td>{{ $collection->loan->client->first_name ?? '' }} {{ $collection->loan->client->last_name ?? '' }}</td>
                <td>{{ $collection->loan->group->group_name ?? 'N/A' }}</td>
                <td>{{ $collection->loan->groupCenter->center_name ?? 'N/A' }}</td>
                <td>{{ $collection->loan->collectionOfficer->full_name ?? 'N/A' }}</td>
                <td class="text-right">{{ number_format($collection->principal_paid, 2) }}</td>
                <td class="text-right">{{ number_format($collection->interest_paid, 2) }}</td>
                <td class="text-right">{{ number_format($collection->penalty_paid, 2) }}</td>
                <td class="text-right"><strong>{{ number_format($collection->total_paid, 2) }}</strong></td>
                <td>{{ ucfirst($collection->payment_method ?? 'N/A') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="7" class="text-right">TOTALS:</th>
                <th class="text-right">{{ number_format($collections->sum('principal_paid'), 2) }}</th>
                <th class="text-right">{{ number_format($collections->sum('interest_paid'), 2) }}</th>
                <th class="text-right">{{ number_format($collections->sum('penalty_paid'), 2) }}</th>
                <th class="text-right">{{ number_format($collections->sum('total_paid'), 2) }}</th>
                <th></th>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>This is a computer-generated report. No signature is required.</p>
        <p>&copy; {{ now()->year }} Loan Management System. All rights reserved.</p>
    </div>
</body>
</html>