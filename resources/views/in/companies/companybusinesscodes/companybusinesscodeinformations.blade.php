@extends('layouts.configside')
@section('title', 'Company Business Codes')
@section('page-title', 'Company Business Codes')

@section('content')
<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-barcode"></i></div>Company Business Codes</h3>
    <button class="arbif-btn-submit" onclick="openCreateForm()"><i class="fas fa-plus"></i> Add Business Code</button>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="businessCodeTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Business Code</th>
                        <th class="sortable">Business Name</th>
                        <th class="sortable">Company</th>
                        <th class="sortable">Branch</th>
                        <th class="sortable">Activity Group</th>
                        <th class="sortable">Segment</th>
                        <th class="sortable">Created By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($businesscodes as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><span class="arbif-badge arbif-badge-navy">{{ $item->business_code }}</span></td>
                        <td>{{ $item->business_name }}</td>
                        <td>{{ $item->company->company_name ?? '—' }}</td>
                        <td>{{ $item->branch->branch_name ?? '—' }}</td>
                        <td>{{ $item->business_activity ?? '—' }}</td>
                        <td>{{ $item->segment ?? '—' }}</td>
                        <td>{{ $item->createdBy->name ?? ($item->user->name ?? '—') }}</td>
                        <td>
                            <a href="{{ route('viewcompanybusinesscodeinformations', [encrypt($item->id)]) }}" class="arbif-btn-edit"> <i class="fas fa-eye"></i> View </a>
                            <a href="{{ route('editcompanybusinesscodeinformations', [encrypt($item->id)]) }}" class="arbif-btn-edit"> <i class="fas fa-pencil"></i> Edit </a>
                            <a onclick="confirmDelete()" href="{{ route('deletecompanybusinesscodeinformations', [encrypt($item->id)]) }}" class="arbif-btn-delete"> <i class="fas fa-trash"></i> Delete </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="arbif-table-empty">
                            <i class="bi bi-inbox"></i>
                            No business codes found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="arbif-pagination">
            <span class="arbif-pagination-info" data-table-info="businessCodeTable"></span>
            <div class="arbif-pagination-pages" data-table-pages="businessCodeTable"></div>
        </div>
    </div>
</div>

<div class="modal fade arbif-modal" id="addFormModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon"><i class="fas fa-barcode"></i></div>
                <h5 class="modal-title">Register New Business Code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <form method="POST" id="dataFormFill" action="{{ route('storecompanybusinesscodeinformations') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Assign Company <span class="text-danger">*</span></label>
                            <select style="width: 100%" name="company_id" data-searchable data-placeholder="Select Corporate Context..." required>
                                <option></option>
                                @foreach($companies as $comp)
                                    <option value="{{ $comp->id }}" {{ old('company_id') == $comp->id ? 'selected' : '' }}>
                                        {{ $comp->company_code }} - {{ $comp->company_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Assign Branch <span class="text-danger">*</span></label>
                            <select style="width: 100%" name="branch_id" data-searchable data-placeholder="Select Operational Branch Location..." required>
                                <option></option>
                                @foreach($branches as $br)
                                    <option value="{{ $br->id }}" {{ old('branch_id') == $br->id ? 'selected' : '' }}>
                                        {{ $br->branch_code ?? $br->company_code }} - {{ $br->branch_name ?? $br->company_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" for="business_code">Business Code <span class="text-danger">*</span></label>
                            <input type="text" id="business_code" name="business_code" class="form-control" placeholder="e.g. ISIC-4610" value="{{ old('business_code') }}" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" for="business_name">Business Name <span class="text-danger">*</span></label>
                            <input type="text" id="business_name" name="business_name" class="form-control" placeholder="e.g. Wholesale Trade Agents" value="{{ old('business_name') }}" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" for="business_activity">Business Activity Focus</label>
                            <input type="text" id="business_activity" name="business_activity" class="form-control" placeholder="e.g. Distribution & Logistics" value="{{ old('business_activity') }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" for="segment">Reporting Segment</label>
                            <input type="text" id="segment" name="segment" class="form-control" placeholder="e.g. Commercial Division" value="{{ old('segment') }}">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label" for="description">Code Scope / Operational Guidelines Description</label>
                            <textarea id="description" name="description" class="form-control" rows="3" placeholder="Describe the corporate activities or boundaries tracked under this code profile...">{{ old('description') }}</textarea>
                        </div>
                    </div>
                    
                    <div class="modal-footer" style="margin-top: 20px;">
                        <button type="button" class="arbif-btn-cancel" data-bs-dismiss="modal">
                            <i class="bi bi-x"></i> Cancel
                        </button>
                        <button onclick="confirmSubmit()" type="submit" class="arbif-btn-submit">
                            <i class="bi bi-check2"></i> <span id="submitBtnText">Save Business Code</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection