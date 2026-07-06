@extends('layouts.configside') 
@section('title', 'Edit Country Account')
@section('page-title', 'Edit Country Account')

@section('content')

<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-edit"></i></div>Edit Country Account</h3>
    <a href="{{ route('accountCountry') }}" class="arbif-btn-cancel"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <form method="POST" action="{{ route('updateaccountCountry', encrypt($data->id)) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label" for="CountryCode"> Country Code </label>
                    <input  type="text" name="CountryCode" id="CountryCode" class="form-control @error('CountryCode') is-invalid @enderror" value="{{ old('CountryCode', $data->CountryCode) }}" placeholder="e.g. TZ" maxlength="5" required >
                </div>
                <div class="col-md-8">
                    <label class="form-label" for="CountryName"> Country Name </label>
                    <input  type="text" name="CountryName" id="CountryName" class="form-control @error('CountryName') is-invalid @enderror" value="{{ old('CountryName', $data->CountryName) }}" placeholder="e.g. Tanzania" required >
                </div>
            </div>
            <div class="mt-4 d-flex justify-content-end gap-2">
                <a href="{{ route('accountCountry') }}" class="arbif-btn-cancel"> <i class="bi bi-x"></i> Cancel</a>
                <button onclick="confirmSubmit()" type="submit" class="arbif-btn-submit"> <i class="bi bi-check2"></i> Update Country </button>
            </div>
        </form>

    </div>
</div>

@endsection