@extends('layouts.configside')
@section('title', 'Companies Information')
@section('page-title', 'Companies Information')

@section('content')
<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-building"></i></div>Companies Information</h3>
    <button class="arbif-btn-submit" onclick="openCreateForm()"><i class="fas fa-plus"></i> Add Company</button>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="companyTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Company Code</th>
                        <th class="sortable">Company Name</th>
                        <th class="sortable">Type</th>
                        <th class="sortable">Parent Company</th>
                        <th class="sortable">Contact Info</th>
                        <th class="sortable">Created By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($companies as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><span class="arbif-badge arbif-badge-navy">{{ $item->company_code }}</span></td>
                        <td>{{ $item->company_name }}</td>
                        <td>{{ $item->company_type }}</td>
                        <td>
                            @if($item->parentCompany)
                                {{ $item->parentCompany->company_name }}
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <small>
                                @if($item->email) <i class="fas fa-envelope"></i> {{ $item->email }}<br> @endif
                                @if($item->phone) <i class="fas fa-phone"></i> {{ $item->phone }} @endif
                                @if(!$item->email && !$item->phone) — @endif
                            </small>
                        </td>
                        <td>{{ $item->createdBy->name ?? ($item->user->name ?? '—') }}</td>
                        <td>
                            <a href="{{ route('viewcompaniesinformations', [encrypt($item->id)]) }}" class="arbif-btn-edit"> <i class="fas fa-eye"></i> View </a>
                            <a href="{{ route('editcompaniesinformations', [encrypt($item->id)]) }}" class="arbif-btn-edit"> <i class="fas fa-pencil"></i> Edit </a>
                            <a onclick="confirmDelete()" href="{{ route('deletecompaniesinformations', [encrypt($item->id)]) }}" class="arbif-btn-delete"> <i class="fas fa-trash"></i> Delete </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="arbif-table-empty">
                            <i class="bi bi-inbox"></i>
                            No company records found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="arbif-pagination">
            <span class="arbif-pagination-info" data-table-info="companyTable"></span>
            <div class="arbif-pagination-pages" data-table-pages="companyTable"></div>
        </div>
    </div>
</div>

<div class="modal fade arbif-modal" id="addFormModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon"><i class="fas fa-building"></i></div>
                <h5 class="modal-title">Register New Company</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <form method="POST" id="dataFormFill" action="{{ route('storecompaniesinformations') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="company_code">Company Code <span class="text-danger">*</span></label>
                            <input type="text" id="company_code" name="company_code" class="form-control" placeholder="e.g. CMP-001" value="{{ old('company_code') }}" maxlength="30" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="company_name">Company Name <span class="text-danger">*</span></label>
                            <input type="text" id="company_name" name="company_name" class="form-control" placeholder="e.g. Acme Holdings Ltd" value="{{ old('company_name') }}" maxlength="200" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="company_type">Company Type <span class="text-danger">*</span></label>
                            <input type="text" id="company_type" name="company_type" class="form-control" placeholder="e.g. Subsidiary, LLC, HQ" value="{{ old('company_type') }}" maxlength="200" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Parent Company</label>
                            <select style="width: 100%" name="parent_company_id" data-searchable data-placeholder="Select Parent Company (Optional)...">
                                <option></option>
                                @foreach($companies as $parentOption)
                                    <option value="{{ $parentOption->id }}" {{ old('parent_company_id') == $parentOption->id ? 'selected' : '' }}>
                                        {{ $parentOption->company_code }} - {{ $parentOption->company_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="established_date">Established Date</label>
                            <input type="date" id="established_date" name="established_date" class="form-control" value="{{ old('established_date') }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="email">Email Address</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="e.g. info@company.com" value="{{ old('email') }}" maxlength="150">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="phone">Phone Number</label>
                            <input type="text" id="phone" name="phone" class="form-control" placeholder="e.g. +255..." value="{{ old('phone') }}" maxlength="30">
                        </div>

                        <div class="col-md-8">
                            <label class="form-label" for="address">Physical Address</label>
                            <input type="text" id="address" name="address" class="form-control" placeholder="e.g. 123 Business Parkway" value="{{ old('address') }}" maxlength="255">
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
                            <label class="form-label" for="village">Village/Street</label>
                            <input type="text" id="village" name="village" class="form-control" placeholder="Village" value="{{ old('village') }}" maxlength="100">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label" for="description">Company Description</label>
                            <textarea id="description" name="description" class="form-control" rows="3" placeholder="Describe the corporate activities or purpose...">{{ old('description') }}</textarea>
                        </div>
                    </div>
                    
                    <div class="modal-footer" style="margin-top: 20px;">
                        <button type="button" class="arbif-btn-cancel" data-bs-dismiss="modal">
                            <i class="bi bi-x"></i> Cancel
                        </button>
                        <button onclick="confirmSubmit()" type="submit" class="arbif-btn-submit">
                            <i class="bi bi-check2"></i> <span id="submitBtnText">Register Company</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection