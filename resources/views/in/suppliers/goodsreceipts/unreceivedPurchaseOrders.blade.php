@extends('layouts.workingside')

@section('title', 'Pending Purchase Orders to Receive')
@section('page-title', 'Pending Goods Receipt')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-truck-loading"></i>
        </div>
        Purchase Orders Awaiting Delivery
    </h3>

    <div>
        <a href="{{ route('goodsReceipt') }}" class="btn btn-secondary px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to GRNs
        </a>
    </div>
</div>

{{-- SEARCH & FILTER --}}
<div class="arbif-card mb-4">
    <div class="arbif-card-body p-3">
        <form method="GET" action="{{ route('unreceivedPurchaseOrders') }}" class="row g-2">
            <div class="col-md-9">
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search by PO Number or Supplier Name...">
            </div>
            <div class="col-md-3 d-flex gap-1">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Search</button>
                @if(request()->filled('search'))
                    <a href="{{ route('unreceivedPurchaseOrders') }}" class="btn btn-secondary text-white">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>PO No.</th>
                        <th>Supplier</th>
                        <th>Order Date</th>
                        <th>Total Value</th>
                        <th>Pending Items</th>
                        <th>Branch</th>
                        <th>PO Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $po)
                    <tr>
                        <td>{{ $data->firstItem() + $index }}</td>
                        <td>
                            <strong class="text-primary">{{ $po->purchase_order_no }}</strong>
                        </td>
                        <td><strong>{{ $po->supplier->SupplierName ?? 'N/A' }}</strong></td>
                        <td>{{ $po->order_date ? $po->order_date->format('d M, Y') : 'N/A' }}</td>
                        <td class="fw-bold">{{ number_format($po->total_amount, 2) }} {{ $po->currency_code }}</td>
                        <td>
                            <span class="badge bg-warning text-dark">
                                {{ $po->items->where('pending_quantity', '>', 0)->count() }} Items Pending
                            </span>
                        </td>
                        <td>{{ $po->branch->branch_name ?? 'N/A' }}</td>
                        <td>
                            <span class="badge {{ $po->order_status == 'Partially Received' ? 'bg-info text-dark' : 'bg-success' }}">
                                {{ $po->order_status }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('creategoodsReceipt', encrypt($po->id)) }}" class="btn btn-sm btn-primary fw-bold">
                                <i class="fas fa-box-open me-1"></i> Receive Items
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">All approved purchase orders have been fully received!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $data->links() }}
        </div>
    </div>
</div>

@endsection