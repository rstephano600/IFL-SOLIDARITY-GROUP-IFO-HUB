@extends('layouts.workingside')

@section('title', 'Archived Isolated Stock Register Lines')
@section('page-title', 'Archived Isolated Stock Register Lines')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-archive text-danger"></i>
        </div>
        Isolated Stock Entries Archive Repository
    </h3>
    <a href="{{ route('productStock') }}" class="btn btn-secondary btn-sm d-flex align-items-center px-3" style="height: 38px; border-radius: 4px;">
        <i class="fas fa-arrow-left me-2"></i> Active Stock Ledger
    </a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="deletedStockTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product Label</th>
                        <th>Isolated Location Node Branch</th>
                        <th>Archived Stock Counts</th>
                        <th>Weighted Average Value</th>
                        <th>Action Processing</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong class="text-dark">{{ $item->product->ProductName ?? 'N/A' }}</strong>
                            <div class="small font-monospace text-muted">{{ $item->product->ProductRefNo ?? 'N/A' }}</div>
                        </td>
                        <td>{{ $item->branch->branch_name ?? 'N/A' }}</td>
                        <td><span class="badge bg-danger text-white">{{ number_format($item->CurrentStock, 2) }} Unit Units</span></td>
                        <td class="fw-bold text-navy">TZS {{ number_format($item->StockValue, 2) }}</td>
                        <td>
                            <a href="{{ route('restoreproductStock', encrypt($item->id)) }}" class="btn btn-sm btn-success d-inline-flex align-items-center px-3" onclick="return confirm('Are you sure you want to restore this isolated inventory stock register ledger profile trace?')">
                                <i class="fas fa-trash-restore me-2"></i> Re-insert Into Operations
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-3">No isolated soft-deleted inventory stock profiles detected within this workspace memory block.</td>
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
    new DataTable('#deletedStockTable',{
        perPage: 10,
        searchable: true,
        sortable: true
    });
</script>
@endpush