@extends('layouts.configside')

@section('title', 'Deleted Product Categories Archive')
@section('page-title', 'Deleted Product Categories Archive')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-trash-restore text-danger"></i>
        </div>
        Deleted Product Categories
    </h3>
    <a href="{{ route('productCategory') }}" class="btn btn-secondary btn-sm d-flex align-items-center px-3" style="height: 38px; border-radius: 4px;">
        <i class="fas fa-arrow-left me-2"></i> Active Categories
    </a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="deletedCategoryTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ref No</th>
                        <th>Code</th>
                        <th>Category Name</th>
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
                                {{ $item->CategoryRefNo }}
                            </span>
                        </td>
                        <td>{{ $item->CategoryCode ?? 'N/A' }}</td>
                        <td>{{ $item->CategoryName }}</td>
                        <td>{{ $item->branch->branch_name ?? 'Global / Central' }}</td>
                        <td>
                            <a href="{{ route('restoreproductCategory', encrypt($item->id)) }}" class="btn btn-sm btn-success d-inline-flex align-items-center px-3" onclick="return confirm('Are you sure you want to restore this category module into standard production operations?')">
                                <i class="fas fa-trash-restore me-2"></i> Restore Category
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-3">No archived soft-deleted entries logged within this division module segment.</td>
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
    new DataTable('#deletedCategoryTable',{
        perPage: 10,
        searchable: true,
        sortable: true
    });
</script>
@endpush