<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $invoice->bill_no }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 30px; background: #fff; }
        .invoice-header { border-bottom: 2px solid #0d6efd; padding-bottom: 20px; margin-bottom: 20px; }
        .company-logo { max-height: 80px; width: auto; object-fit: contain; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
        }
    </style>
</head>
<body>

<div class="text-end mb-3 no-print">
    <button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print me-1"></i> Print Invoice</button>
</div>

<div class="invoice-header d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center gap-3">
        <img src="{{ asset('logo/iflsglogo.png') }}" alt="Company Logo" class="company-logo">
        <div>
            <h2 class="fw-bold text-primary mb-0">{{ $invoice->company->company_name ?? 'COMPANY INVOICE' }}</h2>
            <small class="text-muted">{{ $invoice->branch->branch_name ?? 'Main Branch' }}</small>
        </div>
    </div>
    <div class="text-end">
        <h3 class="fw-bold">INVOICE</h3>
        <strong># {{ $invoice->bill_no }}</strong>
    </div>
</div>

<div class="row mb-4">
    <div class="col-6">
        <h6 class="fw-bold text-uppercase text-muted">Billed To:</h6>
        <h5 class="fw-bold mb-1">{{ $invoice->customer->customer_name }}</h5>
        <p class="mb-0">{{ $invoice->customer->address ?? 'N/A' }}</p>
        <p class="mb-0">Phone: {{ $invoice->customer->phone ?? 'N/A' }}</p>
        <p class="mb-0">Email: {{ $invoice->customer->email ?? 'N/A' }}</p>
    </div>
    <div class="col-6 text-end">
        <p class="mb-1"><strong>Invoice Date:</strong> {{ $invoice->bill_date ? $invoice->bill_date->format('d M, Y') : 'N/A' }}</p>
        <p class="mb-1"><strong>Due Date:</strong> {{ $invoice->due_date ? $invoice->due_date->format('d M, Y') : 'N/A' }}</p>
        <p class="mb-1"><strong>Status:</strong> {{ $invoice->balance_amount == 0 ? 'PAID' : 'PENDING' }}</p>
    </div>
</div>

{{-- DETAILED PRODUCTS & DATES SOLD TABLE --}}
<table class="table table-bordered mb-4 align-middle">
    <thead class="table-light">
        <tr>
            <th style="width: 5%;">#</th>
            <th style="width: 15%;">Date Sold</th>
            <th style="width: 40%;">Product Item</th>
            <th style="width: 10%;" class="text-center">Qty</th>
            <th style="width: 15%;" class="text-end">Unit Price (TZS)</th>
            <th style="width: 15%;" class="text-end">Amount (TZS)</th>
        </tr>
    </thead>
    <tbody>
        @php $counter = 1; @endphp
        @foreach($invoice->items as $billItem)
            @if($billItem->sale && $billItem->sale->items)
                @foreach($billItem->sale->items as $saleLine)
                <tr>
                    <td>{{ $counter++ }}</td>
                    <td>{{ $billItem->sale->sale_date ? \Carbon\Carbon::parse($billItem->sale->sale_date)->format('d M, Y') : 'N/A' }}</td>
                    <td>
                        <strong>{{ $saleLine->product->product_name ?? 'N/A' }}</strong>
                        @if($saleLine->notes)
                            <br><small class="text-muted">{{ $saleLine->notes }}</small>
                        @endif
                    </td>
                    <td class="text-center">{{ number_format($saleLine->quantity, 2) }}</td>
                    <td class="text-end">{{ number_format($saleLine->unit_price, 2) }}</td>
                    <td class="text-end fw-bold">{{ number_format($saleLine->line_total, 2) }}</td>
                </tr>
                @endforeach
            @else
                {{-- Fallback if invoice line item is not linked directly to sale_items --}}
                <tr>
                    <td>{{ $counter++ }}</td>
                    <td>{{ $invoice->bill_date ? $invoice->bill_date->format('d M, Y') : 'N/A' }}</td>
                    <td>{{ $billItem->description }}</td>
                    <td class="text-center">{{ number_format($billItem->quantity, 2) }}</td>
                    <td class="text-end">{{ number_format($billItem->unit_price, 2) }}</td>
                    <td class="text-end fw-bold">{{ number_format($billItem->amount, 2) }}</td>
                </tr>
            @endif
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5" class="text-end fw-bold">Subtotal:</td>
            <td class="text-end fw-bold">{{ number_format($invoice->subtotal, 2) }}</td>
        </tr>
        <tr>
            <td colspan="5" class="text-end fw-bold">Total Paid:</td>
            <td class="text-end fw-bold text-success">{{ number_format($invoice->paid_amount, 2) }}</td>
        </tr>
        <tr>
            <td colspan="5" class="text-end fw-bold fs-6">Balance Due:</td>
            <td class="text-end fw-bold text-danger fs-6">{{ number_format($invoice->balance_amount, 2) }} TZS</td>
        </tr>
    </tfoot>
</table>

{{-- BRANCH PAYMENT BANK INFORMATION --}}
<div class="p-3 bg-light rounded border mb-4">
    <h6 class="fw-bold text-primary mb-2">Payment Details / Bank Account Info:</h6>
    <div class="row">
        <div class="col-md-4">
            <p class="mb-1"><strong>Bank Name:</strong> {{ $invoice->branch->bank_name ?? 'N/A' }}</p>
        </div>
        <div class="col-md-4">
            <p class="mb-1"><strong>Bank Account Name:</strong> {{ $invoice->branch->bank_account_name ?? 'N/A' }}</p>
        </div>
        <div class="col-md-4">
            <p class="mb-1"><strong>Account Number:</strong> {{ $invoice->branch->bank_account_number ?? 'N/A' }}</p>
        </div>
    </div>
</div>

<div class="row mt-5 pt-4">
    <div class="col-6">
        <p class="mb-0"><strong>Authorized Signature:</strong> ________________________</p>
    </div>
    <div class="col-6 text-end">
        <p class="mb-0"><strong>Customer Stamp / Signature:</strong> ________________________</p>
    </div>
</div>

</body>
</html>