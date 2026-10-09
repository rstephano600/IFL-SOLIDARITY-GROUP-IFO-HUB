@extends('layouts.workingside')

@section('title', 'View Quotation Evaluation')
@section('page-title', 'View Quotation Evaluation')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-eye"></i>
        </div>
        Quotation Evaluation Details
    </h3>

    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-outline-secondary d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-print me-2"></i> Print
        </button>
        <a href="{{ route('quotationEvaluation') }}" class="btn btn-outline-secondary d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to List
        </a>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <h5 class="fw-bold text-navy mb-3"><i class="fas fa-info-circle me-1"></i> Evaluation Overview</h5>
                <table class="table table-borderless align-middle">
                    <tr>
                        <td class="fw-semibold" style="width: 160px;">Evaluation No:</td>
                        <td><span class="arbif-badge arbif-badge-navy">{{ $data->evaluation_no }}</span></td>
                    </tr>
                    <tr>
                        <td class="fw-semibold">Status:</td>
                        <td>
                            @if($data->evaluation_status == 'Approved')
                                <span class="badge bg-success">Approved</span>
                            @elseif($data->evaluation_status == 'Rejected')
                                <span class="badge bg-danger">Rejected</span>
                            @else
                                <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-semibold">Evaluated By:</td>
                        <td>{{ $data->evaluator->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold">Evaluated At:</td>
                        <td>{{ $data->evaluated_at ? \Carbon\Carbon::parse($data->evaluated_at)->format('d M, Y H:i') : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold">Branch:</td>
                        <td>{{ $data->branch->branch_name ?? $data->branch->name ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>

            <div class="col-md-6">
                <h5 class="fw-bold text-navy mb-3"><i class="fas fa-file-invoice me-1"></i> Quotation Info</h5>
                <table class="table table-borderless align-middle">
                    <tr>
                        <td class="fw-semibold" style="width: 160px;">Quotation No:</td>
                        <td>{{ $data->quotation->quotation_no ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold">Supplier Name:</td>
                        <td>{{ $data->quotation->supplier->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold">Quotation Date:</td>
                        <td>{{ isset($data->quotation->quotation_date) ? \Carbon\Carbon::parse($data->quotation->quotation_date)->format('d M, Y') : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold">Total Amount:</td>
                        <td><strong>{{ number_format($data->quotation->total_amount ?? 0, 2) }} {{ $data->quotation->currency_code ?? 'TZS' }}</strong></td>
                    </tr>
                </table>
            </div>
        </div>

        <hr>

        <h5 class="fw-bold text-navy mb-3"><i class="fas fa-chart-bar me-1"></i> Score Breakdown</h5>
        <div class="row g-3 mb-4 text-center">
            <div class="col-md-3">
                <div class="p-3 border rounded bg-light">
                    <span class="d-block text-muted small">Technical Score</span>
                    <span class="fs-4 fw-bold text-primary">{{ number_format($data->technical_score, 2) }}%</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 border rounded bg-light">
                    <span class="d-block text-muted small">Price Score</span>
                    <span class="fs-4 fw-bold text-success">{{ number_format($data->price_score, 2) }}%</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 border rounded bg-light">
                    <span class="d-block text-muted small">Delivery Score</span>
                    <span class="fs-4 fw-bold text-warning">{{ number_format($data->delivery_score, 2) }}%</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 border rounded bg-light">
                    <span class="d-block text-muted small">Quality Score</span>
                    <span class="fs-4 fw-bold text-info">{{ number_format($data->quality_score, 2) }}%</span>
                </div>
            </div>
        </div>

        <div class="p-3 mb-4 rounded text-center" style="background-color: #eef2f7; border: 1px solid #dcdfe6;">
            <h4 class="mb-0 fw-bold">Overall Evaluation Score: <span class="text-navy">{{ number_format($data->total_score, 2) }}%</span></h4>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="fw-bold form-label">Recommendation:</label>
                <div class="p-3 border rounded bg-light min-height-100">
                    {{ $data->recommendation ?? 'No recommendation provided.' }}
                </div>
            </div>
            <div class="col-md-6">
                <label class="fw-bold form-label">Comments / Remarks:</label>
                <div class="p-3 border rounded bg-light min-height-100">
                    {{ $data->evaluation_comment ?? 'No comments provided.' }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection