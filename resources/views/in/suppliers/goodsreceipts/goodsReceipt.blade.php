@extends('layouts.workingside')

@section('title', 'Goods Receipts')
@section('page-title', 'Goods Receipts')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-clipboard-check"></i>
        </div>
        Goods Receipts (GRNs)
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('deletedgoodsReceipt') }}" class="btn btn-outline-danger d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-trash-alt me-2"></i> Deleted GRNs
        </a>
        <a href="{{ route('unreceivedPurchaseOrders') }}" class="arbif-btn-submit text-decoration-none">
            <i class="fas fa-truck-loading me-1"></i> Receive Pending POs
        </a>
    </div>
</div>

{{-- FILTERS --}}
<div class="arbif-card mb-4">
    <div class="arbif-card-body p-3">
        <form method="GET" action="{{ route('goodsReceipt') }}" class="row g-2 align-items-center">
            <div class="col-md-3">
                <select name="receipt_status" class="form-select" onchange="this.form.submit()">
                    <option value="">-- All Receipt Statuses --</option>
                    <option value="Received" {{ request('receipt_status') == 'Received' ? 'selected' : '' }}>Received</option>
                    <option value="Partially Received" {{ request('receipt_status') == 'Partially Received' ? 'selected' : '' }}>Partially Received</option>
                    <option value="Inspected" {{ request('receipt_status') == 'Inspected' ? 'selected' : '' }}>Inspected</option>
                </select>
            </div>

            <div class="col-md-3">
                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}" placeholder="From Date">
            </div>

            <div class="col-md-3">
                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}" placeholder="To Date">
            </div>

            <div class="col-md-3 d-flex gap-1">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
                @if(request()->anyFilled(['receipt_status', 'from_date', 'to_date']))
                    <a href="{{ route('goodsReceipt') }}" class="btn btn-secondary text-white">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="goodsReceiptTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>GRN No.</th>
                        <th>PO Reference</th>
                        <th>Supplier</th>
                        <th>Receipt Date</th>
                        <th>Delivery Note</th>
                        <th>Received By</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $data->firstItem() + $index }}</td>
                        <td>
                            <a href="{{ route('viewgoodsReceipt', encrypt($item->id)) }}" class="fw-bold text-decoration-none">
                                {{ $item->receipt_no }}
                            </a>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ $item->purchaseOrder->purchase_order_no ?? 'N/A' }}
                            </span>
                        </td>
                        <td><strong>{{ $item->supplier->SupplierName ?? 'N/A' }}</strong></td>
                        <td>{{ $item->receipt_date ? $item->receipt_date->format('d M, Y') : 'N/A' }}</td>
                        <td>{{ $item->supplier_delivery_note ?? '--' }}</td>
                        <td>{{ $item->receivedBy->name ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-success">
                                {{ $item->receipt_status }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('viewgoodsReceipt', encrypt($item->id)) }}" class="arbif-btn-view">
                                    <i class="fas fa-eye"></i> View GRN
                                </a>

                                @can('view-generally-delete-of-any-item')
                                    <a href="{{ route('destroygoodsReceipt', encrypt($item->id)) }}" class="arbif-btn-delete" onclick="return confirm('Are you sure you want to soft delete this GRN?')">
                                        <i class="fas fa-trash"></i> Del
                                    </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">No Goods Receipts Found</td>
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