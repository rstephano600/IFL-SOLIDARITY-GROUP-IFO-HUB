<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Collection Summary - {{ now()->format('d/m/Y') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
            font-size: 11px;
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
            text-align: center;
        }
        .summary-card h6 {
            font-size: 11px;
            margin-bottom: 5px;
            color: #666;
            text-transform: uppercase;
        }
        .summary-card .value {
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }
        table {
            font-size: 10px;
        }
        table th {
            background-color: #343a40 !important;
            color: white !important;
        }
        .footer-print {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
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
            <h1>COLLECTION SUMMARY REPORT</h1>
            <p class="mb-0">Generated on {{ now()->format('d/m/Y H:i:s') }}</p>
            <p class="mb-0"><strong>Total Records:</strong> {{ $collections->count() }}</p>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-3 col-6">
                <div class="summary-card">
                    <h6>Total Collections</h6>
                    <div class="value text-primary">{{ number_format($summary['filtered']['total'], 2) }}</div>
                    <small class="text-muted">{{ $summary['filtered']['count'] }} payments</small>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="summary-card">
                    <h6>Principal Collected</h6>
                    <div class="value text-success">{{ number_format($summary['filtered']['principal'], 2) }}</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="summary-card">
                    <h6>Interest Collected</h6>
                    <div class="value text-info">{{ number_format($summary['filtered']['interest'], 2) }}</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="summary-card">
                    <h6>Penalty Collected</h6>
                    <div class="value text-warning">{{ number_format($summary['filtered']['penalty'], 2) }}</div>
                </div>
            </div>
        </div>

        <!-- Period Summary -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-dark text-white">
                        <h6 class="mb-0">Summary by Period</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Period</th>
                                    <th class="text-center">Collections</th>
                                    <th class="text-right">Principal</th>
                                    <th class="text-right">Interest</th>
                                    <th class="text-right">Penalty</th>
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Today</strong></td>
                                    <td class="text-center">{{ $summary['today']['count'] }}</td>
                                    <td class="text-right">{{ number_format($summary['today']['principal'], 2) }}</td>
                                    <td class="text-right">{{ number_format($summary['today']['interest'], 2) }}</td>
                                    <td class="text-right">{{ number_format($summary['today']['penalty'], 2) }}</td>
                                    <td class="text-right"><strong>{{ number_format($summary['today']['total'], 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>This Week</strong></td>
                                    <td class="text-center">{{ $summary['week']['count'] }}</td>
                                    <td class="text-right">{{ number_format($summary['week']['principal'], 2) }}</td>
                                    <td class="text-right">{{ number_format($summary['week']['interest'], 2) }}</td>
                                    <td class="text-right">{{ number_format($summary['week']['penalty'], 2) }}</td>
                                    <td class="text-right"><strong>{{ number_format($summary['week']['total'], 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>This Month</strong></td>
                                    <td class="text-center">{{ $summary['month']['count'] }}</td>
                                    <td class="text-right">{{ number_format($summary['month']['principal'], 2) }}</td>
                                    <td class="text-right">{{ number_format($summary['month']['interest'], 2) }}</td>
                                    <td class="text-right">{{ number_format($summary['month']['penalty'], 2) }}</td>
                                    <td class="text-right"><strong>{{ number_format($summary['month']['total'], 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>This Year</strong></td>
                                    <td class="text-center">{{ $summary['year']['count'] }}</td>
                                    <td class="text-right">{{ number_format($summary['year']['principal'], 2) }}</td>
                                    <td class="text-right">{{ number_format($summary['year']['interest'], 2) }}</td>
                                    <td class="text-right">{{ number_format($summary['year']['penalty'], 2) }}</td>
                                    <td class="text-right"><strong>{{ number_format($summary['year']['total'], 2) }}</strong></td>
                                </tr>
                                <tr class="bg-light">
                                    <td><strong>GRAND TOTAL</strong></td>
                                    <td class="text-center"><strong>{{ $summary['total']['count'] }}</strong></td>
                                    <td class="text-right"><strong>{{ number_format($summary['total']['principal'], 2) }}</strong></td>
                                    <td class="text-right"><strong>{{ number_format($summary['total']['interest'], 2) }}</strong></td>
                                    <td class="text-right"><strong>{{ number_format($summary['total']['penalty'], 2) }}</strong></td>
                                    <td class="text-right"><strong>{{ number_format($summary['total']['total'], 2) }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Collection Details -->
        <h5 class="mb-3">Collection Details</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm">
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
                        <td>{{ $collection->paid_date ? $collection->paid_date->format('d/m/Y') : '' }}</td>
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
                <tfoot class="bg-light">
                    <tr>
                        <th colspan="7" class="text-right">PAGE TOTALS:</th>
                        <th class="text-right">{{ number_format($collections->sum('principal_paid'), 2) }}</th>
                        <th class="text-right">{{ number_format($collections->sum('interest_paid'), 2) }}</th>
                        <th class="text-right">{{ number_format($collections->sum('penalty_paid'), 2) }}</th>
                        <th class="text-right">{{ number_format($collections->sum('total_paid'), 2) }}</th>
                        <th></th>
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