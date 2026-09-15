@extends('layouts.workingside')

@section('title', 'Stock Analysis Sheet')
@section('page-title', 'Stock Analysis Sheet')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-chart-pie text-navy"></i>
        </div>
        Stock Ledger Deep Analysis
    </h3>
    <a href="{{ route('productStock') }}" class="btn btn-secondary btn-sm d-flex align-items-center px-3" style="height: 38px; border-radius: 4px;">
        <i class="fas fa-arrow-left me-2"></i> Stock Registry
    </a>
</div>

<div class="row">
    {{-- VALUE SUMMARIES CARDS MATRIX --}}
    <div class="col-md-4">
        <div class="arbif-card mb-4 text-center py-4 bg-navy text-white" style="border-radius: 8px;">
            <div class="arbif-card-body">
                <i class="fas fa-coins fa-3x mb-2 text-warning"></i>
                <h6 class="text-white-50 uppercase tracking-wider small">Total Ledger Asset Valuation</h6>
                <h2 class="fw-bold text-white">TZS {{ number_format($data->StockValue, 2) }}</h2>
                <div class="small text-white-50 mt-1">Based on Weighted Unit Average Costing</div>
            </div>
        </div>

        <div class="arbif-card mb-4">
            <div class="arbif-card-body">
                <h6 class="fw-bold text-secondary border-bottom pb-2 mb-3">Quantity Distribution Matrix</h6>
                <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-1">
                    <span class="text-muted"><i class="fas fa-warehouse me-2"></i> Physical In-Warehouse:</span>
                    <span class="badge bg-secondary text-dark font-monospace fs-6 px-3">{{ number_format($data->CurrentStock, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-1">
                    <span class="text-muted"><i class="fas fa-lock me-2"></i> Reserved / Order Lock:</span>
                    <span class="badge bg-danger font-monospace fs-6 px-3">{{ number_format($data->ReservedStock, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted fw-bold"><i class="fas fa-check-circle me-2 text-success"></i> Free Available Balance:</span>
                    <span class="badge bg-success font-monospace fs-6 px-3">{{ number_format($data->AvailableStock, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- METADATA PROFILE ANALYSIS TABLE --}}
    <div class="col-md-8">
        <div class="arbif-card">
            <div class="arbif-card-body">
                <h5 class="fw-bold text-navy border-bottom pb-2 mb-3">Relational Context Parameters</h5>
                
                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <th width="40%" class="text-secondary fw-bold">Associated Core Product Target</th>
                            <td class="fw-bold text-dark">{{ $data->product->ProductName ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th class="text-secondary fw-bold">Product Blueprint Code Reference</th>
                            <td class="font-monospace text-navy fw-bold">{{ $data->product->ProductRefNo ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th class="text-secondary fw-bold">Calculated Unit Average Cost</th>
                            <td class="fw-bold">TZS {{ number_format($data->AverageCost, 2) }}</td>
                        </tr>
                        <tr>
                            <th class="text-secondary fw-bold">Last Sourced Procurement Value</th>
                            <td>
                                @if($data->LastPurchaseDate)
                                    <div class="fw-bold text-dark">TZS {{ number_format($data->LastPurchasePrice, 2) }}</div>
                                    <div class="small text-muted">Procured Date: {{ $data->LastPurchaseDate->format('d-M-Y') }}</div>
                                    <div class="small text-secondary">Sourced Account Vendor: <strong>{{ $data->supplier->SupplierName ?? 'N/A' }}</strong></div>
                                @else
                                    <span class="text-muted small">No initial trade procurement history tracked.</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="text-secondary fw-bold">Last Outward Dispatch Invoice Value</th>
                            <td>
                                @if($data->LastSaleDate)
                                    <div class="fw-bold text-dark">TZS {{ number_format($data->LastSalePrice, 2) }}</div>
                                    <div class="small text-muted">Invoice Processing Timestamp: {{ $data->LastSaleDate->format('d-M-Y') }}</div>
                                @else
                                    <span class="text-muted small">No operational sales traces recorded yet.</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="text-secondary fw-bold">Node Architecture Location</th>
                            <td>
                                <div class="small"><strong>Holding Corporate Unit:</strong> {{ $data->company->Name ?? $data->company->name ?? 'N/A' }}</div>
                                <div class="small"><strong>Assigned Operations Branch Node:</strong> {{ $data->branch->branch_name ?? 'N/A' }}</div>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-secondary fw-bold">Database Traceability Audit Footprint</th>
                            <td>
                                <div class="small text-muted"><strong>Authorized Tracking Analyst:</strong> {{ $data->user->name ?? 'System Process Layer' }}</div>
                                <div class="small text-muted"><strong>Initialization Handler Profile:</strong> {{ $data->created_by ?? 'Data Seeder Core' }}</div>
                                <div class="small text-muted"><strong>Last Balancing Adjustment Executed By:</strong> {{ $data->updated_by ?? 'Unmodified Baseline' }}</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection