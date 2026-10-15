@extends('layouts.workingside')

@section('title', 'View Service Details')
@section('page-title', 'Service Item Details')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-info-circle"></i>
        </div>
        {{ $data->service_name }}
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('editserviceCategory', encrypt($data->id)) }}" class="arbif-btn-edit px-3 py-2 text-decoration-none" style="border-radius: 4px;">
            <i class="fas fa-pencil me-1"></i> Edit Service
        </a>
        <a href="{{ route('serviceCatalog') }}" class="btn btn-secondary d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to Catalog
        </a>
    </div>
</div>

<div class="row g-4">
    {{-- SERVICE INFO CARD --}}
    <div class="col-md-4">
        <div class="arbif-card h-100">
            <div class="arbif-card-body">
                <h5 class="fw-bold mb-3 border-bottom pb-2">Service Overview</h5>
                
                <p class="mb-2"><strong>Reference No:</strong><br>
                    <span class="arbif-badge arbif-badge-navy mt-1">{{ $data->ReferenceNo }}</span>
                </p>

                <p class="mb-2"><strong>Service Code:</strong><br>
                    <code>{{ $data->service_code }}</code>
                </p>

                <p class="mb-2"><strong>Category:</strong><br>
                    {{ $data->category->category_name ?? 'N/A' }}
                </p>

                <p class="mb-2"><strong>Branch:</strong><br>
                    {{ $data->branch->branch_name ?? 'Global / Central' }}
                </p>

                <p class="mb-2"><strong>Unit Price:</strong><br>
                    {{ number_format($data->default_unit_price, 2) }} / {{ $data->default_unit ?? 'Unit' }}
                </p>

                <p class="mb-2"><strong>Requirements:</strong><br>
                    <span class="badge bg-{{ $data->requires_product ? 'success' : 'secondary' }} me-1">Product: {{ $data->requires_product ? 'Yes' : 'No' }}</span>
                    <span class="badge bg-{{ $data->requires_attachment ? 'success' : 'secondary' }}">Attachment: {{ $data->requires_attachment ? 'Yes' : 'No' }}</span>
                </p>

                <p class="mb-2"><strong>Status:</strong><br>
                    <span class="badge bg-success">{{ $data->Status }}</span>
                </p>

                <p class="mb-0"><strong>Description:</strong><br>
                    {{ $data->description ?? 'No description provided.' }}
                </p>
            </div>
        </div>
    </div>

    {{-- ASSOCIATED REQUEST ITEMS TABLE --}}
    <div class="col-md-8">
        <div class="arbif-card h-100">
            <div class="arbif-card-body">
                <h5 class="fw-bold mb-3 border-bottom pb-2">Service Request Records</h5>
                
                <div class="arbif-table-wrap">
                    <table class="arbif-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Request ID / Ref</th>
                                <th>Quantity</th>
                                <th>Total Price</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data->requestItems as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->serviceRequest->ReferenceNo ?? 'N/A' }}</td>
                                <td>{{ $item->quantity ?? 1 }}</td>
                                <td>{{ number_format($item->total_price ?? 0, 2) }}</td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $item->Status ?? 'Active' }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No requests found linked to this service yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection