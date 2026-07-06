@extends('layouts.workingside')

@section('title', 'Expense Category Configurations')

@section('page-title', 'Expense Category Configurations')

@section('content')

<div class="arbif-page-header d-flex justify-content-between align-items-center mb-4">
    <h3>
        <div class="page-icon">
            <i class="fas fa-tags"></i>
        </div>
        Expense Categories
    </h3>
    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
        <i class="fas fa-plus-circle me-1"></i> Register New Category
    </button>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="expenseCategoriesTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Category Name</th>
                        <th class="sortable">Description</th>
                        <th class="sortable">Created By</th>
                        <th class="sortable">Operational Status</th>
                        <th class="sortable">Audit Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $item->name }}</strong>
                        </td>
                        <td>
                            <span class="text-wrap d-block" style="max-width: 250px;">
                                {{ Str::limit($item->description ?? 'No explicit description provided.', 60) }}
                            </span>
                        </td>
                        <td>
                            {{ optional($item->creator)->name ?? 'System Context' }}
                        </td>
                        <td>
                            @if($item->Status == 'Active')
                                <span class="arbif-badge arbif-badge-success">Active</span>
                            @else
                                <span class="arbif-badge arbif-badge-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <small class="d-block text-muted">Auditing: <code>{{ $item->AuditingStatus }}</code></small>
                            <small class="d-block text-muted">Report: <code>{{ $item->ReportStatus }}</code></small>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                {{-- View Option --}}
                                <a href="{{ route('viewexpensecategory', encrypt($item->id)) }}" class="arbif-btn-view py-1 px-2" style="font-size: 0.8rem;">
                                    <i class="fas fa-eye"></i>
                                </a>

                                {{-- Edit Option --}}
                                <a href="{{ route('editexpensecategory', encrypt($item->id)) }}" class="arbif-btn-edit py-1 px-2" style="font-size: 0.8rem;">
                                    <i class="fas fa-edit"></i>
                                </a>

                                {{-- Status Modifier Triggers --}}
                                @if($item->Status == 'Active')
                                    <a href="{{ route('inactiveexpensecategory', encrypt($item->id)) }}" class="btn btn-warning btn-sm py-0 px-2" title="Deactivate Category" onclick="return confirm('Deactivate this category?');">
                                        <i class="fas fa-ban"></i>
                                    </a>
                                @else
                                    <a href="{{ route('activeexpensecategory', encrypt($item->id)) }}" class="btn btn-success btn-sm py-0 px-2" title="Activate Category" onclick="return confirm('Activate this category?');">
                                        <i class="fas fa-check"></i>
                                    </a>
                                @endif

                                {{-- Hard Removal Trigger --}}
                                <a href="{{ route('deleteexpensecategory', encrypt($item->id)) }}" class="arbif-btn-delete py-1 px-2" style="font-size: 0.8rem;" onclick="return confirm('Are you sure you want to delete this resource permanently?');">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            No Expense Categories Registered Inside the Database.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- REGISTRATION FORM MODAL FRAME --}}
<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('registerexpensecategory') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createCategoryModalLabel"><i class="fas fa-tags me-2"></i>Register New Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label font-weight-bold">Category Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="e.g. Office Consumables" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label font-weight-bold">Detailed Ledger Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" placeholder="Describe what transactions will sit under this classification row...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save Category Records</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection