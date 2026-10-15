@extends('layouts.workingside')

@section('title', 'View Service Category Details')
@section('page-title', 'Service Category Details')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-info-circle"></i>
        </div>
        {{ $data->category_name }}
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('editserviceCategory', encrypt($data->id)) }}" class="arbif-btn-edit px-3 py-2 text-decoration-none" style="border-radius: 4px;">
            <i class="fas fa-pencil me-1"></i> Edit Category
        </a>
        <a href="{{ route('serviceCategory') }}" class="btn btn-secondary d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-arrow-left me-2"></i> Back to List
        </a>
    </div>
</div>

<div class="row g-4">
    {{-- CATEGORY INFO CARD --}}
    <div class="col-md-4">
        <div class="arbif-card h-100">
            <div class="arbif-card-body">
                <h5 class="fw-bold mb-3 border-bottom pb-2">Category Overview</h5>
                
                <p class="mb-2"><strong>Reference No:</strong><br>
                    <span class="arbif-badge arbif-badge-navy mt-1">{{ $data->ReferenceNo }}</span>
                </p>

                <p class="mb-2"><strong>Category Code:</strong><br>
                    <code>{{ $data->category_code }}</code>
                </p>

                <p class="mb-2"><strong>Branch:</strong><br>
                    {{ $data->branch->branch_name ?? 'Global / Central' }}
                </p>

                <p class="mb-2"><strong>Company:</strong><br>
                    {{ $data->company->company_name ?? 'N/A' }}
                </p>

                <p class="mb-2"><strong>Status:</strong><br>
                    <span class="badge bg-success">{{ $data->Status }}</span>
                </p>

                <p class="mb-2"><strong>Registered By:</strong><br>
                    {{ $data->user->name ?? 'N/A' }}
                </p>

                <p class="mb-0"><strong>Description:</strong><br>
                    {{ $data->description ?? 'No description provided.' }}
                </p>
            </div>
        </div>
    </div>

    {{-- ASSOCIATED SERVICES TABLE --}}
    <div class="col-md-8">
        <div class="arbif-card h-100">
            <div class="arbif-card-body">
                <h5 class="fw-bold mb-3 border-bottom pb-2">Associated Services</h5>
                
                <div class="arbif-table-wrap">
                    <table class="arbif-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Service Name</th>
                                <th>Code</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data->services as $index => $service)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $service->service_name ?? 'N/A' }}</td>
                                <td><code>{{ $service->service_code ?? 'N/A' }}</code></td>
                                <td>
                                    <span class="badge bg-{{ ($service->Status ?? 'Active') == 'Active' ? 'success' : 'secondary' }}">
                                        {{ $service->Status ?? 'Active' }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No services associated with this category yet.</td>
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