@extends('layouts.configside') 
@section('title', 'Edit Group Center')
@section('page-title', 'Edit Group Collection Center')

@section('content')

<div class="arbif-page-header">

    <h3>
        <div class="page-icon">
            <i class="fas fa-edit"></i>
        </div>
        Edit Group Collection Center
    </h3>

    <a href="{{ route('groupCenter') }}" class="arbif-btn-cancel">
        <i class="fas fa-arrow-left"></i> Back
    </a>

</div>

<div class="arbif-card">

    <div class="arbif-card-body">

        <form 
            method="POST" 
            action="{{ route('updategroupCenter', encrypt($data->id)) }}"
        >

            @csrf
            @method('PUT')

            <div class="row g-3">

                <div class="col-md-4">

                    <label class="form-label">
                        Center Name
                    </label>

                    <input 
                        type="text"
                        name="center_name"
                        class="form-control @error('center_name') is-invalid @enderror"
                        value="{{ old('center_name', $data->center_name) }}"
                        placeholder="Enter center name"
                        required
                    >

                    @error('center_name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <div class="col-md-4">

                    <label class="form-label">
                        Center Code
                    </label>

                    <input 
                        type="text"
                        class="form-control"
                        value="{{ $data->center_code }}"
                        readonly
                    >

                </div>


                <div class="col-md-4">

                    <label class="form-label">
                        Location
                    </label>

                    <input 
                        type="text"
                        name="location"
                        class="form-control @error('location') is-invalid @enderror"
                        value="{{ old('location', $data->location) }}"
                        placeholder="Enter location"
                    >

                    @error('location')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <div class="col-md-4">

                    <label class="form-label">
                        Area
                    </label>

                    <input 
                        type="text"
                        name="area"
                        class="form-control @error('area') is-invalid @enderror"
                        value="{{ old('area', $data->area) }}"
                        placeholder="Enter area"
                    >

                    @error('area')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <div class="col-md-4">

                    <label class="form-label">
                        Established Date
                    </label>

                    <input 
                        type="date"
                        name="established_date"
                        class="form-control @error('established_date') is-invalid @enderror"
                        value="{{ old('established_date', $data->established_date) }}"
                    >

                    @error('established_date')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <div class="col-md-4">

                    <label class="form-label">
                        Collection Officer
                    </label>

                    <select 
                        name="collection_officer_id"
                        class="form-select select2_demo_3 @error('collection_officer_id') is-invalid @enderror"
                    >

                        <option value="">
                            Select Collection Officer
                        </option>

                        @foreach($employees as $employee)

                            <option 
                                value="{{ $employee->id }}"
                                {{ old('collection_officer_id', $data->collection_officer_id) == $employee->id ? 'selected' : '' }}
                            >
                                {{ $employee->FirstName ?? '' }}
                                {{ $employee->MiddleName ?? '' }}
                                {{ $employee->LastName ?? '' }}
                                - 
                                {{ $employee->EmployeeID ?? '' }}
                            </option>

                        @endforeach

                    </select>

                    @error('collection_officer_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <div class="col-md-12">

                    <label class="form-label">
                        Description
                    </label>

                    <textarea 
                        name="description"
                        rows="5"
                        class="form-control @error('description') is-invalid @enderror"
                        placeholder="Enter center description"
                    >{{ old('description', $data->description) }}</textarea>

                    @error('description')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>


            <div class="mt-4 d-flex justify-content-end gap-2">

                <a href="{{ route('groupCenter') }}" class="arbif-btn-cancel">
                    <i class="bi bi-x"></i> Cancel
                </a>

                <button onclick="confirmsubmit()" type="submit" class="arbif-btn-submit">
                    <i class="bi bi-check2"></i> Update Group Center
                </button>

            </div>

        </form>

    </div>

</div>

@endsection


@push('scripts')
<script>

    $(document).ready(function () {

        $('.select2_demo_3').select2({
            theme: 'bootstrap4',
            width: '100%'
        });

    });

</script>
@endpush