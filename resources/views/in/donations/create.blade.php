@extends('layouts.app')

@section('title', 'Record New Donation')
@section('page-title', 'New Donation')

@section('content')
<div class="container-fluid py-4">

    {{-- ================================================================= --}}
    {{-- HEADER --}}
    {{-- ================================================================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <h2 class="h3 mb-0 text-dark">
            <i class="bi bi-gift-fill me-2 text-danger"></i> Record New Donation
        </h2>
        <a href="{{ route('donations.index') }}" class="btn btn-secondary shadow-sm">
            <i class="bi bi-arrow-left-circle me-1"></i> Back to Donations
        </a>
    </div>

    {{-- ================================================================= --}}
    {{-- FORM CARD --}}
    {{-- ================================================================= --}}
    <div class="card shadow-lg border-0">
        <div class="card-body p-4">
            
            <form action="{{ route('donations.store') }}" method="POST" class="row g-4">
                @csrf
                
                {{-- Global Error Feedback --}}
                @if ($errors->any())
                    <div class="col-12">
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                {{-- --- Section 1: Donation Details --- --}}
                <div class="col-12">
                    <h5 class="text-primary fw-bold border-bottom pb-2"><i class="bi bi-info-circle me-1"></i> Primary Donation Information</h5>
                </div>

                {{-- Donation Title --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Donation Title <span class="text-danger">*</span></label>
                    <input type="text" name="donation_title" class="form-control @error('donation_title') is-invalid @enderror" 
                           value="{{ old('donation_title') }}" required>
                    @error('donation_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                {{-- Donation Date --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Donation Date <span class="text-danger">*</span></label>
                    <input type="date" name="donation_date" class="form-control @error('donation_date') is-invalid @enderror" 
                           value="{{ old('donation_date', \Carbon\Carbon::today()->format('Y-m-d')) }}" required>
                    @error('donation_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                {{-- Support Type --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Support Type</label>
                    <input type="text" name="support_type" class="form-control @error('support_type') is-invalid @enderror" 
                           value="{{ old('support_type') }}" placeholder="E.g., Cash, Goods, Service">
                    @error('support_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Total Amount --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Total Amount <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="amount" class="form-control @error('amount') is-invalid @enderror" 
                           value="{{ old('amount') }}" required placeholder="0.00">
                    @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                {{-- Currency & Status Group --}}
                <div class="col-md-4 row g-2">
                    <div class="col-6">
                        <label class="form-label fw-semibold">Currency</label>
                        <input type="text" name="currency" value="{{ old('currency', 'TZS') }}" class="form-control @error('currency') is-invalid @enderror">
                        @error('currency') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- --- Section 2: Recipient Details --- --}}
                <div class="col-12 mt-5">
                    <h5 class="text-primary fw-bold border-bottom pb-2"><i class="bi bi-people me-1"></i> Recipient Details</h5>
                </div>
                
                {{-- Recipient Name --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Recipient Name <span class="text-danger">*</span></label>
                    <input type="text" name="recipient_name" class="form-control @error('recipient_name') is-invalid @enderror" 
                           value="{{ old('recipient_name') }}" required>
                    @error('recipient_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                {{-- Recipient Type --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Recipient Type</label>
                    <input type="text" name="recipient_type" class="form-control @error('recipient_type') is-invalid @enderror" 
                           value="{{ old('recipient_type') }}" placeholder="Organization, Individual, etc.">
                    @error('recipient_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                {{-- Recipient Contact --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Recipient Contact (Phone/Email)</label>
                    <input type="text" name="recipient_contact" class="form-control @error('recipient_contact') is-invalid @enderror" 
                           value="{{ old('recipient_contact') }}">
                    @error('recipient_contact') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Description --}}
                <div class="col-12">
                    <label class="form-label fw-semibold">Description / Notes</label>
                    <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror" 
                              placeholder="Any additional details about the donation purpose or use.">{{ old('description') }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- --- Section 3: Donation Items (Dynamic) --- --}}
                <div class="col-12 mt-5">
                    <h5 class="text-primary fw-bold border-bottom pb-2">
                        <i class="bi bi-list-check me-1"></i> Detailed Donation Items 
                        <span class="small text-muted fw-normal">(For non-cash donations)</span>
                    </h5>
                </div>

                <div class="col-12">
                    <div class="row g-3 fw-semibold text-muted small mb-2 d-none d-md-flex">
                        <div class="col-md-4">Item Name</div>
                        <div class="col-md-2">Quantity</div>
                        <div class="col-md-2">Unit Value</div>
                        <div class="col-md-4"></div>
                    </div>

                    <div id="items-container">
                        {{-- Initial/Loopable Item Row --}}
                        <div class="row g-3 item-row mb-3 border p-3 rounded bg-light">
                            <div class="col-12 col-md-4">
                                <label class="form-label small fw-semibold d-md-none">Item Name</label>
                                <input type="text" name="items[0][item_name]" class="form-control form-control-sm" placeholder="Item Name" required>
                            </div>
                            <div class="col-6 col-md-2">
                                <label class="form-label small fw-semibold d-md-none">Quantity</label>
                                <input type="number" name="items[0][quantity]" class="form-control form-control-sm" placeholder="Qty" min="1" required>
                            </div>
                            <div class="col-6 col-md-2">
                                <label class="form-label small fw-semibold d-md-none">Unit Value</label>
                                <input type="number" step="0.01" name="items[0][unit_value]" class="form-control form-control-sm" placeholder="Unit Value">
                            </div>
                            <div class="col-12 col-md-4 d-flex align-items-center justify-content-end">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-item w-50 d-none">Remove</button>
                                <small class="text-muted d-md-block d-none">Initial Item</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <button type="button" id="add-item" class="btn btn-outline-primary btn-sm fw-semibold">
                        <i class="bi bi-plus-circle"></i> Add Another Item
                    </button>
                </div>


                {{-- --- Form Submission --- --}}
                <div class="col-12 mt-5 pt-3 border-top">
                    <button type="submit" class="btn btn-success btn-lg px-5 shadow-sm">
                        <i class="bi bi-save me-2"></i> Save Donation
                    </button>
                    <a href="{{ route('donations.index') }}" class="btn btn-secondary btn-lg ms-3">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ================================================================= --}}
{{-- JAVASCRIPT FOR DYNAMIC ITEMS --}}
{{-- ================================================================= --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    let index = 1; // Start index for new items
    const container = document.getElementById('items-container');
    const addBtn = document.getElementById('add-item');

    // Make the initial item row's remove button visible
    const initialRemoveBtn = container.querySelector('.item-row .remove-item');
    if (initialRemoveBtn) {
        initialRemoveBtn.classList.add('d-md-block'); 
        initialRemoveBtn.classList.remove('d-none');
        container.querySelector('.item-row .d-md-block:not(.remove-item)').classList.add('d-none'); // Hide 'Initial Item' text
    }


    addBtn.addEventListener('click', function() {
        const newRow = document.createElement('div');
        newRow.classList.add('row', 'g-3', 'item-row', 'mb-3', 'border', 'p-3', 'rounded', 'bg-light');
        newRow.innerHTML = `
            <div class="col-12 col-md-4">
                <label class="form-label small fw-semibold d-md-none">Item Name</label>
                <input type="text" name="items[${index}][item_name]" class="form-control form-control-sm" placeholder="Item Name" required>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label small fw-semibold d-md-none">Quantity</label>
                <input type="number" name="items[${index}][quantity]" class="form-control form-control-sm" placeholder="Qty" min="1" required>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label small fw-semibold d-md-none">Unit Value</label>
                <input type="number" step="0.01" name="items[${index}][unit_value]" class="form-control form-control-sm" placeholder="Unit Value">
            </div>
            <div class="col-12 col-md-4 d-flex align-items-center justify-content-end">
                <button type="button" class="btn btn-sm btn-danger remove-item w-50">
                    <i class="bi bi-trash"></i> Remove
                </button>
            </div>
        `;
        container.appendChild(newRow);
        index++;
    });

    // Delegated event listener for removing items
    container.addEventListener('click', function(e) {
        if (e.target.closest('.remove-item')) {
            e.target.closest('.item-row').remove();
        }
    });
});
</script>
@endsection