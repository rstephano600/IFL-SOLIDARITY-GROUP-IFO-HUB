@extends('layouts.workingside')

@section('title', 'Guarantors')
@section('page-title', 'Loan Guarantors')

@section('content')

<div class="arbif-page-header">

    <h3>
        <div class="page-icon">
            <i class="fas fa-user-shield"></i>
        </div>
        Guarantors
    </h3>

    <button class="arbif-btn-submit"
            data-bs-toggle="modal"
            data-bs-target="#addFormModal">

        <i class="fas fa-plus"></i>
        Register Guarantor

    </button>

</div>

<div class="arbif-card">

    <div class="arbif-card-body">

        <div class="arbif-table-wrap">

            <table class="arbif-table"
                   id="guarantorTable">

                <thead>

                    <tr>

                        <th>#</th>
                        <th>Guarantor No</th>
                        <th>Full Name</th>
                        <th>Phone</th>
                        <th>NIDA</th>
                        <th>Occupation</th>
                        <th>Actions</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($data as $index => $item)

                    <tr>

                        <td>{{ $index + 1 }}</td>

                        <td>
                            {{ $item->guarantor_number }}
                        </td>

                        <td>
                            {{ $item->full_name }}
                        </td>

                        <td>
                            {{ $item->phone_number }}
                        </td>

                        <td>
                            {{ $item->nida_number }}
                        </td>

                        <td>
                            {{ $item->occupation }}
                        </td>

                        <td>

                            <a href="{{ route('viewguarantor', encrypt($item->id)) }}"
                               class="arbif-btn-view">

                                View

                            </a>

                            <a href="{{ route('editguarantor', encrypt($item->id)) }}"
                               class="arbif-btn-edit">

                                Edit

                            </a>

                            <a href="{{ route('destroyguarantor', encrypt($item->id)) }}"
                               class="arbif-btn-delete"
                               onclick="return confirm('Are you sure?')">

                                Delete

                            </a>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="7" class="text-center">

                            No Guarantors Found

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


{{-- REGISTER MODAL --}}

<div class="modal fade arbif-modal"
     id="addFormModal"
     tabindex="-1">

    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <div class="modal-icon">
                    <i class="bi bi-person-plus"></i>
                </div>

                <h5 class="modal-title">
                    Register Guarantor
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <form method="POST"
                      action="{{ route('storeguarantor') }}">

                    @csrf

                    <div class="row g-3">

                        <div class="col-md-4">
                            <label>First Name</label>
                            <input type="text"
                                   name="first_name"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="col-md-4">
                            <label>Middle Name</label>
                            <input type="text"
                                   name="middle_name"
                                   class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label>Last Name</label>
                            <input type="text"
                                   name="last_name"
                                   class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label>Gender</label>
                            <select name="gender"
                                    class="form-control">
                                <option value="">Select</option>
                                <option>Male</option>
                                <option>Female</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label>Phone Number</label>
                            <input type="text"
                                   name="phone_number"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="col-md-4">
                            <label>Alternative Phone</label>
                            <input type="text"
                                   name="alternative_phone"
                                   class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label>NIDA Number</label>
                            <input type="text"
                                   name="nida_number"
                                   class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label>Email</label>
                            <input type="email"
                                   name="email"
                                   class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label>Occupation</label>
                            <input type="text"
                                   name="occupation"
                                   class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label>Relationship</label>
                            <input type="text"
                                   name="relationship_with_client"
                                   class="form-control">
                        </div>

                        <div class="col-md-12">
                            <label>Physical Address</label>
                            <textarea name="physical_address"
                                      rows="3"
                                      class="form-control"></textarea>
                        </div>

                        <div class="col-md-12">
                            <label>Remarks</label>
                            <textarea name="remarks"
                                      rows="3"
                                      class="form-control"></textarea>
                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="submit"
                                class="arbif-btn-submit">

                            Save Guarantor

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection

@push('scripts')
<script>

$(document).ready(function(){

    new DataTable('#guarantorTable', {

        perPage: 10,
        searchable: true,
        sortable: true

    });

});

</script>
@endpush