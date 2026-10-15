@extends('layouts.configside')

@section('title', 'Expense Categories')
@section('page-title', 'Expense Categories Information')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-tags"></i>
        </div>
        Expense Categories
    </h3>

    <div class="d-flex gap-2">
        <button class="arbif-btn-submit" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="fas fa-plus"></i> Add Expense Category
        </button>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="expenseCategoryTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Category Code</th>
                        <th>Category Name</th>
                        <th>Description</th>
                        <th>Branch</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <span class="arbif-badge arbif-badge-navy">
                                {{ $item->expense_code }}
                            </span>
                        </td>
                        <td><strong>{{ $item->name }}</strong></td>
                        <td>{{ Str::limit($item->description, 50, '...') ?? 'N/A' }}</td>
                        <td>
                            @if($item->Status === 'Active')
                                <span class="badge bg-success">Active</span>
                            @elseif($item->Status === 'Inactive')
                                <span class="badge bg-warning text-dark">Inactive</span>
                            @else
                                <span class="badge bg-danger">{{ $item->Status }}</span>
                            @endif
                        </td>
                        <td>{{ $item->creator->name ?? $item->user->name ?? 'N/A' }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('viewexpensecategory', encrypt($item->id)) }}" class="arbif-btn-view">
                                    <i class="fas fa-eye"></i> View
                                </a>

                                <a href="{{ route('editexpensecategory', encrypt($item->id)) }}" class="arbif-btn-edit">
                                    <i class="fas fa-pencil"></i> Edit
                                </a>

                                @if($item->Status === 'Active')
                                    <a href="{{ route('inactiveexpensecategory', encrypt($item->id)) }}" class="btn btn-sm btn-outline-warning" onclick="return confirm('Deactivate this expense category?')">
                                        <i class="fas fa-ban"></i> Deactivate
                                    </a>
                                @else
                                    <a href="{{ route('activeexpensecategory', encrypt($item->id)) }}" class="btn btn-sm btn-outline-success" onclick="return confirm('Activate this expense category?')">
                                        <i class="fas fa-check-circle"></i> Activate
                                    </a>
                                @endif

                                @can('view-generally-delete-of-any-item')
                                    <a href="{{ route('deleteexpensecategory', encrypt($item->id)) }}" class="arbif-btn-delete" onclick="return confirm('Are you sure you want to delete this expense category?')">
                                        <i class="fas fa-trash"></i> Del
                                    </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No Expense Categories Found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- REGISTER EXPENSE CATEGORY MODAL --}}
<div class="modal fade arbif-modal" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon">
                    <i class="fas fa-folder-plus"></i>
                </div>
                <h5 class="modal-title" id="addCategoryModalLabel">Register Expense Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('storeexpensecategory') }}">
                    @csrf

                    <div class="md-3">
                        <label class="form-label">Branch <span class="text-danger">*</span></label>
                        <select name="branch_id" class="form-select" data-searchable data-placeholder="Search branch..." required>
                            <option value="">-- Select Branch --</option>
                            @foreach($branchies as $branch)
                                <option value="{{ $branch->id }}"
                                    {{ old('branch_id', '') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->branch_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Category Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="e.g. Office Supplies, Travel & Lodging" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="4" class="form-control" placeholder="Provide category purpose and guidelines...">{{ old('description') }}</textarea>
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
    document.addEventListener("DOMContentLoaded", function () {
        if (typeof DataTable !== 'undefined') {
            new DataTable('#expenseCategoryTable', {
                perPage: 10,
                searchable: true,
                sortable: true
            });
        }
    });
</script>
@endpush