@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-warning text-dark">
            <h4 class="mb-0">Edit Expense</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('expenses.update', $expense->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Basic Expense Info --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Title</label>
                        <input type="text" name="expense_title" class="form-control" value="{{ old('expense_title', $expense->expense_title) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Date</label>
                        <input type="date" name="expense_date" class="form-control" value="{{ old('expense_date', $expense->expense_date) }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Category</label>
                        <select name="expense_category_id" class="form-select">
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $expense->expense_category_id==$cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Currency</label>
                        <input type="text" name="currency" class="form-control" value="{{ old('currency', $expense->currency) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Total Amount</label>
                        <input type="number" step="0.01" name="total_amount" class="form-control" value="{{ old('total_amount', $expense->total_amount) }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="3" class="form-control">{{ old('description', $expense->description) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Attachment</label>
                    <input type="file" name="attachment" class="form-control">
                    @if($expense->attachment)
                        <small>Current: <a href="{{ asset('storage/'.$expense->attachment) }}" target="_blank">View</a></small>
                    @endif
                </div>
<hr>
<h5 class="fw-bold">Expense Items</h5>
<div id="items-container">
    @php $index = 0; @endphp
    @foreach($expense->items as $item)
    <div class="row g-3 item-row mb-2">
        <div class="col-md-4">
            <input type="text" name="items[{{ $index }}][item_name]" class="form-control" placeholder="Item Name" value="{{ $item->item_name }}" required>
        </div>
        <div class="col-md-2">
            <input type="number" name="items[{{ $index }}][quantity]" class="form-control" placeholder="Qty" min="1" value="{{ $item->quantity }}" required>
        </div>
        <div class="col-md-2">
            <input type="number" step="0.01" name="items[{{ $index }}][unit_cost]" class="form-control" placeholder="Unit Cost" value="{{ $item->unit_cost }}">
        </div>
        <div class="col-md-2">
            <input type="text" name="items[{{ $index }}][supplier_name]" class="form-control" placeholder="Supplier" value="{{ $item->supplier_name }}">
        </div>
        <div class="col-md-2">
            <input type="file" name="items[{{ $index }}][attachment]" class="form-control">
            @if($item->attachment)
                <small><a href="{{ asset('storage/'.$item->attachment) }}" target="_blank">View</a></small>
            @endif
        </div>
        <div class="col-md-12 text-end mt-1">
            <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
        </div>
    </div>
    @php $index++; @endphp
    @endforeach
</div>

<button type="button" id="add-item" class="btn btn-outline-primary mb-3">+ Add Item</button>

<div>
    <button type="submit" class="btn btn-warning">Update Expense</button>
    <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Cancel</a>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let index = {{ $index ?? 0 }};
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
                <input type="number" name="items[${index}][quantity]" class="form-control" placeholder="Qty" min="1" value="1" required>
            </div>
            <div class="col-md-2">
                <input type="number" step="0.01" name="items[${index}][unit_cost]" class="form-control" placeholder="Unit Cost">
            </div>
            <div class="col-md-2">
                <input type="text" name="items[${index}][supplier_name]" class="form-control" placeholder="Supplier">
            </div>
            <div class="col-md-2">
                <input type="file" name="items[${index}][attachment]" class="form-control">
            </div>
            <div class="col-md-12 text-end mt-1">
                <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
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
@endpush

@endsection
