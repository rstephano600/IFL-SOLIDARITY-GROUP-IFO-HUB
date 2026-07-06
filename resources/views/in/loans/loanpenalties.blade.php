@extends('layouts.workingside')

@section('title', 'Loan Penalties')
@section('page-title', 'Loan Penalties Management')

@section('content')

<div class="arbif-page-header">

    <h3>
        <div class="page-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        Loan Penalties
    </h3>

    <button class="arbif-btn-submit"
            data-bs-toggle="modal"
            data-bs-target="#addFormModal">

        <i class="fas fa-plus"></i>
        Register Penalty

    </button>

</div>


<div class="arbif-card">

    <div class="arbif-card-body">

        <div class="arbif-table-wrap">

            <table class="arbif-table" id="loanPenaltyTable">

                <thead>

                    <tr>

                        <th class="sortable">#</th>
                        <th class="sortable">Loan Number</th>
                        <th class="sortable">Client</th>
                        <th class="sortable">Penalty Category</th>
                        <th class="sortable">Penalty Date</th>
                        <th class="sortable">Overdue Days</th>
                        <!-- <th class="sortable">Rate (%)</th> -->
                        <th class="sortable">Penalty Amount</th>
                        <th class="sortable">Status</th>
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
                            {{ optional($item->penaltyCategory)->name }}
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($item->penalty_date)->format('d M Y') }}
                        </td>

                        <td>
                            {{ $item->overdue_days }}
                        </td>

                        <!-- <td>
                            {{ number_format($item->penalty_rate,2) }}
                        </td> -->

                        <td>
                            {{ number_format($item->penalty_amount,2) }}
                        </td>

                        <td>

                            @if($item->payment_status == 'PAID')

                                <span class="badge bg-success">
                                    PAID
                                </span>

                            @else

                                <span class="badge bg-danger">
                                    NOT PAID
                                </span>

                            @endif

                        </td>

                        <td>

                            <a href="{{ route('viewloanpenalty', encrypt($item->id)) }}"
                               class="arbif-btn-view">

                                View

                            </a>

                            <a href="{{ route('editloanpenalty', encrypt($item->id)) }}"
                               class="arbif-btn-edit">

                                Edit

                            </a>

                            <a href="{{ route('destroyloanpenalty', encrypt($item->id)) }}"
                               class="arbif-btn-delete"
                               onclick="return confirm('Are you sure you want to delete this penalty?')">

                                Delete

                            </a>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="10" class="text-center">
                            No Loan Penalties Found
                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


<div class="modal fade arbif-modal"
     id="addFormModal"
     tabindex="-1">

    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <div class="modal-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>

                <h5 class="modal-title">
                    Register Loan Penalty
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <form method="POST"
                      action="{{ route('storeloanpenalty') }}">

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


                        <div class="col-md-6">

                            <label class="form-label">
                                Penalty Category
                            </label>

                            <select name="penalty_id"
                                    data-searchable data-placeholder="Search ..."
                                    required>

                                <option value="">
                                    Select Category
                                </option>

                                @foreach($penalties as $penalty)

                                <option value="{{ $penalty->id }}">
                                    {{ $penalty->name }}
                                </option>

                                @endforeach

                            </select>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Penalty Date
                            </label>

                            <input type="date"
                                   name="penalty_date"
                                   value="{{ date('Y-m-d') }}"
                                   class="form-control"
                                   required>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Overdue Days
                            </label>

                            <input type="number"
                                   name="overdue_days"
                                   class="form-control"
                                   required>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Penalty amount
                            </label>

                            <input type="number"
                                   step="1"
                                   name="penalty_rate"
                                   class="form-control"
                                   required>

                        </div>


                        <div class="col-md-12">

                            <label class="form-label">
                                Remarks
                            </label>

                            <textarea name="remarks"
                                      rows="4"
                                      class="form-control"></textarea>

                        </div>

                    </div>

                    <div class="modal-footer mt-4">

                        <button type="button"
                                class="arbif-btn-cancel"
                                data-bs-dismiss="modal">

                            Cancel

                        </button>

                        <button type="submit"
                                class="arbif-btn-submit">

                            Save Penalty

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

    new DataTable('#loanPenaltyTable',{

        perPage:10,
        searchable:true,
        sortable:true

    });

    $('.select2_demo_3').select2({

        theme:'bootstrap4',
        width:'100%'

    });

});

</script>

@endpush