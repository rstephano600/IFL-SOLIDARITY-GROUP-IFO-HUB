@extends('layouts.workingside')

@section('title', 'Service Requests')
@section('page-title', 'Service Requests')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-hand-holding-box"></i>
        </div>
        Service Requests
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('deletedServiceRequest') }}" class="btn btn-outline-danger d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-trash-alt me-2"></i> Deleted Requests
        </a>
        <a href="{{ route('createServiceRequest') }}" class="arbif-btn-submit text-decoration-none">
            <i class="fas fa-plus me-1"></i> New Service Request
        </a>
    </div>
</div>

{{-- FILTERS BAR --}}
<div class="arbif-card mb-4">
    <div class="arbif-card-body p-3">
        <form method="GET" action="{{ route('serviceRequest') }}" class="row g-2 align-items-center">
            <div class="col-md-3">
                <select name="request_status" class="form-select" onchange="this.form.submit()">
                    <option value="">-- All Statuses --</option>
                    <option value="Pending Chairman Approval" {{ request('request_status') == 'Pending Chairman Approval' ? 'selected' : '' }}>Pending Chairman Approval</option>
                    <option value="Pending Group Chairman Approval" {{ request('request_status') == 'Pending Group Chairman Approval' ? 'selected' : '' }}>Pending Group Chairman Approval</option>
                    <option value="Approved" {{ request('request_status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                    <option value="Rejected" {{ request('request_status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="Completed" {{ request('request_status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            <div class="col-md-3">
                <select name="committee_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Filter by Committee --</option>
                    @foreach($committees as $committee)
                        <option value="{{ $committee->id }}" {{ request('committee_id') == $committee->id ? 'selected' : '' }}>
                            {{ $committee->committee_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <select name="priority" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Priority --</option>
                    <option value="Low" {{ request('priority') == 'Low' ? 'selected' : '' }}>Low</option>
                    <option value="Medium" {{ request('priority') == 'Medium' ? 'selected' : '' }}>Medium</option>
                    <option value="High" {{ request('priority') == 'High' ? 'selected' : '' }}>High</option>
                    <option value="Urgent" {{ request('priority') == 'Urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
            </div>

            <div class="col-md-2">
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search SR No, title...">
            </div>

            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Filter</button>
                @if(request()->anyFilled(['request_status', 'committee_id', 'priority', 'search']))
                    <a href="{{ route('serviceRequest') }}" class="btn btn-secondary text-white">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="serviceRequestTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>SR No.</th>
                        <th>Request Title</th>
                        <th>Requester</th>
                        <th>Committee</th>
                        <th>Request Date</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Print Form</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $data->firstItem() + $index }}</td>
                        <td>
                            <a href="{{ route('viewServiceRequest', encrypt($item->id)) }}" class="fw-bold text-decoration-none">
                                {{ $item->request_no }}
                            </a>
                            <br><small class="text-muted">{{ $item->ReferenceNo }}</small>
                        </td>
                        <td><strong>{{ $item->request_title }}</strong></td>
                        <td>{{ $item->requester->name ?? 'N/A' }}</td>
                        <td>{{ $item->committee_name ?? $item->committee->committee_name ?? 'N/A' }}</td>
                        <td>{{ $item->request_date ? $item->request_date->format('d M, Y') : 'N/A' }}</td>
                        <td>
                            <span class="badge {{ $item->priority == 'High' || $item->priority == 'Urgent' ? 'bg-danger' : ($item->priority == 'Medium' ? 'bg-warning text-dark' : 'bg-info text-dark') }}">
                                {{ $item->priority }}
                            </span>
                        </td>
                        <td>
                            @php
                                $statusClasses = [
                                    'Pending Chairman Approval' => 'bg-warning text-dark',
                                    'Pending Group Chairman Approval' => 'bg-info text-dark',
                                    'Approved' => 'bg-success',
                                    'Rejected' => 'bg-danger',
                                    'Completed' => 'bg-primary',
                                ];
                            @endphp
                            <span class="badge {{ $statusClasses[$item->request_status] ?? 'bg-secondary' }}">
                                {{ $item->request_status }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('printServiceRequest', encrypt($item->id)) }}" class="arbif-btn-view" target="_blank">
                                    <i class="fas fa-print me-1"></i> Form
                                </a>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('viewServiceRequest', encrypt($item->id)) }}" class="arbif-btn-view">
                                    <i class="fas fa-eye"></i> View
                                </a>

                                @if(!in_array($item->request_status, ['Approved', 'Completed', 'Cancelled']))
                                    <a href="{{ route('editServiceRequest', encrypt($item->id)) }}" class="arbif-btn-edit">
                                        <i class="fas fa-pencil"></i> Edit
                                    </a>
                                @endif

                                @can('view-generally-delete-of-any-item')
                                    <a href="{{ route('destroyServiceRequest', encrypt($item->id)) }}" class="arbif-btn-delete" onclick="return confirm('Are you sure you want to delete this service request?')">
                                        <i class="fas fa-trash"></i> Del
                                    </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center">No Service Requests Found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection