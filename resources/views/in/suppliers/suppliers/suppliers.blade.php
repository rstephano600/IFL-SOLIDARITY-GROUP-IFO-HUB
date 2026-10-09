@extends('layouts.workingside')

@section('title', 'Suppliers')
@section('page-title', 'Suppliers List')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-truck"></i>
        </div>
        Suppliers Management
    </h3>

    <div class="d-flex gap-2 wrap-mobile">
        <a href="{{ route('deletedSuppliers') }}" class="btn btn-outline-danger d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-trash-alt me-2"></i> Deleted Suppliers
        </a>
        <button class="arbif-btn-submit bg-info text-dark" data-bs-toggle="modal" data-bs-target="#addMemberSupplierModal">
            <i class="fas fa-user-check me-1"></i> Add Member Supplier
        </button>
        <button class="arbif-btn-submit" data-bs-toggle="modal" data-bs-target="#addExternalSupplierModal">
            <i class="fas fa-plus me-1"></i> Add External Supplier
        </button>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="suppliersTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ref No</th>
                        <th>Code</th>
                        <th>Supplier Name</th>
                        <th>Type</th>
                        <th>Category</th>
                        <th>Branch</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <span class="arbif-badge arbif-badge-navy">
                                {{ $item->SupplierRefNo }}
                            </span>
                        </td>
                        <td>{{ $item->SupplierCode ?? 'N/A' }}</td>
                        <td>
                            <strong>{{ $item->SupplierName }}</strong>
                            @if($item->SupplierType === 'Member' && $item->member)
                                <br><small class="text-muted"><i class="fas fa-id-badge me-1"></i>Member Ref: {{ $item->member->member_no ?? 'N/A' }}</small>
                            @endif
                        </td>
                        <td>
                            @if($item->SupplierType === 'Member')
                                <span class="badge bg-info text-dark"><i class="fas fa-users me-1"></i> Member</span>
                            @else
                                <span class="badge bg-secondary"><i class="fas fa-building me-1"></i> External</span>
                            @endif
                        </td>
                        <td>{{ $item->category->CategoryName ?? 'N/A' }}</td>
                        <td>{{ $item->branch->branch_name ?? 'Global / Central' }}</td>
                        <td>{{ $item->Phone ?? 'N/A' }}</td>
                        <td>
                            <a href="{{ route('viewSupplier', encrypt($item->id)) }}" class="arbif-btn-view">
                                <i class="fas fa-eye"></i> view
                            </a>

                            <a href="{{ route('editSupplier', encrypt($item->id)) }}" class="arbif-btn-edit">
                                <i class="fas fa-pencil"></i> edit
                            </a>

                            @can('view-generally-delete-of-any-item')
                            <a href="{{ route('destroySupplier', encrypt($item->id)) }}" class="arbif-btn-delete" onclick="return confirm('Are you sure you want to remove this supplier?')">
                                <i class="fas fa-trash"></i> del
                            </a>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">No Suppliers Found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- 1. REGISTER MEMBER SUPPLIER MODAL --}}
<div class="modal fade arbif-modal" id="addMemberSupplierModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 90%;">
        <div class="modal-content">
            <div class="modal-header bg-info-subtle">
                <div class="modal-icon text-info">
                    <i class="fas fa-user-check"></i>
                </div>
                <h5 class="modal-title fw-bold">Register Member Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('storeMemberSupplier') }}">
                    @csrf

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Assigned Branch <span class="text-danger">*</span></label>
                            <select name="branch_id" class="form-select" data-searchable data-placeholder="Search ..." required>
                                <option value="">-- Select Branch --</option>
                                @foreach($branchies as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Select Group Member <span class="text-danger">*</span></label>
                            <select name="Member_id" class="form-select" data-searchable data-placeholder="Search member..." required>
                                <option value="">-- Select Member --</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}">
                                        {{ $member->member_name ?? $member->name }} ({{ $member->member_code ?? 'ID: '.$member->id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Supplier Category <span class="text-danger">*</span></label>
                            <select name="SupplierCategory_id" class="form-select" required>
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->CategoryName }} ({{ $category->SupplierGroup }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Supplier Code</label>
                            <input type="text" name="SupplierCode" class="form-control" placeholder="e.g. MBR-SUP-001">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">TIN Number</label>
                            <input type="text" name="TinNumber" class="form-control" placeholder="123-456-789">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">VAT Number</label>
                            <input type="text" name="VatNumber" class="form-control" placeholder="VAT-123456">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Credit Limit</label>
                            <input type="number" step="0.01" min="0" name="CreditLimit" class="form-control" value="0.00">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Opening Balance</label>
                            <input type="number" step="0.01" min="0" name="OpeningBalance" class="form-control" value="0.00">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Payment Terms</label>
                            <input type="text" name="PaymentTerms" class="form-control" placeholder="e.g. Net 30 Days">
                        </div>
                    </div>

                    <div class="modal-footer px-0 pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="arbif-btn-submit">Save Member Supplier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- 2. REGISTER EXTERNAL SUPPLIER MODAL --}}
<div class="modal fade arbif-modal" id="addExternalSupplierModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon">
                    <i class="fas fa-truck"></i>
                </div>
                <h5 class="modal-title fw-bold">Register External Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('storeExternalSupplier') }}">
                    @csrf

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Assigned Branch <span class="text-danger">*</span></label>
                            <select name="branch_id" class="form-select" data-searchable data-placeholder="Search ..." required>
                                <option value="">-- Select Branch --</option>
                                @foreach($branchies as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Supplier Category <span class="text-danger">*</span></label>
                            <select name="SupplierCategory_id" class="form-select" required>
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->CategoryName }} ({{ $category->SupplierGroup }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="FirstName" class="form-control" placeholder="e.g. John" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Middle Name</label>
                            <input type="text" name="MiddleName" class="form-control" placeholder="e.g. Peter">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="LastName" class="form-control" placeholder="e.g. Doe" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label class="form-label">Company / Business Name</label>
                            <input type="text" name="SupplierName" class="form-control" placeholder="e.g. ABC ENTERPRISES LTD (Leave blank to use personal name)">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Supplier Code</label>
                            <input type="text" name="SupplierCode" class="form-control" placeholder="e.g. EXT-001">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="text" name="Phone" class="form-control" placeholder="+255 700 000 000" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Alternative Phone</label>
                            <input type="text" name="AlternativePhone" class="form-control" placeholder="+255 600 000 000">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="Email" class="form-control" placeholder="supplier@example.com">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Contact Person</label>
                            <input type="text" name="ContactPerson" class="form-control" placeholder="Representative Name">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">City / Location</label>
                            <input type="text" name="City" class="form-control" placeholder="e.g. Dar es Salaam">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Region</label>
                            <input type="text" name="Region" class="form-control" placeholder="e.g. Ilala">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Country</label>
                            <input type="text" name="Country" class="form-control" value="Tanzania">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">TIN Number</label>
                            <input type="text" name="TinNumber" class="form-control" placeholder="123-456-789">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">VAT Number</label>
                            <input type="text" name="VatNumber" class="form-control" placeholder="VAT-123456">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Credit Limit</label>
                            <input type="number" step="0.01" min="0" name="CreditLimit" class="form-control" value="0.00">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Opening Balance</label>
                            <input type="number" step="0.01" min="0" name="OpeningBalance" class="form-control" value="0.00">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Payment Terms</label>
                            <input type="text" name="PaymentTerms" class="form-control" placeholder="e.g. Net 30 Days">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Physical Address</label>
                        <textarea name="Address" rows="2" class="form-control" placeholder="Enter full physical address details..."></textarea>
                    </div>

                    <div class="modal-footer px-0 pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="arbif-btn-submit">Save External Supplier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    new DataTable('#suppliersTable', {
        perPage: 10,
        searchable: true,
        sortable: true
    });
</script>
@endpush