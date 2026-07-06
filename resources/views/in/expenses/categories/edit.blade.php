@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-warning text-dark">
            <h4 class="mb-0">Edit Expense Category</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('expense-categories.update', $expenseCategory->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Category Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $expenseCategory->name) }}" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description (optional)</label>
                    <textarea name="description" id="description" rows="3" class="form-control">{{ old('description', $expenseCategory->description) }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="active" {{ $expenseCategory->status === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="innactive" {{ $expenseCategory->status === 'innactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success">Update</button>
                    <a href="{{ route('expense-categories.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
