@extends('layouts.app')
@section('title', 'Groups')
@section('page-title', 'Group Details')

@section('content')
<style>
    /* Styling for the Print View */
    @media print {
        .no-print {
            display: none !important;
        }
        .container-fluid {
            width: 100%;
            margin: 0;
            padding: 0;
        }
        .card {
            border: 1px solid #ccc !important;
            page-break-inside: avoid;
            margin-bottom: 1rem;
        }
        .card-header {
            background-color: #f0f0f0 !important;
            color: #333 !important;
        }
    }
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <h2 class="h3 mb-0 text-dark">
            <i class="bi bi-people-fill text-primary me-2"></i> Group: <span class="text-primary">{{ $group->group_name }}</span>
        </h2>
        <div class="btn-group shadow-sm" role="group">
            <a href="{{ route('groups.index') }}" class="btn btn-secondary d-flex align-items-center">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
            <a href="{{ route('groups.edit', $group->id) }}" class="btn btn-warning d-flex align-items-center">
                <i class="bi bi-pencil-square me-1"></i> Edit Group
            </a>
            <button onclick="exportGroupData({{ $group->id }})" class="btn btn-success d-flex align-items-center">
                <i class="bi bi-file-earmark-spreadsheet me-1"></i> Export Data
            </button>
            <button onclick="window.print()" class="btn btn-info d-flex align-items-center">
                <i class="bi bi-printer me-1"></i> Print
            </button>
        </div>
    </div>

    <div class="card shadow-lg border-start ">
        <div class="card-header bg-light py-3">
            <h5 class="mb-0 text-primary">General Information</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                    <div class="col-md-6 col-lg-4">
                    <p class="mb-0"><strong>Group Code:</strong></p>
                            <strong>Group Code:</strong> <span class="fw-bold text-dark">{{ $group->group_code }}</span>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <strong>Group Type:</strong> {{ $group->group_type ?? '—' }}
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <strong>Credit Officer:</strong> {{ $group->creditOfficer->first_name ?? '—' }}
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <strong>Registration Date:</strong> {{ $group->registration_date ?? '—' }}
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <strong>Location:</strong> {{ $group->location ?? '—' }}
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <strong>Status:</strong>
                            @if($group->is_active)
                                <span class="badge bg-success fs-6">Active</span>
                            @else
                                <span class="badge bg-danger fs-6">Inactive</span>
                            @endif
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <strong>Description:</strong> 
                            <p class="text-muted mt-2 mb-0">{{ $group->description ?? 'No description provided.' }}</p>
                        </div>
            </div>
                
        </div>
    </div>
<br>

<div class="card shadow-lg border-start">
    <div class="card-header bg-light py-3">
        <h5 class="mb-0 text-primary">Clients Belonging to {{ $group->group_name }}</h5>
    </div>
        <div class="card shadow-lg mt-4">
        <div class="card-header d-flex justify-content-between align-items-center bg-light py-3">
            <h5 class="mb-0 text-primary"></h5>

            <a href="{{ route('clients.create', ['group_id' => $group->id]) }}" class="btn btn-primary btn-sm d-flex align-items-center">
             <i class="bi bi-plus-lg me-1"></i> Add New Client to The group
            </a>
        </div>
    <div class="card-body">
        @if($group->clients->isEmpty())
            <p class="text-muted">No clients assigned to this group.</p>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Client Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Country</th>
                            <th>Status</th>
                            <th>KYC Completed</th>
                            <th>Registered At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($group->clients as $index => $client)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $client->first_name }} {{ $client->last_name }}</td>
                                <td>{{ $client->email ?? '—' }}</td>
                                <td>{{ $client->phone ?? '—' }}</td>
                                <td>{{ $client->country ?? '—' }}</td>
                                <td>
                                    @if($client->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @elseif($client->status === 'inactive')
                                        <span class="badge bg-secondary">Inactive</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>
                                <td>{{ $client->kyc_completed_at ? $client->kyc_completed_at : 'Not Completed' }}</td>
                                <td>{{ $client->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>


</div>

<script>
    /**
     * Placeholder function for exporting a single group's data (Group details + Members list).
     * In a real application, this would make an AJAX request to a backend route
     * that generates and downloads the CSV or PDF file.
     */
    function exportGroupData(groupId) {
        // NOTE: You must define a route like: 
        // Route::get('/groups/{group}/export/single', [GroupController::class, 'exportSingle'])->name('groups.export.single');
        const exportUrl = `/groups/${groupId}/export/single`; 
        
        // Simulating the download
        alert(`Exporting Group ID ${groupId} data, including member details, to CSV. A download should start if the backend route is configured.`);
        
        // Uncomment the line below in a live environment where the route exists:
        // window.location.href = exportUrl; 
    }
</script>
@endsection
