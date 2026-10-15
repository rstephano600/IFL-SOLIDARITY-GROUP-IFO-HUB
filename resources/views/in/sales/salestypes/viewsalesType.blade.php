@extends('layouts.configside')

@section('title', 'View Sales Type')
@section('page-title', 'Sales Type Details')

@section('content')

<div class="arbif-page-header mb-4">
    <h3>
        <div class="page-icon">
            <i class="fas fa-info-circle"></i>
        </div>
        Sales Type: {{ $data->type_name }}
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('salesType') }}" class="btn btn-secondary px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to List
        </a>
        <a href="{{ route('editsalesType', encrypt($data->id)) }}" class="arbif-btn-submit text-decoration-none">
            <i class="fas fa-pencil me-1"></i> Edit
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="arbif-card mb-4">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-sliders-h me-1"></i> Configuration Overview</h6>

                <table class="table table-borderless align-middle">
                    <tr>
                        <th style="width: 30%;" class="text-muted">Reference No:</th>
                        <td><span class="arbif-badge arbif-badge-navy">{{ $data->ReferenceNo }}</span></td>
                    </tr>
                    <tr>
                        <th class="text-muted">Type Code:</th>
                        <td class="fw-bold">{{ $data->type_code }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Type Name:</th>
                        <td>{{ $data->type_name }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Default Payment Terms:</th>
                        <td><span class="badge bg-info text-dark">{{ $data->default_payment_terms }}</span></td>
                    </tr>
                    <tr>
                        <th class="text-muted">Display Order:</th>
                        <td>{{ $data->display_order }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Requires Customer:</th>
                        <td>
                            @if($data->requires_customer)
                                <span class="badge bg-success">Yes</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted">Requires Employee:</th>
                        <td>
                            @if($data->requires_employee)
                                <span class="badge bg-success">Yes</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted">Allows Discount:</th>
                        <td>
                            @if($data->allows_discount)
                                <span class="badge bg-primary">Allowed</span>
                            @else
                                <span class="badge bg-danger">Disabled</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted">Description:</th>
                        <td>{{ $data->description ?? 'No description provided.' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="arbif-card">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-building me-1"></i> System Metadata</h6>

                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Status:</span>
                        <span class="badge bg-success">{{ $data->Status }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Company:</span>
                        <span class="fw-bold">{{ $data->company->company_name ?? 'N/A' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Branch:</span>
                        <span class="fw-bold">{{ $data->branch->branch_name ?? 'Global / Central' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Created By:</span>
                        <span>{{ $data->creator->name ?? 'System' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Created At:</span>
                        <span>{{ $data->created_at ? $data->created_at->format('d M, Y H:i') : 'N/A' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Updated At:</span>
                        <span>{{ $data->updated_at ? $data->updated_at->format('d M, Y H:i') : 'N/A' }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection