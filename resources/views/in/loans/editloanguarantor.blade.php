@extends('layouts.workingside')

@section('title','Edit Loan Guarantor')

@section('content')

<div class="arbif-card">

    <div class="arbif-card-body">

        <form method="POST"
              action="{{ route('updateloanguarantor', encrypt($data->id)) }}">

            @csrf

            <div class="row g-3">

                <div class="col-md-12">

                    <label>Loan</label>

                    <select name="loan_id"
                            class="form-control select2_demo_3">

                        @foreach($loans as $loan)

                        <option value="{{ $loan->id }}"
                            {{ $data->loan_id == $loan->id ? 'selected' : '' }}>

                            {{ $loan->loan_number }}

                        </option>

                        @endforeach

                    </select>

                </div>

                <div class="col-md-12">

                    <label>Guarantor</label>

                    <select name="guarantor_id"
                            class="form-control select2_demo_3">

                        @foreach($guarantors as $guarantor)

                        <option value="{{ $guarantor->id }}"
                            {{ $data->guarantor_id == $guarantor->id ? 'selected' : '' }}>

                            {{ $guarantor->full_name }}

                        </option>

                        @endforeach

                    </select>

                </div>

                <div class="col-md-6">

                    <label>Guarantee Amount</label>

                    <input type="number"
                           step="0.01"
                           name="guarantee_amount"
                           value="{{ $data->guarantee_amount }}"
                           class="form-control">

                </div>

                <div class="col-md-6">

                    <label>Relationship Type</label>

                    <input type="text"
                           name="relationship_type"
                           value="{{ $data->relationship_type }}"
                           class="form-control">

                </div>

                <div class="col-md-12">

                    <label>Remarks</label>

                    <textarea name="remarks"
                              rows="3"
                              class="form-control">{{ $data->remarks }}</textarea>

                </div>

            </div>

            <div class="mt-4">

                <button type="submit"
                        class="arbif-btn-submit">

                    Update Loan Guarantor

                </button>

            </div>

        </form>

    </div>

</div>

@endsection