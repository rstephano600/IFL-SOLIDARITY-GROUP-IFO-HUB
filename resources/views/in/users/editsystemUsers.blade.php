@extends('layouts.configside')
@section('title', 'System Users Informations')
@section('page-title', 'System Users Informations')

@section('content')

<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-edit"></i></div>Edit Users Informations</h3>
    <a href="{{ route('systemUsers') }}" class="arbif-btn-cancel"><i class="fas fa-arrow-left"></i> Back </a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <form method="POST" action="{{ route('updatesystemUsers', encrypt($data->id)) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label" for="FirstName">First Name</label>
                    <input  type="text" name="FirstName" id="FirstName" class="form-control @error('FirstName') is-invalid @enderror" value="{{ old('FirstName', $data->FirstName) }}" placeholder="e.g. Robert" required >
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="MiddleName">Middle Name</label>
                    <input  type="text" name="MiddleName" id="MiddleName" class="form-control @error('MiddleName') is-invalid @enderror" value="{{ old('MiddleName', $data->MiddleName) }}" placeholder="e.g. Robert">
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="LastName">Last Name</label>
                    <input  type="text" name="LastName" id="LastName" class="form-control @error('LastName') is-invalid @enderror" value="{{ old('LastName', $data->LastName) }}" placeholder="e.g. Robert" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="email">Email Address</label>
                    <input  type="text" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $data->email) }}" placeholder="e.g. Robert" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="phone">Phone Number</label>
                    <input  type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $data->phone) }}" placeholder="e.g. 0657856790" >
                </div>
                <div class="col-md-4">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select @error('role') is-invalid @enderror">
                        @foreach($roles as $r)
                            <option value="{{ $r }}" {{ old('role', $data->role) == $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
                        @endforeach
                    </select>
                    @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4 d-flex justify-content-end gap-2">
                <a href="{{ route('systemUsers') }}" class="arbif-btn-cancel"><i class="fas fa-x"></i> Cancel</a>
                <button onclick="confirmSubmit()" type="submit" class="arbif-btn-submit"><i class="fas fa-check2"></i> Update Users Informations</button>
            </div>
        </form>

    </div>
</div>

@endsection