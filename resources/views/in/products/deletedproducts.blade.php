@extends('layouts.workingside')

@section('title', 'Deleted Products Repository Archive')
@section('page-title', 'Deleted Products Repository Archive')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-trash-restore text-danger"></i>
        </div>
        Soft-Deleted Product Catalog Archive
    </h3>
    <a href="{{ route('products') }}" class="btn btn-secondary btn-sm d-flex align-items-center px-3" style="height: 38px; border-radius: 4px;">
        <i class="fas fa-arrow-left me-2"></i> Active Catalog
    </a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="deletedProductsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ref No</th>
                        <th>Product Label</th>
                        <th>Primary Category</th>
                        <th>Base Selling Retail Price</th>
                        <th>Operational Node Branch</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <span class="arbif-badge bg-danger text-white">
                                {{ $item->ProductRefNo }}
                            </span>
                        </td>
                        <td>
                            <strong>{{ $item->ProductName }}</strong>
                            @if($item->ProductCode)<div class="small text-muted font-monospace">{{ $item->ProductCode }}</div>@endif
                        </td>
                        <td>{{ $item->category->CategoryName ?? 'N/A' }}</td>
                        <td class="fw-bold text-navy">TZS {{ number_format($item->RetailPrice, 2) }}</td>
                        <td>{{ $item->branch->branch_name ?? 'Global Central Node' }}</td>
                        <td>
                            <a href="{{ route('restoreproducts', encrypt($item->id)) }}" class="btn btn-sm btn-success d-inline-flex align-items-center px-3" onclick="return confirm('Are you sure you want to restore this product portfolio asset back into active listings?')">
                                <i class="fas fa-trash-restore me-2"></i> Restore Portfolio Asset
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-3">No archived soft-deleted product assets discovered in this branch dataset partition.</td>
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
    new DataTable('#deletedProductsTable',{
        perPage: 10,
        searchable: true,
        sortable: true
    });
</script>
@endpush