@extends('layouts.configside') 
@section('title', 'Edit First Branch Account')
@section('page-title', 'Edit First Branch Account')

@section('content')

<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-edit"></i></div>Edit First Branch Account</h3>
    <a href="{{ route('accountFirstBranch') }}" class="arbif-btn-cancel"><i class="fas fa-arrow-left"></i> Back </a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <form method="POST" action="{{ route('updateaccountFirstBranch', encrypt($data->id)) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label" for="FirstAccountCode">Account Root</label>
                    <select name="AccountRoot_id" class="form-select @error('AccountRoot_id') is-invalid @enderror" required>
                        <option value="">Select Country</option>
                        @foreach($accountroot as $country)
                            <option 
                                value="{{ $country->id }}"
                                {{ old('AccountRoot_id', $data->AccountRoot_id) == $country->id ? 'selected' : '' }} >
                                {{$country->AccountCode}} - {{$country->AccountName}}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="FirstAccountCode">First Branch Code</label>
                    <input  type="text" name="FirstAccountCode" id="FirstAccountCode" class="form-control @error('FirstAccountCode') is-invalid @enderror" value="{{ old('FirstAccountCode', $data->FirstAccountCode) }}" placeholder="e.g. 01" maxlength="5" required >
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="FirstAccountName"> First Branch Name </label>
                    <input  type="text" name="FirstAccountName" id="FirstAccountName" class="form-control @error('FirstAccountName') is-invalid @enderror" value="{{ old('FirstAccountName', $data->FirstAccountName) }}" placeholder="e.g. Asset" required >
                </div>
            </div>
            <div class="mt-4 d-flex justify-content-end gap-2">
                <a href="{{ route('accountFirstBranch') }}" class="arbif-btn-cancel"><i class="bi bi-x"></i> Cancel</a>
                <button onclick="confirmSubmit()" type="submit" class="arbif-btn-submit"><i class="bi bi-check2"></i> Update First Branch</button>
            </div>
        </form>

    </div>
</div>

@endsection