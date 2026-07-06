@extends('layouts.configside') 
@section('title', 'Edit Group')
@section('page-title', 'Edit Center Group')

@section('content')

<div class="arbif-page-header">

    <h3>
        <div class="page-icon">
            <i class="fas fa-edit"></i>
        </div>
        Edit Center Group
    </h3>

    <a href="{{ route('centerGroups') }}" class="arbif-btn-cancel">
        <i class="fas fa-arrow-left"></i> Back
    </a>

</div>

<div class="arbif-card">

    <div class="arbif-card-body">

        <form 
            method="POST" 
            action="{{ route('updatecenterGroups', encrypt($data->id)) }}"
        >

            @csrf
            @method('PUT')

            <div class="row g-3">

                <div class="col-md-4">

                    <label class="form-label">
                        Group Center
                    </label>

                    <select 
                        name="group_center_id" data-searchable data-placeholder="Search ...">

                        <option value="">
                            Select Group Center
                        </option>

                        @foreach($groupcenters as $center)

                            <option 
                                value="{{ $center->id }}"
                                {{ old('group_center_id', $data->group_center_id) == $center->id ? 'selected' : '' }}
                            >

                                {{ $center->center_name ?? '' }}
                                -
                                {{ $center->center_code ?? '' }}

                            </option>

                        @endforeach

                    </select>

                    @error('group_center_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>



                <div class="col-md-4">

                    <label class="form-label">
                        Group Code
                    </label>

                    <input 
                        type="text"
                        class="form-control"
                        value="{{ $data->group_code }}"
                        readonly
                    >

                </div>



                <div class="col-md-4">

                    <label class="form-label">
                        Group Name
                    </label>

                    <input 
                        type="text"
                        name="group_name"
                        class="form-control @error('group_name') is-invalid @enderror"
                        value="{{ old('group_name', $data->group_name) }}"
                        required
                    >

                    @error('group_name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>



                <div class="col-md-4">

                    <label class="form-label">
                        Group Type
                    </label>

                    <input 
                        type="text"
                        name="group_type"
                        class="form-control @error('group_type') is-invalid @enderror"
                        value="{{ old('group_type', $data->group_type) }}"
                    >

                    @error('group_type')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

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
                    >

                    @error('location')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>



                <div class="col-md-4">

                    <label class="form-label">
                        Registration Date
                    </label>

                    <input 
                        type="date"
                        name="registration_date"
                        class="form-control @error('registration_date') is-invalid @enderror"
                        value="{{ old('registration_date', $data->registration_date) }}"
                    >

                    @error('registration_date')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>



                <div class="col-md-4">

                    <label class="form-label">
                        Credit Officer
                    </label>

                    <select 
                        name="credit_officer_id"
                        class="form-select select2_demo_3 @error('credit_officer_id') is-invalid @enderror"
                    >

                        <option value="">
                            Select Credit Officer
                        </option>

                        @foreach($employees as $employee)

                            <option 
                                value="{{ $employee->id }}"
                                {{ old('credit_officer_id', $data->credit_officer_id) == $employee->id ? 'selected' : '' }}
                            >

                                {{ $employee->FirstName ?? '' }}
                                {{ $employee->MiddleName ?? '' }}
                                {{ $employee->LastName ?? '' }}
                                -
                                {{ $employee->EmployeeID ?? '' }}

                            </option>

                        @endforeach

                    </select>

                    @error('credit_officer_id')
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
                    >{{ old('description', $data->description) }}</textarea>

                    @error('description')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>



            <div class="mt-4 d-flex justify-content-end gap-2">

                <a href="{{ route('centerGroups') }}" class="arbif-btn-cancel">
                    <i class="bi bi-x"></i> Cancel
                </a>

                <button onclick="confirmsubmit()" type="submit" class="arbif-btn-submit">
                    <i class="bi bi-check2"></i> Update Group
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