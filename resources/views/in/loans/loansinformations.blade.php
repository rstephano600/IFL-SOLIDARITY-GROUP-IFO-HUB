@extends('layouts.workingside')

@section('title', 'Loans Informations')

@section('page-title', 'Loans Informations')

@section('content')

<div class="arbif-page-header">

    <h3>

        <div class="page-icon">
            <i class="fas fa-hand-holding-usd"></i>
        </div>

        Loans Informations

    </h3>


    <button class="arbif-btn-submit"
            data-bs-toggle="modal"
            data-bs-target="#addFormModal">

        <i class="fas fa-plus"></i>
        Register Loan

    </button>

</div>



<div class="arbif-card">

    <div class="arbif-card-body">

        <div class="arbif-table-wrap">

            <table class="arbif-table"
                   id="loanTable">

                <thead>

                    <tr>

                        <th class="sortable">#</th>

                        <th class="sortable">Loan Number</th>

                        <th class="sortable">Client</th>

                        <th class="sortable">Loan Category</th>

                        <th class="sortable">Requested Amount</th>

                        <!-- <th class="sortable">Interest</th> -->

                        <th class="sortable">Total Repayable</th>

                        <th class="sortable">Inst Frequency</th>

                        <th class="sortable">Approval Status</th>

                        <!-- <th class="sortable">Expected End</th> -->

                        <th>Actions</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($data as $index => $item)

                    <tr>

                        <td>{{ $index + 1 }}</td>

                        <td>

                            <span >
                                {{ $item->loan_number ?? 'N/A' }}
                            </span>

                        </td>

                        <td>
                            {{ optional($item->client)->client->name ?? 'N/A' }}
                        </td>

                        <td>
                            {{ optional($item->loanCategory)->name ?? 'N/A' }}
                        </td>

                        <td>
                            {{ number_format($item->loanCategory->amount_disbursed ?? 0, 2) }}
                        </td>

                        <!-- <td>
                            {{ number_format($item->interest_amount ?? 0, 2) }}
                        </td> -->

                        <td>
                            <strong>
                                {{ number_format($item->loanCategory->repayable_amount ?? 0, 2) }}
                            </strong>
                        </td>

                        <td>
                            {{ number_format($item->client_payable_frequency ?? 0, 2) }}
                        </td>

                        <td>

                            @if($item->ApprovalStatus == 'Approved')
                                <span class="arbif-badge arbif-badge-success">
                                    Approved
                                </span>
                            @elseif($item->ApprovalStatus == 'Pending')
                                <span class="arbif-badge arbif-badge-warning">
                                    Pending
                                </span>
                            @else
                                <span class="arbif-badge arbif-badge-danger">
                                    {{ $item->ApprovalStatus }}
                                </span>
                            @endif
                        </td>

                        <!-- <td>
                            {{ $item->expected_end_date
                                ? \Carbon\Carbon::parse($item->expected_end_date)->format('d M Y')
                                : 'N/A'
                            }}
                        </td> -->

                        <td>
                            <a href="{{ route('viewloaninformation', encrypt($item->id)) }}"
                               class="arbif-btn-view">
                                <i class="fas fa-eye"></i>
                                View
                            </a>
                            @if($item->ApprovalStatus == 'Pending')
                            <a href="{{ route('editloaninformation', encrypt($item->id)) }}"
                               class="arbif-btn-edit">
                                <i class="fas fa-pencil"></i>
                                Edit
                            </a>
                            <a href="{{ route('destroyloaninformation', encrypt($item->id)) }}"
                               class="arbif-btn-delete"
                               onclick="return confirm('Are you sure you want to DELETE this Loan Information?');">
                                <i class="fas fa-trash"></i>
                                Delete
                            </a>
                            @endif

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="11"
                            class="text-center">

                            No Loan Informations Found

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>



{{-- REGISTER LOAN MODAL --}}

<div class="modal fade arbif-modal"
     id="addFormModal"
     tabindex="-1">

    <div class="modal-dialog modal-dialog-centered modal-xl">

        <div class="modal-content">

            <div class="modal-header">

                <div class="modal-icon">
                    <i class="bi bi-cash-stack"></i>
                </div>

                <h5 class="modal-title">
                    Register Loan Information
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>


            <div class="modal-body">

                <form method="POST"
                      action="{{ route('registerloaninformation') }}">

                    @csrf

                    <div class="row g-3">

                        <div class="col-12">

                            <h5 class="arbif-section-title">
                                Loan Registration Information
                            </h5>

                        </div>

                        {{-- CLIENT --}}
                        <div class="col-md-12">
                            <label class="form-label"> Client </label>
                            <select name="client_id" data-searchable data-placeholder="Search ...">
                                <option value=""> Select Client </option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">
                                        {{ $client->client->name ?? '' }} - (Group : {{ $client->group->group_name ?? '' }}) (Center : {{ $client->groupCenter->center_name ?? '' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- LOAN CATEGORY --}}
                        <div class="col-md-4">
                            <label class="form-label"> Loan Category </label>
                            <select name="loan_category_id" data-searchable data-placeholder="Search ..." required>
                                <option value="">Select Loan Category</option>
                                @foreach($loanCategories as $category)
                                    <option value="{{ $category->id }}"
                                            data-interest="{{ $category->interest_rate }}"
                                            data-frequency="{{ $category->repayment_frequency }}">
                                        {{ $category->name }} - ({{ $category->amount_disbursed }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- <div class="col-md-4">
                            <label class="form-label">Group</label>
                            <select name="group_id" data-searchable data-placeholder="Search group...">
                                <option value="">Select Group</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->group_name }}</option>
                                @endforeach
                            </select>
                        </div> -->

                        <div class="col-md-4">
                            <label class="form-label"> Requested Date</label>
                            <input type="date"  name="application_date" id="application_date" class="form-control loan-calc" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"> Membership Fee </label>
                            <input type="number" step="0.01" name="membership_fee" id="membership_fee" class="form-control loan-calc" placeholder="0.00" required>
                        </div>

                        {{-- CLIENT PAYABLE --}}
                        <!-- <div class="col-md-4">
                            <label class="form-label"> Client Payable Frequency </label>
                            <input type="number" step="0.01" name="client_payable_frequency" id="client_payable_frequency" class="form-control loan-calc" placeholder="0.00" required>
                        </div> -->

                    </div>

                    <div class="modal-footer mt-4">
                        <button type="button"
                                class="arbif-btn-cancel"
                                data-bs-dismiss="modal">
                            <i class="bi bi-x"></i>
                            Cancel
                        </button>
                        <button type="submit"
                                class="arbif-btn-submit">
                            <i class="bi bi-check-circle"></i>
                            Register Loan
                        </button>
                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection
