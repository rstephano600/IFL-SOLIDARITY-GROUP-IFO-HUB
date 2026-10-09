@extends('layouts.workingside')

@section('title', 'Deleted Supplier Categories')
@section('page-title', 'Deleted Supplier Categories')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-trash-alt"></i>
        </div>
        Deleted Supplier Categories
    </h3>

    <div>
        <a href="{{ route('supplierCategory') }}" class="btn btn-secondary d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to Active Categories
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="deletedSupplierCategoryTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Code</th>
                        <th>Category Name</th>
                        <th>Supplier Group</th>
                        <th>Branch</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <span class="arbif-badge arbif-badge-navy">
                                {{ $item->CategoryCode }}
                            </span>
                        </td>
                        <td>{{ $item->CategoryName }}</td>
                        <td>{{ $item->SupplierGroup }}</td>
                        <td>{{ $item->branch->branch_name ?? 'Global / Central' }}</td>
                        <td>{{ Str::limit($item->Description, 50) }}</td>
                        <td>
                            <a href="{{ route('restoresupplierCategory', encrypt($item->id)) }}" class="btn btn-sm btn-outline-success" onclick="return confirm('Are you sure you want to restore this category?')">
                                <i class="fas fa-undo me-1"></i> Restore
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No Soft-Deleted Supplier Categories Found</td>
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
    new DataTable('#deletedSupplierCategoryTable', {
        perPage: 10,
        searchable: true,
        sortable: true
    });
</script>
@endpush