@extends('layouts.app')

@section('title', 'Collection Summary')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">
                    <i class="fas fa-money-bill-wave"></i> Collection Summary
                </h2>
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

    <!-- Quick Summary Cards -->
    <div class="row mb-4">
        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="card border-left-primary shadow h-100">
                <div class="card-body py-3">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Today</div>
                    <div class="h6 mb-0 font-weight-bold text-gray-800">
                        {{ number_format($summary['today']['total'], 2) }}
                    </div>
                    <small class="text-muted">{{ $summary['today']['count'] }} collections</small>
                </div>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="card border-left-success shadow h-100">
                <div class="card-body py-3">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">This Week</div>
                    <div class="h6 mb-0 font-weight-bold text-gray-800">
                        {{ number_format($summary['week']['total'], 2) }}
                    </div>
                    <small class="text-muted">{{ $summary['week']['count'] }} collections</small>
                </div>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="card border-left-info shadow h-100">
                <div class="card-body py-3">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">This Month</div>
                    <div class="h6 mb-0 font-weight-bold text-gray-800">
                        {{ number_format($summary['month']['total'], 2) }}
                    </div>
                    <small class="text-muted">{{ $summary['month']['count'] }} collections</small>
                </div>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="card border-left-warning shadow h-100">
                <div class="card-body py-3">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">This Year</div>
                    <div class="h6 mb-0 font-weight-bold text-gray-800">
                        {{ number_format($summary['year']['total'], 2) }}
                    </div>
                    <small class="text-muted">{{ $summary['year']['count'] }} collections</small>
                </div>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="card border-left-dark shadow h-100">
                <div class="card-body py-3">
                    <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">Total</div>
                    <div class="h6 mb-0 font-weight-bold text-gray-800">
                        {{ number_format($summary['total']['total'], 2) }}
                    </div>
                    <small class="text-muted">{{ $summary['total']['count'] }} collections</small>
                </div>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="card border-left-secondary shadow h-100">
                <div class="card-body py-3">
                    <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Filtered</div>
                    <div class="h6 mb-0 font-weight-bold text-gray-800">
                        {{ number_format($summary['filtered']['total'], 2) }}
                    </div>
                    <small class="text-muted">{{ $summary['filtered']['count'] }} collections</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter"></i> Filters & Search
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('loans.collections.summary.index') }}" id="filterForm">
                <div class="row">
                    <!-- Quick Date Filter -->
                    <div class="col-md-3 mb-3">
                        <label for="date_filter">Quick Date Filter</label>
                        <select name="date_filter" id="date_filter" class="form-control">
                            <option value="">Custom Range</option>
                            <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Today</option>
                            <option value="yesterday" {{ request('date_filter') == 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                            <option value="this_week" {{ request('date_filter') == 'this_week' ? 'selected' : '' }}>This Week</option>
                            <option value="last_week" {{ request('date_filter') == 'last_week' ? 'selected' : '' }}>Last Week</option>
                            <option value="this_month" {{ request('date_filter') == 'this_month' ? 'selected' : '' }}>This Month</option>
                            <option value="last_month" {{ request('date_filter') == 'last_month' ? 'selected' : '' }}>Last Month</option>
                            <option value="this_year" {{ request('date_filter') == 'this_year' ? 'selected' : '' }}>This Year</option>
                        </select>
                    </div>

                    <!-- Date From -->
                    <div class="col-md-3 mb-3">
                        <label for="paid_date_from">Paid Date From</label>
                        <input type="date" name="paid_date_from" id="paid_date_from" class="form-control" value="{{ request('paid_date_from') }}">
                    </div>

                    <!-- Date To -->
                    <div class="col-md-3 mb-3">
                        <label for="paid_date_to">Paid Date To</label>
                        <input type="date" name="paid_date_to" id="paid_date_to" class="form-control" value="{{ request('paid_date_to') }}">
                    </div>

                    <!-- Search -->
                    <div class="col-md-3 mb-3">
                        <label for="search">Search</label>
                        <input type="text" name="search" id="search" class="form-control" placeholder="Loan#, Client, Group..." value="{{ request('search') }}">
                    </div>

                    <!-- Group Center -->
                    <div class="col-md-3 mb-3">
                        <label for="group_center_id">Group Center</label>
                        <select name="group_center_id" id="group_center_id" class="form-control">
                            <option value="">All Centers</option>
                            @foreach($filterData['groupCenters'] as $center)
                                <option value="{{ $center->id }}" {{ request('group_center_id') == $center->id ? 'selected' : '' }}>
                                    {{ $center->center_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Group -->
                    <div class="col-md-3 mb-3">
                        <label for="group_id">Group</label>
                        <select name="group_id" id="group_id" class="form-control">
                            <option value="">All Groups</option>
                            @foreach($filterData['groups'] as $group)
                                <option value="{{ $group->id }}" {{ request('group_id') == $group->id ? 'selected' : '' }}>
                                    {{ $group->group_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Client -->
                    <div class="col-md-3 mb-3">
                        <label for="client_id">Client</label>
                        <select name="client_id" id="client_id" class="form-control">
                            <option value="">All Clients</option>
                            @foreach($filterData['clients'] as $client)
                                <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->first_name }} {{ $client->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Loan -->
                    <div class="col-md-3 mb-3">
                        <label for="loan_id">Loan Number</label>
                        <select name="loan_id" id="loan_id" class="form-control">
                            <option value="">All Loans</option>
                            @foreach($filterData['loans'] as $loan)
                                <option value="{{ $loan->id }}" {{ request('loan_id') == $loan->id ? 'selected' : '' }}>
                                    {{ $loan->loan_number }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Collection Officer -->
                    <div class="col-md-3 mb-3">
                        <label for="collection_officer_id">Collection Officer</label>
                        <select name="collection_officer_id" id="collection_officer_id" class="form-control">
                            <option value="">All Officers</option>
                            @foreach($filterData['officers'] as $officer)
                                <option value="{{ $officer->id }}" {{ request('collection_officer_id') == $officer->id ? 'selected' : '' }}>
                                    {{ $officer->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Payment Method -->
                    <div class="col-md-3 mb-3">
                        <label for="payment_method">Payment Method</label>
                        <select name="payment_method" id="payment_method" class="form-control">
                            <option value="">All Methods</option>
                            <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="mobile_money" {{ request('payment_method') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                            <option value="check" {{ request('payment_method') == 'check' ? 'selected' : '' }}>Check</option>
                        </select>
                    </div>

                    <!-- Loan Status -->
                    <div class="col-md-3 mb-3">
                        <label for="status">Loan Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="defaulted" {{ request('status') == 'defaulted' ? 'selected' : '' }}>Defaulted</option>
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div class="col-md-3 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary mr-2">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="{{ route('loans.collections.summary.index') }}" class="btn btn-secondary">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Detailed Summary Breakdown -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Filtered Results Breakdown</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-2">
                                <small class="text-muted">Principal Collected</small>
                                <h5 class="text-success">{{ number_format($summary['filtered']['principal'], 2) }}</h5>
                            </div>
                        </div>
                        <!-- <div class="col-md-3">
                            <div class="mb-2">
                                <small class="text-muted">Interest Collected</small>
                                <h5 class="text-info">{{ number_format($summary['filtered']['interest'], 2) }}</h5>
                            </div>
                        </div> -->
                        <div class="col-md-3">
                            <div class="mb-2">
                                <small class="text-muted">Penalty Collected</small>
                                <h5 class="text-warning">{{ number_format($summary['filtered']['penalty'], 2) }}</h5>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-2">
                                <small class="text-muted">Total Collections</small>
                                <h5 class="text-primary">{{ number_format($summary['filtered']['total'], 2) }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Trending Graph -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-chart-line"></i> Collection Trends
            </h6>
        </div>
        <div class="card-body">
            <canvas id="collectionTrendChart" height="80"></canvas>
        </div>
    </div>

   <!-- Collections Table -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Collection Details</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="collectionsTable">
                    <thead class="thead-light">
                        <tr>
                            <th>Date</th>
                            <th>Inst#</th>
                            <th>Loan Number</th>
                            <th>Client</th>
                            <th>Group</th>
                            <th>Center</th>
                            <th>Officer</th>
                            <th class="text-right">Principal</th>
                            <!-- <th class="text-right">Interest</th> -->
                            <th class="text-right">Penalty</th>
                            <th class="text-right">Total</th>
                            <th>Method</th>
                            <th>Paid By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($collections as $collection)
                        <tr>
                            <td>
                                @if($collection->paid_date)
                                    {{ \Carbon\Carbon::parse($collection->paid_date)->format('d/m/Y') }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="text-center">{{ $collection->installment_number ?? '-' }}</td>
                            <td>{{ optional($collection->loan)->loan_number ?? 'N/A' }}</td>
                            <td>
                                @if($collection->loan && $collection->loan->client)
                                    {{ $collection->loan->client->first_name }} {{ $collection->loan->client->last_name }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ optional(optional($collection->loan)->group)->group_name ?? 'N/A' }}</td>
                            <td>{{ optional(optional($collection->loan)->groupCenter)->center_name ?? 'N/A' }}</td>
                            <td>{{ optional(optional($collection->loan)->collectionOfficer)->full_name ?? 'N/A' }}</td>
                            <td class="text-right">{{ number_format($collection->principal_paid ?? 0, 2) }}</td>
                            <!-- <td class="text-right">{{ number_format($collection->interest_paid ?? 0, 2) }}</td> -->
                            <td class="text-right">{{ number_format($collection->penalty_paid ?? 0, 2) }}</td>
                            <td class="text-right font-weight-bold">{{ number_format($collection->total_paid ?? 0, 2) }}</td>
                            <td>
                                <span class="badge badge-info">{{ ucfirst($collection->payment_method ?? 'cash') }}</span>
                            </td>
                            <td>{{ optional($collection->payer)->full_name ?? 'System' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="13" class="text-center text-muted py-4">
                                <i class="fas fa-info-circle"></i> No collections found matching your filters
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-light">
                        <tr>
                            <th colspan="7" class="text-right">Page Totals:</th>
                            <th class="text-right">{{ number_format($collections->sum('principal_paid'), 2) }}</th>
                            <!-- <th class="text-right">{{ number_format($collections->sum('interest_paid'), 2) }}</th> -->
                            <th class="text-right">{{ number_format($collections->sum('penalty_paid'), 2) }}</th>
                            <th class="text-right">{{ number_format($collections->sum('total_paid'), 2) }}</th>
                            <th colspan="2"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $collections->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.border-left-primary {
    border-left: 4px solid #4e73df !important;
}
.border-left-success {
    border-left: 4px solid #1cc88a !important;
}
.border-left-info {
    border-left: 4px solid #36b9cc !important;
}
.border-left-warning {
    border-left: 4px solid #f6c23e !important;
}
.border-left-dark {
    border-left: 4px solid #5a5c69 !important;
}
.border-left-secondary {
    border-left: 4px solid #858796 !important;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize chart
    loadTrendChart();
    
    // Cascading dropdowns
    initCascadingDropdowns();
    
    // Quick date filter changes
    $('#date_filter').change(function() {
        if ($(this).val()) {
            $('#paid_date_from, #paid_date_to').val('').prop('disabled', true);
        } else {
            $('#paid_date_from, #paid_date_to').prop('disabled', false);
        }
    });
    
    // Trigger on page load
    if ($('#date_filter').val()) {
        $('#paid_date_from, #paid_date_to').prop('disabled', true);
    }
});

// Cascading Dropdowns
function initCascadingDropdowns() {
    $('#group_center_id').change(function() {
        const centerId = $(this).val();
        if (centerId) {
            $.get(`/loans/collections/api/groups-by-center/${centerId}`, function(groups) {
                $('#group_id').html('<option value="">All Groups</option>');
                groups.forEach(group => {
                    $('#group_id').append(`<option value="${group.id}">${group.group_name}</option>`);
                });
            }).fail(function() {
                console.error('Failed to load groups');
            });
        } else {
            $('#group_id').html('<option value="">All Groups</option>');
        }
        $('#client_id').html('<option value="">All Clients</option>');
        $('#loan_id').html('<option value="">All Loans</option>');
    });

    $('#group_id').change(function() {
        const groupId = $(this).val();
        if (groupId) {
            $.get(`/loans/collections/api/clients-by-group/${groupId}`, function(clients) {
                $('#client_id').html('<option value="">All Clients</option>');
                clients.forEach(client => {
                    $('#client_id').append(`<option value="${client.id}">${client.first_name} ${client.last_name}</option>`);
                });
            }).fail(function() {
                console.error('Failed to load clients');
            });
        } else {
            $('#client_id').html('<option value="">All Clients</option>');
        }
        $('#loan_id').html('<option value="">All Loans</option>');
    });

    $('#client_id').change(function() {
        const clientId = $(this).val();
        if (clientId) {
            $.get(`/loans/collections/api/loans-by-client/${clientId}`, function(loans) {
                $('#loan_id').html('<option value="">All Loans</option>');
                loans.forEach(loan => {
                    $('#loan_id').append(`<option value="${loan.id}">${loan.loan_number}</option>`);
                });
            }).fail(function() {
                console.error('Failed to load loans');
            });
        } else {
            $('#loan_id').html('<option value="">All Loans</option>');
        }
    });
}

// Load Trending Chart
function loadTrendChart() {
    const params = new URLSearchParams(window.location.search);
    
    $.get(`/loans/collections/summary/trending-data?${params.toString()}`, function(data) {
        const ctx = document.getElementById('collectionTrendChart').getContext('2d');
        
        if (window.collectionChart) {
            window.collectionChart.destroy();
        }
        
        window.collectionChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.map(item => item.month),
                datasets: [
                    {
                        label: 'Total Collections',
                        data: data.map(item => item.total),
                        borderColor: 'rgb(78, 115, 223)',
                        backgroundColor: 'rgba(78, 115, 223, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Principal',
                        data: data.map(item => item.principal),
                        borderColor: 'rgb(28, 200, 138)',
                        backgroundColor: 'rgba(28, 200, 138, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Interest',
                        data: data.map(item => item.interest),
                        borderColor: 'rgb(54, 185, 204)',
                        backgroundColor: 'rgba(54, 185, 204, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Penalty',
                        data: data.map(item => item.penalty),
                        borderColor: 'rgb(246, 194, 62)',
                        backgroundColor: 'rgba(246, 194, 62, 0.1)',
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
                        text: 'Collection Trends Over Time',
                        font: {
                            size: 16,
                            weight: 'bold'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
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
                                    return 'Collections: ' + data[index].collection_count;
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
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
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
        $('#collectionTrendChart').parent().html(
            '<div class="alert alert-warning">Unable to load chart data.</div>'
        );
    });
}

// Export Functions
function exportExcel() {
    const params = new URLSearchParams(window.location.search);
    const btn = event.target.closest('button');
    const originalHtml = btn.innerHTML;
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Exporting...';
    
    window.location.href = `/loans/collections/summary/export/excel?${params.toString()}`;
    
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
    
    window.location.href = `/loans/collections/summary/export/pdf?${params.toString()}`;
    
    setTimeout(() => {
        btn.disabled = false;
        btn.innerHTML = originalHtml;
    }, 2000);
}

function printReport() {
    const params = new URLSearchParams(window.location.search);
    window.open(`/loans/collections/summary/print?${params.toString()}`, '_blank');
}
</script>
@endpush