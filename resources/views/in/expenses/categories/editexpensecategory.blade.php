@extends('layouts.configside')

@section('title', 'Edit Expense Category')
@section('page-title', 'Edit Expense Category')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-pencil"></i>
        </div>
        Edit Expense Category
    </h3>

    <div>
        <a href="{{ route('expensecategoryinformations') }}" class="btn btn-secondary d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to Categories
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body p-4">
        <form method="POST" action="{{ route('updateexpensecategory', encrypt($data->id)) }}">
            @csrf

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Assigned Branch <span class="text-danger">*</span></label>
                    <select name="branch_id" class="form-select" required>
                        <option value="">-- Select Branch --</option>
                        @foreach($branchies as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id', $data->branch_id) == $branch->id ? 'selected' : '' }}>
                                {{ $branch->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Category Code</label>
                    <input type="text" class="form-control" value="{{ $data->expense_code }}" readonly disabled>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Category Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $data->name) }}" placeholder="e.g. Office Supplies, Travel & Lodging" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Description</label>
                <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror" placeholder="Provide category purpose and guidelines...">{{ old('description', $data->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- AUDIT FOOTER METADATA --}}
            <div class="p-3 bg-light rounded border mb-4">
                <div class="row text-muted small">
                    <div class="col-md-4">
                        <i class="fas fa-user-plus me-1"></i> <strong>Created By:</strong> {{ $data->creator->name ?? $data->user->name ?? 'N/A' }}
                    </div>
                    <div class="col-md-4">
                        <i class="fas fa-clock me-1"></i> <strong>Created At:</strong> {{ $data->created_at ? $data->created_at->format('d M, Y H:i') : 'N/A' }}
                    </div>
                    <div class="col-md-4">
                        <i class="fas fa-user-pen me-1"></i> <strong>Last Updated By:</strong> {{ $data->updater->name ?? 'N/A' }}
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('expensecategoryinformations') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="arbif-btn-submit">Update Category</button>
            </div>
        </form>
    </div>
</div>

@endsection