@extends('layouts.workingside')

@section('title', 'Sales Customers')
@section('page-title', 'Sales Customers')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-users"></i>
        </div>
        Sales Customers Management
    </h3>

    <div class="d-flex gap-2">
        <a href="{{ route('deletedsalesCustomer') }}" class="btn btn-outline-danger d-flex align-items-center px-3" style="border-radius: 4px;">
            <i class="fas fa-trash-alt me-2"></i> Deleted Customers
        </a>
        <button class="arbif-btn-submit" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
            <i class="fas fa-user-plus me-1"></i> Register Customer
        </button>
    </div>
</div>

{{-- SEARCH & FILTER BAR --}}
<div class="arbif-card mb-4">
    <div class="arbif-card-body p-3">
        <form method="GET" action="{{ route('salesCustomer') }}" class="row g-2 align-items-center">
            <div class="col-md-3">
                <select name="customer_type" class="form-select" onchange="this.form.submit()">
                    <option value="">-- All Customer Types --</option>
                    <option value="COMPANY" {{ request('customer_type') == 'COMPANY' ? 'selected' : '' }}>COMPANY / CORPORATE</option>
                    <option value="INDIVIDUAL" {{ request('customer_type') == 'INDIVIDUAL' ? 'selected' : '' }}>INDIVIDUAL</option>
                    <option value="WALK_IN" {{ request('customer_type') == 'WALK_IN' ? 'selected' : '' }}>WALK-IN</option>
                </select>
            </div>

            <div class="col-md-6">
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search by code, name, phone, or email...">
            </div>

            <div class="col-md-3 d-flex gap-1">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Search</button>
                @if(request()->anyFilled(['customer_type', 'search']))
                    <a href="{{ route('salesCustomer') }}" class="btn btn-secondary text-white">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <div class="arbif-table-wrap">
            <table class="arbif-table" id="salesCustomerTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ref No.</th>
                        <th>Code</th>
                        <th>Customer Name</th>
                        <th>Type</th>
                        <th>Phone / Email</th>
                        <th>Credit Limit (TZS)</th>
                        <th>Branch</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $data->firstItem() + $index }}</td>
                        <td>
                            <span class="arbif-badge arbif-badge-navy">
                                {{ $item->ReferenceNo }}
                            </span>
                        </td>
                        <td><strong>{{ $item->customer_code }}</strong></td>
                        <td>
                            {{ $item->customer_name }}
                            @if($item->customer_type === 'COMPANY' && $item->billingCompany)
                                <br><small class="text-muted"><i class="fas fa-building me-1"></i>{{ $item->billingCompany->company_name }}</small>
                            @endif
                        </td>
                        <td>
                            @php
                                $typeBadges = [
                                    'COMPANY'    => 'bg-primary',
                                    'INDIVIDUAL' => 'bg-info text-dark',
                                    'WALK_IN'    => 'bg-secondary',
                                ];
                            @endphp
                            <span class="badge {{ $typeBadges[$item->customer_type] ?? 'bg-secondary' }}">
                                {{ $item->customer_type }}
                            </span>
                        </td>
                        <td>
                            <small class="d-block"><i class="fas fa-phone me-1"></i>{{ $item->phone ?? 'N/A' }}</small>
                            <small class="text-muted"><i class="fas fa-envelope me-1"></i>{{ $item->email ?? 'N/A' }}</small>
                        </td>
                        <td class="fw-bold text-success">{{ number_format($item->credit_limit, 2) }}</td>
                        <td>{{ $item->branch->branch_name ?? 'Global / Central' }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('viewsalesCustomer', encrypt($item->id)) }}" class="arbif-btn-view">
                                    <i class="fas fa-eye"></i> View
                                </a>

                                <a href="{{ route('editsalesCustomer', encrypt($item->id)) }}" class="arbif-btn-edit">
                                    <i class="fas fa-pencil"></i> Edit
                                </a>

                                @can('view-generally-delete-of-any-item')
                                    <a href="{{ route('destroysalesCustomer', encrypt($item->id)) }}" class="arbif-btn-delete" onclick="return confirm('Are you sure you want to delete this customer?')">
                                        <i class="fas fa-trash"></i> Del
                                    </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">No Sales Customers Found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $data->links() }}
        </div>
    </div>
</div>
{{-- REGISTER CUSTOMER MODAL --}}
<div class="modal fade arbif-modal" id="addCustomerModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 80%;">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h5 class="modal-title">Register Sales Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form method="POST" action="{{ route('storesalesCustomer') }}" id="salesCustomerForm" novalidate>
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $e)
                                    <li>{{ $e }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Assigned Branch <span class="text-danger">*</span></label>
                            <select name="branch_id" id="branch_id" class="form-select" data-searchable data-placeholder="Search ...">
                                <option value="">-- Select Branch --</option>
                                @foreach($branchies as $branch)
                                    <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->branch_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Customer Type <span class="text-danger">*</span></label>
                            <select name="customer_type" id="modal_cust_type" class="form-select" required>
                                <option value="INDIVIDUAL" {{ old('customer_type', 'INDIVIDUAL') == 'INDIVIDUAL' ? 'selected' : '' }}>INDIVIDUAL</option>
                                <option value="COMPANY"    {{ old('customer_type') == 'COMPANY' ? 'selected' : '' }}>COMPANY / CORPORATE</option>
                                <option value="WALK_IN"    {{ old('customer_type') == 'WALK_IN' ? 'selected' : '' }}>WALK-IN</option>
                            </select>
                        </div>
                    </div>

                    {{-- Shown only when type = COMPANY --}}
                    <div class="row g-3 mb-3" id="billing_company_div" style="display: none;">
                        <div class="col-md-12">
                            <label class="form-label">
                                Linked Billing Company <span class="text-danger">*</span>
                            </label>
                            <select name="billing_company_id" id="billing_company_id" >
                                <option value="">-- Select Billing Company --</option>
                                @foreach($billingCompanies as $company)
                                    <option value="{{ $company->id }}" {{ old('billing_company_id') == $company->id ? 'selected' : '' }}>
                                        {{ $company->company_name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="text-danger small mt-1 d-none" id="billing_company_error">
                                Please select a billing company for a COMPANY customer.
                            </div>
                            @if($billingCompanies->isEmpty())
                                <div class="form-text text-warning">
                                    No billing companies yet.
                                    <a href="{{ route('billingCompanies.index') }}">Register one first</a>.
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label class="form-label">Customer Name / Full Title <span class="text-danger">*</span></label>
                            <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name') }}" placeholder="e.g. JOHN DOS SANTOS / ACME CORP" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Customer Code (Auto-generated if empty)</label>
                            <input type="text" name="customer_code" class="form-control" value="{{ old('customer_code') }}" placeholder="e.g. CUST-00001">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Contact Person</label>
                            <input type="text" name="contact_person" class="form-control" value="{{ old('contact_person') }}" placeholder="Contact Name">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="+255...">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="client@domain.com">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Credit Limit (TZS)</label>
                            <input type="number" step="0.01" name="credit_limit" class="form-control" value="{{ old('credit_limit', 0) }}" min="0">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Payment Terms (Days)</label>
                            <input type="number" name="payment_terms_days" class="form-control" value="{{ old('payment_terms_days', 0) }}" min="0" placeholder="e.g. 30">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Physical Address / Notes</label>
                        <textarea name="address" rows="3" class="form-control" placeholder="Provide street address, P.O. Box, or notes...">{{ old('address') }}</textarea>
                    </div>

                    <div class="modal-footer px-0 pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="arbif-btn-submit">Save Customer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function () {

        const $type   = $('#modal_cust_type');
        const $div    = $('#billing_company_div');
        const $select = $('#billing_company_id');
        const $error  = $('#billing_company_error');

        function toggleCompanyField() {
            if ($type.val() === 'COMPANY') {
                $div.slideDown();
            } else {
                $div.slideUp();
                $select.val('').trigger('change');   // clear so a stale id is never posted
                $error.addClass('d-none');
            }
        }

        $type.on('change', toggleCompanyField);
        toggleCompanyField();

        // Validate in JS instead of the native "required" attribute.
        // Searchable plugins hide the real <select>, and a hidden required field
        // blocks submit silently ("invalid form control is not focusable").
        $('#salesCustomerForm').on('submit', function (e) {
            if ($type.val() === 'COMPANY' && !$select.val()) {
                e.preventDefault();
                $error.removeClass('d-none');
                $div[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });

        $select.on('change', function () {
            if ($(this).val()) $error.addClass('d-none');
        });

        // Re-open the modal after a failed server-side validation
        @if ($errors->any())
            new bootstrap.Modal(document.getElementById('addCustomerModal')).show();
        @endif
    });
</script>
@endpush

