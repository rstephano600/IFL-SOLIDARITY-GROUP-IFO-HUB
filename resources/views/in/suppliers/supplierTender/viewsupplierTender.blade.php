@extends('layouts.workingside')

@section('title', 'Tender Document - ' . $data->tender_no)
@section('page-title', 'View Supplier Tender')

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
        Tender Profile: {{ $data->tender_no }}
    </h3>

    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-primary d-flex align-items-center gap-2">
            <i class="fas fa-print"></i> Print Document
        </button>
        <a href="{{ route('supplierTender') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
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
                <h3 style="margin:0;">TENDER DETAILS</h3>
                <small>Generated: {{ date('d M Y, H:i') }}</small>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-8">
                <h4 class="text-primary font-weight-bold">{{ $data->tender_title }}</h4>
                <p class="text-muted mb-1"><strong>Tender Ref No:</strong> {{ $data->tender_no }}</p>
                <p class="text-muted mb-0"><strong>Associated PR:</strong> {{ $data->purchaseRequest->pr_no ?? 'N/A' }}</p>
            </div>
            <div class="col-4 text-end">
                <span class="badge bg-navy fs-6 px-3 py-2 mb-2">Status: {{ strtoupper($data->tender_status) }}</span>
                <p class="mb-0"><strong>Branch:</strong> {{ $data->branch->branch_name ?? 'Central' }}</p>
            </div>
        </div>

        <hr>

        {{-- Core Specifications --}}
        <h5 class="fw-bold mb-3 border-bottom pb-2">1. Tender Specifications</h5>
        <div class="row mb-4">
            <div class="col-md-3 col-6 mb-3">
                <small class="text-muted d-block">Tender Opening Date</small>
                <strong>{{ \Carbon\Carbon::parse($data->tender_date)->format('d M Y') }}</strong>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <small class="text-muted d-block">Closing Date</small>
                <strong class="text-danger">{{ \Carbon\Carbon::parse($data->closing_date)->format('d M Y') }}</strong>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <small class="text-muted d-block">Estimated Budget</small>
                <strong>{{ $data->currency_code }} {{ number_format($data->estimated_budget, 2) }}</strong>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <small class="text-muted d-block">Evaluation Method</small>
                <strong>{{ $data->evaluation_method ?? 'Standard Evaluation' }}</strong>
            </div>
        </div>

        {{-- Description --}}
        <h5 class="fw-bold mb-2 border-bottom pb-2">2. Scope & Description</h5>
        <div class="p-3 bg-light rounded mb-4">
            <p class="mb-0">{!! nl2br(e($data->description ?? 'No specific details provided.')) !!}</p>
        </div>

        {{-- Award Information --}}
        <h5 class="fw-bold mb-3 border-bottom pb-2">3. Award Summary</h5>
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <small class="text-muted d-block">Awarded Supplier</small>
                <strong>{{ $data->awardedSupplier->supplier_name ?? $data->awardedSupplier->name ?? 'Not Yet Awarded' }}</strong>
            </div>
            <div class="col-md-6 mb-3">
                <small class="text-muted d-block">Award Date</small>
                <strong>{{ $data->awarded_at ? \Carbon\Carbon::parse($data->awarded_at)->format('d M Y, H:i') : 'Pending' }}</strong>
            </div>
            <div class="col-12">
                <small class="text-muted d-block">Award Decision Note</small>
                <p class="mb-0 text-dark">{{ $data->award_comment ?? 'N/A' }}</p>
            </div>
        </div>

        {{-- Audit Trail / Sign-off --}}
        <h5 class="fw-bold mb-3 border-bottom pb-2">4. Record Metadata</h5>
        <div class="row text-muted small">
            <div class="col-md-4">
                <strong>Created By:</strong> {{ $data->creator->name ?? $data->user->name ?? 'System User' }}
            </div>
            <div class="col-md-4">
                <strong>Last Updated By:</strong> {{ $data->updater->name ?? 'N/A' }}
            </div>
            <div class="col-md-4">
                <strong>Record Date:</strong> {{ \Carbon\Carbon::parse($data->created_at)->format('d M Y, H:i') }}
            </div>
        </div>

        {{-- Printable Signatures Section --}}
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