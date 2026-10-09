@extends('layouts.configside')

@section('title', 'Deleted Suppliers')
@section('page-title', 'Deleted Suppliers')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-trash-alt"></i>
        </div>
        Deleted Suppliers
    </h3>

    <div>
        <a href="{{ route('suppliers') }}" class="btn btn-secondary d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to Active Suppliers
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="deletedSuppliersTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ref No</th>
                        <th>Supplier Name</th>
                        <th>Type</th>
                        <th>Category</th>
                        <th>Branch</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <span class="arbif-badge arbif-badge-navy">
                                {{ $item->SupplierRefNo }}
                            </span>
                        </td>
                        <td>{{ $item->SupplierName }}</td>
                        <td>
                            @if($item->SupplierType === 'Member')
                                <span class="badge bg-info text-dark">Member</span>
                            @else
                                <span class="badge bg-secondary">External</span>
                            @endif
                        </td>
                        <td>{{ $item->category->CategoryName ?? 'N/A' }}</td>
                        <td>{{ $item->branch->branch_name ?? 'Global / Central' }}</td>
                        <td>{{ $item->Phone ?? 'N/A' }}</td>
                        <td>
                            <a href="{{ route('restoreSupplier', encrypt($item->id)) }}" class="btn btn-sm btn-outline-success" onclick="return confirm('Are you sure you want to restore this supplier?')">
                                <i class="fas fa-undo me-1"></i> Restore
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">No Soft-Deleted Suppliers Found</td>
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
    new DataTable('#deletedSuppliersTable', {
        perPage: 10,
        searchable: true,
        sortable: true
    });
</script>
@endpush