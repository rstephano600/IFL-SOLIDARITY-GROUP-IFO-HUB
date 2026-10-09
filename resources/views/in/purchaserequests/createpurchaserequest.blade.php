@extends('layouts.workingside')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                <i class="bi bi-cart-plus me-2"></i>
                New Purchase Request
            </h4>

            <p class="text-muted mb-0">
                Request products, equipment, stock or services.
            </p>
        </div>

        <a href="{{ route('getpurchaserequests') }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>
            Back

        </a>

    </div>


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}
    @if ($errors->any())

        <div class="alert alert-danger alert-dismissible fade show">

            <strong>
                <i class="bi bi-exclamation-triangle me-1"></i>
                Please correct the following errors:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
        PURCHASE REQUEST FORM
    ========================================================== --}}
    <form method="POST"
          action="{{ route('storepurchaserequest') }}"
          id="purchaseRequestForm">

        @csrf


        {{-- =====================================================
            BASIC INFORMATION
        ====================================================== --}}
        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-white">

                <strong>
                    <i class="bi bi-info-circle me-1"></i>
                    Request Information
                </strong>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    {{-- REQUEST DATE --}}
                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Request Date
                            <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               name="request_date"
                               class="form-control @error('request_date') is-invalid @enderror"
                               value="{{ old('request_date', date('Y-m-d')) }}"
                               required>

                        @error('request_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- REQUIRED DATE --}}
                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Required Date
                        </label>

                        <input type="date"
                               name="required_date"
                               class="form-control @error('required_date') is-invalid @enderror"
                               value="{{ old('required_date') }}">

                        @error('required_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- PRIORITY --}}
                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Priority
                            <span class="text-danger">*</span>
                        </label>

                        <select name="priority"
                                class="form-select"
                                required>

                            <option value="Normal"
                                {{ old('priority', 'Normal') == 'Normal' ? 'selected' : '' }}>
                                Normal
                            </option>

                            <option value="Low"
                                {{ old('priority') == 'Low' ? 'selected' : '' }}>
                                Low
                            </option>

                            <option value="High"
                                {{ old('priority') == 'High' ? 'selected' : '' }}>
                                High
                            </option>

                            <option value="Urgent"
                                {{ old('priority') == 'Urgent' ? 'selected' : '' }}>
                                Urgent
                            </option>

                        </select>

                    </div>


                    {{-- CURRENCY --}}
                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Currency
                            <span class="text-danger">*</span>
                        </label>

                        <select name="currency_code"
                                class="form-select"
                                required>

                            <option value="TZS"
                                {{ old('currency_code', 'TZS') == 'TZS' ? 'selected' : '' }}>
                                TZS - Tanzanian Shilling
                            </option>

                            <option value="USD"
                                {{ old('currency_code') == 'USD' ? 'selected' : '' }}>
                                USD - US Dollar
                            </option>

                        </select>

                    </div>


                    {{-- PURPOSE --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Purpose
                        </label>

                        <input type="text"
                               name="purpose"
                               class="form-control"
                               placeholder="Example: Kitchen supplies for September"
                               value="{{ old('purpose') }}">

                    </div>


                    {{-- BUDGET --}}
                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Budget Amount
                        </label>

                        <input type="number"
                               name="budget_amount"
                               id="budget_amount"
                               class="form-control"
                               min="0"
                               step="0.01"
                               value="{{ old('budget_amount', 0) }}">

                    </div>


                    {{-- PROCUREMENT TYPE --}}
                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Procurement Type
                        </label>

                        <div id="procurementTypeBox"
                             class="form-control bg-light">

                            <span class="text-muted">
                                Automatically determined
                            </span>

                        </div>

                    </div>


                    {{-- JUSTIFICATION --}}
                    <div class="col-12">

                        <label class="form-label fw-semibold">
                            Justification / Description
                        </label>

                        <textarea name="justification"
                                  rows="3"
                                  class="form-control"
                                  placeholder="Explain why the purchase is required...">{{ old('justification') }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            PROCUREMENT WORKFLOW INFORMATION
        ====================================================== --}}
        <div class="card shadow-sm border-0 mb-4"
             id="workflowInformation"
             style="display:none;">

            <div class="card-header bg-light">

                <strong>
                    <i class="bi bi-diagram-3 me-1"></i>
                    Procurement Workflow
                </strong>

            </div>


            <div class="card-body">

                <div class="row">

                    <div class="col-md-4">

                        <small class="text-muted">
                            Procurement Type
                        </small>

                        <div id="workflowType"
                             class="fw-bold">
                            -
                        </div>

                    </div>


                    <div class="col-md-4">

                        <small class="text-muted">
                            Workflow Complexity
                        </small>

                        <div id="workflowComplexity"
                             class="fw-bold">
                            -
                        </div>

                    </div>


                    <div class="col-md-4">

                        <small class="text-muted">
                            Workflow Steps
                        </small>

                        <div id="workflowSteps"
                             class="fw-bold">
                            -
                        </div>

                    </div>

                </div>


                <div class="alert alert-info mt-3 mb-0">

                    <i class="bi bi-info-circle me-2"></i>

                    The system automatically determines the procurement
                    workflow from the products you select.

                </div>

            </div>

        </div>


        {{-- =====================================================
            DIFFERENT PROCUREMENT TYPE WARNING
        ====================================================== --}}
        <div id="differentTypeWarning"
             class="alert alert-warning d-none mb-4">

            <i class="bi bi-exclamation-triangle me-2"></i>

            <strong>Different procurement types selected.</strong>

            Products belonging to different procurement workflows
            must be submitted as separate purchase requests.

        </div>


        {{-- =====================================================
            REQUESTED ITEMS
        ====================================================== --}}
        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-white d-flex justify-content-between align-items-center">

                <strong>
                    <i class="bi bi-box-seam me-1"></i>
                    Requested Items
                </strong>

                <button type="button"
                        class="btn btn-sm btn-primary"
                        id="addItemBtn">

                    <i class="bi bi-plus-circle me-1"></i>
                    Add Item

                </button>

            </div>


            <div class="card-body">

                <div id="itemsContainer"></div>


                {{-- EMPTY MESSAGE --}}
                <div id="emptyItemsMessage"
                     class="text-center py-5">

                    <i class="bi bi-cart-x fs-1 text-muted"></i>

                    <h6 class="mt-3">
                        No items added
                    </h6>

                    <p class="text-muted">
                        Click "Add Item" to add products to this purchase request.
                    </p>

                    <button type="button"
                            class="btn btn-primary"
                            onclick="addItem()">

                        <i class="bi bi-plus-circle me-1"></i>
                        Add First Item

                    </button>

                </div>


                {{-- =================================================
                    TOTAL
                ================================================== --}}
                <div class="border-top pt-3 mt-3">

                    <div class="row justify-content-end">

                        <div class="col-md-4">

                            <div class="d-flex justify-content-between">

                                <strong>
                                    Estimated Total:
                                </strong>

                                <strong class="text-primary fs-5">

                                    <span id="grandTotal">
                                        0.00
                                    </span>

                                    <span id="grandTotalCurrency">
                                        {{ old('currency_code', 'TZS') }}
                                    </span>

                                </strong>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            ACTIONS
        ====================================================== --}}
        <div class="card shadow-sm border-0">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <a href="{{ route('getpurchaserequests') }}"
                       class="btn btn-outline-secondary">

                        <i class="bi bi-x-circle me-1"></i>
                        Cancel

                    </a>


                    <button type="submit"
                            class="btn btn-primary btn-lg"
                            id="saveRequestBtn">

                        <i class="bi bi-save me-1"></i>
                        Save Purchase Request

                    </button>

                </div>

            </div>

        </div>


    </form>

</div>


{{-- =============================================================
    JAVASCRIPT
============================================================= --}}

@php

    /*
     * Convert products into a simple array before passing
     * them to JavaScript.
     *
     * This avoids the Blade @json() parser problem that occurred
     * when a closure containing an array was placed directly
     * inside @json().
     */

    $productJsonData = $products->map(function ($product) {

        return [
            'id' => $product->id,
            'code' => $product->product_code,
            'name' => $product->product_name,
            'cost_price' => (float) $product->cost_price,
            'unit_id' => $product->unit_of_measure_id,
            'unit_name' => optional($product->unitOfMeasure)->UnitName,
            'procurement_type_id' => $product->procurement_type_id,
            'procurement_code' => optional($product->procurementType)->procurement_code,
            'procurement_name' => optional($product->procurementType)->procurement_name,
        ];

    })->values();

@endphp


<script>

    /*
    |--------------------------------------------------------------------------
    | PRODUCTS
    |--------------------------------------------------------------------------
    */

    const products = @json($productJsonData);


    /*
    |--------------------------------------------------------------------------
    | VARIABLES
    |--------------------------------------------------------------------------
    */

    let itemIndex = 0;

    let selectedProcurementType = null;


    /*
    |--------------------------------------------------------------------------
    | ADD ITEM
    |--------------------------------------------------------------------------
    */

    function addItem(productId = '') {

        document.getElementById('emptyItemsMessage').style.display = 'none';


        const container =
            document.getElementById('itemsContainer');


        const row =
            document.createElement('div');


        row.className =
            'item-row border rounded p-3 mb-3 bg-light';


        row.dataset.index =
            itemIndex;


        row.innerHTML = `

            <div class="row g-3 align-items-end">


                {{-- PRODUCT --}}
                <div class="col-md-5">

                    <label class="form-label fw-semibold">

                        Product

                        <span class="text-danger">*</span>

                    </label>


                    <select
                        name="items[${itemIndex}][product_id]"
                        class="form-select product-select"
                        required>

                        <option value="">
                            Select Product
                        </option>

                        ${products.map(product => `

                            <option
                                value="${product.id}"
                                ${parseInt(product.id) === parseInt(productId) ? 'selected' : ''}>

                                ${escapeHtml(product.code)}
                                -
                                ${escapeHtml(product.name)}

                            </option>

                        `).join('')}

                    </select>


                    <small class="product-info text-muted"></small>

                </div>


                {{-- QUANTITY --}}
                <div class="col-md-2">

                    <label class="form-label fw-semibold">

                        Quantity

                        <span class="text-danger">*</span>

                    </label>


                    <input
                        type="number"
                        name="items[${itemIndex}][quantity]"
                        class="form-control quantity-input"
                        min="0.001"
                        step="0.001"
                        value="1"
                        required>

                </div>


                {{-- UNIT PRICE --}}
                <div class="col-md-2">

                    <label class="form-label fw-semibold">

                        Unit Price

                    </label>


                    <input
                        type="number"
                        name="items[${itemIndex}][estimated_unit_price]"
                        class="form-control price-input"
                        min="0"
                        step="0.01"
                        value="0">

                </div>


                {{-- TOTAL --}}
                <div class="col-md-2">

                    <label class="form-label fw-semibold">

                        Estimated Total

                    </label>


                    <input
                        type="text"
                        class="form-control item-total"
                        value="0.00"
                        readonly>

                </div>


                {{-- REMOVE --}}
                <div class="col-md-1">

                    <button
                        type="button"
                        class="btn btn-outline-danger w-100 remove-item"
                        title="Remove item">

                        <i class="bi bi-trash"></i>

                    </button>

                </div>


                {{-- DESCRIPTION --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Description
                    </label>


                    <input
                        type="text"
                        name="items[${itemIndex}][description]"
                        class="form-control"
                        placeholder="Optional item description">

                </div>


                {{-- REMARKS --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Remarks
                    </label>


                    <input
                        type="text"
                        name="items[${itemIndex}][remarks]"
                        class="form-control"
                        placeholder="Optional remarks">

                </div>

            </div>

        `;


        container.appendChild(row);


        itemIndex++;


        attachRowEvents(row);


        calculateGrandTotal();


        updateProcurementType();

    }


    /*
    |--------------------------------------------------------------------------
    | ESCAPE HTML
    |--------------------------------------------------------------------------
    */

    function escapeHtml(value) {

        if (value === null || value === undefined) {
            return '';
        }


        return String(value)

            .replace(/&/g, '&amp;')

            .replace(/</g, '&lt;')

            .replace(/>/g, '&gt;')

            .replace(/"/g, '&quot;')

            .replace(/'/g, '&#039;');
    }


    /*
    |--------------------------------------------------------------------------
    | ATTACH ROW EVENTS
    |--------------------------------------------------------------------------
    */

    function attachRowEvents(row) {


        const productSelect =
            row.querySelector('.product-select');


        const quantityInput =
            row.querySelector('.quantity-input');


        const priceInput =
            row.querySelector('.price-input');


        const removeButton =
            row.querySelector('.remove-item');


        /*
         * Product change
         */

        productSelect.addEventListener(
            'change',
            function () {

                handleProductChange(row);

            }
        );


        /*
         * Quantity change
         */

        quantityInput.addEventListener(
            'input',
            function () {

                calculateRowTotal(row);

            }
        );


        /*
         * Price change
         */

        priceInput.addEventListener(
            'input',
            function () {

                calculateRowTotal(row);

            }
        );


        /*
         * Remove item
         */

        removeButton.addEventListener(
            'click',
            function () {

                row.remove();

                updateProcurementType();

                calculateGrandTotal();

                checkEmptyItems();

            }
        );


        /*
         * Existing product
         */

        if (productSelect.value) {

            handleProductChange(row);

        }

    }


    /*
    |--------------------------------------------------------------------------
    | HANDLE PRODUCT CHANGE
    |--------------------------------------------------------------------------
    */

    function handleProductChange(row) {


        const productId =
            parseInt(
                row.querySelector('.product-select').value
            );


        const product =
            products.find(
                p => parseInt(p.id) === productId
            );


        const productInfo =
            row.querySelector('.product-info');


        if (!product) {

            productInfo.innerHTML = '';

            updateProcurementType();

            calculateRowTotal(row);

            return;
        }


        /*
         * Set default price
         */

        const priceInput =
            row.querySelector('.price-input');


        if (
            !priceInput.value ||
            parseFloat(priceInput.value) === 0
        ) {

            priceInput.value =
                parseFloat(product.cost_price || 0)
                    .toFixed(2);

        }


        /*
         * Product information
         */

        const unitName =
            product.unit_name || 'N/A';


        const procurementName =
            product.procurement_name ||
            'Not configured';


        productInfo.innerHTML = `

            Unit:
            <strong>
                ${escapeHtml(unitName)}
            </strong>

            &nbsp; | &nbsp;

            Procurement:
            <strong>
                ${escapeHtml(procurementName)}
            </strong>

        `;


        /*
         * Update workflow
         */

        updateProcurementType();


        /*
         * Calculate total
         */

        calculateRowTotal(row);

    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE PROCUREMENT TYPE
    |--------------------------------------------------------------------------
    */

    function updateProcurementType() {


        const rows =
            document.querySelectorAll('.item-row');


        const typeIds = [];


        let typeName = null;

        let typeCode = null;


        rows.forEach(function (row) {


            const productId =
                parseInt(
                    row.querySelector('.product-select').value
                );


            if (!productId) {

                return;

            }


            const product =
                products.find(
                    p => parseInt(p.id) === productId
                );


            if (!product) {

                return;

            }


            if (product.procurement_type_id) {

                typeIds.push(
                    parseInt(
                        product.procurement_type_id
                    )
                );


                typeName =
                    product.procurement_name;


                typeCode =
                    product.procurement_code;

            }

        });


        const uniqueTypes =
            [...new Set(typeIds)];


        const typeBox =
            document.getElementById(
                'procurementTypeBox'
            );


        const workflowInformation =
            document.getElementById(
                'workflowInformation'
            );


        const differentTypeWarning =
            document.getElementById(
                'differentTypeWarning'
            );


        /*
         * No procurement type
         */

        if (uniqueTypes.length === 0) {


            selectedProcurementType = null;


            typeBox.innerHTML = `

                <span class="text-muted">
                    Automatically determined
                </span>

            `;


            workflowInformation.style.display =
                'none';


            differentTypeWarning.classList.add(
                'd-none'
            );


            return;
        }


        /*
         * Multiple procurement types
         */

        if (uniqueTypes.length > 1) {


            selectedProcurementType = null;


            typeBox.innerHTML = `

                <span class="text-danger fw-bold">

                    <i class="bi bi-exclamation-triangle me-1"></i>

                    Different procurement types

                </span>

            `;


            workflowInformation.style.display =
                'block';


            differentTypeWarning.classList.remove(
                'd-none'
            );


            document.getElementById(
                'workflowType'
            ).innerHTML = `

                <span class="text-danger">

                    Separate requests are required.

                </span>

            `;


            document.getElementById(
                'workflowComplexity'
            ).innerHTML = '-';


            document.getElementById(
                'workflowSteps'
            ).innerHTML = '-';


            return;
        }


        /*
         * Valid procurement type
         */

        selectedProcurementType =
            uniqueTypes[0];


        differentTypeWarning.classList.add(
            'd-none'
        );


        typeBox.innerHTML = `

            <span class="badge bg-primary">

                ${escapeHtml(typeName)}

            </span>

        `;


        workflowInformation.style.display =
            'block';


        document.getElementById(
            'workflowType'
        ).innerHTML =
            escapeHtml(typeName);


        /*
         * Determine complexity
         */

        let complexity =
            'Standard';


        let badge =
            'bg-info';


        if (
            typeCode === 'CAPITAL_EQUIPMENT' ||
            typeCode === 'CAPITAL_ASSET'
        ) {

            complexity =
                'Full Procurement';


            badge =
                'bg-warning text-dark';

        }

        else if (
            typeCode === 'SERVICE'
        ) {

            complexity =
                'Service Procurement';


            badge =
                'bg-secondary';

        }

        else if (
            typeCode === 'STOCK_RESALE'
        ) {

            complexity =
                'Stock Procurement';


            badge =
                'bg-primary';

        }

        else {

            complexity =
                'Quick Procurement';


            badge =
                'bg-success';

        }


        document.getElementById(
            'workflowComplexity'
        ).innerHTML = `

            <span class="badge ${badge}">
                ${complexity}
            </span>

        `;


        /*
         * Determine number of workflow steps
         */

        let steps = 5;


        if (
            typeCode === 'STOCK_RESALE'
        ) {

            steps = 7;

        }

        else if (
            typeCode === 'CAPITAL_EQUIPMENT' ||
            typeCode === 'CAPITAL_ASSET'
        ) {

            steps = 13;

        }

        else if (
            typeCode === 'SERVICE'
        ) {

            steps = 10;

        }


        document.getElementById(
            'workflowSteps'
        ).innerHTML =

            `${steps} workflow steps`;

    }


    /*
    |--------------------------------------------------------------------------
    | CALCULATE ROW TOTAL
    |--------------------------------------------------------------------------
    */

    function calculateRowTotal(row) {


        const quantity =
            parseFloat(
                row.querySelector('.quantity-input').value
            ) || 0;


        const price =
            parseFloat(
                row.querySelector('.price-input').value
            ) || 0;


        const total =
            quantity * price;


        row.querySelector(
            '.item-total'
        ).value =

            total.toLocaleString(
                'en-US',
                {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }
            );


        calculateGrandTotal();

    }


    /*
    |--------------------------------------------------------------------------
    | CALCULATE GRAND TOTAL
    |--------------------------------------------------------------------------
    */

    function calculateGrandTotal() {


        let total = 0;


        document
            .querySelectorAll('.item-row')
            .forEach(function (row) {


                const quantity =
                    parseFloat(
                        row.querySelector(
                            '.quantity-input'
                        ).value
                    ) || 0;


                const price =
                    parseFloat(
                        row.querySelector(
                            '.price-input'
                        ).value
                    ) || 0;


                total +=
                    quantity * price;

            });


        document.getElementById(
            'grandTotal'
        ).innerText =

            total.toLocaleString(
                'en-US',
                {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }
            );

    }


    /*
    |--------------------------------------------------------------------------
    | CHECK EMPTY ITEMS
    |--------------------------------------------------------------------------
    */

    function checkEmptyItems() {


        const rows =
            document.querySelectorAll(
                '.item-row'
            );


        document.getElementById(
            'emptyItemsMessage'
        ).style.display =

            rows.length === 0
                ? 'block'
                : 'none';

    }


    /*
    |--------------------------------------------------------------------------
    | ADD ITEM BUTTON
    |--------------------------------------------------------------------------
    */

    document
        .getElementById('addItemBtn')
        .addEventListener(
            'click',
            function () {

                addItem();

            }
        );


    /*
    |--------------------------------------------------------------------------
    | CURRENCY CHANGE
    |--------------------------------------------------------------------------
    */

    document
        .querySelector(
            'select[name="currency_code"]'
        )
        .addEventListener(
            'change',
            function () {

                document.getElementById(
                    'grandTotalCurrency'
                ).innerText =
                    this.value;

            }
        );


    /*
    |--------------------------------------------------------------------------
    | FORM VALIDATION
    |--------------------------------------------------------------------------
    */

    document
        .getElementById('purchaseRequestForm')
        .addEventListener(
            'submit',
            function (event) {


                const rows =
                    document.querySelectorAll(
                        '.item-row'
                    );


                /*
                 * No products
                 */

                if (rows.length === 0) {

                    event.preventDefault();


                    alert(
                        'Please add at least one product.'
                    );


                    return;

                }


                /*
                 * Check procurement type
                 */

                if (!selectedProcurementType) {

                    event.preventDefault();


                    alert(
                        'Please ensure all selected products belong to the same procurement type.'
                    );


                    return;

                }


                /*
                 * Validate every row
                 */

                let valid =
                    true;


                rows.forEach(function (row) {


                    const product =
                        row.querySelector(
                            '.product-select'
                        );


                    const quantity =
                        row.querySelector(
                            '.quantity-input'
                        );


                    if (!product.value) {

                        valid = false;

                        product.classList.add(
                            'is-invalid'
                        );

                    }

                    else {

                        product.classList.remove(
                            'is-invalid'
                        );

                    }


                    if (
                        !quantity.value ||
                        parseFloat(quantity.value) <= 0
                    ) {

                        valid = false;

                        quantity.classList.add(
                            'is-invalid'
                        );

                    }

                    else {

                        quantity.classList.remove(
                            'is-invalid'
                        );

                    }

                });


                if (!valid) {

                    event.preventDefault();


                    alert(
                        'Please complete all required item information.'
                    );


                    return;

                }


                /*
                 * Prevent double submission
                 */

                const button =
                    document.getElementById(
                        'saveRequestBtn'
                    );


                button.disabled =
                    true;


                button.innerHTML = `

                    <span
                        class="spinner-border spinner-border-sm me-2">
                    </span>

                    Saving Purchase Request...

                `;

            }
        );


    /*
    |--------------------------------------------------------------------------
    | OLD INPUT
    |--------------------------------------------------------------------------
    */

    @if(old('items'))

        @foreach(old('items') as $oldItem)

            addItem(
                @json($oldItem['product_id'] ?? '')
            );


            const lastRow =
                document.querySelectorAll(
                    '.item-row'
                )[
                    document.querySelectorAll(
                        '.item-row'
                    ).length - 1
                ];


            @if(isset($oldItem['quantity']))

                lastRow.querySelector(
                    '.quantity-input'
                ).value =
                    @json($oldItem['quantity']);

            @endif


            @if(isset($oldItem['estimated_unit_price']))

                lastRow.querySelector(
                    '.price-input'
                ).value =
                    @json($oldItem['estimated_unit_price']);

            @endif


            @if(isset($oldItem['description']))

                lastRow.querySelector(
                    '[name$="[description]"]'
                ).value =
                    @json($oldItem['description']);

            @endif


            @if(isset($oldItem['remarks']))

                lastRow.querySelector(
                    '[name$="[remarks]"]'
                ).value =
                    @json($oldItem['remarks']);

            @endif


            calculateRowTotal(lastRow);

        @endforeach

    @else

        addItem();

    @endif


    /*
    |--------------------------------------------------------------------------
    | INITIAL CALCULATION
    |--------------------------------------------------------------------------
    */

    calculateGrandTotal();

    updateProcurementType();

    checkEmptyItems();

</script>

@endsection