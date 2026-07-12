@extends('layouts.configside')
@section('title', 'Branches Information')
@section('page-title', 'Branches Information')

@section('content')
<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-network-wired"></i></div>Branches Information</h3>
    <button class="arbif-btn-submit" onclick="openCreateForm()"><i class="fas fa-plus"></i> Add Branch</button>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="branchTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Branch Code</th>
                        <th class="sortable">Branch Name</th>
                        <th class="sortable">Assigned Company</th>
                        <th class="sortable">Contact Info</th>
                        <th class="sortable">Created By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($branches as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><span class="arbif-badge arbif-badge-navy">{{ $item->branch_code }}</span></td>
                        <td>{{ $item->branch_name }}</td>
                        <td>{{ $item->company->company_name ?? '—' }}</td>
                        <td>
                            <small>
                                @if($item->email) <i class="fas fa-envelope"></i> {{ $item->email }}<br> @endif
                                @if($item->phone) <i class="fas fa-phone"></i> {{ $item->phone }} @endif
                                @if(!$item->email && !$item->phone) — @endif
                            </small>
                        </td>
                        <td>{{ $item->createdBy->name ?? ($item->user->name ?? '—') }}</td>
                        <td>
                            <a href="{{ route('viewbranchiesinformations', [encrypt($item->id)]) }}" class="arbif-btn-edit"> <i class="fas fa-eye"></i> View </a>
                            <a href="{{ route('editbranchiesinformations', [encrypt($item->id)]) }}" class="arbif-btn-edit"> <i class="fas fa-pencil"></i> Edit </a>
                            <a onclick="confirmDelete()" href="{{ route('deletebranchiesinformations', [encrypt($item->id)]) }}" class="arbif-btn-delete"> <i class="fas fa-trash"></i> Delete </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="arbif-table-empty">
                            <i class="bi bi-inbox"></i>
                            No branch records found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="arbif-pagination">
            <span class="arbif-pagination-info" data-table-info="branchTable"></span>
            <div class="arbif-pagination-pages" data-table-pages="branchTable"></div>
        </div>
    </div>
</div>

<div class="modal fade arbif-modal" id="addFormModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon"><i class="fas fa-network-wired"></i></div>
                <h5 class="modal-title">Register New Branch</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <form method="POST" id="dataFormFill" action="{{ route('storebranchiesinformations') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Assign Company <span class="text-danger">*</span></label>
                            <select style="width: 100%" name="company_id" data-searchable data-placeholder="Select Parent Corporate Profile..." required>
                                <option></option>
                                @foreach($companies as $comp)
                                    <option value="{{ $comp->id }}" {{ old('company_id') == $comp->id ? 'selected' : '' }}>
                                        {{ $comp->company_code }} - {{ $comp->company_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="branch_code">Branch Code <span class="text-danger">*</span></label>
                            <input type="text" id="branch_code" name="branch_code" class="form-control" placeholder="e.g. BR-01" value="{{ old('branch_code') }}" maxlength="30" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="branch_name">Branch Name <span class="text-danger">*</span></label>
                            <input type="text" id="branch_name" name="branch_name" class="form-control" placeholder="e.g. Dar es Salaam Central" value="{{ old('branch_name') }}" maxlength="200" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="established_date">Established Date</label>
                            <input type="date" id="established_date" name="established_date" class="form-control" value="{{ old('established_date') }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="email">Branch Email</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="e.g. branchname@company.com" value="{{ old('email') }}" maxlength="150">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="phone">Branch Phone Contact</label>
                            <input type="text" id="phone" name="phone" class="form-control" placeholder="e.g. +255..." value="{{ old('phone') }}" maxlength="30">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label" for="address">Physical Street Address</label>
                            <input type="text" id="address" name="address" class="form-control" placeholder="e.g. Plot 45, Sam Nujoma Road" value="{{ old('address') }}" maxlength="255">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" for="region">Region</label>
                            <input type="text" id="region" name="region" class="form-control" placeholder="Region" value="{{ old('region') }}" maxlength="100">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" for="district">District</label>
                            <input type="text" id="district" name="district" class="form-control" placeholder="District" value="{{ old('district') }}" maxlength="100">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" for="ward">Ward</label>
                            <input type="text" id="ward" name="ward" class="form-control" placeholder="Ward" value="{{ old('ward') }}" maxlength="100">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" for="village">Village / Street</label>
                            <input type="text" id="village" name="village" class="form-control" placeholder="Village" value="{{ old('village') }}" maxlength="100">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label" for="description">Branch Operational Description</label>
                            <textarea id="description" name="description" class="form-control" rows="3" placeholder="Describe regional operations or branch purpose...">{{ old('description') }}</textarea>
                        </div>
                    </div>
                    
                    <div class="modal-footer" style="margin-top: 20px;">
                        <button type="button" class="arbif-btn-cancel" data-bs-dismiss="modal">
                            <i class="bi bi-x"></i> Cancel
                        </button>
                        <button onclick="confirmSubmit()" type="submit" class="arbif-btn-submit">
                            <i class="bi bi-check2"></i> <span id="submitBtnText">Register Branch</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection