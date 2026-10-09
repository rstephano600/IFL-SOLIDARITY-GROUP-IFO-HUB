@extends('layouts.workingside')

@section('title', 'Supplier Quotation - ' . $data->quotation_no)
@section('page-title', 'View Supplier Quotation')

@section('content')

<style>
    @media print {
        body {
            background-color: #fff !important;
            font-family: Arial, sans-serif;
            color: #000;
        }
        .no-print, header, nav, sidebar, .arbif-page-header, .footer {
            display: none !important;
        }
        .printable-area {
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
        }
        .print-header {
            display: flex !important;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .table-bordered th, .table-bordered td {
            border: 1px solid #000 !important;
        }
    }

    .print-header {
        display: none;
    }
</style>

<div class="arbif-page-header no-print">
    <h3>
        <div class="page-icon">
            <i class="fas fa-file-invoice"></i>
        </div>
        Quotation Profile: {{ $data->quotation_no }}
    </h3>

    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-primary d-flex align-items-center gap-2">
            <i class="fas fa-print"></i> Print Document
        </button>
        <a href="{{ route('supplierQuotation') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="arbif-card printable-area">
    <div class="arbif-card-body p-4">
        
        {{-- Header displayed only in print output --}}
        <div class="print-header">
            <div>
                <h2 style="margin:0; font-weight:bold; text-transform:uppercase;">{{ $data->company->name ?? 'COMPANY NAME' }}</h2>
                <p style="margin:0;">{{ $data->branch->branch_name ?? 'Branch Office' }}</p>
            </div>
            <div style="text-align: right;">
                <h3 style="margin:0;">SUPPLIER QUOTATION</h3>
                <small>Date: {{ \Carbon\Carbon::parse($data->quotation_date)->format('d M Y') }}</small>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-6">
                <h5 class="fw-bold text-muted">Supplier Information:</h5>
                <h4 class="text-primary font-weight-bold mb-1">{{ $data->supplier->SupplierName ?? $data->supplier->name ?? 'N/A' }}</h4>
                <p class="mb-0"><strong>Supplier Ref No:</strong> {{ $data->supplier_quotation_no ?? 'N/A' }}</p>
            </div>
            <div class="col-6 text-end">
                <p class="mb-1"><strong>Quotation No:</strong> {{ $data->quotation_no }}</p>
                <p class="mb-1"><strong>Date:</strong> {{ \Carbon\Carbon::parse($data->quotation_date)->format('d M Y') }}</p>
                <p class="mb-1"><strong>Valid Until:</strong> {{ $data->valid_until ? \Carbon\Carbon::parse($data->valid_until)->format('d M Y') : 'N/A' }}</p>
                <p class="mb-0"><strong>Status:</strong> <span class="badge bg-secondary">{{ strtoupper($data->quotation_status) }}</span></p>
            </div>
        </div>

        <hr>

        {{-- Item Table --}}
        <h5 class="fw-bold mb-3 border-bottom pb-2">Quotation Items Specification</h5>
        <div class="table-responsive mb-4">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th>Product / Service</th>
                        <th>Description</th>
                        <th class="text-end" style="width: 10%;">Qty</th>
                        <th class="text-end" style="width: 15%;">Unit Price</th>
                        <th class="text-end" style="width: 10%;">Discount</th>
                        <th class="text-end" style="width: 10%;">Tax</th>
                        <th class="text-end" style="width: 15%;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $item->product->product_name ?? $item->product->name ?? 'N/A' }}</strong></td>
                        <td>{{ $item->description ?? '-' }}</td>
                        <td class="text-end">{{ number_format($item->quantity, 2) }}</td>
                        <td class="text-end">{{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-end">{{ number_format($item->discount_amount, 2) }}</td>
                        <td class="text-end">{{ number_format($item->tax_amount, 2) }}</td>
                        <td class="text-end"><strong>{{ number_format($item->total_amount, 2) }}</strong></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">No items attached to this quotation.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Financial Breakdown --}}
        <div class="row mb-4">
            <div class="col-7">
                <h6 class="fw-bold">Payment Terms:</h6>
                <p class="small text-muted mb-2">{{ $data->payment_terms ?? 'N/A' }}</p>

                <h6 class="fw-bold">Delivery Terms:</h6>
                <p class="small text-muted mb-2">{{ $data->delivery_terms ?? 'N/A' }}</p>

                <h6 class="fw-bold">Notes:</h6>
                <p class="small text-muted mb-0">{{ $data->notes ?? 'None' }}</p>
            </div>
            <div class="col-5">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="text-end"><strong>Subtotal:</strong></td>
                        <td class="text-end">{{ $data->currency_code }} {{ number_format($data->subtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="text-end"><strong>Discount:</strong></td>
                        <td class="text-end">- {{ $data->currency_code }} {{ number_format($data->discount_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="text-end"><strong>Tax:</strong></td>
                        <td class="text-end">+ {{ $data->currency_code }} {{ number_format($data->tax_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="text-end"><strong>Delivery Fee:</strong></td>
                        <td class="text-end">+ {{ $data->currency_code }} {{ number_format($data->delivery_amount, 2) }}</td>
                    </tr>
                    <tr class="border-top border-2">
                        <td class="text-end fs-5"><strong>Grand Total:</strong></td>
                        <td class="text-end fs-5 fw-bold text-primary">{{ $data->currency_code }} {{ number_format($data->total_amount, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Signatures for printing --}}
        <div class="row mt-5 pt-4 d-none d-print-flex">
            <div class="col-4 text-center">
                <p>_______________________<br><strong>Prepared By</strong></p>
            </div>
            <div class="col-4 text-center">
                <p>_______________________<br><strong>Verified By</strong></p>
            </div>
            <div class="col-4 text-center">
                <p>_______________________<br><strong>Approved By</strong></p>
            </div>
        </div>

    </div>
</div>

@endsection