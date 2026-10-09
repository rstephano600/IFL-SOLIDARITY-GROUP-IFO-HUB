@extends('layouts.workingside')

@section('title', 'Supplier Categories')
@section('page-title', 'Supplier Categories')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-truck-loading"></i>
        </div>
        Supplier Categories
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('deletedsupplierCategory') }}" class="btn btn-outline-danger d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-trash-alt me-2"></i> Deleted Categories
        </a>
        <button class="arbif-btn-submit" data-bs-toggle="modal" data-bs-target="#addFormModal">
            <i class="fas fa-plus"></i> Add Supplier Category
        </button>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="supplierCategoryTable">
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
                            <a href="{{ route('viewsupplierCategory', encrypt($item->id)) }}" class="arbif-btn-view">
                                <i class="fas fa-eye"></i> view
                            </a>

                            <a href="{{ route('editsupplierCategory', encrypt($item->id)) }}" class="arbif-btn-edit">
                                <i class="fas fa-pencil"></i> edit
                            </a>
                            
                            @can('view-generally-delete-of-any-item')
                            <a href="{{ route('destroysupplierCategory', encrypt($item->id)) }}" class="arbif-btn-delete" onclick="return confirm('Are you sure you want to remove this supplier category?')">
                                <i class="fas fa-trash"></i> del
                            </a>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No Supplier Categories Found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- REGISTER SUPPLIER CATEGORY MODAL --}}
<div class="modal fade arbif-modal" id="addFormModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon">
                    <i class="bi bi-truck-loading"></i>
                </div>
                <h5 class="modal-title">Register Supplier Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('storesupplierCategory') }}">
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
                            <label class="form-label">Category Code <span class="text-danger">*</span></label>
                            <input type="text" name="CategoryCode" class="form-control" placeholder="e.g. SUP-LOCAL" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Category Name <span class="text-danger">*</span></label>
                            <input type="text" name="CategoryName" class="form-control" placeholder="e.g. RAW MATERIAL SUPPLIERS" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Supplier Group <span class="text-danger">*</span></label>
                            <input type="text" name="SupplierGroup" class="form-control" placeholder="e.g. LOCAL / FOREIGN" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="Description" rows="4" class="form-control" placeholder="Provide supplier category overview details here..."></textarea>
                    </div>

                    <div class="modal-footer px-0 pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="arbif-btn-submit">Save Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    new DataTable('#supplierCategoryTable', {
        perPage: 10,
        searchable: true,
        sortable: true
    });
</script>
@endpush