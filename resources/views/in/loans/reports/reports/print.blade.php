<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Loan Report - {{ now()->format('d/m/Y') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            @page {
                margin: 1cm;
            }
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
        }
        body {
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #333;
            padding-bottom: 15px;
        }
        .summary-card {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
            background: #f8f9fa;
        }
        .summary-card h4 {
            font-size: 14px;
            margin-bottom: 5px;
            color: #666;
        }
        .summary-card .value {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        table {
            font-size: 11px;
        }
        table th {
            background-color: #343a40 !important;
            color: white !important;
        }
        .footer-print {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Print Button -->
        <div class="no-print mb-3 text-right">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print"></i> Print Report
            </button>
            <button onclick="window.close()" class="btn btn-secondary">
                <i class="fas fa-times"></i> Close
            </button>
        </div>

        <!-- Header -->
        <div class="header">
            <h1>LOAN REPORT</h1>
            <p class="mb-0">Generated on {{ now()->format('d/m/Y H:i:s') }}</p>
            <p class="mb-0"><strong>Total Records:</strong> {{ $loans->count() }}</p>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-3 col-6">
                <div class="summary-card text-center">
                    <h4>Total Loans</h4>
                    <div class="value text-primary">{{ number_format($summary['total_loans']) }}</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="summary-card text-center">
                    <h4>Total Disbursed</h4>
                    <div class="value text-info">{{ number_format($summary['total_disbursed'], 2) }}</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="summary-card text-center">
                    <h4>Total Outstanding</h4>
                    <div class="value text-warning">{{ number_format($summary['total_outstanding'], 2) }}</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="summary-card text-center">
                    <h4>Total Profit</h4>
                    <div class="value text-success">{{ number_format($summary['total_profit'], 2) }}</div>
                </div>
            </div>
        </div>

        <!-- Financial Details -->
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Financial Summary</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-sm table-bordered">
                            <tr>
                                <td><strong>Total Interest:</strong></td>
                                <td class="text-right">{{ number_format($summary['total_interest'], 2) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Total Fees:</strong></td>
                                <td class="text-right">{{ number_format($summary['total_fees'], 2) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Total Repayable:</strong></td>
                                <td class="text-right">{{ number_format($summary['total_repayable'], 2) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Total Paid:</strong></td>
                                <td class="text-right">{{ number_format($summary['total_paid'], 2) }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-bordered">
                            <tr>
                                <td><strong>Membership Fees:</strong></td>
                                <td class="text-right">{{ number_format($summary['fees_paid']['membership'], 2) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Insurance Fees:</strong></td>
                                <td class="text-right">{{ number_format($summary['fees_paid']['insurance'], 2) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Penalty Fees:</strong></td>
                                <td class="text-right">{{ number_format($summary['fees_paid']['penalty'], 2) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Other Fees:</strong></td>
                                <td class="text-right">{{ number_format($summary['fees_paid']['other'], 2) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loan Details Table -->
        <h4 class="mb-3">Loan Details</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm">
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
                            <strong>{{ number_format($loan->profit_loss_amount, 2) }}</strong>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-light">
                    <tr>
                        <th colspan="7" class="text-right">TOTALS:</th>
                        <th class="text-right">{{ number_format($summary['total_disbursed'], 2) }}</th>
                        <th class="text-right">{{ number_format($summary['total_interest'], 2) }}</th>
                        <th class="text-right">{{ number_format($summary['total_paid'], 2) }}</th>
                        <th class="text-right">{{ number_format($summary['total_outstanding'], 2) }}</th>
                        <th class="text-right {{ $summary['total_profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ number_format($summary['total_profit'], 2) }}
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer-print">
            <p class="mb-1">This is a computer-generated report. No signature is required.</p>
            <p class="mb-0"><strong>&copy; {{ now()->year }} Loan Management System.</strong> All rights reserved.</p>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>