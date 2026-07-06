@extends('layouts.workingside')

@section('title', 'Edit Loan Penalty')
@section('page-title', 'Edit Loan Penalty')

@section('content')

<div class="arbif-page-header">

    <h3>
        <div class="page-icon">
            <i class="fas fa-edit"></i>
        </div>
        Edit Loan Penalty
    </h3>

    <a href="{{ route('loanpenalties') }}"
       class="arbif-btn-cancel">

        <i class="fas fa-arrow-left"></i>
        Back

    </a>

</div>

<div class="arbif-card">

    <div class="arbif-card-body">

        <form method="POST"
              action="{{ route('updateloanpenalty', encrypt($data->id)) }}">

            @csrf

            <div class="row g-3">

                <div class="col-12">

                    <h5 class="arbif-section-title">
                        Penalty Information
                    </h5>

                </div>

                <div class="col-md-12">

                    <label class="form-label">
                        Loan
                    </label>

                    <select name="loan_id"
                            class="form-control select2_demo_3"
                            required>

                        @foreach($loans as $loan)

                        <option value="{{ $loan->id }}"
                            {{ $data->loan_id == $loan->id ? 'selected' : '' }}>

                            {{ $loan->loan_number }}
                            -
                            {{ optional($loan->client)->FullName }}

                        </option>

                        @endforeach

                    </select>

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        Penalty Category
                    </label>

                    <select name="penalty_id"
                            class="form-control"
                            required>

                        @foreach($penalties as $penalty)

                        <option value="{{ $penalty->id }}"
                            {{ $data->penalty_id == $penalty->id ? 'selected' : '' }}>

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
                           value="{{ $data->penalty_date ? \Carbon\Carbon::parse($data->penalty_date)->format('Y-m-d') : '' }}"
                           class="form-control"
                           required>

                </div>


                <div class="col-md-4">

                    <label class="form-label">
                        Overdue Days
                    </label>

                    <input type="number"
                           name="overdue_days"
                           value="{{ $data->overdue_days }}"
                           class="form-control"
                           required>

                </div>


                <div class="col-md-4">

                    <label class="form-label">
                        Penalty Rate (%)
                    </label>

                    <input type="number"
                           step="0.01"
                           name="penalty_rate"
                           value="{{ $data->penalty_rate }}"
                           class="form-control"
                           required>

                </div>


                <div class="col-md-4">

                    <label class="form-label">
                        Penalty Amount
                    </label>

                    <input type="text"
                           value="{{ number_format($data->penalty_amount,2) }}"
                           class="form-control"
                           readonly>

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        Payment Status
                    </label>

                    <input type="text"
                           value="{{ $data->payment_status }}"
                           class="form-control"
                           readonly>

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        Paid At
                    </label>

                    <input type="text"
                           value="{{ $data->paid_at }}"
                           class="form-control"
                           readonly>

                </div>


                <div class="col-md-12">

                    <label class="form-label">
                        Remarks
                    </label>

                    <textarea name="remarks"
                              rows="4"
                              class="form-control">{{ $data->remarks }}</textarea>

                </div>

            </div>

            <div class="mt-4">

                <button type="submit"
                        class="arbif-btn-submit">

                    <i class="fas fa-save"></i>
                    Update Penalty

                </button>

            </div>

        </form>

    </div>

</div>

@endsection

@push('scripts')

<script>

$(document).ready(function(){

    $('.select2_demo_3').select2({

        theme:'bootstrap4',
        width:'100%'

    });

});

</script>

@endpush