@extends('layouts.workingside')

@section('content')

<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="fw-bold mb-1">

                <i class="bi bi-pencil-square me-2"></i>

                Edit Purchase Request

            </h4>

            <p class="text-muted mb-0">

                {{ $purchaseRequest->request_no }}

            </p>

        </div>


        <div class="d-flex gap-2">

            <a href="{{ route('viewpurchaserequest', encrypt($purchaseRequest->id)) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left me-1"></i>
                Back

            </a>

        </div>

    </div>


    {{-- INFORMATION --}}
    <div class="alert alert-warning">

        <i class="bi bi-info-circle me-2"></i>

        This purchase request is currently a
        <strong>Draft</strong>.

        You can modify the request before submitting it.

    </div>


    <form method="POST"
          action="#"
          id="editPurchaseRequestForm">

        @csrf


        {{-- REQUEST INFORMATION --}}
        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-white">

                <strong>
                    <i class="bi bi-info-circle me-1"></i>
                    Request Information
                </strong>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-3">

                        <label class="form-label">
                            Request Number
                        </label>

                        <input type="text"
                               class="form-control bg-light"
                               value="{{ $purchaseRequest->request_no }}"
                               readonly>

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Request Date
                        </label>

                        <input type="date"
                               name="request_date"
                               class="form-control"
                               value="{{ optional($purchaseRequest->request_date)->format('Y-m-d') }}"
                               required>

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Required Date
                        </label>

                        <input type="date"
                               name="required_date"
                               class="form-control"
                               value="{{ optional($purchaseRequest->required_date)->format('Y-m-d') }}">

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Priority
                        </label>

                        <select name="priority"
                                class="form-select">

                            @foreach(['Low','Normal','High','Urgent'] as $priority)

                                <option value="{{ $priority }}"
                                    {{ $purchaseRequest->priority == $priority ? 'selected' : '' }}>

                                    {{ $priority }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Purpose
                        </label>

                        <input type="text"
                               name="purpose"
                               class="form-control"
                               value="{{ $purchaseRequest->purpose }}">

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Budget Amount
                        </label>

                        <input type="number"
                               name="budget_amount"
                               class="form-control"
                               min="0"
                               step="0.01"
                               value="{{ $purchaseRequest->budget_amount }}">

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Currency
                        </label>

                        <select name="currency_code"
                                class="form-select">

                            <option value="TZS"
                                {{ $purchaseRequest->currency_code == 'TZS' ? 'selected' : '' }}>
                                TZS
                            </option>

                            <option value="USD"
                                {{ $purchaseRequest->currency_code == 'USD' ? 'selected' : '' }}>
                                USD
                            </option>

                        </select>

                    </div>


                    <div class="col-12">

                        <label class="form-label">
                            Justification
                        </label>

                        <textarea name="justification"
                                  class="form-control"
                                  rows="4">{{ $purchaseRequest->justification }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ITEMS --}}
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


                <div class="border-top pt-3 mt-3">

                    <div class="row justify-content-end">

                        <div class="col-md-4">

                            <div class="d-flex justify-content-between">

                                <strong>
                                    Estimated Total:
                                </strong>

                                <strong class="text-primary">

                                    <span id="grandTotal">
                                        0.00
                                    </span>

                                    {{ $purchaseRequest->currency_code }}

                                </strong>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ACTIONS --}}
        <div class="card shadow-sm border-0">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <a href="{{ route('viewpurchaserequest', encrypt($purchaseRequest->id)) }}"
                       class="btn btn-outline-secondary">

                        Cancel

                    </a>


                    <button type="button"
                            class="btn btn-primary"
                            onclick="showUpdateMessage()">

                        <i class="bi bi-save me-1"></i>
                        Save Changes

                    </button>

                </div>

            </div>

        </div>

    </form>

</div>


<script>

const products = @json(
    $products->map(function($product) {

        return [

            'id' => $product->id,

            'code' => $product->product_code,

            'name' => $product->product_name,

            'cost_price' => (float) $product->cost_price,

            'unit_id' => $product->unit_of_measure_id,

            'unit_name' => optional($product->unitOfMeasure)->UnitName,

            'procurement_type_id' =>
                $product->procurement_type_id,

            'procurement_name' =>
                optional($product->procurementType)->procurement_name,

        ];

    })
);


let itemIndex = 0;


/*
|--------------------------------------------------------------------------
| Existing Items
|--------------------------------------------------------------------------
*/

@foreach($purchaseRequest->items as $item)

addItem(
    {{ $item->product_id }},
    {{ $item->requested_quantity }},
    {{ $item->estimated_unit_price }},
    @json($item->description),
    @json($item->remarks)
);

@endforeach


/*
|--------------------------------------------------------------------------
| ADD ITEM
|--------------------------------------------------------------------------
*/

function addItem(
    productId = '',
    quantity = 1,
    price = 0,
    description = '',
    remarks = ''
) {

    const container =
        document.getElementById('itemsContainer');


    const row =
        document.createElement('div');


    row.className =
        'item-row border rounded p-3 mb-3 bg-light';


    row.innerHTML = `

        <div class="row g-3 align-items-end">

            <div class="col-md-5">

                <label class="form-label">
                    Product
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
                            ${product.id == productId ? 'selected' : ''}>

                            ${product.code} -
                            ${product.name}

                        </option>

                    `).join('')}

                </select>

            </div>


            <div class="col-md-2">

                <label class="form-label">
                    Quantity
                </label>

                <input
                    type="number"
                    name="items[${itemIndex}][quantity]"
                    class="form-control quantity-input"
                    min="0.001"
                    step="0.001"
                    value="${quantity}"
                    required>

            </div>


            <div class="col-md-2">

                <label class="form-label">
                    Unit Price
                </label>

                <input
                    type="number"
                    name="items[${itemIndex}][estimated_unit_price]"
                    class="form-control price-input"
                    min="0"
                    step="0.01"
                    value="${price}">

            </div>


            <div class="col-md-2">

                <label class="form-label">
                    Total
                </label>

                <input
                    type="text"
                    class="form-control item-total"
                    readonly>

            </div>


            <div class="col-md-1">

                <button
                    type="button"
                    class="btn btn-outline-danger w-100 remove-item">

                    <i class="bi bi-trash"></i>

                </button>

            </div>


            <div class="col-md-6">

                <label class="form-label">
                    Description
                </label>

                <input
                    type="text"
                    name="items[${itemIndex}][description]"
                    class="form-control"
                    value="${description ?? ''}">

            </div>


            <div class="col-md-6">

                <label class="form-label">
                    Remarks
                </label>

                <input
                    type="text"
                    name="items[${itemIndex}][remarks]"
                    class="form-control"
                    value="${remarks ?? ''}">

            </div>

        </div>

    `;


    container.appendChild(row);


    attachEvents(row);


    calculateRowTotal(row);


    itemIndex++;

}


/*
|--------------------------------------------------------------------------
| EVENTS
|--------------------------------------------------------------------------
*/

function attachEvents(row) {

    row.querySelector('.quantity-input')
        .addEventListener(
            'input',
            () => calculateRowTotal(row)
        );


    row.querySelector('.price-input')
        .addEventListener(
            'input',
            () => calculateRowTotal(row)
        );


    row.querySelector('.remove-item')
        .addEventListener(
            'click',
            function() {

                row.remove();

                calculateGrandTotal();

            }
        );

}


/*
|--------------------------------------------------------------------------
| TOTAL
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


    row.querySelector('.item-total').value =
        total.toLocaleString(
            'en-US',
            {
                minimumFractionDigits: 2
            }
        );


    calculateGrandTotal();

}


function calculateGrandTotal() {

    let total = 0;


    document
        .querySelectorAll('.item-row')
        .forEach(row => {

            const quantity =
                parseFloat(
                    row.querySelector('.quantity-input').value
                ) || 0;


            const price =
                parseFloat(
                    row.querySelector('.price-input').value
                ) || 0;


            total += quantity * price;

        });


    document.getElementById('grandTotal').innerText =
        total.toLocaleString(
            'en-US',
            {
                minimumFractionDigits: 2
            }
        );

}


/*
|--------------------------------------------------------------------------
| ADD BUTTON
|--------------------------------------------------------------------------
*/

document
    .getElementById('addItemBtn')
    .addEventListener(
        'click',
        () => addItem()
    );


/*
|--------------------------------------------------------------------------
| UPDATE MESSAGE
|--------------------------------------------------------------------------
*/

function showUpdateMessage() {

    alert(
        'The update method has not yet been connected. The Purchase Request update controller will be added next.'
    );

}

</script>

@endsection