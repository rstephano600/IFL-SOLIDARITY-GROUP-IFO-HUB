<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Loans Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
            color: #333;
            padding: 15px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #4472C4;
            padding-bottom: 10px;
        }
        
        .header h1 {
            color: #4472C4;
            font-size: 20px;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 10px;
            color: #666;
        }
        
        .stats-container {
            margin-bottom: 15px;
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
        }
        
        .stats-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        
        .stats-row {
            display: table-row;
        }
        
        .stats-item {
            display: table-cell;
            padding: 5px;
            width: 20%;
            vertical-align: top;
        }
        
        .stats-label {
            font-size: 8px;
            color: #666;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        
        .stats-value {
            font-size: 11px;
            font-weight: bold;
            color: #333;
        }
        
        .stats-value.positive {
            color: #28a745;
        }
        
        .stats-value.negative {
            color: #dc3545;
        }
        
        .filters {
            margin-bottom: 15px;
            padding: 8px;
            background: #e9ecef;
            border-radius: 3px;
            font-size: 8px;
        }
        
        .filters strong {
            color: #495057;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        table thead {
            background: #4472C4;
            color: white;
        }
        
        table th {
            padding: 6px 4px;
            text-align: left;
            font-size: 8px;
            font-weight: bold;
            border: 1px solid #dee2e6;
        }
        
        table td {
            padding: 5px 4px;
            border: 1px solid #dee2e6;
            font-size: 8px;
        }
        
        table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        table tbody tr:hover {
            background: #e9ecef;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .badge {
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
            display: inline-block;
        }
        
        .badge-pending { background: #fff3cd; color: #856404; }
        .badge-approved { background: #d1ecf1; color: #0c5460; }
        .badge-disbursed { background: #cce5ff; color: #004085; }
        .badge-completed { background: #d4edda; color: #155724; }
        .badge-defaulted { background: #f8d7da; color: #721c24; }
        
        .footer {
            margin-top: 15px;
            text-align: center;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #dee2e6;
            padding-top: 8px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Loan Analysis Report</h1>
        <p>Generated on {{ date('F d, Y H:i:s') }}</p>
    </div>

    <!-- Statistics -->
    <div class="stats-container">
        <div class="stats-grid">
            <div class="stats-row">
                <div class="stats-item">
                    <div class="stats-label">Total Loans</div>
                    <div class="stats-value">{{ number_format($stats->total_loans ?? 0) }}</div>
                </div>
                <div class="stats-item">
                    <div class="stats-label">Requested Amount</div>
                    <div class="stats-value">TZS {{ number_format($stats->total_requested ?? 0, 2) }}</div>
                </div>
                <div class="stats-item">
                    <div class="stats-label">Disbursed Amount</div>
                    <div class="stats-value">TZS {{ number_format($stats->total_disbursed ?? 0, 2) }}</div>
                </div>
                <div class="stats-item">
                    <div class="stats-label">Interest Amount</div>
                    <div class="stats-value">TZS {{ number_format($stats->total_interest ?? 0, 2) }}</div>
                </div>
                <div class="stats-item">
                    <div class="stats-label">Loan Fees</div>
                    <div class="stats-value">TZS {{ number_format($stats->total_loan_fees ?? 0, 2) }}</div>
                </div>
            </div>
            <div class="stats-row">
                <div class="stats-item">
                    <div class="stats-label">Other Fees</div>
                    <div class="stats-value">TZS {{ number_format($stats->total_other_fees ?? 0, 2) }}</div>
                </div>
                <div class="stats-item">
                    <div class="stats-label">Preclosure Fees</div>
                    <div class="stats-value">TZS {{ number_format($stats->total_preclosure ?? 0, 2) }}</div>
                </div>
                <div class="stats-item">
                    <div class="stats-label">Total Paid</div>
                    <div class="stats-value">TZS {{ number_format($stats->total_paid ?? 0, 2) }}</div>
                </div>
                <div class="stats-item">
                    <div class="stats-label">Outstanding</div>
                    <div class="stats-value">TZS {{ number_format($stats->total_outstanding ?? 0, 2) }}</div>
                </div>
                <div class="stats-item">
                    <div class="stats-label">Profit/Loss</div>
                    <div class="stats-value {{ ($stats->total_profit_loss ?? 0) >= 0 ? 'positive' : 'negative' }}">
                        TZS {{ number_format($stats->total_profit_loss ?? 0, 2) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Applied -->
    @if($request->filled(['date_from', 'date_to', 'created_by', 'status']))
    <div class="filters">
        <strong>Filters Applied:</strong>
        @if($request->filled('date_from'))
            Date From: {{ $request->date_from }} |
        @endif
        @if($request->filled('date_to'))
            Date To: {{ $request->date_to }} |
        @endif
        @if($request->filled('created_by'))
            Created By: User #{{ $request->created_by }} |
        @endif
        @if($request->filled('status'))
            Status: {{ ucfirst($request->status) }}
        @endif
    </div>
    @endif

    <!-- Loans Table -->
    <table>
        <thead>
            <tr>
                <th style="width: 8%;">Loan #</th>
                <th style="width: 10%;">Client</th>
                <th class="text-right" style="width: 8%;">Requested</th>
                <th class="text-right" style="width: 8%;">Disbursed</th>
                <th class="text-right" style="width: 7%;">Interest</th>
                <th class="text-right" style="width: 7%;">Fees</th>
                <th class="text-right" style="width: 8%;">Paid</th>
                <th class="text-right" style="width: 8%;">Outstanding</th>
                <th class="text-right" style="width: 8%;">P/L</th>
                <th class="text-center" style="width: 8%;">Status</th>
                <th style="width: 10%;">Created By</th>
                <th class="text-center" style="width: 10%;">Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($loans as $loan)
            @php
                $totalFees = $loan->loan_fee + $loan->other_fee;
                $totalPaid = $loan->amount_paid + $loan->penalty_fee + $loan->total_preclosure;
                $profitLoss = $totalPaid - $loan->amount_disbursed;
            @endphp
            <tr>
                <td>{{ $loan->loan_number }}</td>
                <td>{{ $loan->client->first_name ?? 'N/A' }} {{ $loan->client->first_name ?? 'N/A' }}</td>
                <td class="text-right">{{ number_format($loan->amount_requested, 0) }}</td>
                <td class="text-right">{{ number_format($loan->amount_disbursed, 0) }}</td>
                <td class="text-right">{{ number_format($loan->interest_amount, 0) }}</td>
                <td class="text-right">{{ number_format($totalFees, 0) }}</td>
                <td class="text-right">{{ number_format($totalPaid, 0) }}</td>
                <td class="text-right">{{ number_format($loan->outstanding_balance, 0) }}</td>
                <td class="text-right" style="color: {{ $profitLoss >= 0 ? '#28a745' : '#dc3545' }};">
                    {{ number_format($profitLoss, 0) }}
                </td>
                <td class="text-center">
                    <span class="badge badge-{{ $loan->status }}">{{ ucfirst($loan->status) }}</span>
                </td>
                <td>{{ $loan->creator->username ?? 'N/A' }}</td>
                <td class="text-center">{{ $loan->created_at->format('M d, Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="12" class="text-center">No loans found</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>This is a computer-generated report. Total records: {{ $loans->count() }}</p>
    </div>
</body>
</html>