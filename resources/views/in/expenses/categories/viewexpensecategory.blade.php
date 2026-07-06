@extends('layouts.workingside')

@section('title', 'Expense Category Profile Summary')

@section('page-title', 'Expense Category Profile Summary')

@section('content')

<div class="arbif-page-header d-flex justify-content-between align-items-center mb-4">
    <h3>
        <div class="page-icon">
            <i class="fas fa-folder-open"></i>
        </div>
        Category Registry Lookup: {{ $data->name }}
    </h3>
    <a href="{{ route('expensecategoryinformations') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Back to Configurations
    </a>
</div>

<div class="row g-4">
    <div class="col-md-5">
        <div class="arbif-card">
            <div class="arbif-card-body">
                <h5 class="arbif-section-title mb-3">Meta Identity Profiles</h5>
                <table class="table table-bordered table-striped custom-view-table">
                    <tbody>
                        <tr>
                            <th width="40%">Category Name</th>
                            <td><strong>{{ $data->name }}</strong></td>
                        </tr>
                        <tr>
                            <th>Lifecycle Status</th>
                            <td>
                                <span class="badge {{ $data->Status == 'Active' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $data->Status }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Internal Audit Trace</th>
                            <td><code>{{ $data->AuditingStatus }}</code></td>
                        </tr>
                        <tr>
                            <th>Reporting Engine Tag</th>
                            <td><code>{{ $data->ReportStatus }}</code></td>
                        </tr>
                        <tr>
                            <th>Created Timestamp</th>
                            <td>{{ $data->created_at ? $data->created_at->format('d M Y \a\t H:i') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Modified Timestamp</th>
                            <td>{{ $data->updated_at ? $data->updated_at->format('d M Y \a\t H:i') : 'N/A' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="arbif-card h-100">
            <div class="arbif-card-body d-flex flex-column justify-content-between">
                <div>
                    <h5 class="arbif-section-title mb-3">Ledger Scope Definition & Operational Description</h5>
                    <div class="p-3 bg-light rounded border mb-4">
                        <p class="mb-0 text-wrap text-dark" style="white-space: pre-wrap;">{{ $data->description ?? 'No descriptive text assigned to this database asset category.' }}</p>
                    </div>
                </div>

                {{-- STATISTIC HOVER --}}
                <div class="alert alert-info py-3 px-3 d-flex align-items-center mb-0">
                    <i class="fas fa-calculator fa-2x me-3 opacity-50"></i>
                    <div>
                        <h6 class="mb-0 font-weight-bold">Total Expenses Logged Under This Category</h6>
                        <span class="fs-5 fw-bold">{{ $data->expenses ? $data->expenses->count() : 0 }} entries linked</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection