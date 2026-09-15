@extends('layouts.configside')

@section('title', 'View Unit of Measure')
@section('page-title', 'View Unit of Measure')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-eye"></i>
        </div>
        Unit Details: {{ $data->UnitName }}
    </h3>
    <a href="{{ route('unitOfMeasure') }}" class="btn btn-secondary btn-sm d-flex align-items-center px-3" style="height: 38px; border-radius: 4px;">
        <i class="fas fa-arrow-left me-2"></i> Back to List
    </a>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="arbif-card mb-4">
            <div class="arbif-card-body text-center py-4">
                <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                    <i class="fas fa-balance-scale fa-2x text-primary"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">{{ $data->UnitName }}</h5>
                <span class="badge bg-success px-3 py-2 mt-2" style="font-size: 0.85rem;">{{ $data->Status }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="arbif-card">
            <div class="arbif-card-body">
                <h5 class="card-title border-bottom pb-2 mb-3 fw-bold text-navy">System Metrics & Footprint Logs</h5>
                
                <table class="table table-bordered table-striped custom-info-table">
                    <tbody>
                        <tr>
                            <th width="35%" class="fw-bold text-secondary">Unit Reference No</th>
                            <td class="fw-bold text-navy">{{ $data->UnitRefNo }}</td>
                        </tr>
                        <tr>
                            <th class="fw-bold text-secondary">Unit Code Symbol</th>
                            <td><span class="badge bg-dark px-2 py-1">{{ $data->UnitCode }}</span></td>
                        </tr>
                        <tr>
                            <th class="fw-bold text-secondary">Associated Corporate Entity</th>
                            <td>{{ $data->company->Name ?? $data->company->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th class="fw-bold text-secondary">Assigned Node Branch</th>
                            <td>{{ $data->branch->branch_name ?? 'Global Central Hub' }}</td>
                        </tr>
                        <tr>
                            <th class="fw-bold text-secondary">Created By (Audit Signature)</th>
                            <td>{{ $data->created_by ?? 'System Automated' }}</td>
                        </tr>
                        <tr>
                            <th class="fw-bold text-secondary">Last Handshake Action By</th>
                            <td>{{ $data->updated_by ?? 'System Automated' }}</td>
                        </tr>
                        <tr>
                            <th class="fw-bold text-secondary">Auditing Operations Flag</th>
                            <td>
                                <span class="badge bg-warning text-dark me-2">{{ $data->AuditingStatus }}</span>
                                <span class="badge bg-info text-dark">{{ $data->ReportStatus }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th class="fw-bold text-secondary">System Timestamp Logs</th>
                            <td>{{ $data->created_at ? $data->created_at->format('d M Y, h:i A') : 'N/A' }}</td>
                        </tr>
                    </tbody>
                </table>

                <div class="mt-4">
                    <h5 class="fw-bold text-secondary border-bottom pb-2">Functional Definition Details</h5>
                    <p class="text-muted bg-light p-3 border rounded" style="min-height: 100px; white-space: pre-wrap;">{{ $data->Description ?? 'No custom metrics definition descriptions supplied.' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection