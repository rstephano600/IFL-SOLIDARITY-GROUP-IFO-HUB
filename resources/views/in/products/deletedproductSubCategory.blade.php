@extends('layouts.configside')

@section('title', 'Deleted Sub-Categories Archive')
@section('page-title', 'Deleted Sub-Categories Archive')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-trash-restore text-danger"></i>
        </div>
        Deleted Product Sub-Categories
    </h3>
    <a href="{{ route('productSubCategory') }}" class="btn btn-secondary btn-sm d-flex align-items-center px-3" style="height: 38px; border-radius: 4px;">
        <i class="fas fa-arrow-left me-2"></i> Active Sub-Categories
    </a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="deletedSubCategoryTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ref No</th>
                        <th>Parent Category</th>
                        <th>Sub-Category Name</th>
                        <th>Branch</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <span class="arbif-badge bg-danger text-white">
                                {{ $item->SubCategoryRefNo }}
                            </span>
                        </td>
                        <td>{{ $item->category->CategoryName ?? 'N/A' }}</td>
                        <td>{{ $item->SubCategoryName }}</td>
                        <td>{{ $item->branch->branch_name ?? 'Global / Central' }}</td>
                        <td>
                            <a href="{{ route('restoreproductSubCategory', encrypt($item->id)) }}" class="btn btn-sm btn-success d-inline-flex align-items-center px-3" onclick="return confirm('Are you sure you want to restore this sub-category into active operations?')">
                                <i class="fas fa-trash-restore me-2"></i> Restore
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-3">No archived soft-deleted sub-categories found.</td>
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
    new DataTable('#deletedSubCategoryTable',{
        perPage: 10,
        searchable: true,
        sortable: true
    });
</script>
@endpush