@extends('layouts.configside') 
@section('title', 'Edit Business Account')
@section('page-title', 'Edit Business Account')

@section('content')

<div class="arbif-page-header">

    <h3>
        <div class="page-icon">
            <i class="fas fa-edit"></i>
        </div>
        Edit Business Account
    </h3>

    <a href="{{ route('accountBusiness') }}" class="arbif-btn-cancel">
        <i class="fas fa-arrow-left"></i> Back
    </a>

</div>

<div class="arbif-card">

    <div class="arbif-card-body">

        <form method="POST" action="{{ route('updateaccountBusiness', encrypt($data->id)) }}" enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="row g-3">

                <div class="col-md-4">

                    <label class="form-label">
                        Country
                    </label>

                    <select name="Country_id" class="form-select @error('Country_id') is-invalid @enderror" required>

                        <option value="">Select Country</option>

                        @foreach($countries as $country)

                            <option 
                                value="{{ $country->id }}"
                                {{ old('Country_id', $data->Country_id) == $country->id ? 'selected' : '' }}
                            >
                                {{ $country->CountryName }}
                            </option>

                        @endforeach

                    </select>

                    @error('Country_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <div class="col-md-4">

                    <label class="form-label">
                        Business Code
                    </label>

                    <input 
                        type="text"
                        name="BusinessCode"
                        class="form-control @error('BusinessCode') is-invalid @enderror"
                        value="{{ old('BusinessCode', $data->BusinessCode) }}"
                        maxlength="20"
                        required
                    >

                    @error('BusinessCode')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <div class="col-md-4">

                    <label class="form-label">
                        Business Name
                    </label>

                    <input 
                        type="text"
                        name="BusinessName"
                        class="form-control @error('BusinessName') is-invalid @enderror"
                        value="{{ old('BusinessName', $data->BusinessName) }}"
                        required
                    >

                    @error('BusinessName')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>


            <div class="mt-4 d-flex justify-content-end gap-2">

                <a href="{{ route('accountBusiness') }}" class="arbif-btn-cancel">
                    <i class="bi bi-x"></i> Cancel
                </a>

                <button onclick="confirmsubmit()" type="submit" class="arbif-btn-submit">
                    <i class="bi bi-check2"></i> Update Business
                </button>

            </div>

        </form>

    </div>

</div>

@endsection