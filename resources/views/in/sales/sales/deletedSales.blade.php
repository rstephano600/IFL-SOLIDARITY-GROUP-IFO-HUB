@extends('layouts.workingside')

@section('title', 'Deleted Sales Transactions')
@section('page-title', 'Deleted Sales Transactions')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-trash-alt"></i>
        </div>
        Deleted Sales Transactions
    </h3>

    <div>
        <a href="{{ route('sales') }}" class="btn btn-secondary d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to Active Sales
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="deletedSalesTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Sale No.</th>
                        <th>Reference</th>
                        <th>Date</th>
                        <th>Sales Type</th>
                        <th>Customer</th>
                        <th>Branch</th>
                        <th>Total Amount (TZS)</th>
                        <th>Deleted By</th>
                        <th>Deleted At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="fw-bold">{{ $item->sale_no }}</td>
                        <td>
                            <span class="arbif-badge arbif-badge-navy">
                                {{ $item->ReferenceNo }}
                            </span>
                        </td>
                        <td>{{ $item->sale_date ? \Carbon\Carbon::parse($item->sale_date)->format('d M, Y') : 'N/A' }}</td>
                        <td>{{ $item->salesType->type_name ?? 'N/A' }}</td>
                        <td>{{ $item->customer->customer_name ?? 'Walk-In Customer' }}</td>
                        <td>{{ $item->branch->branch_name ?? 'Global / Central' }}</td>
                        <td class="fw-bold text-danger">{{ number_format($item->total_amount, 2) }}</td>
                        <td>{{ $item->deleter->name ?? 'N/A' }}</td>
                        <td>{{ $item->deleted_at ? $item->deleted_at->format('d M, Y H:i A') : 'N/A' }}</td>
                        <td>
                            <a href="{{ route('restoreSale', encrypt($item->id)) }}" class="btn btn-sm btn-outline-success" onclick="return confirm('Are you sure you want to restore this sale transaction?')">
                                <i class="fas fa-undo me-1"></i> Restore
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center">No Soft-Deleted Sales Transactions Found</td>
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
    new DataTable('#deletedSalesTable', {
        perPage: 10,
        searchable: true,
        sortable: true
    });
</script>
@endpush