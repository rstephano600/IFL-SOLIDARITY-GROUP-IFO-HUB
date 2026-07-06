<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Loan Report - {{ now()->format('d/m/Y') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .summary-section {
            margin-bottom: 30px;
        }
        .summary-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .summary-row {
            display: table-row;
        }
        .summary-cell {
            display: table-cell;
            width: 50%;
            padding: 5px;
        }
        .summary-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .summary-box h3 {
            margin: 0 0 5px 0;
            font-size: 14px;
            color: #666;
        }
        .summary-box .value {
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th {
            background-color: #333;
            color: white;
            padding: 10px 5px;
            text-align: left;
            font-size: 11px;
        }
        table td {
            padding: 8px 5px;
            border-bottom: 1px solid #ddd;
            font-size: 10px;
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
        .text-success {
            color: #28a745;
        }
        .text-danger {
            color: #dc3545;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-success {
            background-color: #28a745;
            color: white;
        }
        .badge-warning {
            background-color: #ffc107;
            color: #333;
        }
        .badge-secondary {
            background-color: #6c757d;
            color: white;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .detail-summary {
            margin: 20px 0;
        }
        .detail-table {
            width: 100%;
        }
        .detail-table td {
            padding: 5px 10px;
            border: none;
        }
        .detail-table tr:nth-child(odd) {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Loan Report</h1>
        <p>Generated on {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>Total Records: {{ $loans->count() }}</p>
    </div>

    <div class="summary-section">
        <h2>Summary Overview</h2>
        <div class="summary-grid">
            <div class="summary-row">
                <div class="summary-cell">
                    <div class="summary-box">
                        <h3>Total Loans</h3>
                        <div class="value">{{ number_format($summary['total_loans']) }}</div>
                    </div>
                </div>
                <div class="summary-cell">
                    <div class="summary-box">
                        <h3>Total Disbursed</h3>
                        <div class="value">{{ number_format($summary['total_disbursed'], 2) }}</div>
                    </div>
                </div>
            </div>
            <div class="summary-row">
                <div class="summary-cell">
                    <div class="summary-box">
                        <h3>Total Outstanding</h3>
                        <div class="value">{{ number_format($summary['total_outstanding'], 2) }}</div>
                    </div>
                </div>
                <div class="summary-cell">
                    <div class="summary-box">
                        <h3>Total Profit</h3>
                        <div class="value" style="color: #28a745;">{{ number_format($summary['total_profit'], 2) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="detail-summary">
            <h3>Financial Details</h3>
            <table class="detail-table">
                <tr>
                    <td><strong>Total Interest:</strong></td>
                    <td class="text-right">{{ number_format($summary['total_interest'], 2) }}</td>
                    <td><strong>Total Fees:</strong></td>
                    <td class="text-right">{{ number_format($summary['total_fees'], 2) }}</td>
                </tr>
                <tr>
                    <td><strong>Total Repayable:</strong></td>
                    <td class="text-right">{{ number_format($summary['total_repayable'], 2) }}</td>
                    <td><strong>Total Paid:</strong></td>
                    <td class="text-right">{{ number_format($summary['total_paid'], 2) }}</td>
                </tr>
                <tr>
                    <td><strong>Membership Fees:</strong></td>
                    <td class="text-right">{{ number_format($summary['fees_paid']['membership'], 2) }}</td>
                    <td><strong>Insurance Fees:</strong></td>
                    <td class="text-right">{{ number_format($summary['fees_paid']['insurance'], 2) }}</td>
                </tr>
                <tr>
                    <td><strong>Penalty Fees:</strong></td>
                    <td class="text-right">{{ number_format($summary['fees_paid']['penalty'], 2) }}</td>
                    <td><strong>Other Fees:</strong></td>
                    <td class="text-right">{{ number_format($summary['fees_paid']['other'], 2) }}</td>
                </tr>
            </table>
        </div>
    </div>

    <h2>Loan Details</h2>
    <table>
        <thead>
            <tr>
                <th>Loan #</th>
                <th>Center</th>
                <th>Group</th>
                <th>Client</th>
                <th>Officer</th>
                <th>Disb. Date</th>
                <th>Status</th>
                <th class="text-right">Disbursed</th>
                <th class="text-right">Interest</th>
                <th class="text-right">Paid</th>
                <th class="text-right">Outstanding</th>
                <th class="text-right">Profit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($loans as $loan)
            <tr>
                <td>{{ $loan->loan_number }}</td>
                <td>{{ $loan->groupCenter->center_name ?? 'N/A' }}</td>
                <td>{{ $loan->group->group_name ?? 'N/A' }}</td>
                <td>{{ $loan->client->first_name ?? '' }} {{ $loan->client->last_name ?? '' }}</td>
                <td>{{ $loan->collectionOfficer->full_name ?? 'N/A' }}</td>
                <td>{{ $loan->disbursement_date ? $loan->disbursement_date->format('d/m/Y') : '' }}</td>
                <td>
                    <span class="badge badge-{{ $loan->status == 'active' ? 'success' : ($loan->status == 'pending' ? 'warning' : 'secondary') }}">
                        {{ ucfirst($loan->status) }}
                    </span>
                </td>
                <td class="text-right">{{ number_format($loan->amount_disbursed, 2) }}</td>
                <td class="text-right">{{ number_format($loan->interest_amount, 2) }}</td>
                <td class="text-right">{{ number_format($loan->amount_paid, 2) }}</td>
                <td class="text-right">{{ number_format($loan->outstanding_balance, 2) }}</td>
                <td class="text-right {{ $loan->profit_loss_amount >= 0 ? 'text-success' : 'text-danger' }}">
                    {{ number_format($loan->profit_loss_amount, 2) }}
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #e9ecef; font-weight: bold;">
                <td colspan="7" class="text-right">TOTALS:</td>
                <td class="text-right">{{ number_format($summary['total_disbursed'], 2) }}</td>
                <td class="text-right">{{ number_format($summary['total_interest'], 2) }}</td>
                <td class="text-right">{{ number_format($summary['total_paid'], 2) }}</td>
                <td class="text-right">{{ number_format($summary['total_outstanding'], 2) }}</td>
                <td class="text-right {{ $summary['total_profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                    {{ number_format($summary['total_profit'], 2) }}
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>This is a computer-generated report. No signature is required.</p>
        <p>&copy; {{ now()->year }} Loan Management System. All rights reserved.</p>
    </div>
</body>
</html>