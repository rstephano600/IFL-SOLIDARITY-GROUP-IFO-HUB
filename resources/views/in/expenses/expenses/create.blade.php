@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Add Expense</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('expenses.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Whoops!</strong> Please check the fields below for errors.
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
                {{-- Basic Expense Info --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Title</label>
                        <input type="text" name="expense_title" class="form-control" value="{{ old('expense_title') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Date</label>
                        <input type="date" name="expense_date" class="form-control" value="{{ old('expense_date', date('Y-m-d')) }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Category</label>
                        <select name="expense_category_id" class="form-select">
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Currency</label>
                        <input type="text" name="currency" class="form-control" value="{{ old('currency', 'TZS') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Total Amount</label>
                        <input type="number" step="0.01" name="total_amount" class="form-control" value="{{ old('total_amount', 0) }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="3" class="form-control">{{ old('description') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Attachment</label>
                    <input type="file" name="attachment" class="form-control">
                </div>

<hr>
<h5 class="fw-bold">Expense Items</h5>
<div id="items-container">
    <div class="row g-3 item-row mb-2">
        <div class="col-md-4">
            <input type="text" name="items[0][item_name]" class="form-control" placeholder="Item Name" required>
        </div>
        <div class="col-md-2">
            <input type="number" name="items[0][quantity]" class="form-control" placeholder="Qty" min="1" required>
        </div>
        <div class="col-md-2">
            <input type="number" step="0.01" name="items[0][unit_cost]" class="form-control" placeholder="Unit Cost">
        </div>
        <div class="col-md-2">
            <input type="text" name="items[0][supplier_name]" class="form-control" placeholder="Supplier">
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-danger remove-item">Remove</button>
        </div>
    </div>
</div>

<button type="button" id="add-item" class="btn btn-outline-primary mb-3">+ Add Item</button>

<div>
    <button type="submit" class="btn btn-success">Save Expense</button>
    <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Cancel</a>
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let index = 1;
    const container = document.getElementById('items-container');
    const addBtn = document.getElementById('add-item');

    addBtn.addEventListener('click', function() {
        const newRow = document.createElement('div');
        newRow.classList.add('row', 'g-3', 'item-row', 'mb-2');
        newRow.innerHTML = `
            <div class="col-md-4">
                <input type="text" name="items[${index}][item_name]" class="form-control" placeholder="Item Name" required>
            </div>
            <div class="col-md-2">
                <input type="number" name="items[${index}][quantity]" class="form-control" placeholder="Qty" min="1" required>
            </div>
            <div class="col-md-2">
                <input type="number" step="0.01" name="items[${index}][unit_cost]" class="form-control" placeholder="Unit Cost">
            </div>
            <div class="col-md-2">
                <input type="text" name="items[${index}][supplier_name]" class="form-control" placeholder="Supplier">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger remove-item">Remove</button>
            </div>
        `;
        container.appendChild(newRow);
        index++;
    });

    container.addEventListener('click', function(e) {
        if(e.target.classList.contains('remove-item')){
            e.target.closest('.item-row').remove();
        }
    });
});
</script>

@endsection
