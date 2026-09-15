@extends('layouts.configside')

@section('title', 'Product Sub-Categories')
@section('page-title', 'Product Sub-Categories')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-tags"></i>
        </div>
        Product Sub-Categories
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('deletedproductSubCategory') }}" class="btn btn-outline-danger d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-trash-alt me-2"></i> Deleted Sub-Categories
        </a>
        <button class="arbif-btn-submit" data-bs-toggle="modal" data-bs-target="#addSubCategoryModal">
            <i class="fas fa-plus"></i> Add Sub-Category
        </button>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="subCategoryTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ref No</th>
                        <th>Parent Category</th>
                        <th>Sub-Category Name</th>
                        <th>Code</th>
                        <th>Branch</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <span class="arbif-badge arbif-badge-navy">
                                {{ $item->SubCategoryRefNo }}
                            </span>
                        </td>
                        <td>
                            <span class="text-secondary fw-bold">
                                {{ $item->category->CategoryName ?? 'N/A' }}
                            </span>
                        </td>
                        <td>{{ $item->SubCategoryName }}</td>
                        <td>{{ $item->SubCategoryCode ?? 'N/A' }}</td>
                        <td>{{ $item->branch->branch_name ?? 'Global / Central' }}</td>
                        <td>
                            <a href="{{ route('viewproductSubCategory', encrypt($item->id)) }}" class="arbif-btn-view">
                                <i class="fas fa-eye"></i>view
                            </a>

                            <a href="{{ route('editproductSubCategory', encrypt($item->id)) }}" class="arbif-btn-edit">
                                <i class="fas fa-pencil"></i>edit
                            </a>
                            @can('view-generally-delete-of-any-item')

                            <a href="{{ route('destroyproductSubCategory', encrypt($item->id)) }}" class="arbif-btn-delete" onclick="return confirm('Are you sure you want to remove this sub-category?')">
                                <i class="fas fa-trash"></i>del
                            </a>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No Sub-Categories Found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- REGISTER SUB-CATEGORY MODAL --}}
<div class="modal fade arbif-modal" id="addSubCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon">
                    <i class="bi bi-patch-check"></i>
                </div>
                <h5 class="modal-title">Register Product Sub-Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('storeproductSubCategory') }}">
                    @csrf

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Assigned Branch <span class="text-danger">*</span></label>
                            <select name="branch_id" class="form-select" data-searchable data-placeholder="Search ..." required>
                                <option value="">-- Select Branch --</option>
                                @foreach($branchies as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Parent Product Category <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select" data-searchable data-placeholder="Search ..." required>
                                <option value="">-- Select Parent Category --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->CategoryName }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">

                        <div class="col-md-6">
                            <label class="form-label">Sub-Category Code</label>
                            <input type="text" name="SubCategoryCode" class="form-control" placeholder="e.g. SUB-01">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Sub-Category Name <span class="text-danger">*</span></label>
                            <input type="text" name="SubCategoryName" class="form-control" placeholder="e.g. PORTLAND CEMENT, LED BULBS" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="Description" rows="4" class="form-control" placeholder="Provide summary details..."></textarea>
                    </div>

                    <div class="modal-footer px-0 pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="arbif-btn-submit">Save Sub-Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    new DataTable('#subCategoryTable',{
        perPage: 10,
        searchable: true,
        sortable: true
    });
</script>
@endpush