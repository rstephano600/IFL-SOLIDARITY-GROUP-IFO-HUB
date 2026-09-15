@extends('layouts.workingside')

@section('title', 'Adjust Product Stock Master Ledger')
@section('page-title', 'Adjust Product Stock Master Ledger')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-sliders-h text-warning"></i>
        </div>
        Inventory Physical Audit Correction: {{ $data->product->ProductName ?? 'N/A' }}
    </h3>
    <a href="{{ route('productStock') }}" class="btn btn-secondary btn-sm d-flex align-items-center px-3" style="height: 38px; border-radius: 4px;">
        <i class="fas fa-arrow-left me-2"></i> Stock Ledger
    </a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <form method="POST" action="{{ route('updateproductStock', encrypt($data->id)) }}">
            @csrf

            <div class="row g-3 mb-4 bg-light p-3 border rounded">
                <div class="col-md-6">
                    <label class="form-label text-muted fw-bold">Target Operational Node Branch <span class="text-danger">*</span></label>
                    <select name="branch_id" class="form-select" required>
                        <option value="">-- Select Target Branch --</option>
                        @foreach($branchies as $branch)
                            <option value="{{ $branch->id }}" {{ $data->branch_id == $branch->id ? 'selected' : '' }}>
                                {{ $branch->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-muted fw-bold">Mapped Catalog Target Product <span class="text-danger">*</span></label>
                    <select name="product_id" class="form-select" required>
                        <option value="">-- Change Target Product --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ $data->product_id == $product->id ? 'selected' : '' }}>
                                {{ $product->ProductName }} (SKU: {{ $product->ProductCode ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <h5 class="fw-bold text-navy border-bottom pb-2 mb-3">Stock Sizing Rules</h5>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Physical Quantities Counted <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="CurrentStock" class="form-control fw-bold fs-5 text-dark" value="{{ $data->CurrentStock }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Active Sales Orders Reservations</label>
                    <input type="number" step="0.01" name="ReservedStock" class="form-control" value="{{ $data->ReservedStock }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Weighted Average Base Cost (TZS) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="AverageCost" class="form-control fw-bold fs-5 text-navy" value="{{ $data->AverageCost }}" required>
                </div>
            </div>

            <h5 class="fw-bold text-secondary border-bottom pb-2 mb-3">Historical Purchase Trace</h5>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Last Sourced Purchase Price (TZS)</label>
                    <input type="number" step="0.01" name="LastPurchasePrice" class="form-control" value="{{ $data->LastPurchasePrice }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Last Sourced Purchase Date</label>
                    <input type="date" name="LastPurchaseDate" class="form-control" value="{{ $data->LastPurchaseDate ? $data->LastPurchaseDate->format('Y-m-d') : '' }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Last Procurement Supplier Profile</label>
                    <select name="LastSupplier_id" class="form-select">
                        <option value="">-- Select Supplier Name --</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ $data->LastSupplier_id == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->SupplierName }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <h5 class="fw-bold text-secondary border-bottom pb-2 mb-3">Historical Outward Sales Footprint</h5>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Last Dispatched Unit Sale Price (TZS)</label>
                    <input type="number" step="0.01" name="LastSalePrice" class="form-control" value="{{ $data->LastSalePrice }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Last Dispatched Order Date</label>
                    <input type="date" name="LastSaleDate" class="form-control" value="{{ $data->LastSaleDate ? $data->LastSaleDate->format('Y-m-d') : '' }}">
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('productStock') }}" class="btn btn-secondary px-4">Abandone Changes</a>
                <button type="submit" class="arbif-btn-submit px-4">Execute Matrix Rebalancing</button>
            </div>
        </form>
    </div>
</div>

@endsection