@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Edit Donation</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('donations.update', $donation->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Donation Info --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="donation_title" class="form-label">Donation Title</label>
                        <input type="text" name="donation_title" id="donation_title" value="{{ old('donation_title', $donation->donation_title) }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="support_type" class="form-label">Support Type</label>
                        <input type="text" name="support_type" id="support_type" value="{{ old('support_type', $donation->support_type) }}" class="form-control">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" rows="3" class="form-control">{{ old('description', $donation->description) }}</textarea>
                </div>

                {{-- Recipient Info --}}
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="recipient_name" class="form-label">Recipient Name</label>
                        <input type="text" name="recipient_name" id="recipient_name" value="{{ old('recipient_name', $donation->recipient_name) }}" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label for="recipient_type" class="form-label">Recipient Type</label>
                        <input type="text" name="recipient_type" id="recipient_type" value="{{ old('recipient_type', $donation->recipient_type) }}" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label for="recipient_contact" class="form-label">Recipient Contact</label>
                        <input type="text" name="recipient_contact" id="recipient_contact" value="{{ old('recipient_contact', $donation->recipient_contact) }}" class="form-control">
                    </div>
                </div>

                {{-- Donation Specifics --}}
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="amount" class="form-label">Total Amount</label>
                        <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount', $donation->amount) }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="currency" class="form-label">Currency</label>
                        <input type="text" name="currency" id="currency" value="{{ old('currency', $donation->currency) }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="donation_date" class="form-label">Donation Date</label>
                        <input type="date" name="donation_date" id="donation_date" value="{{ old('donation_date', $donation->donation_date) }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="pending" {{ $donation->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $donation->status == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="completed" {{ $donation->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                </div>

                {{-- Attachment --}}
                <div class="mb-3">
                    <label for="attachment" class="form-label">Attachment (optional)</label>
                    <input type="file" name="attachment" id="attachment" class="form-control">
                    @if($donation->attachment)
                        <small class="text-muted">Current file: <a href="{{ asset('storage/'.$donation->attachment) }}" target="_blank">View</a></small>
                    @endif
                </div>

                {{-- Donation Items --}}
                <div class="border rounded p-3 mt-4">
                    <h5 class="text-primary mb-3">Donation Items</h5>
                    <div id="itemContainer">
                        @foreach($donation->items as $index => $item)
                        <div class="row g-3 mb-2 item-row">
                            <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                            <div class="col-md-3">
                                <input type="text" name="items[{{ $index }}][item_name]" class="form-control" value="{{ $item->item_name }}" placeholder="Item Name">
                            </div>
                            <div class="col-md-2">
                                <input type="number" name="items[{{ $index }}][quantity]" class="form-control" value="{{ $item->quantity }}" placeholder="Qty">
                            </div>
                            <div class="col-md-3">
                                <input type="number" step="0.01" name="items[{{ $index }}][unit_value]" class="form-control" value="{{ $item->unit_value }}" placeholder="Unit Value">
                            </div>
                            <div class="col-md-3">
                                <input type="number" step="0.01" name="items[{{ $index }}][total_value]" class="form-control" value="{{ $item->total_value }}" placeholder="Total Value">
                            </div>
                            <div class="col-md-1 d-flex align-items-center">
                                <button type="button" class="btn btn-danger btn-sm remove-item">×</button>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="addItemBtn">+ Add Item</button>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-success px-4">Update Donation</button>
                    <a href="{{ route('donations.index') }}" class="btn btn-secondary px-4">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- JavaScript for dynamic fields --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    let itemIndex = {{ $donation->items->count() }};
    const addBtn = document.getElementById('addItemBtn');
    const container = document.getElementById('itemContainer');

    addBtn.addEventListener('click', () => {
        const row = document.createElement('div');
        row.classList.add('row', 'g-3', 'mb-2', 'item-row');
        row.innerHTML = `
            <div class="col-md-3"><input type="text" name="items[${itemIndex}][item_name]" class="form-control" placeholder="Item Name"></div>
            <div class="col-md-2"><input type="number" name="items[${itemIndex}][quantity]" class="form-control" placeholder="Qty"></div>
            <div class="col-md-3"><input type="number" step="0.01" name="items[${itemIndex}][unit_value]" class="form-control" placeholder="Unit Value"></div>
            <div class="col-md-3"><input type="number" step="0.01" name="items[${itemIndex}][total_value]" class="form-control" placeholder="Total Value"></div>
            <div class="col-md-1 d-flex align-items-center">
                <button type="button" class="btn btn-danger btn-sm remove-item">×</button>
            </div>
        `;
        container.appendChild(row);
        itemIndex++;
    });

    container.addEventListener('click', e => {
        if (e.target.classList.contains('remove-item')) {
            e.target.closest('.item-row').remove();
        }
    });
});
</script>
@endsection


