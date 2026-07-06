@extends('layouts.app')

@section('title', 'Loan Reports')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Loan Reports</h2>
                <div class="btn-group">
                    <button type="button" class="btn btn-success" onclick="exportExcel()">
                        <i class="fas fa-file-excel"></i> Excel
                    </button>
                    <button type="button" class="btn btn-danger" onclick="exportPdf()">
                        <i class="fas fa-file-pdf"></i> PDF
                    </button>
                    <button type="button" class="btn btn-info" onclick="printReport()">
                        <i class="fas fa-print"></i> Print
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-filter"></i> Filters
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reports.loans.index') }}" id="filterForm">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="group_center_id">Group Center</label>
                        <select name="group_center_id" id="group_center_id" class="form-control">
                            <option value="">All Centers</option>
                            @foreach($groupCenters as $center)
                                <option value="{{ $center->id }}" {{ request('group_center_id') == $center->id ? 'selected' : '' }}>
                                    {{ $center->center_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="group_id">Group</label>
                        <select name="group_id" id="group_id" class="form-control">
                            <option value="">All Groups</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}" {{ request('group_id') == $group->id ? 'selected' : '' }}>
                                    {{ $group->group_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="client_id">Client</label>
                        <select name="client_id" id="client_id" class="form-control">
                            <option value="">All Clients</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->first_name }} {{ $client->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="collection_officer_id">Collection Officer</label>
                        <select name="collection_officer_id" id="collection_officer_id" class="form-control">
                            <option value="">All Officers</option>
                            @foreach($officers as $officer)
                                <option value="{{ $officer->id }}" {{ request('collection_officer_id') == $officer->id ? 'selected' : '' }}>
                                    {{ $officer->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="defaulted" {{ request('status') == 'defaulted' ? 'selected' : '' }}>Defaulted</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="disbursement_date_from">Disbursement From</label>
                        <input type="date" name="disbursement_date_from" id="disbursement_date_from" class="form-control" value="{{ request('disbursement_date_from') }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="disbursement_date_to">Disbursement To</label>
                        <input type="date" name="disbursement_date_to" id="disbursement_date_to" class="form-control" value="{{ request('disbursement_date_to') }}">
                    </div>

                    <div class="col-md-3 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary mr-2">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="{{ route('reports.loans.index') }}" class="btn btn-secondary">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Loans</h6>
                    <h3 class="mb-0">{{ number_format($summary['total_loans']) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Disbursed</h6>
                    <h3 class="mb-0">{{ number_format($summary['total_disbursed'], 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Outstanding</h6>
                    <h3 class="mb-0">{{ number_format($summary['total_outstanding'], 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Profit</h6>
                    <h3 class="mb-0">{{ number_format($summary['total_profit'], 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Summary -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Detailed Summary</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-sm">
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
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Membership Fees Paid:</strong></td>
                            <td class="text-right">{{ number_format($summary['fees_paid']['membership'], 2) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Insurance Fees Paid:</strong></td>
                            <td class="text-right">{{ number_format($summary['fees_paid']['insurance'], 2) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Penalty Fees Paid:</strong></td>
                            <td class="text-right">{{ number_format($summary['fees_paid']['penalty'], 2) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Amount With Preclosure:</strong></td>
                            <td class="text-right">{{ number_format($summary['amount_with_preclosure'], 2) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Amount Refunded:</strong></td>
                            <td class="text-right">{{ number_format($summary['amount_with_refund'], 2) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Other Fees Paid:</strong></td>
                            <td class="text-right">{{ number_format($summary['fees_paid']['other'], 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Trending Graph -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-chart-line"></i> Loan Trends
            </h5>
        </div>
        <div class="card-body">
            <canvas id="loanTrendChart" height="80"></canvas>
        </div>
    </div>

    <!-- Loans Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Loan Details</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Loan #</th>
                            <th>Center</th>
                            <th>Group</th>
                            <th>Client</th>
                            <th>Officer</th>
                            <th>Disbursement Date</th>
                            <th>Status</th>
                            <th>Disbursed</th>
                            <th>Interest</th>
                            <th>Paid</th>
                            <th>Outstanding</th>
                            <th>Profit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($loans as $loan)
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
                        @empty
                        <tr>
                            <td colspan="12" class="text-center">No loans found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $loans->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
// Cascading dropdowns
$('#group_center_id').change(function() {
    const centerId = $(this).val();
    if (centerId) {
        $.get(`/api/groups-by-center/${centerId}`, function(groups) {
            $('#group_id').html('<option value="">All Groups</option>');
            groups.forEach(group => {
                $('#group_id').append(`<option value="${group.id}">${group.group_name}</option>`);
            });
        });
    } else {
        $('#group_id').html('<option value="">All Groups</option>');
    }
    $('#client_id').html('<option value="">All Clients</option>');
});

$('#group_id').change(function() {
    const groupId = $(this).val();
    if (groupId) {
        $.get(`/api/clients-by-group/${groupId}`, function(clients) {
            $('#client_id').html('<option value="">All Clients</option>');
            clients.forEach(client => {
                $('#client_id').append(`<option value="${client.id}">${client.first_name} ${client.last_name}</option>`);
            });
        });
    } else {
        $('#client_id').html('<option value="">All Clients</option>');
    }
});

// Load trending chart
function loadTrendChart() {
    const params = new URLSearchParams(window.location.search);
    
    $.get(`{{ route('reports.loans.trending') }}?${params.toString()}`, function(data) {
        const ctx = document.getElementById('loanTrendChart').getContext('2d');
        
        // Destroy existing chart if it exists
        if (window.loanChart) {
            window.loanChart.destroy();
        }
        
        window.loanChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.map(item => item.month),
                datasets: [
                    {
                        label: 'Disbursed Amount',
                        data: data.map(item => item.disbursed),
                        borderColor: 'rgb(54, 162, 235)',
                        backgroundColor: 'rgba(54, 162, 235, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Amount Paid',
                        data: data.map(item => item.paid),
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Interest Earned',
                        data: data.map(item => item.interest),
                        borderColor: 'rgb(255, 159, 64)',
                        backgroundColor: 'rgba(255, 159, 64, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Profit',
                        data: data.map(item => item.profit),
                        borderColor: 'rgb(76, 175, 80)',
                        backgroundColor: 'rgba(76, 175, 80, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 15
                        }
                    },
                    title: {
                        display: true,
                        text: 'Loan Performance Trends Over Time',
                        font: {
                            size: 16,
                            weight: 'bold'
                        },
                        padding: {
                            top: 10,
                            bottom: 20
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14
                        },
                        bodyFont: {
                            size: 13
                        },
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += new Intl.NumberFormat('en-US', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }).format(context.parsed.y) + ' TZS';
                                return label;
                            },
                            footer: function(tooltipItems) {
                                if (tooltipItems.length > 0) {
                                    const index = tooltipItems[0].dataIndex;
                                    return 'Loans: ' + data[index].loan_count;
                                }
                                return '';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            font: {
                                size: 11
                            },
                            callback: function(value) {
                                if (value >= 1000000) {
                                    return (value / 1000000).toFixed(1) + 'M';
                                } else if (value >= 1000) {
                                    return (value / 1000).toFixed(0) + 'K';
                                }
                                return value.toFixed(0);
                            }
                        }
                    }
                }
            }
        });
    }).fail(function(xhr, status, error) {
        console.error('Error loading chart data:', error);
        $('#loanTrendChart').parent().html(
            '<div class="alert alert-warning">Unable to load chart data. Please try again later.</div>'
        );
    });
}

// Export functions
function exportExcel() {
    const params = new URLSearchParams(window.location.search);
    const btn = event.target.closest('button');
    const originalHtml = btn.innerHTML;
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Exporting...';
    
    window.location.href = `{{ route('reports.loans.export.excel') }}?${params.toString()}`;
    
    setTimeout(() => {
        btn.disabled = false;
        btn.innerHTML = originalHtml;
    }, 2000);
}

function exportPdf() {
    const params = new URLSearchParams(window.location.search);
    const btn = event.target.closest('button');
    const originalHtml = btn.innerHTML;
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating...';
    
    window.location.href = `{{ route('reports.loans.export.pdf') }}?${params.toString()}`;
    
    setTimeout(() => {
        btn.disabled = false;
        btn.innerHTML = originalHtml;
    }, 2000);
}

function printReport() {
    const params = new URLSearchParams(window.location.search);
    window.open(`{{ route('reports.loans.print') }}?${params.toString()}`, '_blank');
}

// Load chart on page load
$(document).ready(function() {
    loadTrendChart();
    
    // Add loading animation to filter button
    $('#filterForm').on('submit', function() {
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Filtering...').prop('disabled', true);
    });
    
    // Initialize tooltips if Bootstrap tooltip is available
    if (typeof $().tooltip === 'function') {
        $('[data-toggle="tooltip"]').tooltip();
    }
});
</script>
@endpush