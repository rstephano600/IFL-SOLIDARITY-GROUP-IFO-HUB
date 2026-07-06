@extends('layouts.configside') 
@section('title', 'Edit Second Branch Account')
@section('page-title', 'Edit Second Branch Account')

@section('content')

<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-edit"></i></div>Edit Second Branch Account</h3>
    <a href="{{ route('accountSecondBranch') }}" class="arbif-btn-cancel"><i class="fas fa-arrow-left"></i> Back </a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <form method="POST" action="{{ route('updateaccountSecondBranch', encrypt($data->id)) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label" for="FirstRoot_id">Account Root</label>
                    <select name="FirstRoot_id" class="form-select @error('FirstRoot_id') is-invalid @enderror" required>
                        <option value="">Select Country</option>
                        @foreach($accountfirst as $country)
                            <option 
                                value="{{ $country->id }}"
                                {{ old('FirstRoot_id', $data->FirstRoot_id) == $country->id ? 'selected' : '' }} >
                                {{$country->FirstAccountCode}} - {{$country->FirstAccountName}}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="SecondAccountCode">Second Branch Code</label>
                    <input  type="text" name="SecondAccountCode" id="SecondAccountCode" class="form-control @error('SecondAccountCode') is-invalid @enderror" value="{{ old('SecondAccountCode', $data->SecondAccountCode) }}" placeholder="e.g. 01" maxlength="5" required >
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="SecondAccountName"> Second Branch Name </label>
                    <input  type="text" name="SecondAccountName" id="SecondAccountName" class="form-control @error('SecondAccountName') is-invalid @enderror" value="{{ old('SecondAccountName', $data->SecondAccountName) }}" placeholder="e.g. Asset" required >
                </div>
            </div>
            <div class="mt-4 d-flex justify-content-end gap-2">
                <a href="{{ route('accountSecondBranch') }}" class="arbif-btn-cancel"><i class="fas fa-x"></i> Cancel</a>
                <button onclick="confirmSubmit()" type="submit" class="arbif-btn-submit"><i class="fas fa-check2"></i> Update Second Branch</button>
            </div>
        </form>

    </div>
</div>

@endsection