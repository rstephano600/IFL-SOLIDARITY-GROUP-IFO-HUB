@extends('layouts.workingside')

@section('title','Edit Guarantor')

@section('content')

<div class="arbif-card">

    <div class="arbif-card-body">

        <form method="POST"
              action="{{ route('updateguarantor', encrypt($data->id)) }}">

            @csrf

            <div class="row g-3">

                <div class="col-md-4">
                    <label>First Name</label>
                    <input type="text"
                           name="first_name"
                           value="{{ $data->first_name }}"
                           class="form-control">
                </div>

                <div class="col-md-4">
                    <label>Middle Name</label>
                    <input type="text"
                           name="middle_name"
                           value="{{ $data->middle_name }}"
                           class="form-control">
                </div>

                <div class="col-md-4">
                    <label>Last Name</label>
                    <input type="text"
                           name="last_name"
                           value="{{ $data->last_name }}"
                           class="form-control">
                </div>

                <div class="col-md-6">
                    <label>Phone</label>
                    <input type="text"
                           name="phone_number"
                           value="{{ $data->phone_number }}"
                           class="form-control">
                </div>

                <div class="col-md-6">
                    <label>NIDA</label>
                    <input type="text"
                           name="nida_number"
                           value="{{ $data->nida_number }}"
                           class="form-control">
                </div>

                <div class="col-md-12">
                    <label>Address</label>
                    <textarea name="physical_address"
                              rows="3"
                              class="form-control">{{ $data->physical_address }}</textarea>
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

                    Update Guarantor

                </button>

            </div>

        </form>

    </div>

</div>

@endsection