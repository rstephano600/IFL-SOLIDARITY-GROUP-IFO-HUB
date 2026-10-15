@extends('layouts.configside')

@section('title', 'View Staff Meal Shift')
@section('page-title', 'Meal Shift Details')

@section('content')

<div class="arbif-page-header mb-4">
    <h3>
        <div class="page-icon">
            <i class="fas fa-info-circle"></i>
        </div>
        Meal Shift: {{ $data->shift_name }}
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('staffMealShift') }}" class="btn btn-secondary px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to List
        </a>
        <a href="{{ route('editstaffMealShift', encrypt($data->id)) }}" class="arbif-btn-submit text-decoration-none">
            <i class="fas fa-pencil me-1"></i> Edit Shift
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="arbif-card mb-4">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-sliders-h me-1"></i> Shift Overview</h6>

                <table class="table table-borderless align-middle">
                    <tr>
                        <th style="width: 30%;" class="text-muted">Reference No:</th>
                        <td><span class="arbif-badge arbif-badge-navy">{{ $data->ReferenceNo }}</span></td>
                    </tr>
                    <tr>
                        <th class="text-muted">Shift Code:</th>
                        <td class="fw-bold">{{ $data->shift_code }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Shift Name:</th>
                        <td>{{ $data->shift_name }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Operating Hours:</th>
                        <td>
                            @if($data->start_time && $data->end_time)
                                <span class="badge bg-info text-dark fs-6">
                                    <i class="fas fa-clock me-1"></i>{{ \Carbon\Carbon::parse($data->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($data->end_time)->format('H:i') }}
                                </span>
                            @else
                                <span class="badge bg-secondary">Flexible / Unrestricted</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted">Display Order:</th>
                        <td>{{ $data->display_order }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- CONFIGURED RATES --}}
        <div class="arbif-card mb-4">
            <div class="arbif-card-body p-4">
                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-dollar-sign me-1"></i> Associated Meal Rates</h6>

                <div class="table-responsive">
                    <table class="table table-sm table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Meal Type</th>
                                <th>Unit Price (TZS)</th>
                                <th>Effective From</th>
                                <th>Effective Until</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data->mealRates as $rate)
                            <tr>
                                <td><strong>{{ $rate->meal_type }}</strong></td>
                                <td class="fw-bold text-success">{{ number_format($rate->unit_price, 2) }}</td>
                                <td>{{ $rate->effective_from ? $rate->effective_from->format('d M, Y') : 'N/A' }}</td>
                                <td>{{ $rate->effective_until ? $rate->effective_until->format('d M, Y') : 'Ongoing' }}</td>
                                <td><span class="badge bg-success">{{ $rate->Status }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No meal rates assigned to this shift.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
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