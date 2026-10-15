@extends('layouts.app')   {{-- change to your real layout name --}}

@section('content')
@php
    // When the edit form failed validation, old() belongs to the edit modal, not the create modal.
    $editing = session('edit_id');
    $createVal = fn($k, $d = null) => $editing ? $d : old($k, $d);
    $editVal   = fn($k, $d = null) => $editing ? old($k, $d) : $d;
@endphp

<div class="container-fluid py-3">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <h4 class="mb-0">Billing Companies</h4>

        <div class="d-flex gap-2">
            <form method="GET" action="{{ route('billingCompanies.index') }}" class="d-flex gap-2">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                       placeholder="Search name, code, TIN, phone">
                <button class="btn btn-secondary" type="submit">Search</button>
            </form>
            <button class="arbif-btn-submit" data-bs-toggle="modal" data-bs-target="#addBillingModal">
                <i class="fas fa-plus"></i> Add billing company
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Code</th>
                    <th>Company</th>
                    <th>TIN / VRN</th>
                    <th>Contact</th>
                    <th class="text-end">Credit limit (TZS)</th>
                    <th>Terms</th>
                    <th>Customers</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($billingCompanies as $bc)
                    <tr>
                        <td>{{ $billingCompanies->firstItem() + $loop->index }}</td>
                        <td>{{ $bc->company_code }}</td>
                        <td>
                            <strong>{{ $bc->company_name }}</strong><br>
                            <small class="text-muted">{{ $bc->email }}</small>
                        </td>
                        <td>
                            {{ $bc->tin_number ?: '-' }}<br>
                            <small class="text-muted">{{ $bc->vrn_number ?: '-' }}</small>
                        </td>
                        <td>
                            {{ $bc->contact_person ?: '-' }}<br>
                            <small class="text-muted">{{ $bc->phone }}</small>
                        </td>
                        <td class="text-end">{{ number_format($bc->credit_limit, 2) }}</td>
                        <td>{{ $bc->payment_terms_days }} days</td>
                        <td>{{ $bc->customers_count }}</td>
                        <td>
                            <span class="badge {{ $bc->Status === 'Active' ? 'bg-success' : 'bg-secondary' }}">
                                {{ $bc->Status }}
                            </span>
                        </td>
                        <td class="text-end text-nowrap">
                            <button type="button" class="btn btn-sm btn-outline-primary btn-edit-billing"
                                    data-id="{{ $bc->id }}"
                                    data-company="{{ json_encode($bc->only([
                                        'company_code','company_name','tin_number','vrn_number','registration_number',
                                        'contact_person','phone','email','city','region','country','address',
                                        'credit_limit','payment_terms_days','Status'
                                    ])) }}">
                                <i class="fas fa-edit"></i> Edit
                            </button>

                            <form method="POST" action="{{ route('billingCompanies.destroy', $bc->id) }}"
                                  class="d-inline"
                                  onsubmit="return confirm('Delete {{ addslashes($bc->company_name) }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">
                            No billing companies yet. Use "Add billing company" to register the first one.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $billingCompanies->links() }}
</div>

{{-- ADD MODAL --}}
<div class="modal fade arbif-modal" id="addBillingModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 80%;">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon"><i class="fas fa-building"></i></div>
                <h5 class="modal-title">Register Billing Company</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('billingCompanies.store') }}">
                    @csrf

                    @if ($errors->createBilling->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->createBilling->all() as $e)<li>{{ $e }}</li>@endforeach
                            </ul>
                        </div>
                    @endif

                    @include('billing_companies._fields', ['p' => 'c', 'v' => $createVal])

                    <div class="modal-footer px-0 pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="arbif-btn-submit">Save company</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- EDIT MODAL --}}
<div class="modal fade arbif-modal" id="editBillingModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 80%;">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon"><i class="fas fa-edit"></i></div>
                <h5 class="modal-title">Edit Billing Company</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="#" id="editBillingForm">
                    @csrf
                    @method('PUT')

                    @if ($errors->updateBilling->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->updateBilling->all() as $e)<li>{{ $e }}</li>@endforeach
                            </ul>
                        </div>
                    @endif

                    @include('billing_companies._fields', ['p' => 'e', 'v' => $editVal])

                    <div class="modal-footer px-0 pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="arbif-btn-submit">Save changes</button>
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
        const updateUrl = "{{ route('billingCompanies.update', ':id') }}";
        const fields = ['company_code','company_name','tin_number','vrn_number','registration_number',
                        'contact_person','phone','email','city','region','country','address',
                        'credit_limit','payment_terms_days','Status'];

        // Fill the edit modal from the row's data
        $('.btn-edit-billing').on('click', function () {
            const c = $(this).data('company');
            fields.forEach(f => $('#e_' + f).val(c[f] ?? ''));
            $('#editBillingForm').attr('action', updateUrl.replace(':id', $(this).data('id')));
            new bootstrap.Modal(document.getElementById('editBillingModal')).show();
        });

        // Re-open the right modal after a failed validation
        @if (session('edit_id'))
            $('#editBillingForm').attr('action', updateUrl.replace(':id', {{ session('edit_id') }}));
            new bootstrap.Modal(document.getElementById('editBillingModal')).show();
        @elseif ($errors->createBilling->any())
            new bootstrap.Modal(document.getElementById('addBillingModal')).show();
        @endif
    });
</script>
@endpush