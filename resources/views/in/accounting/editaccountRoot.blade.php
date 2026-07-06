@extends('layouts.configside') 
@section('title', 'Edit account Root Account')
@section('page-title', 'Edit account Root Account')

@section('content')

<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-edit"></i></div>Edit account Root Account</h3>
    <a href="{{ route('accountRoot') }}" class="arbif-btn-cancel"><i class="fas fa-arrow-left"></i> Back </a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <form method="POST" action="{{ route('updateaccountRoot', encrypt($data->id)) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label" for="AccountCode">account Root Code</label>
                    <input  type="text" name="AccountCode" id="AccountCode" class="form-control @error('AccountCode') is-invalid @enderror" value="{{ old('AccountCode', $data->AccountCode) }}" placeholder="e.g. 01" maxlength="5" required >
                </div>
                <div class="col-md-8">
                    <label class="form-label" for="AccountName"> account Root Name </label>
                    <input  type="text" name="AccountName" id="AccountName" class="form-control @error('AccountName') is-invalid @enderror" value="{{ old('AccountName', $data->AccountName) }}" placeholder="e.g. Asset" required >
                </div>
            </div>
            <div class="mt-4 d-flex justify-content-end gap-2">
                <a href="{{ route('accountRoot') }}" class="arbif-btn-cancel"><i class="bi bi-x"></i> Cancel</a>
                <button onclick="confirmSubmit()" type="submit" class="arbif-btn-submit"><i class="bi bi-check2"></i> Update account Root</button>
            </div>
        </form>

    </div>
</div>

@endsection