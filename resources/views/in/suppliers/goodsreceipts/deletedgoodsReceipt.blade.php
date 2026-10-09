@extends('layouts.workingside')

@section('title', 'Deleted Goods Receipts')
@section('page-title', 'Deleted Goods Receipts')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-trash-alt"></i>
        </div>
        Deleted Goods Receipts
    </h3>

    <div>
        <a href="{{ route('goodsReceipt') }}" class="btn btn-secondary px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to Active GRNs
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="deletedGRNTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>GRN No.</th>
                        <th>PO Ref</th>
                        <th>Supplier</th>
                        <th>Deleted By</th>
                        <th>Deleted At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="fw-bold">{{ $item->receipt_no }}</td>
                        <td>{{ $item->purchaseOrder->purchase_order_no ?? 'N/A' }}</td>
                        <td>{{ $item->supplier->SupplierName ?? 'N/A' }}</td>
                        <td>{{ $item->deleter->name ?? 'N/A' }}</td>
                        <td>{{ $item->deleted_at ? $item->deleted_at->format('d M, Y H:i A') : 'N/A' }}</td>
                        <td>
                            <a href="{{ route('restoregoodsReceipt', encrypt($item->id)) }}" class="btn btn-sm btn-outline-success" onclick="return confirm('Restore this Goods Receipt?')">
                                <i class="fas fa-undo me-1"></i> Restore
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No Soft-Deleted Goods Receipts Found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    new DataTable('#deletedGRNTable', {
        perPage: 10,
        searchable: true,
        sortable: true
    });
</script>
@endpush