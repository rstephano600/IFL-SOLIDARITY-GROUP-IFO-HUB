@extends('layouts.workingside')

@section('title', 'Inventory Stock Master Ledger')
@section('page-title', 'Inventory Stock Master Ledger')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-warehouse"></i>
        </div>
        Current Inventory Levels
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('deletedproductStock') }}" class="btn btn-outline-danger d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-archive me-2"></i> Stock Archive
        </a>
        <button class="arbif-btn-submit" data-bs-toggle="modal" data-bs-target="#addStockModal">
            <i class="fas fa-plus"></i> Initialize Stock Item
        </button>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="stockTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Branch </th>
                        <th>Product Details</th>
                        <th>Product Code</th>
                        <th>Quantities</th>
                        <th>Cost & Valuation (TZS)</th>
                        <th>Last Purchase Action</th>
                        <th>Last Sale Action</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div class="fw-bold">{{ $item->branch->postal_address ?? ' ' }}</div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $item->product->ProductName ?? 'N/A' }}</div>
                            <small class="text-navy font-monospace">{{ $item->product->ProductRefNo ?? 'N/A' }}</small>
                            <div class="small text-muted">SKU: {{ $item->product->ProductCode ?? 'N/A' }}</div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $item->product->ProductCode ?? 'N/A' }}</div>
                        </td>

                        <td>
                            <div class="small"><strong>Physical Count:</strong> <span class="badge bg-secondary text-dark">{{ number_format($item->CurrentStock, 2) }}</span></div>
                            <div class="small text-danger"><strong>Reserved:</strong> {{ number_format($item->ReservedStock, 2) }}</div>
                            <div class="small text-success fw-bold"><strong>Available:</strong> {{ number_format($item->AvailableStock, 2) }}</div>
                        </td>
                        <td>
                            <div class="small"><strong>Avg Cost:</strong> {{ number_format($item->AverageCost, 2) }}</div>
                            <div class="small text-navy fw-bold"><strong>Total Value:</strong> {{ number_format($item->StockValue, 2) }}</div>
                        </td>
                        <td>
                            @if($item->LastPurchaseDate)
                                <div class="small"><strong>Price:</strong> {{ number_format($item->LastPurchasePrice, 2) }}</div>
                                <div class="small text-muted"><i class="far fa-calendar-alt"></i> {{ $item->LastPurchaseDate->format('d-M-Y') }}</div>
                                <div class="small text-truncate text-secondary" style="max-width: 150px;"><strong>Supplier:</strong> {{ $item->supplier->SupplierName ?? 'N/A' }}</div>
                            @else
                                <span class="text-muted small">No Purchase Trace</span>
                            @endif
                        </td>
                        <td>
                            @if($item->LastSaleDate)
                                <div class="small"><strong>Price:</strong> {{ number_format($item->LastSalePrice, 2) }}</div>
                                <div class="small text-muted"><i class="far fa-calendar-alt"></i> {{ $item->LastSaleDate->format('d-M-Y') }}</div>
                            @else
                                <span class="text-muted small">No Sales Traded</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-success">{{ $item->ProductStatus ?? 'Available' }}</span>
                            <div class="small text-muted text-center mt-1">{{ $item->AuditingStatus }}</div>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('viewproductStock', encrypt($item->id)) }}" class="arbif-btn-view" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @can('view-generally-delete-of-any-item')
                                <a href="{{ route('editproductStock', encrypt($item->id)) }}" class="arbif-btn-edit" title="Audit adjustments">
                                    <i class="fas fa-sliders-h"></i>
                                </a>
                                <a href="{{ route('destroyproductStock', encrypt($item->id)) }}" class="arbif-btn-delete" title="Archive Stock Line" onclick="return confirm('Are you sure you want to isolate this stock entry line item?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">No stock ledger balances found operating inside this branch partition.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- INITIALIZE STOCK MODAL --}}
<div class="modal fade arbif-modal" id="addStockModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon">
                    <i class="fas fa-cubes"></i>
                </div>
                <h5 class="modal-title">Initialize Product Stock Balance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('storeproductStock') }}">
                    @csrf

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Operational Node Branch Target <span class="text-danger">*</span></label>
                            <select name="branch_id" class="form-select" data-searchable data-placeholder="Search ..." required>
                                <option value="">-- Choose Branch Target --</option>
                                @foreach($branchies as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Select Master Catalog Product <span class="text-danger">*</span></label>
                            <select name="product_id" class="form-select" data-searchable data-placeholder="Search ..." required>
                                <option value="">-- Choose Product --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->ProductName }} (SKU: {{ $product->ProductCode ?? 'N/A' }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Starting Physical Count <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="CurrentStock" class="form-control" placeholder="0.00" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Reserved / Committed Count</label>
                            <input type="number" step="0.01" name="ReservedStock" class="form-control" value="0.00">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Average Unit Unit Cost (TZS) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="AverageCost" class="form-control" placeholder="0.00" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Last Purchase Unit Price (TZS)</label>
                            <input type="number" step="0.01" name="LastPurchasePrice" class="form-control" placeholder="Defaults to Avg Cost">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Last Sourced Purchase Date</label>
                            <input type="date" name="LastPurchaseDate" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Primary Trade Supplier</label>
                            <select name="LastSupplier_id" class="form-select" data-searchable data-placeholder="Search ..." required>
                                <option value="">-- Optional Supplier Mapping --</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->SupplierName }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer px-0 pb-0 mt-4">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="arbif-btn-submit">Commit Stock Entry</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    new DataTable('#stockTable',{
        perPage: 10,
        searchable: true,
        sortable: true
    });
</script>
@endpush