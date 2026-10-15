@extends('layouts.workingside')

@section('title', 'Create Service Request')
@section('page-title', 'Create Service Request')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
@endpush

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-plus-circle"></i>
        </div>
        New Service Request
    </h3>

    <div>
        <a href="{{ route('serviceRequest') }}" class="btn btn-secondary px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to List
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body p-4">
        <form method="POST" action="{{ route('storeServiceRequest') }}" enctype="multipart/form-data" id="serviceRequestForm">
            @csrf

            <h6 class="fw-bold text-primary mb-3"><i class="fas fa-info-circle me-1"></i> General Request Header</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Assigned Branch <span class="text-danger">*</span></label>
                    <select name="branch_id" class="form-select searchable-select" required>
                        <option value="">-- Select Branch --</option>
                        @foreach($branchies as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Committee <span class="text-danger">*</span></label>
                    <select name="committee_id" class="form-select searchable-select" required>
                        <option value="">-- Select Committee --</option>
                        @foreach($committees as $committee)
                            <option value="{{ $committee->id }}" {{ old('committee_id') == $committee->id ? 'selected' : '' }}>
                                {{ $committee->committee_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Service Category</label>
                    <select name="service_category_id" class="form-select searchable-select">
                        <option value="">-- Service Category --</option>
                        @foreach($servicescategories as $servicescategory)
                            <option value="{{ $servicescategory->id }}" {{ old('service_category_id') == $servicescategory->id ? 'selected' : '' }}>
                                {{ $servicescategory->category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Request Title <span class="text-danger">*</span></label>
                    <input type="text" name="request_title" class="form-control" value="{{ old('request_title') }}" placeholder="e.g. ANNUAL IT MAINTENANCE & EQUIPMENT SERVICE" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Request Date <span class="text-danger">*</span></label>
                    <input type="date" name="request_date" class="form-control" value="{{ old('request_date', date('Y-m-d')) }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Required Date</label>
                    <input type="date" name="required_date" class="form-control" value="{{ old('required_date') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Priority <span class="text-danger">*</span></label>
                    <select name="priority" class="form-select" required>
                        <option value="Low" {{ old('priority') == 'Low' ? 'selected' : '' }}>Low</option>
                        <option value="Medium" {{ old('priority') == 'Medium' || !old('priority') ? 'selected' : '' }}>Medium</option>
                        <option value="High" {{ old('priority') == 'High' ? 'selected' : '' }}>High</option>
                        <option value="Urgent" {{ old('priority') == 'Urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Currency <span class="text-danger">*</span></label>
                    <input type="text" name="requested_currency" class="form-control" value="{{ old('requested_currency', 'TZS') }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Beneficiary Name</label>
                    <input type="text" name="beneficiary_name" class="form-control" value="{{ old('beneficiary_name') }}" placeholder="e.g. IT Department / External Provider">
                </div>

                <div class="col-12">
                    <label class="form-label">Purpose / Overview</label>
                    <textarea name="purpose" class="form-control" rows="2" placeholder="Brief explanation of why this service or products are needed...">{{ old('purpose') }}</textarea>
                </div>
            </div>

            {{-- REQUEST ITEMS SECTION --}}
            <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                <h6 class="fw-bold text-primary mb-0"><i class="fas fa-list-check me-1"></i> Service & Item Breakdown</h6>
                <button type="button" class="btn btn-sm btn-outline-primary" id="addItemRow">
                    <i class="fas fa-plus me-1"></i> Add Item Row
                </button>
            </div>

            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle" id="itemsTable">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 20%;">Item Type</th>
                            <th style="width: 45%;">Service Catalog / Product (Searchable)</th>
                            <th style="width: 20%;">Description / Notes</th>
                            <th style="width: 10%;">Quantity <span class="text-danger">*</span></th>
                            <th style="width: 5%;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="itemsBody">
                        <tr class="item-row">
                            <td>
                                <select name="items[0][item_type]" class="form-select item-type-select" required>
                                    <option value="Service">Service</option>
                                    <option value="Product">Product</option>
                                    <option value="Expense">Expense</option>
                                    <option value="Other">Other</option>
                                </select>
                            </td>
                            <td>
                                <div class="catalog-wrap">
                                    <select name="items[0][service_catalog_id]" class="form-select service-select searchable-select">
                                        <option value="">-- Search Service Catalog --</option>
                                        @foreach($services as $srv)
                                            <option value="{{ $srv->id }}">{{ $srv->service_name ?? $srv->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="product-wrap d-none">
                                    <select name="items[0][product_id]" class="form-select product-select searchable-select">
                                        <option value="">-- Search Product --</option>
                                        @foreach($products as $prod)
                                            <option value="{{ $prod->id }}">{{ $prod->product_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </td>
                            <td>
                                <input type="text" name="items[0][item_description]" class="form-control" placeholder="Item details..." required>
                            </td>
                            <td>
                                <input type="number" step="0.001" name="items[0][quantity]" class="form-control qty-input" value="1" min="0.001" required>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-row" disabled>
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- ATTACHMENTS --}}
            <h6 class="fw-bold text-primary mb-3"><i class="fas fa-paperclip me-1"></i> Supporting Documents</h6>
            <div class="mb-4">
                <input type="file" name="attachments[]" class="form-control" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                <small class="text-muted">You can select multiple files (PDF, Word documents, or Images).</small>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('serviceRequest') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="arbif-btn-submit">Submit Service Request</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    let rowIndex = 1;

    function initSelect2(element) {
        $(element).select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: '-- Select / Search --',
            allowClear: true
        });
    }

    $(document).ready(function() {$('.searchable-select').each(function() {
            initSelect2(this);
        });

        // Toggle Service vs Product Selects
        $('#itemsBody').on('change', '.item-type-select', function() {
            const row = $(this).closest('.item-row');
            const type = $(this).val();

            if (type === 'Product') {
                row.find('.catalog-wrap').addClass('d-none');
                row.find('.product-wrap').removeClass('d-none');
            } else {
                row.find('.product-wrap').addClass('d-none');
                row.find('.catalog-wrap').removeClass('d-none');
            }
        });

        $('#addItemRow').on('click', function () {
            const tbody = document.getElementById('itemsBody');
            const firstRow = tbody.querySelector('.item-row');

            // Destroy select2 before cloning
            $(firstRow).find('.searchable-select').select2('destroy');

            const newRow = firstRow.cloneNode(true);

            // Re-init select2 on original row
            $(firstRow).find('.searchable-select').each(function() { initSelect2(this); });

            // Reset dynamic input names and values
            $(newRow).find('input, select').each(function() {
                const name = $(this).attr('name');
                if (name) {
                    $(this).attr('name', name.replace(/\[\d+\]/, `[${rowIndex}]`));
                }
                if ($(this).hasClass('qty-input'))$(this).val(1);
                else if ($(this).is('input'))$(this).val('');
            });

            $(newRow).find('.remove-row').removeAttr('disabled');
            tbody.appendChild(newRow);

            // Init select2 on cloned row
            $(newRow).find('.searchable-select').each(function() { initSelect2(this); });

            rowIndex++;
        });

        $('#itemsBody').on('click', '.remove-row', function () {
            if (document.querySelectorAll('.item-row').length > 1) {
                $(this).closest('.item-row').remove();
            }
        });
    });
</script>
@endpush