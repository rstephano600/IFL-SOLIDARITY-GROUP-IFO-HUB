@extends('layouts.workingside')

@section('title','Edit Loan Information')

@section('page-title','Edit Loan Information')

@section('content')
<div class="arbif-page-header">

    <h3>

        <div class="page-icon">
            <i class="fas fa-hand-holding-usd"></i>
        </div>

        Edit Loans Informations {{ $data->loan_number }}

    </h3>

</div>

<div class="arbif-card">

    <div class="arbif-card-body">

        <form method="POST"
              action="{{ route('updateloaninformation', encrypt($data->id)) }}">

            @csrf

            <div class="row g-3">

                <div class="col-md-6">

                    <label class="form-label">
                        Client
                    </label>

                    <select name="client_id"
                            class="form-control select2_demo_3"
                            required>

                        @foreach($clients as $client)

                            <option value="{{ $client->id }}"
                                {{ $data->client_id == $client->id ? 'selected' : '' }}>

                                {{ optional($client->client)->name ?? 'N/A' }}

                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        Group
                    </label>

                    <select name="group_id"
                            class="form-control select2_demo_3">

                        <option value="">
                            Select Group
                        </option>

                        @foreach($groups as $group)

                            <option value="{{ $group->id }}"
                                {{ $data->group_id == $group->id ? 'selected' : '' }}>

                                {{ $group->group_name }}

                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="col-md-3">

                    <label class="form-label">
                        Loan Category
                    </label>

                    <select name="loan_category_id"
                            class="form-control select2_demo_3"
                            required>

                        @foreach($loanCategories as $category)

                            <option value="{{ $category->id }}"
                                {{ $data->loan_category_id == $category->id ? 'selected' : '' }}>

                                {{ $category->name }}

                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="col-md-3">

                    <label class="form-label">
                        Amount Requested
                    </label>

                    <input type="number"
                           step="0.01"
                           name="amount_requested"
                           value="{{ $data->amount_requested }}"
                           class="form-control"
                           required>

                </div>


                <div class="col-md-3">

                    <label class="form-label">
                        Client Installment
                    </label>

                    <input type="number"
                           step="0.01"
                           name="client_payable_frequency"
                           value="{{ $data->client_payable_frequency }}"
                           class="form-control"
                           required>

                </div>

                <div class="col-md-3">

                    <label class="form-label">
                        Membership Fee
                    </label>

                    <input type="number"
                           step="0.01"
                           name="membership_fee"
                           value="{{ $data->membership_fee }}"
                           class="form-control"
                           required>

                </div>

            </div>

            <div class="mt-4">

                <button type="submit"
                        class="arbif-btn-submit">

                    <i class="fas fa-save"></i>
                    Update Loan Information

                </button>

            </div>

        </form>

    </div>

</div>

@endsection