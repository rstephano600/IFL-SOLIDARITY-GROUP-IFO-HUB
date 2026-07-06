@extends('layouts.workingside')

@section('title', 'Loan Guarantors')
@section('page-title', 'Loan Guarantors')

@section('content')

<div class="arbif-page-header">

    <h3>
        <div class="page-icon">
            <i class="fas fa-user-shield"></i>
        </div>
        Loan Guarantors
    </h3>

    <button class="arbif-btn-submit"
            data-bs-toggle="modal"
            data-bs-target="#addFormModal">

        <i class="fas fa-plus"></i>
        Assign Guarantor

    </button>

</div>

<div class="arbif-card">

    <div class="arbif-card-body">

        <div class="arbif-table-wrap">

            <table class="arbif-table"
                   id="loanGuarantorTable">

                <thead>

                    <tr>

                        <th class="sortable">#</th>
                        <th class="sortable">Loan Number</th>
                        <th class="sortable">Client</th>
                        <th class="sortable">Guarantor</th>
                        <th class="sortable">Guaranteed Amount</th>
                        <th class="sortable">Relationship</th>
                        <th>Actions</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($data as $index => $item)

                    <tr>

                        <td>{{ $index + 1 }}</td>

                        <td>
                            {{ optional($item->loan)->loan_number }}
                        </td>

                        <td>
                            {{ optional($item->client)->client->name }}
                        </td>

                        <td>
                            {{ optional($item->guarantor)->full_name }}
                        </td>

                        <td>
                            {{ number_format($item->guarantee_amount,2) }}
                        </td>

                        <td>
                            {{ $item->relationship_type }}
                        </td>

                        <td>

                            <a href="{{ route('viewloanguarantor', encrypt($item->id)) }}"
                               class="arbif-btn-view">
                                View
                            </a>

                            <a href="{{ route('editloanguarantor', encrypt($item->id)) }}"
                               class="arbif-btn-edit">
                                Edit
                            </a>

                            <a href="{{ route('destroyloanguarantor', encrypt($item->id)) }}"
                               class="arbif-btn-delete"
                               onclick="return confirm('Are you sure?')">

                                Delete

                            </a>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="7"
                            class="text-center">

                            No Loan Guarantors Found

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


{{-- MODAL --}}

<div class="modal fade arbif-modal"
     id="addFormModal"
     tabindex="-1">

    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <div class="modal-icon">
                    <i class="fas fa-user-check"></i>
                </div>

                <h5 class="modal-title">
                    Assign Loan Guarantor
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <form method="POST"
                      action="{{ route('storeloanguarantor') }}">

                    @csrf

                    <div class="row g-3">

                        <div class="col-md-12">

                            <label class="form-label">
                                Loan
                            </label>

                            <select name="loan_id"
                                    data-searchable data-placeholder="Search ..."
                                    required>

                                <option value="">
                                    Select Loan
                                </option>

                                @foreach($loans as $loan)

                                <option value="{{ $loan->id }}">

                                    {{ $loan->loan_number }}
                                    -
                                    {{ optional($loan->client)->client->name ?? ' ' }}

                                </option>

                                @endforeach

                            </select>

                        </div>


                        <div class="col-md-12">

                            <label class="form-label">
                                Guarantor
                            </label>

                            <select name="guarantor_id"
                                    data-searchable data-placeholder="Search ..."
                                    required>

                                <option value="">
                                    Select Guarantor
                                </option>

                                @foreach($guarantors as $guarantor)

                                <option value="{{ $guarantor->id }}">

                                    {{ $guarantor->full_name }}
                                    -
                                    {{ $guarantor->phone_number }}

                                </option>

                                @endforeach

                            </select>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Guarantee Amount
                            </label>

                            <input type="number"
                                   step="0.01"
                                   name="guarantee_amount"
                                   class="form-control"
                                   required>

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">
                                Relationship Type
                            </label>

                            <input type="text"
                                   name="relationship_type"
                                   class="form-control">

                        </div>

                        <div class="col-md-12">

                            <label class="form-label">
                                Remarks
                            </label>

                            <textarea name="remarks"
                                      rows="3"
                                      class="form-control"></textarea>

                        </div>

                    </div>

                    <div class="modal-footer mt-3">

                        <button type="submit"
                                class="arbif-btn-submit">

                            Save Assignment

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

$(document).ready(function () {

    new DataTable('#loanGuarantorTable');

    $('.select2_demo_3').select2({
        theme: 'bootstrap4',
        width: '100%'
    });

});

</script>

@endpush