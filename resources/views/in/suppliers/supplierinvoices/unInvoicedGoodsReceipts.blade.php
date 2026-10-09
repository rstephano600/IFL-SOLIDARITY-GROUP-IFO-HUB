@extends('layouts.workingside')

@section('title', 'Pending GRNs to Invoice')
@section('page-title', 'Goods Receipts Awaiting Invoicing')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-receipt"></i>
        </div>
        Goods Receipts Awaiting Invoicing
    </h3>

    <div>
        <a href="{{ route('supplierInvoice') }}" class="btn btn-secondary px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to Invoices
        </a>
    </div>
</div>

<div class="arbif-card mb-4">
    <div class="arbif-card-body p-3">
        <form method="GET" action="{{ route('unInvoicedGoodsReceipts') }}" class="row g-2">
            <div class="col-md-9">
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search by GRN No, PO No, or Supplier Name...">
            </div>
            <div class="col-md-3 d-flex gap-1">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Search</button>
                @if(request()->filled('search'))
                    <a href="{{ route('unInvoicedGoodsReceipts') }}" class="btn btn-secondary text-white">Reset</a>
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
                        <th>GRN No.</th>
                        <th>PO Reference</th>
                        <th>Supplier</th>
                        <th>Receipt Date</th>
                        <th>Delivery Note</th>
                        <th>Invoiced Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $grn)
                    <tr>
                        <td>{{ $data->firstItem() + $index }}</td>
                        <td><strong class="text-primary">{{ $grn->receipt_no }}</strong></td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ $grn->purchaseOrder->purchase_order_no ?? 'N/A' }}
                            </span>
                        </td>
                        <td><strong>{{ $grn->supplier->SupplierName ?? 'N/A' }}</strong></td>
                        <td>{{ $grn->receipt_date ? $grn->receipt_date->format('d M, Y') : 'N/A' }}</td>
                        <td>{{ $grn->supplier_delivery_note ?? '--' }}</td>
                        <td>
                            <span class="badge bg-warning text-dark">{{ $grn->invoiced_status }}</span>
                        </td>
                        <td>
                            <a href="{{ route('createsupplierInvoice', encrypt($grn->id)) }}" class="btn btn-sm btn-primary fw-bold">
                                <i class="fas fa-file-invoice-dollar me-1"></i> Generate Invoice
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">All received goods have been invoiced!</td>
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